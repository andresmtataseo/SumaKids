<?php
session_start();
include 'conexion.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Validar entrada
if (!isset($_POST['email']) || empty(trim($_POST['email']))) {
    echo 'Por favor, ingresa tu correo electrónico.';
    exit();
}

$email = mysqli_real_escape_string($conexion, trim($_POST['email']));

// Verificar formato de correo
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Formato de correo inválido.';
    exit();
}

// Consulta segura
$query = "SELECT password FROM usuarios WHERE email = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Verificar si el correo existe
if ($row = mysqli_fetch_assoc($result)) {
    $password = $row['password'];

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'grupo4pw.miuni.kids';
        $mail->SMTPAuth = true;
        $mail->Username = 'recuperacion@grupo4pw.miuni.kids';
        $mail->Password = 'rotaria2001.';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('recuperacion@grupo4pw.miuni.kids', 'Soporte');
        $mail->addAddress($email);
        $mail->Subject = 'Recuperacion de contrasena';
        $mail->Body = "Hola, tu contraseña actual es:\n\n$password\n\nTe recomendamos guardarla en un lugar seguro.";

        $mail->send();

        echo 'Se ha enviado un correo con tu contraseña.';
    } catch (Exception $e) {
        echo 'Error al enviar el correo: ' . $mail->ErrorInfo . '.';
    }
} else {
    echo 'El correo no está registrado.';
}

// Cerrar conexión
mysqli_stmt_close($stmt);
mysqli_close($conexion);
