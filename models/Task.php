<?php

require_once __DIR__ . '/../core/Database.php';

class Task {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM tasks ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
     public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO tasks (title, description, completed)
            VALUES (:title, :description, :completed)
        ");

        return $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'] ?? null,
            ':completed' => $data['completed'] ?? 0
        ]);
    }

    public function getById($id) {
    $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);

    return $stmt->fetch();
}
}
