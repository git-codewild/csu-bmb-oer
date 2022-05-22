<?php

namespace codewild\csubmboer\controllers;

use codewild\csubmboer\authorization\AuthHandler;
use codewild\csubmboer\core\Application;
use codewild\csubmboer\core\exception\ForbiddenException;
use codewild\csubmboer\core\Request;
use codewild\csubmboer\core\Response;
use codewild\csubmboer\models\Appendix;

class AppendixController extends \codewild\csubmboer\core\Controller
{
    public function index(Request $request, Response $response){
        $appendices = Appendix::findAll();

        return $this->render("appendix/index", ['model' => $appendices]);
    }

    public function create(Request $request, Response $response){
        $model = new Appendix();
        if (!AuthHandler::authorize($model, 'create')){
            return new ForbiddenException();
        }
        if ($request->isPost()){
            $body = $request->getBody();
            $model->loadData($body);
            //Automatically generates $appendix->path
            $model->path =  preg_replace('/\s/', '-', strtolower($model->title));
            if ($model->validate() && $model->save()){
                Application::$app->session->setFlash('success', 'Appendix created!');
                return $response->redirect('/appendix');
            }
        }
        return $this->render("appendix/create", ['model' => $model]);
    }

    public function details(Request $request, Response $response){
        $path = $request->getRouteParams()['path'];
        $model = Appendix::findOne(['path' => $path]);

        return $this->render('appendix/details', ['model' => $model]);
    }

    public function edit(Request $request, Response $response){
        $path = $request->getRouteParams()['path'];
        $model = Appendix::findOne(['path' => $path]);

        if ($request->isPost()){
            $body = $request->getBody();
            $model->loadData($body);
            if ($model->validate() && $model->update()) {
                Application::$app->session->setFlash('success', 'Appendix created!');
                return $response->redirect("/appendix/$model->path");
            }
        }

        return $this->render('appendix/edit', ['model' => $model]);
    }

}
