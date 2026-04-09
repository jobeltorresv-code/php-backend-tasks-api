<?php

require_once __DIR__ . '/../core/Database.php';

class Task {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT * FROM tasks 
            WHERE deleted_at IS NULL
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO tasks (title, description, created_at)
            VALUES (:title, :description, NOW())
        ");

        return $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'] ?? null
        ]);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT * FROM tasks 
            WHERE id = :id 
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE tasks 
            SET title = :title,
                description = :description
            WHERE id = :id
            AND deleted_at IS NULL
        ");

        $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'] ?? null,
            ':id' => $id
        ]);

        return $stmt->rowCount();
    }
}