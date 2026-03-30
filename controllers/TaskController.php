<?php

require_once __DIR__ . '/../core/Database.php';

class TaskController {

    public function index() {
        header('Content-Type: application/json');

        try {
            $db = Database::getInstance()->getConnection();

            $stmt = $db->query("SELECT 1");

            echo json_encode([
                "message" => "Conexión a DB exitosa"
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "error" => "DB test failed",
                "message" => $e->getMessage()
            ]);
        }
    }
}