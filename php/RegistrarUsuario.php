<?php

include 'conexion.php';

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$password = $_POST['password'];

$query = "INSERT INTO usuarios (nombre, apellido, email, password)  VALUES ('$nombre', '$apellido', '$email', '$password')";
$queryVerificar = "SELECT * FROM usuarios WHERE email='$email'";

// Verificamos
$excuteVerificar = mysqli_query($conexion, $queryVerificar);
if (mysqli_num_rows($excuteVerificar) > 0) {
    echo '
    <script>
        alert("El correo ingresado ya se encuentra registrado");
        window.location.href = "../signup.html";
    </script>';
} else {
    // Insertamos nuevo usuario
    $excute = mysqli_query($conexion, $query);
    if ($excute) {
        echo '
    <script>
        alert("Usuario registrado");
        window.location.href = "../signin.html";
    </script>';
    } else {
        echo '
    <script>
        alert("Usuario no registrado");
        window.location.href = "../signup.html";
    </script>';
    }
}

mysqli_close($conexion);