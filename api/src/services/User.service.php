<?php

require_once __DIR__ . '/../models/User.model.php';
require_once __DIR__ . '/../../../config/Database.php';

class UserService {

    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new UserService();
        }
        return self::$instance;
    }

    public function autenticarUsuario($email, $password) {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT id, nombre, apellido, email, password FROM usuarios WHERE email = ?");
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
           // if (password_verify($password, $usuario['password'])) {
            if ($password === $usuario['password']) {
                return [
                    'id' => $usuario['id'],
                    'nombre' => $usuario['nombre'],
                    'apellido' => $usuario['apellido'],
                    'email' => $usuario['email']
                ];
            }
        }
        return null;
    }

    public function registrarUsuario($nombre, $apellido, $email, $password) {
        $db = Database::getConnection();

        // Verificar si el correo ya está registrado
        $stmt = $db->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->execute();
        $usuarioExistente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuarioExistente) {
            // Si el correo ya está registrado, devolver null
            return null;
        }

        // Insertar el nuevo usuario en la base de datos
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(2, $apellido, PDO::PARAM_STR);
        $stmt->bindParam(3, $email, PDO::PARAM_STR);
        $stmt->bindParam(4, $password, PDO::PARAM_STR); // No estamos usando hashing en este caso, pero puedes añadirlo si es necesario

        $stmt->execute();

        // Devolver los datos del nuevo usuario, excepto la contraseña
        return [
            'id' => $db->lastInsertId(),
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email
        ];
    }

}