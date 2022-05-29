<?php

namespace codewild\csubmboer\controllers;

use codewild\csubmboer\authorization\AuthHandler;
use codewild\csubmboer\core\Application;
use codewild\csubmboer\core\Controller;
use codewild\csubmboer\core\exception\ForbiddenException;
use codewild\csubmboer\core\middleware\AuthMiddleware;
use codewild\csubmboer\core\Request;
use codewild\csubmboer\core\Response;
use codewild\csubmboer\models\Module;
use codewild\csubmboer\models\ModuleVersion;
use codewild\csubmboer\models\UserRole;

class ModuleController extends Controller
{
    public function __construct(){
        $this->registerMiddleware(new AuthMiddleware([
            'create' => new Module(),
        ]));

    }
    // INDEX
    public function index(Request $request, Response $response) {
        $userId = Application::$app->user->id ?? null;
        $allModules = Module::findAll();
        $allVersions = ModuleVersion::findAll();
        $versions = array();
        foreach ($allModules as $module) {
            $version = Module::getLatestVersion($module->path);
            if ($version->status === 2){
                $versions[] = $version;
            } else if ($version->created_by === $userId) {
                $versions[] = $version;
            }
        }
//        echo '<pre>';
//        var_dump($versions);
//        exit;
        return $this->render('module/index', ['model' => $versions]);
    }

    //CREATE
    public function create(Request $request, Response $response){
        $inputModel = new Module();
        if($request->isPost()){
            $inputModel->loadData($request->getBody());

            if (AuthHandler::authorize($inputModel, 'create')){
                $inputModel->created_by = Application::$app->user->id;

                if ($inputModel->validate() && $inputModel->save()) {
                    Application::$app->session->setFlash('success', 'Module created successfully.');
                    return $response->redirect("/modules");
                }
            } else {
                throw new ForbiddenException();
            }
        }
        return $this->render('module/create', ['model' => $inputModel]);
    }
    // EDIT
    public function edit(Request $request, Response $response) {
        $params = $request->getRouteParams();
        $module = Module::findOne(['path' => $params['path']]);

        if (!AuthHandler::authorize($module, 'update')){
            throw new ForbiddenException();
        }

        $inputModel = new Module();
        if ($request->isPost()){
            $body = $request->getBody();
            $inputModel->loadData($body);
            if (array_key_exists('update', $body)) {
                $keys = array_keys($body);
                $inputModel->id = $module->id;
                if ($inputModel->validate($keys) && $inputModel->update($keys)) {
                    Application::$app->session->setFlash('success', 'Module updated successfully.');
                    return $response->redirect();
                }
            }
            if (array_key_exists('delete', $body)){
                if (!AuthHandler::authorize($module, 'delete')){
                    Application::$app->session->setFlash('danger', 'You cannot delete a module with published versions!');
                    return $response->redirect();
                }
                else if ($module->delete()){
                    Application::$app->session->setFlash('success', 'Module was deleted!');
                    return $response->redirect('/');
                } else {
                    Application::$app->session->setFlash('danger', 'There was a problem deleting the module');
                    return $response->redirect();
                }
            }
        } else {
            $inputModel->loadData($module);
        }

        return $this->render("module/edit", ['model'=>$module, 'inputModel' => $inputModel]);
    }

    // module/{path} or module/{path}/v/{id} or ch{n}/{path}
    public function details(Request $request, Response $response){
        $params = $request->getRouteParams();
        $module = Module::findOne(['path' => $params['path']]);
        $module->getVersions();

        if(array_key_exists('id', $params)){
            $version = current(array_filter($module->versions, fn($var) => $var->shortId() === $params['id']));
            $articleRef = '/module/{path}/v/{id}/{n}';
        } else {
            $version = Module::getLatestVersion($module->path);
            $articleRef = array_key_exists('ch', $params) ? '/ch'.$params['ch'].'/{path}/{n}' : '/module/{path}/{n}';
        }

        if ($version->status !== 2 && !AuthHandler::authorize($version, 'read')){
            throw new ForbiddenException();
        }

        $version->getNavs();

        // POST
        if ($request->isPost()){
            if (Application::isGuest()){
                throw new ForbiddenException();
            }
            $body = $request->getBody();
            // DELETE VERSION
            if (array_key_exists('deleteVersion', $body)){
                if ($version->delete()) {
                    $url = Request::createUrl('/module/{path}', ['path' => $module->path]);
                    Application::$app->session->setFlash('danger', 'You have successfully deleted the version!');
                    // TODO: Fix redirect behavior if last version is deleted
                    return $response->redirect($url);
                }
            }
            // FORK VERSION
            if (array_key_exists('forkVersion', $body)){
                $newId = $version->clone();
                if ($newId) {
                    $url = Request::createUrl('/module/{path}/v/{id}', ['path' => $module->path, 'id' => $newId]);
                    Application::$app->session->setFlash('success', 'You have successfully cloned a module version!');
                    return $response->redirect($url);
                }
            }
            // APPROVE OR REJECT VERSION
            if (!AuthHandler::authorize($version, 'approve')){
                throw new ForbiddenException();
            } else {
                if (array_key_exists('approve', $request->getBody())){
                    $version->status = ModuleVersion::STATUS_APPROVED;
                    if ($version->update(['status'])) {
                        Application::$app->session->setFlash('success', 'Approved!');
                        return $response->redirect();
                    }
                }
                if (array_key_exists('reject', $request->getBody())){
                    // Send a message to the user explaining reasoning
                    $version->status = ModuleVersion::STATUS_CREATED;
                    if ($version->update(['status'])) {
                        return $response->redirect();
                    }
                }
            }
        }
        return $this->render('module/details', ['model' => $module, 'version' => $version, 'articleRef' => $articleRef]);
    }

}
