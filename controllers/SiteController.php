<?php

namespace codewild\csubmboer\controllers;

use codewild\csubmboer\authorization\AuthHandler;
use codewild\csubmboer\core\Application;
use codewild\csubmboer\core\Controller;
use codewild\csubmboer\core\exception\ForbiddenException;
use codewild\csubmboer\core\Request;
use codewild\csubmboer\core\Response;
use codewild\csubmboer\models\Article;
use codewild\csubmboer\models\Outline;
use codewild\csubmboer\models\ContactForm;
use codewild\csubmboer\models\Module;
use codewild\csubmboer\models\Search;


class SiteController extends Controller {

    public function home(Request $request, Response $response){
        $chapters = Outline::findMany(['parentId' => NULL], 'n');
        $newChapter = new Outline();
        if ($request->isPost()) {
            if (!AuthHandler::authorize($newChapter, 'create')) {
                throw new ForbiddenException();
            }
            $newChapter->loadData($request->getBody());
            $newChapter->n = count($chapters) + 1;
            if ($newChapter->validate() && $newChapter->save()) {
                Application::$app->session->setFlash('success', 'Chapter saved successfully.');
                return $response->redirect('/');
            }
        }

        return $this->render('home', ['model' => $chapters, 'inputModel' => $newChapter]);
    }
    public function contact(Request $request, Response $response) {
        $contact = new ContactForm();
        if ($request->isPost()){
            $contact->loadData($request->getBody());
            if($contact->validate() && $contact->save()){
                Application::$app->session->setFlash('success', 'Thanks for contacting us! We will respond to the email provided ASAP.');
                return $response->redirect('/contact');
            }
        }
        return $this->render('contact', [
            'model' => $contact,
        ]);
    }

    public function about(){

        return $this->render('about');
    }

    public function search(Request $request, Response $response) {
        $body = $request->getBody();
        $q = array_key_exists('q', $body) ? $body['q'] : null;

        $results = (empty($q)) ? null : Search::search($q);

        return $this->render('search', ['q' => $q, 'results' => $results]);
    }



    public function error(Request $request, Response $response) {
        $contact = new ContactForm();
        if ($request->isPost()){
            $contact->loadData($request->getBody());
            $contact->priority = 1;
            if($contact->save()){
                Application::$app->session->setFlash('success', 'Thank you for reporting this issue. Our team will review the information. Please try your action again later.');
                return $response->redirect('/contact');
            }
        }
        return $this->render('_error', [
            'contact' => $contact,
        ]);
    }



}

?>
