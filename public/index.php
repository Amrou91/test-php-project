<?php

require '../vendor/autoload.php';

use Dotenv\Dotenv;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/api/uuid', ['App\Controllers\UuidController', 'generateUuid']);
    $r->addRoute('POST', '/api/login', ['App\Controllers\AuthController', 'login']);
    $r->addRoute('GET', '/api/protected', ['App\Controllers\ProtectedController', 'protectedRoute']);  
});

// Get HTTP request
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(["error" => "Route not found"]);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        
        [$class, $method] = $handler;
        $controller = new $class();
        
        // Read JSON input for POST requests
        if ($httpMethod === 'POST' && $uri === '/api/login') {
            $data = json_decode(file_get_contents('php://input'), true); // Get JSON input
            $response = $controller->$method($data); // Get the response from the controller
            echo $response; // Output the response
        } else {
            echo json_encode($controller->$method($vars)); // Handle other requests normally
        }
        break;
        
}
