<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController
{
    public function login(array $data)
        {
            // Validate input
            if (!isset($data['username']) || !isset($data['password'])) {
                return new JsonResponse(['error' => 'Username and password are required.'], 400);
            }

            // Verify the username and password
            if ($data['username'] !== 'admin' || $data['password'] !== 'secret') {
                return new JsonResponse(['error' => 'Unauthorized'], 401);
            }

            // Generate JWT token
            $payload = [
                'username' => $data['username'],
                'iat' => time(),
                'exp' => time() + 3600 // Token valid for 1 hour
            ];
            
            $secret = getenv('JWT_SECRET');
            if (!$secret) {
                return new JsonResponse(['error' => 'JWT secret is not set.'], 500);
            }

            $jwt = JWT::encode($payload, $secret, 'HS256');

            return new JsonResponse(['token' => $jwt]);
        }
}
