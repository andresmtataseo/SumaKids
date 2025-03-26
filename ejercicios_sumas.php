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

// Conexión a la base de datos para recuperar ejercicios ya resueltos y el nivel del usuario
include 'php/conexion.php';

$idUsuario = $_SESSION['usuario']['id'];
$ejerciciosBuenosDB = array();

// Se asume que se agregó la columna "card_index" en la tabla "ejercicios"
// (Ejemplo: ALTER TABLE ejercicios ADD card_index INT NULL;)
$query = "SELECT * FROM ejercicios WHERE usuario_id = '$idUsuario' AND card_index IS NOT NULL AND nivel = " . (isset($_SESSION['nivel']) ? $_SESSION['nivel'] : 1);
$result = mysqli_query($conexion, $query);
if ($result) {
    while($row = mysqli_fetch_assoc($result)) {
        // Se guarda el ejercicio resuelto según la posición de la card
        $ejerciciosBuenosDB[$row['card_index']] = $row;
    }
}
mysqli_close($conexion);

// Recuperar o inicializar el nivel del usuario
if (!isset($_SESSION['nivel'])) {
    // Aquí podrías consultar la tabla "niveles" para ver si el usuario ya tiene nivel
    // Si no existe, se inicia en 1 y se inserta en la tabla (esto es un ejemplo)
    $_SESSION['nivel'] = 1;
    // Opcional: Insertar en la tabla niveles (si no existe registro para este usuario)
    // Se recomienda tener un script aparte para manejar la lógica de niveles.
}

// Función para generar sumas aleatorias de tres cifras
function generarSuma() {
    $num1 = rand(100, 999);
    $num2 = rand(100, 999);
    return [$num1, $num2];
}

// Si ya existe el arreglo de ejercicios en la sesión, se usa; de lo contrario se crea.
if (!isset($_SESSION['ejercicios_suma'])) {
    $ejercicios = [];
    for ($i = 0; $i < 8; $i++) {
        if (isset($ejerciciosBuenosDB[$i])) {
            // Ejercicio ya resuelto (se recupera de la DB)
            $ejercicio = [
                'numero1'    => $ejerciciosBuenosDB[$i]['numero1'],
                'numero2'    => $ejerciciosBuenosDB[$i]['numero2'],
                'resultado'  => $ejerciciosBuenosDB[$i]['respuesta_usuario'], // resultado del ejercicio
                'solucionado'=> true,
                'card_index' => $i
            ];
        } else {
            // Ejercicio no resuelto: se genera una sola vez y se guarda en la sesión
            list($num1, $num2) = generarSuma();
            $ejercicio = [
                'numero1'    => $num1,
                'numero2'    => $num2,
                'solucionado'=> false,
                'card_index' => $i
            ];
        }
        $ejercicios[] = $ejercicio;
    }
    $_SESSION['ejercicios_suma'] = $ejercicios;
} else {
    $ejercicios = $_SESSION['ejercicios_suma'];
}

