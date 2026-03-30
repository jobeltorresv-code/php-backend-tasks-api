<?php

require_once __DIR__ . '/../models/Task.php';

class TaskController {

    public function index() {
        header('Content-Type: application/json');

        try {
            $taskModel = new Task();
            $tasks = $taskModel->getAll();

            echo json_encode($tasks);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "error" => "Failed to fetch tasks",
                "message" => $e->getMessage()
            ]);
        }
    }
}