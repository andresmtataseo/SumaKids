<?php

require_once __DIR__ . '/../services/User.service.php';
require_once __DIR__ . '/utils/ResponseMethods.php';

class UserController {

    private $requestMethod;
    private $userService;

    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new UserController();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->userService = UserService::getInstance();
    }

    public function processRequest() {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        switch ($this->requestMethod) {
            case 'GET':
                $this->getCase();
                break;
            case 'POST':
                // Obtener los datos del input
                $input = json_decode(file_get_contents("php://input"), true);

                // Verificar si faltan los datos necesarios para el login
                if (isset($input['email']) && isset($input['password']) && !isset($input['nombre']) && !isset($input['apellido'])) {
                    // Si solo email y password, es login
                    $this->postCaseLogin($input);
                } elseif (isset($input['nombre']) && isset($input['apellido']) && isset($input['email']) && isset($input['password'])) {
                    // Si también se incluye nombre y apellido, es registro
                    $this->postCaseRegister($input);
                } else {
                    ResponseMethods::printError(400, "Faltan datos requeridos.");
                }
                break;
            default:
                ResponseMethods::printError(400);
                break;
        }
    }


    // HTTP methods cases

    private function getCase() {

    }

    private function postCaseLogin($input) {
        if (!isset($input['email']) || !isset($input['password'])) {
            ResponseMethods::printError(400, "Faltan datos requeridos.");
            return;
        }

        $this->authenticateUser($input['email'], $input['password']);
    }

    private function postCaseRegister($input) {
        if (!isset($input['nombre']) || !isset($input['apellido']) || !isset($input['email']) || !isset($input['password'])) {
            ResponseMethods::printError(400, "Faltan datos requeridos.");
            return;
        }

        $this->registerUser($input['nombre'], $input['apellido'], $input['email'], $input['password']);
    }


    // Private methods

    private function authenticateUser($email, $password) {
        try {
            $usuario = $this->userService->autenticarUsuario($email, $password);

            if ($usuario) {
                session_start();
                $_SESSION['usuario'] = $usuario;
                ResponseMethods::printJSON("SUCCESS", $usuario);
            } else {
                ResponseMethods::printError(401, "Credenciales incorrectas.");
            }
        } catch (Exception $ex) {
            ResponseMethods::printError(500, "Ocurrió un error inesperado. Por favor, intente nuevamente.");
        }
    }

    private function registerUser($nombre, $apellido, $email, $password) {
        try {
            $usuario = $this->userService->registrarUsuario($nombre, $apellido, $email, $password);

            if ($usuario) {
                ResponseMethods::printJSON("SUCCESS", $usuario);
            } else {
                ResponseMethods::printError(409, "El correo electrónico ya está registrado.");
            }
        } catch (Exception $ex) {
            ResponseMethods::printError(500, "Ocurrió un error inesperado. Por favor, intente nuevamente.");
        }
    }

}
