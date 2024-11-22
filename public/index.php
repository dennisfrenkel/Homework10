<?php

require_once "../app/models/Model.php";
require_once "../app/models/User.php";
require_once "../app/controllers/UserController.php";
require_once "../app/models/Posts.php"; 
require_once "../app/controllers/PostsController.php";

$env = parse_ini_file(__DIR__ . '/../.env');
define('DBNAME', $env['DBNAME']);
define('DBHOST', $env['DBHOST']);
define('DBUSER', $env['DBUSER']);
define('DBPASS', $env['DBPASS']);
define('DBPORT', $env['DBPORT']);

use app\controllers\UserController;
use app\controllers\PostsController;

$uri = strtok($_SERVER["REQUEST_URI"], '?');
$uriArray = explode("/", $uri);

$db = new PDO(
    'mysql:host=' . DBHOST . ';port=' . DBPORT . ';dbname=' . DBNAME, 
    DBUSER, 
    DBPASS
);

$postController = new PostsController($db);  

if ($uriArray[1] === 'api' && $uriArray[2] === 'users' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
    $userController = new UserController();
    
    if ($id) {
        $userController->getUserByID($id);
    } else {
        $userController->getAllUsers();
    }
}

if ($uriArray[1] === 'api' && $uriArray[2] === 'users' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController = new UserController();
    $userController->saveUser();
}

if ($uriArray[1] === 'api' && $uriArray[2] === 'users' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $userController = new UserController();
    $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
    $userController->updateUser($id);
}

if ($uriArray[1] === 'api' && $uriArray[2] === 'users' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $userController = new UserController();
    $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
    $userController->deleteUser($id);
}

if ($uriArray[1] === 'api' && $uriArray[2] === 'posts' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
    
    if ($id) {
        $postController->getPostByID($id);
    } else {
        $postController->getAllPosts();
    }

} elseif ($uriArray[1] === 'api' && $uriArray[2] === 'posts' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $postController->savePost();

} elseif ($uriArray[1] === 'api' && $uriArray[2] === 'posts' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
    $postController->updatePost($id);

} elseif ($uriArray[1] === 'api' && $uriArray[2] === 'posts' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
    $postController->deletePost($id);
}

// Views for user-related pages
if ($uri === '/users-add' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $userController = new UserController();
    $userController->usersAddView();
}

if ($uriArray[1] === 'users-update' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $userController = new UserController();
    $userController->usersUpdateView();
}

if ($uriArray[1] === 'users-delete' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $userController = new UserController();
    $userController->usersDeleteView();
}

if ($uri === '/' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $userController = new UserController();
    $userController->usersView();
}

if ($uri === '/posts-add' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $postController->postsAddView();
}

if ($uriArray[1] === 'posts-update' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $postController->postsUpdateView();
}

if ($uriArray[1] === 'posts-delete' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $postController->postsDeleteView();
}

if ($uriArray[1] === 'posts-view' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $postController->postsView();
}


include '../public/assets/views/notFound.html';
exit();

?>
