<?php

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;


function login(Request $request) {
    $validatedData = validateLoginData($request);

    $hardcodedUsername = 'admin';
    $hardcodedPassword = 'secret';

    if ($validatedData['username'] === $hardcodedUsername && $validatedData['password'] === $hardcodedPassword) {
        $token = generateJWT($validatedData['username']);

        return json_encode(['token' => $token]);
    } else {
        return json_encode(['error' => 'Invalid credentials'], JSON_PRETTY_PRINT);
    }
}


function generateJWT($username) {
    $payload = [
        'iat' => time(), 
        'exp' => time() + (60 * 60), 
        'username' => $username,
    ];

    return JWT::encode($payload, ENV['JWT_SECRET']);
}

