<?php

require_once __DIR__ . '/../controllers/TaskController.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$controller = new TaskController();

// GET /
if ($requestMethod === 'GET' && $requestUri === '/') {
    echo json_encode([
        "message" => "API funcionando"
    ]);
}

// GET /tasks
elseif ($requestMethod === 'GET' && $requestUri === '/tasks') {
    $controller->index();
}

// POST /tasks
elseif ($requestMethod === 'POST' && $requestUri === '/tasks') {
    $controller->store();
}

// GET /tasks/{id}
elseif ($requestMethod === 'GET' && preg_match('#^/tasks/(\d+)$#', $requestUri, $matches)) {
    $controller->show($matches[1]);
}

// PUT /tasks/{id}
elseif ($requestMethod === 'PUT' && preg_match('#^/tasks/(\d+)$#', $requestUri, $matches)) {
    $controller->update($matches[1]);
}

// 404
else {
    http_response_code(404);
    echo json_encode([
        "error" => "Route not found"
    ]);
}