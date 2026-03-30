<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../core/Router.php';

// Inicializar router
$router = new Router();

// Cargar rutas 
require_once __DIR__ . '/../routes/api.php';

// Ejecutar router
$router->resolve($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);