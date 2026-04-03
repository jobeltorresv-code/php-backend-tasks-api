<?php

require_once __DIR__ . '/../models/Task.php';

class TaskController {
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function index() {
        header('Content-Type: application/json');

        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM tasks WHERE deleted_at IS NULL"
            );
            $stmt->execute();

            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    public function show($id) {
        header('Content-Type: application/json');

        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM tasks WHERE id = :id AND deleted_at IS NULL"
            );
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $task = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$task) {
                http_response_code(404);
                echo json_encode([
                    "error" => "Task not found"
                ]);
                return;
            }

            echo json_encode($task);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "error" => "Failed to fetch task",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function update($id) {
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
            // Validar que exista y NO esté eliminada
            $stmt = $this->conn->prepare(
                "SELECT id FROM tasks WHERE id = :id AND deleted_at IS NULL"
            );
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode([
                    "error" => "Task not found"
                ]);
                return;
            }

            $taskModel = new Task();
            $updatedRows = $taskModel->update($id, $input);

            echo json_encode([
                "message" => "Task updated successfully",
                "updated" => $updatedRows
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "error" => "Failed to update task",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        header('Content-Type: application/json');

        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode([
                "error" => "Invalid ID"
            ]);
            return;
        }

        try {
            // Soft delete + validación integrada
            $stmt = $this->conn->prepare(
                "UPDATE tasks 
                 SET deleted_at = NOW() 
                 WHERE id = :id AND deleted_at IS NULL"
            );

            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode([
                    "error" => "Task not found or already deleted"
                ]);
                return;
            }

            echo json_encode([
                "message" => "Task deleted successfully (soft delete)"
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "error" => "Failed to delete task",
                "message" => $e->getMessage()
            ]);
        }
    }
}