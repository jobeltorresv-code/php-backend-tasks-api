<?php

class AuthController
{
    private $authService;

    public function __construct($db)
    {
        $this->authService = new AuthService($db);
    }

    public function register()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            Validator::required(['email', 'password'], $data);

            $user = $this->authService->register($data);

            Response::success($user, "User created", 201);

        } catch (Exception $e) {
            Response::error($e->getMessage(), $e->getCode());
        }
    }

    public function login()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            Validator::required(['email', 'password'], $data);

            $result = $this->authService->login($data);

            Response::success($result, "Login successful");

        } catch (Exception $e) {
            Response::error($e->getMessage(), $e->getCode());
        }
    }
}