<?php
// index.php

require_once __DIR__ . '/config/database.php';

spl_autoload_register(function ($class_name) {
    if (file_exists(__DIR__ . '/controllers/' . $class_name . '.php')) {
        require_once __DIR__ . '/controllers/' . $class_name . '.php';
    } elseif (file_exists(__DIR__ . '/models/' . $class_name . '.php')) {
        require_once __DIR__ . '/models/' . $class_name . '.php';
    }
});

$request_uri = $_SERVER['REQUEST_URI'];

$base_path = '/HIDRA_S.A_de_C.V';

$uri = parse_url($request_uri, PHP_URL_PATH);

if (strpos($uri, $base_path) === 0) {
    $uri = substr($uri, strlen($base_path));
}

$uri = '/' . trim($uri, '/');

if ($uri === '/' || $uri === '') {
    header('Location: ' . $base_path . '/views/login.php');
    exit;
} else {
    $parts = explode('/', trim($uri, '/'));

    $controllerMap = [
        'login' => 'AuthController',
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
        http_response_code(404);
        die("404 - Ruta no encontrada: " . htmlspecialchars($route));
    }
}

if (class_exists($controllerName)) {
    $controller = new $controllerName();

    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        http_response_code(404);
        die("404 - Acción no encontrada: " . htmlspecialchars($action));
    }
} else {
    http_response_code(404);
    die("404 - Controlador no encontrado: " . htmlspecialchars($controllerName));
}