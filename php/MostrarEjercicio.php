<?php
session_start();
include 'conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    echo '
    <script>
        alert("Debe iniciar sesión");
        window.location = "signin.html";
    </script>
    ';
    exit();
}

$idUsuario = $_SESSION['usuario']['id'];

$query = "SELECT * FROM ejercicios WHERE usuario_id = '$idUsuario' ORDER BY fecha_realizacion DESC";
$execute = mysqli_query($conexion, $query);

// Inicializar una lista de ejercicios resueltos
$ejerciciosResueltos = [];

// Verificar si hay resultados
if (mysqli_num_rows($execute) > 0) {
    // Iterar sobre los resultados
    while ($row = mysqli_fetch_assoc($execute)) {
        $numero1 = $row['numero1'];
        $numero2 = $row['numero2'];
        $resultadoUsuario = $row['resultado_usuario'];

        // Crear un ejercicio y agregarlo a la lista
        $ejerciciosResueltos[] = [
            'numero1' => $numero1,
            'numero2' => $numero2,
            'respuesta' => $resultadoUsuario
        ];
    }

    // Devolver la lista de ejercicios resueltos
    echo json_encode($ejerciciosResueltos);
} else {
    echo '<p>No has realizado ningún ejercicio aún.</p>';
}

mysqli_close($conexion);