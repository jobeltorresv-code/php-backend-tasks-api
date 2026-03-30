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
}