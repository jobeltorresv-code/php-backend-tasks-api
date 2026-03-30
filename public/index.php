<?php

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../routes/api.php';

// Inicializar router
$router = new Router();

// Cargar rutas
require_once __DIR__ . '/../routes/api.php';

// Ejecutar router
$router->resolve($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);