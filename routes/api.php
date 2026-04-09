<?php

require_once __DIR__ . '/../controllers/TaskController.php';
require_once __DIR__ . '/../services/TaskService.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Validator.php';

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/JWTHandler.php';

// Config DB
$config = require __DIR__ . '/../config/database.php';

// Conexión
try {
    $conn = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8",
        $config['username'],
        $config['password']
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    Response::error("Database connection failed", 500);
    exit;
}

// 🔥 DI
$taskModel = new Task($conn);
$taskService = new TaskService($taskModel);
$taskController = new TaskController($taskService);

$authController = new AuthController($conn);

// Request
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// ROOT
if ($requestMethod === 'GET' && $requestUri === '/') {
    Response::success(["message" => "API funcionando"]);
}

// AUTH 🔥
elseif ($requestMethod === 'POST' && $requestUri === '/auth/register') {
    $authController->register();
}

elseif ($requestMethod === 'POST' && $requestUri === '/auth/login') {
    $authController->login();
}

// TASKS
elseif ($requestMethod === 'GET' && $requestUri === '/tasks') {
    $taskController->index();
}

elseif ($requestMethod === 'POST' && $requestUri === '/tasks') {
    $taskController->store();
}

elseif ($requestMethod === 'GET' && preg_match('#^/tasks/(\d+)$#', $requestUri, $matches)) {
    $taskController->show($matches[1]);
}

elseif ($requestMethod === 'PUT' && preg_match('#^/tasks/(\d+)$#', $requestUri, $matches)) {
    $taskController->update($matches[1]);
}

elseif ($requestMethod === 'DELETE' && preg_match('#^/tasks/(\d+)$#', $requestUri, $matches)) {
    $taskController->delete($matches[1]);
}

elseif ($requestMethod === 'PATCH' && preg_match('#^/tasks/(\d+)/restore$#', $requestUri, $matches)) {
    $taskController->restore($matches[1]);
}

// 404
else {
    Response::error("Route not found", 404);
}