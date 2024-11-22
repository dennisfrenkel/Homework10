<?php

namespace app\models; 

use app\models\Model;
use PDO;
use PDOException;
use Exception;

class Posts {
    private $db;
    private $id;
    private $title;
    private $content;
    private $created_at;
    private $updated_at;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllPosts(): array
    {
        try {
            $sql = "SELECT * FROM posts";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Couldn't receive posts");
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public static function fromArray(array $data): self
    {
        $post = new self($data['db'] ?? null);
        $post->id = $data['id'] ?? null;
        $post->title = $data['title'] ?? '';
        $post->content = $data['content'] ?? '';
        $post->created_at = $data['created_at'] ?? null;
        $post->updated_at = $data['updated_at'] ?? null;
        return $post;
    }

    public function savePost(array $data): bool
    {
        $sql = "INSERT INTO posts (title, content, created_at) VALUES (:title, :content, :created_at)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':created_at', $data['created_at']);

        if ($stmt->execute()) {
            $this->id = $this->db->lastInsertId(); 
            return true;
        } else {
            throw new Exception('Failed to save post.');
        }
    }
    
    public function deletePost(array $data): bool
    {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
    
        return $stmt->execute(); 
    }

    public function updatePost(int $id, array $data): bool
    {
        $sql = "UPDATE posts SET title = :title, content = :content, updated_at = :updated_at WHERE id = :id";
        $stmt = $this->db->prepare($sql);
    
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
    
        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception('Failed to update post.');
        }
    }

    public function getPostById(int $id): array
    {
        $sql = "SELECT * FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($post) {
            return $post;
        } else {
            throw new Exception("Post with ID $id not found.");
        }
    }
}
?>
