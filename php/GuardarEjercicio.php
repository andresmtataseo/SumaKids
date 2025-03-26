<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    echo 'Debe iniciar sesión';
    exit();
}

$idUsuario = $_SESSION['usuario']['id'];
$numero1 = $_POST['numero1'];
$numero2 = $_POST['numero2'];
$resultadoCorrecto = $_POST['resultado_correcto'];
$respuesta_usuario = $_POST['resultado_usuario'];
$card_index = $_POST['card_index']; // Índice de la card
$nivel = $_POST['nivel']; // Nivel actual del usuario

if ($respuesta_usuario == $resultadoCorrecto) {
    // Se inserta el ejercicio junto con la posición de la card y el nivel
    $query = "INSERT INTO ejercicios (usuario_id, numero1, numero2, resultado_correcto, respuesta_usuario, card_index, nivel) 
              VALUES ('$idUsuario', '$numero1', '$numero2', '$resultadoCorrecto', '$respuesta_usuario', '$card_index', '$nivel')";
    $execute = mysqli_query($conexion, $query);

    if ($execute) {
        echo 'El ejercicio se guardó correctamente';
    } else {
        echo 'No se guardó correctamente';
    }
} else {
    echo 'La respuesta es incorrecta, no se guardó en la base de datos.';
}

mysqli_close($conexion);
?>
