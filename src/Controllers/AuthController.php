<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Illuminate\Validation\Factory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController {
    public function login(Request $request) {
        $data = json_decode($request->getContent(), true);

        $validator = new Factory();
        $validation = $validator->make($data, [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validation->fails()) {
            return new JsonResponse(['error' => $validation->errors()], 400);
        }

        if ($data['username'] !== 'admin' || $data['password'] !== 'secret') {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $payload = [
            'username' => $data['username'],
            'iat' => time(),
            'exp' => time() + 3600
        ];
        $jwt = JWT::encode($payload, getenv('JWT_SECRET'), 'HS256');

        return new JsonResponse(['token' => $jwt]);
    }
}
