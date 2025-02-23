<?php
require '../vendor/autoload.php';
require '../config/database.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Factory\AppFactory;


$app = AppFactory::create();
$app->post('/api/login', function ($request, $response, $args) {
    $database = new Database();
    $conn = $database->getConnection();
    
    $data = json_decode($request->getBody()->getContents(), true);
    if (!isset($data['email']) || !isset($data['password'])) {
        return $response->withJson(["message" => "Invalid input"], 400);
    }

    $query = "SELECT id, password FROM users WHERE email = :email LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $data['email']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($data['password'], $user["password"])) {
        $payload = [
            "iss" => "http://localhost",
            "iat" => time(),
            "exp" => time() + 3600,
            "sub" => $user["id"]
        ];

        $jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
        return $response->withJson(["token" => $jwt]);
    } else {
        return $response->withJson(["message" => "Invalid credentials"], 401);
    }
});

$app->run();
?>
