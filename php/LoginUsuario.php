<?php

session_start();

include 'conexion.php';

$email = $_POST['email'];
$password = $_POST['password'];
$password = hash('sha256', $password);

$query = "SELECT * FROM usuarios WHERE email='$email' AND password='$password'";

$excute =  mysqli_query($conexion, $query);
if (mysqli_num_rows($excute) > 0) {
    $_SESSION['usuario'] = $email;
    header("Location: ../juegos.php");
} else {
    echo '
    <script>
        alert("Credenciales incorrectas");
        window.location.href = "../signin.html";
    </script>    
    ';
}

mysqli_close($conexion);