<?php

require_once __DIR__ . '/../controllers/TaskController.php';

$router->get('/tasks', function() {
    $controller = new TaskController();
    $controller->index();
});
$router->get('/', function () {
    echo json_encode([
        "message" => "API funcionando"
    ]);
});