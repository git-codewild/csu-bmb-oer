<?php

namespace codewild\csubmboer\controllers;

use codewild\phpmvc\Application;
use codewild\phpmvc\Controller;
use codewild\phpmvc\Request;
use codewild\phpmvc\Response;
use codewild\phpmvc\middleware\AuthMiddleware;
use codewild\csubmboer\models\LoginForm;
use codewild\csubmboer\models\User;
use codewild\csubmboer\models\UserVM;

class UserController extends Controller {

    public function __construct(){
//        $this->registerMiddleware(new AuthMiddleware(['profile']));
    }

    public function login(Request $request, Response $response){
        $query = $request->parseQuery();
        $loginForm = new LoginForm();

        $returnUrl = '/';
        if (array_key_exists('return', $query)){
            $returnUrl = $query['return'];
        }
        
        if($request->isPost()){
            $loginForm->loadData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()){
                $response->redirect($returnUrl);
            }
        }
        return $this->render('user/login', ['model' => $loginForm]);
    }

    public function register(Request $request){
        $user = new User();

        if($request->isPost()){
            $user->loadData($request->getBody());

            if ($user->validate() && $user->save()){
                Application::$app->session->setFlash('success', 'Thanks for joining!');
                Application::$app->response->redirect('/');
            }

            return $this->render('user/register', ['model' => $user]);
        }
        //is GET
        return $this->render('user/register', ['model' => $user]);
    }

    public function profile(Request $request){
        $user = User::findOne(['id' => Application::$app->user->id]);

        if ($request->isPost()){
            $body = $request->getBody();
            $keys = array_keys($body);
            $user->loadData($request->getBody());

            if ($user->validate($keys) && $user->update($keys)) {
                Application::$app->session->setFlash('success', 'Profile updated');
                Application::$app->response->redirect();
            }
        }

        return $this->render('user/profile', ['model' => $user]);
    }

    public function logout(Request $request, Response $response){
        $query = $request->parseQuery();

        $returnUrl = '/';
        // TODO: Parse returnUrl and only return if allowed
//        DON'T WANT TO BE REDIRECTED TO A FORBIDDEN URL
//        if (array_key_exists('return', $query)){
//            $returnUrl = $query['return'];
//        }

        Application::$app->logout();
        $response->redirect($returnUrl);
    }
}

?>
