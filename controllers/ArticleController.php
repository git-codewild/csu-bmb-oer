<?php

namespace codewild\csubmboer\controllers;

use codewild\csubmboer\authorization\AuthHandler;
use codewild\phpmvc\Application;
use codewild\phpmvc\Controller;
use codewild\phpmvc\exception\ForbiddenException;
use codewild\phpmvc\middleware\AuthMiddleware;
use codewild\phpmvc\Request;
use codewild\phpmvc\Response;
use codewild\csubmboer\models\Article;
use codewild\csubmboer\models\ArticleNav;
use codewild\csubmboer\models\DataFile;
use codewild\csubmboer\models\DataNav;
use codewild\csubmboer\models\Figure;
use codewild\csubmboer\models\Module;
use codewild\csubmboer\models\ModuleVersion;
use codewild\csubmboer\models\Outline;
use codewild\csubmboer\models\Script_JSmol;

class ArticleController extends Controller
{
    public function index(Request $request, Response $response){
        $routeParams = $request->getRouteParams();

        $chapter = array_key_exists('ch', $routeParams) ? Outline::findOne(['n' => $routeParams['ch'], 'parentId' => null]) : null;

        if (array_key_exists('id', $routeParams)) {
            $version = ModuleVersion::findByShortId($routeParams['id']);
            $articleRef = '/module/{path}/v/{id}/{n}';
        } else {
            $version = Module::getLatestVersion($routeParams['path']);
            $articleRef = array_key_exists('ch', $routeParams) ? '/ch'.$routeParams['ch'].'/{path}/{n}' : '/module/{path}/{n}';
        }
        $version->getNavs();

        if (array_key_exists('n', $routeParams)){
            $article = current(ArticleNav::filter($version->articleNavs, ['n' => (int) $routeParams['n']]))->article;
            $article->getSlides();
        } else {
            $article = false;
        }

        return $this->render('article/index', ['model' => $version, 'article' => $article, 'articleRef' => $articleRef, 'chapter' => $chapter]);
    }



    public function edit(Request $request, Response $response){
        $routeParams = $request->getRouteParams();
        $version = ModuleVersion::findByShortId($routeParams['id']);
        $version->getNavs();
        $nav = current(ArticleNav::filter($version->articleNavs, ['n' => (int) $routeParams['n']]));
        $nav->version = $version;
        $nav->getNeighbors();
        $article = $nav->article;

        if (!AuthHandler::authorize($version, 'update')){
            throw new ForbiddenException();
        }

        $article->getSlides();

        // INPUT MODELS
        $newFigure = new Figure();
        $newDataFile = new DataFile();

        if ($request->isPost()){
            $body = $request->getBody();

            // ARTICLE UPDATE
            if (array_key_exists('updateArticle', $body)){
                if ($article->title === $body['title'] && strcmp($article->html, $body['html']) === 0) {
                    Application::$app->session->setFlash('info', 'No changes were detected.');
                    return $response->redirect();
                } else {
                    $article->loadData($body);
                    if ($article->validate() && $article->update()) {
                        Application::$app->session->setFlash('success', 'Article was edited!');
                        return $response->redirect();
                    }
                }
            }
            // ADD FIGURE
            if (array_key_exists('addFigure', $body)){
                $newFigure->loadData($body);
                if ($newFigure->image->upload('name', '/img/uploads/')) {
                    $newFigure->imageId = $newFigure->image::lastInsertId();
                    if ($newFigure->validate() && $newFigure->create($article->id)) {
                        Application::$app->session->setFlash('success', 'Figure was created!');
                        return $response->redirect();
                    }
                }
            }
            // DELETE SLIDE
            if (array_key_exists('deleteSlide', $body)){

                $slide = current(array_filter($article->slides, fn($s) => $s->resourceId === $body['resourceId'] || $s->resourceId === $body['id']));
                if ($slide->delete()){
                    Application::$app->session->setFlash('success', 'Slide was deleted!');
                } else {
                    Application::$app->session->setFlash('danger', 'There was a problem deleting the slide');
                }
                return $response->redirect();
            }

            // EDIT FIGURE
            if (array_key_exists('editFigure', $body)){
                $slide = current(array_filter($article->slides, fn($s) => $s->resourceId === $body['id']));
                $figure = $slide->resource;
                $figure->loadData($body);
                if ($figure->update()){
                    Application::$app->session->setFlash('success', 'Figure was edited!');
                    return $response->redirect();
                }
            }

            // ADD JSMOL
            if (array_key_exists('addJSmol', $body)){
                $jsmol = Script_JSmol::create($article->id);
                return $response->redirect();
            }
            // CREATE DATA FILE
            if (array_key_exists('createDataFile', $body)) {
                $scriptId = $body['id'];
                $newDataFile->loadData($body);
                if ($newDataFile->validate()){
                    $radio = $body['createDataFileRadio'];

                    // REMOTE LOCATION
                    if ($radio === 'path' && $newDataFile->create($scriptId)){
                        Application::$app->session->setFlash('success', 'Data file created!');
                    }
                    // LOCAL LOCATION
                    else if ($radio === 'name' && $newDataFile->upload('name', '/data/')) {
                        DataNav::create(DataFile::lastInsertId(), $scriptId);
                        Application::$app->session->setFlash('success', 'Data file uploaded!');
                    }
                    return $response->redirect();
                }
            }
            // APPEND DATA FILE
            if (array_key_exists('appendDataFile', $body)){
                if (DataNav::create($body['dataFileId'], $body['id'])){
                    Application::$app->session->setFlash('success', 'Data file connected!');
                    return $response->redirect();
                }
            }
            // DELETE DATA FILE
            if (array_key_exists('deleteDataFile', $body)){
                $dataNav = DataNav::findOne(['dataFileId' => $body['dataFileId'], 'scriptId' => $body['scriptId']]);
                if ($dataNav->delete()){
                    Application::$app->session->setFlash('success', 'Data file deleted!');
                    return $response->redirect();
                }
            }

            // EDIT SCRIPT
            if (array_key_exists('scriptEdit', $body)){
                $slide = current(array_filter($article->slides, fn($s) => $s->resourceId === $body['id']));
                $script = $slide->resource;
                $script->loadData($body);
                if ($script->validate() && $script->update()){
                    Application::$app->session->setFlash('success', 'Script was edited!');
                    return $response->redirect();
                }
            }
            // DELETE SCRIPT
            if (array_key_exists('scriptDelete', $body)){
                $slide = current(array_filter($article->slides, fn($s) => $s->resourceId === $body['id']));
                $script = $slide->resource;
                if ($script->delete()){
                    Application::$app->session->setFlash('success', 'Script was deleted!');
                    return $response->redirect();
                } else {
                    Application::$app->session->setFlash('danger', 'There was a problem deleting the script.');
                    return $response->redirect();
                }
            }
        }

        return $this->render('article/edit', ['model' => $nav, 'article' => $article, 'newFigure' => $newFigure, 'newDataFile' => $newDataFile]);
    }
}
