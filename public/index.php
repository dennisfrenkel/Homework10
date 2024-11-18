<?php
require_once "../app/models/Model.php";
require_once "../app/models/User.php";
require_once "../app/models/Post.php";
require_once "../app/controllers/UserController.php";
require_once "../app/controllers/PostController.php";

// Set environment variables
$env = parse_ini_file('../.env');
define('DBNAME', $env['DBNAME']);
define('DBHOST', $env['DBHOST']);
define('DBUSER', $env['DBUSER']);
define('DBPASS', $env['DBPASS']);

use app\controllers\UserController;
use app\controllers\PostController;

$uri = strtok($_SERVER["REQUEST_URI"], '?');
$uriArray = explode("/", $uri);

// Users API
if ($uriArray[1] === 'api' && $uriArray[2] === 'users') {
    $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
    $userController = new UserController();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $id ? $userController->getUserByID($id) : $userController->getAllUsers();
            break;
        case 'POST':
            $userController->saveUser();
            break;
        case 'PUT':
            $userController->updateUser($id);
            break;
        case 'DELETE':
            $userController->deleteUser($id);
            break;
    }
}

// Posts API
if ($uriArray[1] === 'api' && $uriArray[2] === 'posts') {
    $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
    $postController = new PostController();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $id ? $postController->getPostByID($id) : $postController->getAllPosts();
            break;
        case 'POST':
            $postController->savePost();
            break;
        case 'PUT':
            $postController->updatePost($id);
            break;
        case 'DELETE':
            $postController->deletePost($id);
            break;
    }
}

// Views for Users
if ($uri === '/users-add') {
    $userController = new UserController();
    $userController->usersAddView();
} elseif ($uri === '/users-update') {
    $userController = new UserController();
    $userController->usersUpdateView();
} elseif ($uri === '/users-delete') {
    $userController = new UserController();
    $userController->usersDeleteView();
} elseif ($uri === '/users') {
    $userController = new UserController();
    $userController->usersView();
}

// Views for Posts
elseif ($uri === '/posts-add') {
    $postController = new PostController();
    $postController->postsAddView();
} elseif ($uri === '/posts-update') {
    $postController = new PostController();
    $postController->postsUpdateView();
} elseif ($uri === '/posts-delete') {
    $postController = new PostController();
    $postController->postsDeleteView();
} elseif ($uri === '/posts') {
    $postController = new PostController();
    $postController->postsView();
}

// Default 404 page
else {
    include '../public/assets/views/notFound.html';
    exit();
}
?>
