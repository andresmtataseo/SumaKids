<?php
class Database {
    private $host = "localhost"; // Cambiar cuando subas el proyecto a un hosting
    private $db_name = "sumakids"; // Cambiar si el nombre de la base de datos es diferente en hosting
    private $username = "root"; // Cambiar si el usuario de la base de datos es diferente en hosting
    private $password = ""; // Cambiar cuando subas a producción
    public $conn;

    public static function getConnection() {
        $db = new Database();
        $db->conn = null;
        try {
            $db->conn = new PDO("mysql:host=" . $db->host . ";dbname=" . $db->db_name, $db->username, $db->password);
            $db->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $db->conn;
    }
}