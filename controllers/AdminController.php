<?php

namespace codewild\csubmboer\controllers;

use codewild\phpmvc\middleware\AuthMiddleware;
use codewild\phpmvc\Request;
use codewild\phpmvc\Response;
use codewild\csubmboer\models\ContactForm;
use codewild\csubmboer\models\Image;
use codewild\csubmboer\models\ModuleVersion;
use codewild\csubmboer\models\UserVM;

class AdminController extends \codewild\phpmvc\Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware());
    }

    public function index(Request $request, Response $response){
        return $this->render('admin/index');
    }

    public function contacts(Request $request, Response $response){
        $contacts = ContactForm::findAll();
        return $this->render('admin/contacts', ['model' => $contacts]);
    }

    public function images(Request $request, Response $response){
        $images = Image::findAll();
        return $this->render('admin/images', ['model' => $images]);
    }


    public function modules(Request $request, Response $response){
        $versions = ModuleVersion::findMany(['status' => 1]);
        return $this->render('admin/modules', ['model' => $versions]);
    }

    public function users(Request $request, Response $response){
        $users = UserVM::findAll();
        return $this->render('admin/users', ['model' => $users]);
    }

}
