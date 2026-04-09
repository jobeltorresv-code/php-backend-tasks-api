<?php

class User
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(["email" => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO users (email, password)
            VALUES (:email, :password)
        ");

        $stmt->execute([
            "email" => $data['email'],
            "password" => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);

        return $this->conn->lastInsertId();
    }
}