<?php
namespace app\models;

use app\models\Model;

class Post extends Model {
    public function getAllPostsByTitle($title) {
        $query = "SELECT * FROM posts WHERE title LIKE :title";
        return $this->fetchAllWithParams($query, ['title' => '%' . $title . '%']);
    }

    public function getAllPosts() {
        $query = "SELECT * FROM posts";
        return $this->fetchAll($query);
    }

    public function getPostById($id) {
        $query = "SELECT * FROM posts WHERE id = :id";
        $result = $this->fetchOneWithParams($query, ['id' => $id]); // Use fetchOne for a single post
        return $result ?: null; 


    public function savePost($inputData) {
        $query = "INSERT INTO posts (title, content) VALUES (:title, :content)";
        return $this->executeWithParams($query, $inputData); // Assuming executeWithParams is available in the parent Model class
    }

    public function updatePost($inputData) {
        $query = "UPDATE posts SET title = :title, content = :content WHERE id = :id";
        return $this->executeWithParams($query, $inputData); // Assuming executeWithParams is available in the parent Model class
    }

    public function deletePost($inputData) {
        $query = "DELETE FROM posts WHERE id = :id";
        return $this->executeWithParams($query, $inputData); // Assuming executeWithParams is available in the parent Model class
    }
}
