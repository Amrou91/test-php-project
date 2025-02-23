<?php
require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Factory\AppFactory;


$app = AppFactory::create();
$app->get('/api/protected', function ($request, $response, $args) {
    $headers = getallheaders();
    if (!isset($headers["Authorization"])) {
        return $response->withJson(["message" => "Access denied"], 403);
    }

    $jwt = str_replace("Bearer ", "", $headers["Authorization"]);

    try {
        $decoded = JWT::decode($jwt, new Key($_ENV['JWT_SECRET'], 'HS256'));
        return $response->withJson(["message" => "Access granted", "id" => $decoded->sub]);
    } catch (Exception $e) {
        return $response->withJson(["message" => "Invalid token", "error" => $e->getMessage()], 401);
    }
});

?>
