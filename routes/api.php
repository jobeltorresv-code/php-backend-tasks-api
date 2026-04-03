<?php

require_once __DIR__ . '/../controllers/TaskController.php';

// Cargar config
$config = require __DIR__ . '/../config/database.php';

// Crear conexión PDO
try {
    $conn = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8",
        $config['username'],
        $config['password']
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Database connection failed",
        "message" => $e->getMessage()
    ]);
    exit;
}

// Instanciar controller
$controller = new TaskController($conn);

// Request
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

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

// DELETE /tasks/{id}
elseif ($requestMethod === 'DELETE' && preg_match('#^/tasks/(\d+)$#', $requestUri, $matches)) {
    $controller->delete($matches[1]);
}

// 404
else {
    http_response_code(404);
    echo json_encode([
        "error" => "Route not found"
    ]);
}