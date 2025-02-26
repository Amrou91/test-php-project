<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProtectedController {
    public function protectedRoute(Request $request) {
        $authHeader = $request->headers->get('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return new JsonResponse(['error' => 'Token required'], 401);
        }

        try {
            $decoded = JWT::decode($matches[1], new Key(getenv('JWT_SECRET'), 'HS256'));
            return new JsonResponse(['message' => 'Welcome, ' . $decoded->username]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid token'], 401);
        }
    }
}
