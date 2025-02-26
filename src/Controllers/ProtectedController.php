<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProtectedController
{
    public function protectedRoute()
    {
        // Check if the Authorization header is set
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if (!$authHeader) {
            return new JsonResponse(['error' => 'Authorization header not found.'], 401);
        }

        // Extract the token from the header
        list($type, $token) = explode(" ", $authHeader);

        // Check if the token type is Bearer
        if (strcasecmp($type, 'Bearer') !== 0) {
            return new JsonResponse(['error' => 'Invalid token type.'], 401);
        }

        // Retrieve the JWT secret from environment variables
        $secret = $_ENV['JWT_SECRET'] ?? null;

        if (!$secret) {
            return new JsonResponse(['error' => 'JWT secret is not set.'], 500);
        }

        try {
            // Decode the JWT token
            $decoded = JWT::decode($token, $secret, ['HS256']);

            // Return a welcome message or dummy data
            return new JsonResponse([
                'message' => 'Welcome, ' . $decoded->username . '!',
                'data' => [
                    'example' => 'This is some protected data.'
                ]
            ]);
        } catch (ExpiredException $e) {
            return new JsonResponse(['error' => 'Unauthorized: Token is expired.'], 401);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Unauthorized: Token is invalid.'], 401);
        }
    }
}
