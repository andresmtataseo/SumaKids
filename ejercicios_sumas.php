<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo '
    <script>
        alert("Debe iniciar sesión");
        window.location = "signin.html";
    </script>
    ';
    session_destroy();
    die();
}

// Generar sumas aleatorias de tres cifras solo si no existen en la sesión
function generarSuma()
{
    $num1 = rand(100, 999);
    $num2 = rand(100, 999);
    return [$num1, $num2];
}

// Verificar si ya existen ejercicios en la sesión
if (!isset($_SESSION['ejercicios_suma'])) {
    $ejercicios = [];
    for ($i = 0; $i < 8; $i++) {
        $ejercicios[] = generarSuma();
    }
    $_SESSION['ejercicios_suma'] = $ejercicios;
}

$ejercicios = $_SESSION['ejercicios_suma'];
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ejercicios de Sumas - SumaKids</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

<div class="container">

    <div class="row mb-4">
        <div class="col-md-3 text-center">
            <a href="juegos.php" class="btn btn-primary me-2">ㅤVolverㅤ</a>
        </div>
        <h2 class="col-md-6 text-center">Ejercicios de Sumas</h2>
        <div class="col-md-3 text-center">
            Barra de progreso
        </div>
    </div>

    <div class="row">
        <?php foreach ($ejercicios as $index => $ejercicio): ?>
            <div class="col-md-3 mb-4">
                <div class="card exercise-card p-3" id="exerciseCard<?php echo $index; ?>" data-bs-toggle="modal"
                     data-bs-target="#modalEjercicio<?php echo $index; ?>">
                    <div class="sum-container">
                        <div><?php echo $ejercicio[0]; ?></div>
                        <div class="plus">+ <?php echo $ejercicio[1]; ?></div>
                        <hr>
                        <div style="display: flex; align-items: center; justify-content: end;">
                            <div class="plus" id="cuartaPosicionListo<?php echo $index; ?>">ㅤ</div>
                            <div class="plus" id="terceraPosicionListo<?php echo $index; ?>"></div>
                            <div class="plus" id="segundaPosicionListo<?php echo $index; ?>"></div>
                            <div class="plus" id="primeraPosicionListo<?php echo $index; ?>"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para resolver el ejercicio -->
            <div class="modal fade" id="modalEjercicio<?php echo $index; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Resolver Ejercicio <?php echo $index + 1; ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="sum-container">
                                <div class="plus-modal"><?php echo $ejercicio[0]; ?></div>
                                <div class="plus-modal">+ <?php echo $ejercicio[1]; ?></div>
                                <hr>
                                <div style="display: flex; align-items: center; justify-content: end;">
                                    <div class="plus-modal-result digit" id="cuartaPosicion<?php echo $index; ?>">0</div>
                                    <div class="plus-modal-result digit" id="terceraPosicion<?php echo $index; ?>">0</div>
                                    <div class="plus-modal-result digit" id="segundaPosicion<?php echo $index; ?>">0</div>
                                    <div class="plus-modal-result digit" id="primeraPosicion<?php echo $index; ?>">0</div>
                                </div>
                                <button class="btn btn-primary mt-3" onclick="comprobarRespuesta(<?php echo $ejercicio[0] + $ejercicio[1]; ?>, <?php echo $index; ?>)">Comprobar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
</div>

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

<script>
    function mostrarModal() {
        // Mostrar el modal de Bootstrap
        var modal = new bootstrap.Modal(document.getElementById('modalCerrarSesion'));
        modal.show();
    }
    // Función para incrementar el valor de cada posición
    document.querySelectorAll('.digit').forEach(digit => {
        digit.addEventListener('click', function () {
            let valorActual = parseInt(this.textContent);
            if (valorActual != 0) {
                this.textContent = 0;
            }
            this.textContent = (valorActual + 1) % 10;
            const sonido = new Audio('assets/incrementar.mp3');
            sonido.play();
        });
    });

    function comprobarRespuesta(resultadoCorrecto, index) {
        //transformar repuesta del usuario
        let primera = document.getElementById(`primeraPosicion${index}`).textContent;
        let segunda = document.getElementById(`segundaPosicion${index}`).textContent;
        let tercera = document.getElementById(`terceraPosicion${index}`).textContent;
        let cuarta = document.getElementById(`cuartaPosicion${index}`).textContent;
        let respuestaUsuario = parseInt(cuarta + tercera + segunda + primera);

        console.log("Correcto:", resultadoCorrecto);
        console.log("Usuario:", respuestaUsuario);

        // Seleccionar los divs donde mostrar los resultados
        let primeraPosicion = document.getElementById(`primeraPosicionListo${index}`);
        let segundaPosicion = document.getElementById(`segundaPosicionListo${index}`);
        let terceraPosicion = document.getElementById(`terceraPosicionListo${index}`);
        let cuartaPosicion = document.getElementById(`cuartaPosicionListo${index}`);

        //comparamos
        if (respuestaUsuario === resultadoCorrecto) {
            primeraPosicion.textContent = primera;
            segundaPosicion.textContent = segunda;
            terceraPosicion.textContent = tercera;
            cuartaPosicion.textContent = cuarta;

            let card = document.getElementById(`exerciseCard${index}`);
            card.classList.add('disabled');

            // Enviar los datos al servidor para guardar en la base de datos
            let numero1 = <?php echo $ejercicio[0]; ?>;
            let numero2 = <?php echo $ejercicio[1]; ?>;
            let resultadoUsuario = respuestaUsuario;

            let formData = new FormData();
            formData.append('numero1', numero1);
            formData.append('numero2', numero2);
            formData.append('resultado_correcto', resultadoCorrecto);
            formData.append('resultado_usuario', resultadoUsuario);

            fetch('php/GuardarEjercicio.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text())
                .then(data => console.log(data))
                .catch(error => console.error('Error al guardar:', error));

            const sonido = new Audio('assets/bueno.mp3');
            sonido.play();

            // Cerrar modal
            let modal = document.getElementById(`modalEjercicio${index}`);
            let modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
        } else {
            const sonido = new Audio('assets/malo.mp3');
            sonido.play();

        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>