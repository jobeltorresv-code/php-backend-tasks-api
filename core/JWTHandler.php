<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler
{
    private static $secret = "a8f9d2c1e7b3f6a9c4e2d1b7a8c9e0f123456789abcdef9876543210fedcba";
    public static function generate($user)
    {
        $payload = [
            "iss" => "your-app",
            "iat" => time(),
            "exp" => time() + (60 * 60), // 1 hora
            "data" => [
                "id" => $user['id'],
                "email" => $user['email']
            ]
        ];

        return JWT::encode($payload, self::$secret, 'HS256');
    }

    public static function validate($token)
    {
        try {
            return JWT::decode($token, new Key(self::$secret, 'HS256'));
        } catch (Exception $e) {
            throw new Exception("Invalid token", 401);
        }
    }
}