<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../signin.html");
    exit();
}

include 'conexion.php';

$idUsuario = $_SESSION['usuario']['id'];

// Calcular el nuevo nivel (incrementando el nivel actual de la sesión)
$newNivel = isset($_SESSION['nivel']) ? $_SESSION['nivel'] + 1 : 1;

// Verificar si el usuario ya tiene un registro en la tabla niveles
$queryCheck = "SELECT * FROM niveles WHERE usuario_id = '$idUsuario'";
$resultCheck = mysqli_query($conexion, $queryCheck);

if (mysqli_num_rows($resultCheck) > 0) {
    // Si existe, se actualiza el nivel y se refresca la fecha
    $queryUpdate = "UPDATE niveles SET nivel = '$newNivel', fecha = CURRENT_TIMESTAMP WHERE usuario_id = '$idUsuario'";
    mysqli_query($conexion, $queryUpdate);
} else {
    // Si no existe, se inserta un nuevo registro
    $queryInsert = "INSERT INTO niveles (usuario_id, nivel) VALUES ('$idUsuario', '$newNivel')";
    mysqli_query($conexion, $queryInsert);
}

mysqli_close($conexion);

// Actualizar la sesión con el nuevo nivel y reiniciar el arreglo de ejercicios
$_SESSION['nivel'] = $newNivel;
unset($_SESSION['ejercicios_suma']);

// Redirigir a la página de ejercicios para que se genere un nuevo set con el nivel actualizado
header("Location: ../ejercicios_sumas.php");
exit();