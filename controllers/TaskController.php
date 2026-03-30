<?php

class TaskController {

    public function index() {
        header('Content-Type: application/json');

        echo json_encode([
            "message" => "Lista de tareas (placeholder)"
        ]);
    }
}