// Verificar si todos los ejercicios fueron resueltos para subir de nivel
$countSolucionados = 0;
foreach ($ejercicios as $ej) {
    if ($ej['solucionado']) {
        $countSolucionados++;
    }
}
if ($countSolucionados === 8) {
    // Incrementar nivel en la sesión
    $_SESSION['nivel']++;
    // Aquí puedes actualizar/inserta en la tabla niveles para guardar el nuevo nivel del usuario.
    // Reiniciamos el arreglo de ejercicios para el nuevo nivel:
    unset($_SESSION['ejercicios_suma']);
    // Redirigir para refrescar la página y que se genere el nuevo set de ejercicios:
    header("Location: ejercicios_sumas.php");
    exit();
}
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
            <a href="juegos.php" class="btn btn-danger me-2">Salir</a>
        </div>
        <h2 class="col-md-6 text-center">Nivel <?php echo $_SESSION['nivel']; ?></h2>
        <div class="col-md-3 text-center">
            <a class="btn btn-warning me-2" href="reiniciar_nivel.php">Reiniciar Nivel</a>
        </div>
    </div>

    <div class="row">
        <?php foreach ($ejercicios as $index => $ejercicio):
            $solucionado = $ejercicio['solucionado'];
            ?>
            <div class="col-md-3 mb-4">
                <div class="card exercise-card p-3 <?php echo ($solucionado) ? 'disabled' : ''; ?>" id="exerciseCard<?php echo $index; ?>" <?php echo (!$solucionado) ? 'data-bs-toggle="modal" data-bs-target="#modalEjercicio'.$index.'"' : ''; ?>>
                    <div class="sum-container">
                        <div><?php echo $ejercicio['numero1']; ?></div>
                        <div class="plus">+ <?php echo $ejercicio['numero2']; ?></div>
                        <hr>
                        <div style="display: flex; align-items: center; justify-content: end;">
                            <?php if($solucionado):
                                // Se muestra el resultado formateado a 4 dígitos
                                $solvedAnswer = str_pad($ejercicio['resultado'], 4, "0", STR_PAD_LEFT);
                                ?>
                                <div class="plus" id="cuartaPosicionListo<?php echo $index; ?>"><?php echo $solvedAnswer[0]; ?></div>
                                <div class="plus" id="terceraPosicionListo<?php echo $index; ?>"><?php echo $solvedAnswer[1]; ?></div>
                                <div class="plus" id="segundaPosicionListo<?php echo $index; ?>"><?php echo $solvedAnswer[2]; ?></div>
                                <div class="plus" id="primeraPosicionListo<?php echo $index; ?>"><?php echo $solvedAnswer[3]; ?></div>
                            <?php else: ?>
                                <div class="plus" id="cuartaPosicionListo<?php echo $index; ?>">ㅤ</div>
                                <div class="plus" id="terceraPosicionListo<?php echo $index; ?>"></div>
                                <div class="plus" id="segundaPosicionListo<?php echo $index; ?>"></div>
                                <div class="plus" id="primeraPosicionListo<?php echo $index; ?>"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para resolver el ejercicio (solo si no está resuelto) -->
            <?php if(!$solucionado): ?>
            <div class="modal fade" id="modalEjercicio<?php echo $index; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Resolver Ejercicio <?php echo $index + 1; ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="sum-container">
                                <div class="plus-modal"><?php echo $ejercicio['numero1']; ?></div>
                                <div class="plus-modal">+ <?php echo $ejercicio['numero2']; ?></div>
                                <hr>
                                <div style="display: flex; align-items: center; justify-content: end;">
                                    <div class="plus-modal-result digit" id="cuartaPosicion<?php echo $index; ?>">0</div>
                                    <div class="plus-modal-result digit" id="terceraPosicion<?php echo $index; ?>">0</div>
                                    <div class="plus-modal-result digit" id="segundaPosicion<?php echo $index; ?>">0</div>
                                    <div class="plus-modal-result digit" id="primeraPosicion<?php echo $index; ?>">0</div>
                                </div>
                                <button class="btn btn-primary mt-3" onclick="comprobarRespuesta(<?php echo $ejercicio['numero1'] + $ejercicio['numero2']; ?>, <?php echo $index; ?>, <?php echo $ejercicio['numero1']; ?>, <?php echo $ejercicio['numero2']; ?>)">Comprobar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

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
        var modal = new bootstrap.Modal(document.getElementById('modalCerrarSesion'));
        modal.show();
    }

    // Incrementar el valor de cada dígito (solo para ejercicios no resueltos)
    document.querySelectorAll('.digit').forEach(digit => {
        digit.addEventListener('click', function () {
            if(this.closest('.exercise-card')?.classList.contains('disabled')) return;
            let valorActual = parseInt(this.textContent);
            this.textContent = (valorActual + 1) % 10;
            const sonido = new Audio('assets/incrementar.mp3');
            sonido.play();
        });
    });

    function comprobarRespuesta(resultadoCorrecto, index, numero1, numero2) {
        let primera = document.getElementById(`primeraPosicion${index}`).textContent;
        let segunda = document.getElementById(`segundaPosicion${index}`).textContent;
        let tercera = document.getElementById(`terceraPosicion${index}`).textContent;
        let cuarta = document.getElementById(`cuartaPosicion${index}`).textContent;
        let respuestaUsuario = parseInt(cuarta + tercera + segunda + primera);

        // Mostrar en la card el resultado si es correcto
        let primeraPosicion = document.getElementById(`primeraPosicionListo${index}`);
        let segundaPosicion = document.getElementById(`segundaPosicionListo${index}`);
        let terceraPosicion = document.getElementById(`terceraPosicionListo${index}`);
        let cuartaPosicion = document.getElementById(`cuartaPosicionListo${index}`);

        if (respuestaUsuario === resultadoCorrecto) {
            primeraPosicion.textContent = primera;
            segundaPosicion.textContent = segunda;
            terceraPosicion.textContent = tercera;
            cuartaPosicion.textContent = cuarta;

            let card = document.getElementById(`exerciseCard${index}`);
            card.classList.add('disabled');

            // Enviar los datos al servidor, incluyendo el índice de card y el nivel actual
            let formData = new FormData();
            formData.append('numero1', numero1);
            formData.append('numero2', numero2);
            formData.append('resultado_correcto', resultadoCorrecto);
            formData.append('resultado_usuario', respuestaUsuario);
            formData.append('card_index', index);
            formData.append('nivel', <?php echo $_SESSION['nivel']; ?>);

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
