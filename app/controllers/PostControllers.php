<?php
namespace app\controllers;

use app\models\Post;

class PostController
{
    public function validatePost($inputData) {
        $errors = [];
        $title = $inputData['title'] ?? null;
        $content = $inputData['content'] ?? null;

        if ($title) {
            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
            if (strlen($title) < 2) {
                $errors['titleShort'] = 'Title is too short';
            }
        } else {
            $errors['requiredTitle'] = 'Title is required';
        }

        if ($content) {
            $content = htmlspecialchars($content, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
            if (strlen($content) < 2) {
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

    public function getAllPosts() {
        $postModel = new Post();
        header("Content-Type: application/json");
        $posts = $postModel->getAllPosts();
        echo json_encode($posts);
        exit();
    }

    public function getPostByID($id) {
        if (!$id) {
            http_response_code(404);
            echo json_encode(['error' => 'Post not found']);
            exit();
        }

        $postModel = new Post();
        header("Content-Type: application/json");
        $post = $postModel->getPostById($id);
        
        if ($post) {
            echo json_encode($post);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Post not found']);
        }
        exit();
    }

    public function savePost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); 
            echo json_encode(['error' => 'Method not allowed']);
            exit();
        }

        $jsonData = file_get_contents('php://input');
        $inputData = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            parse_str(file_get_contents('php://input'), $inputData);
        }
        $postData = $this->validatePost($inputData);

        $post = new Post();
        $result = $post->savePost($postData['title'], $postData['content']);

        if ($result) {
            http_response_code(201); 
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500); 
            echo json_encode(['error' => 'Failed to save post']);
        }
        exit();
    }

    public function updatePost($id) {
        if (!$id) {
            http_response_code(404); // Not Found
            echo json_encode(['error' => 'Post not found']);
            exit();
        }

        parse_str(file_get_contents('php://input'), $_PUT);

        $inputData = [
            'title' => $_PUT['title'] ?? false,
            'content' => $_PUT['content'] ?? false,
        ];

        $postData = $this->validatePost($inputData);

        $post = new Post();
        $post->updatePost(
            [
                'id' => $id,
                'title' => $postData['title'],
                'content' => $postData['content'],
            ]
        );

        http_response_code(200); 
        echo json_encode(['success' => true]);
        exit();
    }

    public function deletePost($id) {
        if (!$id) {
            http_response_code(404); 
            echo json_encode(['error' => 'Post not found']);
            exit();
        }

        $post = new Post();
        $post->deletePost(
            [
                'id' => $id,
            ]
        );

        http_response_code(200); 
        echo json_encode(['success' => true]);
        exit();
    }

    public function postsView() {
        include '../public/assets/views/posts/posts-view.html';
        exit();
    }

    public function postsAddView() {
        include '../public/assets/views/posts/posts-add.html';
        exit();
    }

    public function postsDeleteView() {
        include '../public/assets/views/posts/posts-delete.html';
        exit();
    }

    public function postsUpdateView() {
        include '../public/assets/views/posts/posts-update.html';
        exit();
    }
}
