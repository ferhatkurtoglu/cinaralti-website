<?php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;
    
    public function __construct() {
        $this->host = defined('DB_HOST') ? DB_HOST : 'localhost';
        $this->db_name = defined('DB_NAME') ? DB_NAME : 'cinaralti_db';
        $this->username = defined('DB_USER') ? DB_USER : 'root';
        $this->password = defined('DB_PASS') ? DB_PASS : '';
        
        // Bağlantıyı kurur
        $this->getConnection();
    }
    
    public function getConnection() {
        if ($this->conn === null) {
            try {
                // MySQL socket yolu varsa kullan
                $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
                if (defined('DB_SOCKET') && !empty(DB_SOCKET)) {
                    $dsn = "mysql:unix_socket=" . DB_SOCKET . ";dbname=" . $this->db_name . ";charset=utf8mb4";
                }
                
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch(PDOException $exception) {
                echo "Connection error: " . $exception->getMessage();
                return null;
            }
        }
        
        return $this->conn;
    }
    
    public function prepare($sql) {
        $conn = $this->getConnection();
        if ($conn === null) {
            throw new Exception("Veritabanı bağlantısı kurulamadı");
        }
        return $conn->prepare($sql);
    }
    
    public function query($sql) {
        return $this->getConnection()->query($sql);
    }
    
    public function lastInsertId() {
        return $this->getConnection()->lastInsertId();
    }
    
    public function beginTransaction() {
        return $this->getConnection()->beginTransaction();
    }
    
    public function commit() {
        return $this->getConnection()->commit();
    }
    
    public function rollback() {
        return $this->getConnection()->rollback();
    }
} 