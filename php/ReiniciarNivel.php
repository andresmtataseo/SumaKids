<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo '<script>
            alert("Debe iniciar sesión");
            window.location = "signin.html";
          </script>';
    session_destroy();
    exit();
}

include 'conexion.php';

$idUsuario = $_SESSION['usuario']['id'];
$nivel = $_SESSION['nivel'];

// Eliminar de la base de datos los ejercicios resueltos (aquellos que tienen definido card_index)
// correspondientes al nivel actual del usuario
$queryDelete = "DELETE FROM ejercicios WHERE usuario_id = '$idUsuario' AND nivel = '$nivel' AND card_index IS NOT NULL";
$execute = mysqli_query($conexion, $queryDelete);

if ($execute) {
    // Limpiar el arreglo de ejercicios en la sesión para forzar la generación de ejercicios nuevos
    unset($_SESSION['ejercicios_suma']);
    // Redirigir al usuario a la página de ejercicios para que se regeneren los ejercicios
    header("Location: ../ejercicios_sumas.php");
    exit();
} else {
    echo "Error al reiniciar el nivel.";
}

mysqli_close($conexion);
