<?php

// Load the Composer autoloader
require '../vendor/autoload.php';

// Import necessary classes
use Dotenv\Dotenv; // For loading environment variables
use FastRoute\RouteCollector; // For route collection
use function FastRoute\simpleDispatcher; // Function to create a dispatcher

// Load environment variables from the .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Create a dispatcher to handle routing
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    // Define routes for the application
    $r->addRoute('GET', '/api/uuid', ['App\Controllers\UuidController', 'generateUuid']); // Route for UUID generation
    $r->addRoute('POST', '/api/login', ['App\Controllers\AuthController', 'login']); // Route for user login
    $r->addRoute('GET', '/api/protected', ['App\Controllers\ProtectedController', 'protectedRoute']); // Protected route requiring authentication
});

// Get the HTTP request method (e.g., GET, POST)
$httpMethod = $_SERVER['REQUEST_METHOD'];
// Parse the URI to extract the path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Dispatch the request to the appropriate route
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// Handle the dispatch result
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // Return a 404 error if the route is not found
        http_response_code(404);
        echo json_encode(["error" => "Route not found"]); // Respond with an error message
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        // Return a 405 error if the request method is not allowed for the route
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]); // Respond with an error message
        break;

    case FastRoute\Dispatcher::FOUND:
        // If the route is found, get the handler and any variables from the route
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        // Separate the controller class and method from the handler
        [$class, $method] = $handler;
        // Create an instance of the controller
        $controller = new $class();

        // Check if the request is a POST to the login route
        if ($httpMethod === 'POST' && $uri === '/api/login') {
            // Read JSON input from the request body
            $data = json_decode(file_get_contents('php://input'), true); // Decode JSON into an associative array
            // Call the login method on the controller and get the response
            $response = $controller->$method($data);
            // Output the response as JSON
            echo $response;
        } else {
            // For other requests, call the method without additional input data
            echo json_encode($controller->$method($vars)); // Handle other requests and output the response
        }
        break;
}
