<?php

class AuthService
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new User($db);
    }

    public function register($data)
    {
        $existing = $this->userModel->findByEmail($data['email']);

        if ($existing) {
            throw new Exception("Email already exists", 400);
        }

        $id = $this->userModel->create($data);

        return ["id" => $id, "email" => $data['email']];
    }

    public function login($data)
    {
        $user = $this->userModel->findByEmail($data['email']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            throw new Exception("Invalid credentials", 401);
        }

        $token = JWTHandler::generate($user);

        return ["token" => $token];
    }
}