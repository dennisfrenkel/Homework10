<?php
namespace app\models;

use app\models\Model;

class Post extends Model {

    public function getAllPosts() {
        $query = "SELECT * FROM posts";
        return $this->fetchAll($query);
    }

    public function getPostById($id){
        $query = "SELECT * FROM posts WHERE id = :id";
        return $this->fetchWithParams($query, ['id' => $id]);
    }

    public function savePost($inputData){
        $query = "INSERT INTO posts (title, content) VALUES (:title, :content)";
        return $this->executeWithParams($query, $inputData);
    }

    public function updatePost($inputData, $id){
        $query = "UPDATE posts SET title = :title, content = :content WHERE id = :id";
        $inputData['id'] = $id;
        return $this->executeWithParams($query, $inputData);
    }

    public function deletePost($id){
        $query = "DELETE FROM posts WHERE id = :id";
        return $this->executeWithParams($query, ['id' => $id]);
    }
}
