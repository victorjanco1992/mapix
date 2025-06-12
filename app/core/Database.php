<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            // Configuración del DSN con el charset utf8mb4
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            
            // Opciones de PDO, incluyendo el atributo para establecer el charset
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT            => 30,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            ];
        
            // Crear la instancia de PDO
            $this->pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
            
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}

