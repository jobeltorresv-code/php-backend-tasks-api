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

$router->post('/tasks', function() {
    $controller = new TaskController();
    $controller->store();
});
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

require_once __DIR__ . '/../controllers/TaskController.php';

$controller = new TaskController();

// GET /tasks
if ($requestMethod === 'GET' && $requestUri === '/tasks') {
    $controller->index();
}

// POST /tasks
elseif ($requestMethod === 'POST' && $requestUri === '/tasks') {
    $controller->store();
}

// GET /tasks/{id}
elseif ($requestMethod === 'GET' && preg_match('#^/tasks/(\d+)$#', $requestUri, $matches)) {
    $id = $matches[1];
    $controller->show($id);
}

// 404 fallback
else {
    http_response_code(404);
    echo json_encode([
        "error" => "Route not found"
    ]);
}