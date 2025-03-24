<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    echo '
    <script>
        alert("Debe iniciar session");
        window.location = "signin.html";
    </script>
    ';
    session_destroy();
    die();
}

?>

<!doctype html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SumaKids - Juegos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <div class="col-md-3 mb-2 mb-md-0">
            <a href="index.html" class="d-inline-flex link-body-emphasis text-decoration-none">
                <img class="bi" width="100" height="32" role="img" aria-label="Bootstrap" src="assets/logo.png"/>
            </a>
        </div>

        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><a href="index.html" class="nav-link px-2 link-secondary">Inicio</a></li>
            <li><a href="juegos.php" class="nav-link px-2">Juegos</a></li>
            <li><a href="faqs.html" class="nav-link px-2">FAQs</a></li>
            <li><a href="nosotros.html" class="nav-link px-2">Nosotros</a></li>
        </ul>

        <div class="col-md-3 text-end">
            <a href="php/CerrarSesion.php" class="btn btn-outline-primary me-2">Cerrar Sesión</a>
        </div>
    </header>
</div>

<div class="container">
    <div class="row"> <!-- Fila para las cards -->
        <!-- Card 2 -->
        <div class="col-md-6 mb-4"> <!-- Cada card ocupa 6 columnas en pantallas medianas y grandes -->
            <a href="ejercicios_sumas.php" style="text-decoration: none"> <!-- Enlace a la página de ejercicios de sumas -->
                <div class="card h-100"> <!-- Asegura que todas las cards tengan la misma altura -->
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="assets/logo%20con%20fondo.png" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Ejercicios de Sumas</h5>
                                <p class="card-text">Practica sumas simples con números de tres cifras y dos filas.
                                    Ejercicios diseñados para niños que están aprendiendo a sumar.</p>
                                <p class="card-text"><small class="text-body-secondary">Última actualización hace 3
                                        minutos</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card 1 -->
        <div class="col-md-6 mb-4"> <!-- Cada card ocupa 6 columnas en pantallas medianas y grandes -->
            <div class="card h-100"> <!-- Asegura que todas las cards tengan la misma altura -->
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="assets/logo%20con%20fondo.png" class="img-fluid rounded-start" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Ejercicios de multiplicación (No disponible)</h5>
                            <p class="card-text">Practica multiplicaciones simples con números de tres cifras.
                                Ejercicios diseñados para
                                niños que están aprendiendo a multiplicar.</p>
                            <p class="card-text"><small class="text-body-secondary">Muy pronto...</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<div class="container">
    <footer class="py-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a href="index.html" class="nav-link px-2 text-body-secondary">Inicio</a></li>
            <li class="nav-item"><a href="juegos.php" class="nav-link px-2 text-body-secondary">Juegos</a></li>
            <li class="nav-item"><a href="faqs.html" class="nav-link px-2 text-body-secondary">FAQs</a></li>
            <li class="nav-item"><a href="nosotros.html" class="nav-link px-2 text-body-secondary">Nosotros</a></li>
        </ul>
        <p class="text-center text-body-secondary">© 2025 SumaKids, Inc</p>
    </footer>
</div>

<script src="https://kit.fontawesome.com/fb92c26a74.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>