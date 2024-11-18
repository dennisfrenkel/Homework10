<?php
namespace app\models;

use app\models\Model;

class Post extends Model {

    public function getAllPosts() {
        $query = "SELECT * FROM posts";
        return $this->fetchAll($query);
    }

    public function getPostById($id) {
        $query = "SELECT * FROM posts WHERE id = :id";
        return $this->fetch($query, ['id' => $id]);
    }

    public function savePost($inputData) {
        $query = "INSERT INTO posts (title, content) VALUES (:title, :content)";
        return $this->fetchAllWithParams($query, $inputData);
    }

    public function updatePost($inputData) {
        $query = "UPDATE posts SET title = :title, content = :content WHERE id = :id";
        return $this->fetchAllWithParams($query, $inputData);
    }

    public function deletePost($inputData) {
        $query = "DELETE FROM posts WHERE id = :id";
        return $this->fetchAllWithParams($query, $inputData);
    }
}
