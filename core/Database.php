<?php

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $config = require __DIR__ . '/../config/database.php';

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";

        try {
            $this->connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // errores reales
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // arrays asociativos
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                "error" => "Database connection failed",
                "message" => $e->getMessage()
            ]);
            exit;
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}