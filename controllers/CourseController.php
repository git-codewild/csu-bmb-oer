<?php

namespace codewild\csubmboer\controllers;

use codewild\csubmboer\authorization\AuthHandler;
use codewild\phpmvc\Application;
use codewild\phpmvc\Controller;
use codewild\phpmvc\exception\DbException;
use codewild\phpmvc\exception\ForbiddenException;
use codewild\phpmvc\middleware\AuthMiddleware;
use codewild\phpmvc\Request;
use codewild\phpmvc\Response;
use codewild\csubmboer\models\Module;
use codewild\csubmboer\models\Outline;

class CourseController extends Controller
{
    // ch{n}
    public function index(Request $request){
        $params = $request->getRouteParams();
        // If only $params['ch'] is passed, get chapter by index and null parentId;
        $chapter = Outline::findOne(['n' => $params['ch'], 'parentId' => NULL]);

        return $this->render('course/index', ['model' => $chapter]);
    }

    // ch{n}/edit
    public function edit(Request $request, Response $response){
        $params = $request->getRouteParams();
        // If only $params['ch'] is passed, get chapter by index and null parentId;
        $chapter = Outline::findOne(['n' => $params['ch'], 'parentId' => NULL]);
        foreach ($chapter->children as $child) {
            if ($child->parentId === $chapter->id) {
                $child->parentId = null;
            }
        }

        $modules = Module::getPublishedModules();

        $inputModel = new Outline();

        if (!AuthHandler::authorize($chapter, 'update')){
            throw new ForbiddenException();
        }

        // POST
        if ($request->isPost()) {
            $body = $request->getBody();
            $toUpdate = current(Outline::filter($chapter->children, ['id' => $body['id']]));

            // CREATE NEW
            if (array_key_exists('create', $body)){
                $inputModel->loadData($body);
                $inputModel->parentId = $inputModel->parentId ?? $chapter->id;
                // TODO: Unnecessary call to database, can get count from $chapter
                $siblings = Outline::findMany(['parentId' => $inputModel->parentId]);
                $inputModel->n = count($siblings) + 1;
                if ($inputModel->validate() && $inputModel->save()) {
                    Application::$app->session->setFlash('success', 'Section saved successfully.');
                    return $response->redirect();
                }
            };
            // DELETE
            if (array_key_exists('delete', $body)){
                $inputModel = Outline::findOne(['id' => $body['id']]);
                if($inputModel->delete()){
                    Application::$app->session->setFlash('success', 'Section was deleted.');
                    return $response->redirect();
                }
            };
            // RENAME
            if (array_key_exists('rename', $body)){
                $section = current(Outline::filter($chapter->children, ['id' => $body['id']]));
                $section->title = $body['title'];
                if ($section->validate(['title']) && $section->update(['title'])){
                    Application::$app->session->setFlash('success', 'Section was renamed.');
                    return $response->redirect();
                }
            };
            // MOVE UP
            if (array_key_exists('moveUp', $body)){
                if ($toUpdate->n > 1) {
                    $toUpdate->n -= 1;
                    $toSwap = current(Outline::filter($chapter->children, ['parentId' => $toUpdate->parentId, 'n' => $toUpdate->n]));
                    $toSwap->n += 1;
                    if ($toUpdate->update(['n']) && $toSwap->update(['n'])){
                        Application::$app->session->setFlash('success', 'Section was reordered.');
                        return $response->redirect();
                    }
                }
            };
            // INDENT AND OUTDENT
            if (array_key_exists('indent', $body)) {
                $siblings = Outline::filter($chapter->children, ['parentId' => $toUpdate->parentId]);
                unset($siblings[array_search($toUpdate, $siblings)]);

                if (!empty($siblings)) {
                    foreach ($siblings as $sibling) {
                        if ($sibling->n > $toUpdate->n){
                            $sibling->n -= 1;
                            try{
                                $sibling->update(['n']);
                            } catch (\PDOException $e) {
                                throw new DbException($e->getMessage());
                            }
                        }
                    }
                }

                // INDENT
                if (is_null($toUpdate->parentId)) {
                    $newParent = current(Outline::filter($chapter->children, ['n' => $toUpdate->n - 1, 'parentId' => $toUpdate->parentId]));
//                    if (!is_null($newParent->parentId)) {
//                        $newParent = current(Outline::filter($chapter->children, ['id' => $newParent->parentId]));
//                    }
                    $toUpdate->parentId = $newParent->id;
                    $toUpdate->n = count($newParent->children) + 1;
                    if (!empty($toUpdate->children)) {
                        foreach ($toUpdate->children as $child) {
                            $child->parentId = $newParent->id;
                            $child->n += $toUpdate->n;
                            if (!$child->update(['parentId', 'n'])) {
                                Application::$app->session->setFlash('danger', 'Article ' . $child->title . ' could not be updated.');
                                return $response->redirect();
                            }
                        }
                    }

                }
                // OUTDENT
                else {
                    $siblings = Outline::filter($chapter->children, ['parentId' => null]);
                    $toUpdate->n = current(Outline::filter($chapter->children, ['id' => $toUpdate->parentId]))->n + 1;
                    foreach($siblings as $sibling){
                        if ($sibling->n >= $toUpdate->n){
                            $sibling->n += 1;
                            try{
                                $sibling->update(['n']);
                            } catch (\PDOException $e) {
                                throw new DbException($e->getMessage());
                            }
                        }
                    }
                    $toUpdate->parentId = $chapter->id;
                }
                if ($toUpdate->update(['parentId', 'n'])) {
                    Application::$app->session->setFlash('success', 'Section was indented.');
                    return $response->redirect();
                }
            }
            // SELECT MODULE
            if (array_key_exists('selectModule', $body)){
                $module = current(array_filter($modules, fn($v) => $v->id === $body['moduleId']));
                $versionId = Module::getLatestVersion($module->path)->id;
                $toUpdate->moduleVersionId = $versionId;
                $toUpdate->title = $module->title;

                if ($toUpdate->update(['title', 'moduleVersionId'])){
                    Application::$app->session->setFlash('success', 'Module was linked successfully!');
                    return $response->redirect();
                }

            }
        }
        return $this->render('course/edit', ['model' => $chapter, 'inputModel' => $inputModel, 'modules' => $modules]);
    }
}
