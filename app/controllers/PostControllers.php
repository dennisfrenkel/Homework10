<?php
namespace app\controllers;

use app\models\Posts;
use PDO;

require_once "../app/models/Posts.php";

class PostsController
{
    private $postModel;

    
    public function __construct(PDO $db)
    {
        $this->postModel = new Posts($db);
    }

    public function validatePost(array $inputData): array
    {
        $errors = [];
        $title = $inputData['title'];
        $content = $inputData['content'];

        if ($title) {
            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
            if (strlen($title) < 5) {
                $errors['titleShort'] = 'Title is too short';
            }
        } else {
            $errors['requiredTitle'] = 'Title is required';
        }

        if ($content) {
            $content = htmlspecialchars($content, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
            if (strlen($content) < 10) {
                $errors['contentShort'] = 'Content is too short';
            }
        } else {
            $errors['requiredContent'] = 'Content is required';
        }

        if (count($errors)) {
            http_response_code(400);
            echo json_encode($errors);
            exit();
        }

        return [
            'title' => $title,
            'content' => $content,
        ];
    }

    public function getAllPosts(): void
    {
        header("Content-Type: application/json");
        echo json_encode($this->postModel->getAllPosts());
        exit();
    }

    public function getPostByID(int $id): void
    {
        if (!$id) {
            http_response_code(404);
            echo json_encode(['error' => 'Post not found']);
            exit();
        }

        header("Content-Type: application/json");
        $post = $this->postModel->getPostById($id);
        
        if ($post) {
            echo json_encode($post);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Post not found']);
        }
        exit();
    }

    public function savePost(): void
    {
        $inputData = [
            'title' => $_POST['title'] ?? false,
            'content' => $_POST['content'] ?? false,
        ];
        $postData = $this->validatePost($inputData);

        $this->postModel->savePost($postData);

        http_response_code(200);
        echo json_encode([
            'success' => true
        ]);
        exit();
    }

    public function updatePost(int $id): void
    {
        if (!$id) {
            http_response_code(404);
            exit();
        }

        parse_str(file_get_contents('php://input'), $_PUT);

        $inputData = [
            'title' => $_PUT['title'] ?? false,
            'content' => $_PUT['content'] ?? false,
        ];
        $postData = $this->validatePost($inputData);

        $this->postModel->updatePost($id, $postData);

        http_response_code(200);
        echo json_encode([
            'success' => true
        ]);
        exit();
    }

    public function deletePost(int $id): void
    {
        if (!$id) {
            http_response_code(400); 
            echo json_encode(['error' => 'Post ID is required']);
            exit();
        }

        if ($this->postModel->deletePost(['id' => $id])) {
            http_response_code(200);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete post']);
        }
        exit();
    }
    public function postsView(): void
    {
        include '../public/assets/views/posts/posts-view.html';
        exit();
    }

    public function postsAddView(): void
    {
        include '../public/assets/views/posts/posts-add.html';
        exit();
    }

    public function postsDeleteView(): void
    {
        include '../public/assets/views/posts/posts-delete.html';
        exit();
    }

    public function postsUpdateView(): void
    {
        include '../public/assets/views/posts/posts-update.html';
        exit();
    }
}
?>
