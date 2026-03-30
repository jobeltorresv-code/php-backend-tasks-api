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
    public function store() {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['title']) || empty(trim($input['title']))) {
            http_response_code(400);
            echo json_encode([
                "error" => "Validation failed",
                "message" => "Title is required"
            ]);
            return;
        }

        try {
            $taskModel = new Task();
            $success = $taskModel->create($input);

            if ($success) {
                http_response_code(201);
                echo json_encode([
                    "message" => "Task created successfully"
                ]);
            } else {
                throw new Exception("Insert failed");
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "error" => "Failed to create task",
                "message" => $e->getMessage()
            ]);
        }
    }
}