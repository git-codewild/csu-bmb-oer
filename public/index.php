<?php

use codewild\csubmboer\controllers\AppendixController;
use codewild\phpmvc\Application;
use codewild\csubmboer\controllers\AdminController;
use codewild\csubmboer\controllers\ArticleController;
use codewild\csubmboer\controllers\CourseController;
use codewild\csubmboer\controllers\SiteController;
use codewild\csubmboer\controllers\ModuleController;
use codewild\csubmboer\controllers\ModuleVersionController;
use codewild\csubmboer\controllers\UserController;
use codewild\csubmboer\models\UserVM;

$root = $_SERVER['DOCUMENT_ROOT'].'/../';
$views = $root.'/views';

require_once $root.'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($root);
$dotenv->load();

$config = [
    'userClass' => UserVM::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ],
];

$pathPattern = '{path:\w+-?\w+?}';

date_default_timezone_set('America/Denver');

$app = new Application($root, $views, $config);

$app->router->get('/', [SiteController::class, 'home']);
$app->router->post('/', [SiteController::class, 'home']);

$app->router->get('/about', [SiteController::class, 'about']);

$app->router->get('/search', [SiteController::class, 'search']);

$app->router->post('/error', [SiteController::class, 'error']);

$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'contact']);

$app->router->get('/admin', [AdminController::class, 'index']);
$app->router->post('/admin', [AdminController::class, 'index']);

$app->router->get('/admin/contacts', [AdminController::class, 'contacts']);
$app->router->post('/admin/contacts', [AdminController::class, 'contacts']);

$app->router->get('/admin/images', [AdminController::class, 'images']);
$app->router->post('/admin/images', [AdminController::class, 'images']);

$app->router->get('/admin/modules', [AdminController::class, 'modules']);
$app->router->post('/admin/modules', [AdminController::class, 'modules']);

$app->router->get('/admin/users', [AdminController::class, 'users']);
$app->router->post('/admin/users', [AdminController::class, 'users']);

$app->router->get('/ch{ch:\d{1,2}}', [CourseController::class, 'index']);

$app->router->get('/ch{ch:\d{1,2}}/edit', [CourseController::class, 'edit']);
$app->router->post('/ch{ch:\d{1,2}}/edit', [CourseController::class, 'edit']);

$app->router->get("/ch{ch:\d{1,2}}/$pathPattern", [ModuleController::class, 'details']);
$app->router->post("/ch{ch:\d{1,2}}/$pathPattern", [ModuleController::class, 'details']);

$app->router->get("/ch{ch:\d{1,2}}/$pathPattern/{n:\d{1,2}}", [ArticleController::class, 'index']);

$app->router->get('/modules', [ModuleController::class, 'index']);

$app->router->get('/modules/create', [ModuleController::class, 'create']);
$app->router->post('/modules/create', [ModuleController::class, 'create']);

$app->router->get("/module/$pathPattern", [ModuleController::class, 'details']);
$app->router->post("/module/$pathPattern", [ModuleController::class, 'details']);

$app->router->get("/module/$pathPattern/v/{id:\w{7}}", [ModuleController::class, 'details']);
$app->router->post("/module/$pathPattern/v/{id:\w{7}}", [ModuleController::class, 'details']);

$app->router->get("/module/$pathPattern/edit", [ModuleController::class, 'edit']);
$app->router->post("/module/$pathPattern/edit", [ModuleController::class, 'edit']);

$app->router->get("/module/$pathPattern/v/{id:\w{7}}/edit", [ModuleVersionController::class, 'edit']);
$app->router->post("/module/$pathPattern/v/{id:\w{7}}/edit", [ModuleVersionController::class, 'edit']);

$app->router->get("/module/$pathPattern/{n:\d{1,2}}", [ArticleController::class, 'index']);
$app->router->post("/module/$pathPattern/{n:\d{1,2}}", [ArticleController::class, 'index']);

$app->router->get("/module/$pathPattern/v/{id:\w{7}}/{n:\d{1,2}}", [ArticleController::class, 'index']);
$app->router->post("/module/$pathPattern/v/{id:\w{7}}/{n:\d{1,2}}", [ArticleController::class, 'index']);


$app->router->get("/module/$pathPattern/v/{id:\w{7}}/edit/{n:\d{1,2}}", [ArticleController::class, 'edit']);
$app->router->post("/module/$pathPattern/v/{id:\w{7}}/edit/{n:\d{1,2}}", [ArticleController::class, 'edit']);

$app->router->get("/appendix", [AppendixController::class, 'index']);

$app->router->get("/appendix/create", [AppendixController::class, 'create']);
$app->router->post("/appendix/create", [AppendixController::class, 'create']);

// TODO: refine path regex (will need to update router to accept () in url pattern
$app->router->get("/appendix/{path:[^/]+\w}/", [AppendixController::class, 'details']);
$app->router->post("/appendix/{path:[^/]+\w}/", [AppendixController::class, 'details']);

$app->router->get("/appendix/{path:[^/]+\w}/edit", [AppendixController::class, 'edit']);
$app->router->post("/appendix/{path:[^/]+\w}/edit", [AppendixController::class, 'edit']);

$app->router->get('/login', [UserController::class, 'login']);
$app->router->post('/login', [UserController::class, 'login']);

$app->router->get('/register', [UserController::class, 'register']);
$app->router->post('/register', [UserController::class, 'register']);

$app->router->get('/profile', [UserController::class, 'profile']);
$app->router->post('/profile', [UserController::class, 'profile']);

// For security, needs to change to post
$app->router->get('/logout', [UserController::class, 'logout']);

$app->run();

?>
