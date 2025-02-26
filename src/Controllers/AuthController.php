<?php

namespace App\Controllers;

use Firebase\JWT\JWT; // Import the JWT library for token encoding
use Symfony\Component\HttpFoundation\JsonResponse; // Import JsonResponse for sending JSON responses

class AuthController
{
    // Login function to authenticate users and generate a JWT token
    public function login(array $data)
    {
        // Log the input data for debugging purposes
        error_log(print_r($data, true));

        // Validate input: check if username and password are provided
        if (!isset($data['username']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Username and password are required.'], 400);
        }

        // Verify the username and password against hardcoded values
        if ($data['username'] !== 'admin' || $data['password'] !== 'secret') {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        // Create the payload for the JWT token
        $payload = [
            'username' => $data['username'], // User's username
            'iat' => time(), // Issued at time (current time)
            'exp' => time() + 3600 // Expiration time (1 hour from now)
        ];

        // Retrieve the JWT secret from the environment variables
        $secret = $_ENV['JWT_SECRET'] ?? null;

        // Check if the JWT secret is set
        if (!$secret) {
            return new JsonResponse(['error' => 'JWT secret is not set.'], 500);
        }

        // Encode the payload to create a JWT token
        $jwt = JWT::encode($payload, $secret, 'HS256');

        // Return the generated token as a JSON response
        return new JsonResponse(['token' => $jwt]);
    }
}
