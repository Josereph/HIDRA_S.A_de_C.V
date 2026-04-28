<?php
// index.php

require_once __DIR__ . '/config/database.php';

// Autocarga simple de clases
spl_autoload_register(function ($class_name) {
    if (file_exists(__DIR__ . '/controllers/' . $class_name . '.php')) {
        require_once __DIR__ . '/controllers/' . $class_name . '.php';
    } elseif (file_exists(__DIR__ . '/models/' . $class_name . '.php')) {
        require_once __DIR__ . '/models/' . $class_name . '.php';
    }
});

// Enrutador Básico
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = ''; // Cambiar si está en subcarpeta, pero usaremos php -S que mapea a /

// Limpiar URI y parámetros GET
$uri = parse_url($request_uri, PHP_URL_PATH);
$uri = str_replace($base_path, '', $uri);

if ($uri === '/' || $uri === '') {
    $controllerName = 'ClientController';
    $action = 'index';
} else {
    $parts = explode('/', trim($uri, '/'));
    
    $controllerMap = [
        'clientes' => 'ClientController',
        'territorio' => 'TerritoryController',
        'configuracion' => 'ConfigController',
    ];

    $route = $parts[0] ?? '';
    $actionPart = $parts[1] ?? 'index';

    if (isset($controllerMap[$route])) {
        $controllerName = $controllerMap[$route];
        $action = $actionPart;
    } else {
        // 404
        http_response_code(404);
        die("404 - Ruta no encontrada");
    }
}

// Despachar a Controlador
if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        http_response_code(404);
        die("404 - Acción no encontrada");
    }
} else {
    http_response_code(404);
    die("404 - Controlador no encontrado");
}
