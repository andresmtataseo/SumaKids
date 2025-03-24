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

// Generar sumas aleatorias de tres cifras
function generarSuma()
{
    $num1 = rand(100, 999);
    $num2 = rand(100, 999);
    return [$num1, $num2];
}

$ejercicios = [];
for ($i = 0; $i < 8; $i++) {
    $ejercicios[] = generarSuma();
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ejercicios de Sumas - SumaKids</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .exercise-card {
            cursor: pointer;
            text-align: center;
            align-items: center;
            font-size: 2rem;
            font-weight: bold;
            padding: 10px 20px 20px 8px !important;
            border-radius: 10px;
            background-color: #f8f9fa;
        }

        .sum-container {
            display: inline-block;
            text-align: right;
            font-size: 3.5rem;
            font-family: monospace;
            line-height: 1.2;
        }

        .plus {
            font-size: 3.5rem;
            font-weight: bold;
        }

        .plus-modal {
            font-size: 6.5rem;
            font-weight: bold;
        }

        .plus-modal-result {
            font-size: 6.5rem;
            font-weight: bold;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .plus-modal-result:hover {
            border: 2px solid #007bff;
            border-radius: 5px;
        }

        hr {
            margin: auto !important;
            opacity: 1 !important;
        }

    </style>
</head>
<body>

<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-between py-3 mb-4 border-bottom">
        <div>
            <a href="index.html" class="d-inline-flex link-body-emphasis text-decoration-none">
                <img src="assets/logo.png" alt="Logo">
            </a>
        </div>
        <a href="php/CerrarSesion.php" class="btn btn-outline-primary">Cerrar Sesión</a>
    </header>

    <h2 class="text-center mb-4">Ejercicios de Sumas</h2>

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

<script>
    // Función para incrementar el valor de cada posición
    document.querySelectorAll('.digit').forEach(digit => {
        digit.addEventListener('click', function () {
            let valorActual = parseInt(this.textContent);
            this.textContent = (valorActual + 1) % 10; // Incrementa hasta 9, luego vuelve a 0
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
            card.style.backgroundColor = '#d4edda';

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

            // Cerrar modal
            let modal = document.getElementById(`modalEjercicio${index}`);
            let modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
        } else {
            let card = document.getElementById(`exerciseCard${index}`);
            card.style.backgroundColor = '#f8d7da';

            primeraPosicion.textContent = primera;
            segundaPosicion.textContent = segunda;
            terceraPosicion.textContent = tercera;
            cuartaPosicion.textContent = cuarta;

            let modal = document.getElementById(`modalEjercicio${index}`);
            let modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
