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
    <link rel="icon" type="image/png" href="assets/logito.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <div class="col-md-3 mb-2 mb-md-0">
            <a class="d-inline-flex link-body-emphasis text-decoration-none">
                <img class="bi" role="img" aria-label="Bootstrap" src="assets/logo.png"/>
            </a>
        </div>

        <div class="col-md-6 text-center">
            <p class="fw-bold" style="margin-bottom: 1px !important;"><?php echo $_SESSION['usuario']['nombre'] . " " . $_SESSION['usuario']['apellido']; ?></p>
            <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 1px !important;"><?php echo $_SESSION['usuario']['email']; ?></p>
        </div>

        <div class="col-md-3 text-end">
            <a href="javascript:void(0);" class="btn btn-outline-primary me-2" onclick="mostrarModal()">Cerrar Sesión</a>
        </div>
    </header>
</div>

<main class="flex-grow-1">
    <div class="container">
        <div class="row"> <!-- Fila para las cards -->
            <!-- Card 2 -->
            <div class="col-md-6 mb-4"> <!-- Cada card ocupa 6 columnas en pantallas medianas y grandes -->
                <a href="ejercicios_sumas.php" style="text-decoration: none">
                    <!-- Enlace a la página de ejercicios de sumas -->
                    <div class="card" style="min-height: 300px;"> <!-- Se establece una altura mínima -->
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="assets/logo%20con%20fondo.png" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Ejercicios de Sumas</h5>
                                    <p class="card-text">Practica sumas simples con números de tres cifras y dos filas.
                                        Ejercicios diseñados para niños que están aprendiendo a sumar.</p>
                                    <p class="card-text"><small class="text-body-secondary">Disponible</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 1 -->
            <div class="col-md-6 mb-4"> <!-- Cada card ocupa 6 columnas en pantallas medianas y grandes -->
                <div class="card" style="min-height: 300px;"> <!-- Se establece una altura mínima -->
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="assets/logo%20con%20fondo.png" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Ejercicios de multiplicación <b>(No disponible)</b></h5>
                                <p class="card-text">Practica multiplicaciones simples con números de tres cifras.
                                    Ejercicios diseñados para niños que están aprendiendo a multiplicar.</p>
                                <p class="card-text"><small class="text-body-secondary">Muy pronto...</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Resta -->
            <div class="col-md-6 mb-4"> <!-- Cada card ocupa 6 columnas en pantallas medianas y grandes -->
                <div class="card" style="min-height: 300px;"> <!-- Se establece una altura mínima -->
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="assets/logo%20con%20fondo.png" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Ejercicios de Resta <b>(No disponible)</b></h5>
                                <p class="card-text">Practica restas simples con números de tres cifras. Ejercicios diseñados para niños que están aprendiendo a restar.</p>
                                <p class="card-text"><small class="text-body-secondary">Muy pronto...</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de División -->
            <div class="col-md-6 mb-4"> <!-- Cada card ocupa 6 columnas en pantallas medianas y grandes -->
                <div class="card" style="min-height: 300px;"> <!-- Se establece una altura mínima -->
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="assets/logo%20con%20fondo.png" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Ejercicios de División <b>(No disponible)</b></h5>
                                <p class="card-text">Practica divisiones simples con números de tres cifras. Ejercicios diseñados para niños que están aprendiendo a dividir.</p>
                                <p class="card-text"><small class="text-body-secondary">Muy pronto...</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</main>

<!-- Modal de confirmación de cierre de sesión -->
<div class="modal fade" id="modalCerrarSesion" tabindex="-1" aria-labelledby="modalCerrarSesionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCerrarSesionLabel">Confirmación de Cierre de Sesión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que quieres cerrar sesión?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="php/CerrarSesion.php" class="btn btn-primary">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <footer class="py-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
        </ul>
        <p class="text-center text-body-secondary">© 2025 SumaKids, Inc</p>
    </footer>
</div>

<script>
    function mostrarModal() {
        // Mostrar el modal de Bootstrap
        var modal = new bootstrap.Modal(document.getElementById('modalCerrarSesion'));
        modal.show();
    }
</script>

<script src="https://kit.fontawesome.com/fb92c26a74.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>