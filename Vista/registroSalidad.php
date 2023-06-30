<?php
session_start();

require_once '../Modelo/conexion.php';
require_once '../Controlador/registro.php';
// Establecer la zona horaria de Ecuador
date_default_timezone_set('America/Guayaquil');

// Verificar si existen datos de persona en la sesión
if (isset($_SESSION['nombres'])) {
    $persona = [
        'id' => $_SESSION['Id'],
        'foto' => $_SESSION['foto'],
        'nombres' => $_SESSION['nombres'],
        'apellidos' => $_SESSION['apellidos'],
        'placa' => $_SESSION['placa'],
        'correo' => $_SESSION['correo'],
        'cedula' => $_SESSION['cedula']
    ];

    // Limpiar las variables de sesión
    unset($_SESSION['Id']);
    unset($_SESSION['foto']);
    unset($_SESSION['nombres']);
    unset($_SESSION['apellidos']);
    unset($_SESSION['placa']);
    unset($_SESSION['correo']);
    unset($_SESSION['cedula']);
} else {
    // No hay datos de persona en la sesión
    $persona = [
        'Id' => "",
        'foto' => "",
        'nombres' => "",
        'apellidos' => "",
        'placa' => "",
        'correo' => "",
        'cedula' => ""
    ];
}

// Verificar si se hizo clic en el botón de registrar salida
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrarSalida'])) {
    if (!empty($_POST['idPersona'])) {
        $registro = new Registro();
        $registro->idPersona = $_POST['idPersona'];
        $registro->fechaSalida = date('Y-m-d H:i:s');

        // Actualizar la hora de salida en el registro existente
        if ($registro->actualizarHoraSalida()) {

// Obtener el correo de la persona que se está registrando
            $correoPersona = obtenerCorreoPersona($_POST['idPersona']);
            // Enviar correo con la confirmación de salida
            enviarCorreo( $correoPersona, "Se ha registrado la salida de su  vehiculo");

            // Redirigir a la página de éxito
            header('Location: ../Vista/registroEntrada.php');
            exit();
        } else {
            // Error al actualizar la hora de salida
            $errorMensaje = 'Error al registrar la salida.';
        }
    } else {
        // ID de persona no proporcionado
        $errorMensaje = 'No se proporcionó el ID de persona.';
    }
}


function obtenerCorreoPersona($idPersona) {
    require_once '../Modelo/conexion.php';
    // Establecer la conexión con la base de datos (debes configurar tus propios datos de conexión)
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    // Verificar si hay errores en la conexión
    if ($conn->connect_error) {
        // Error al conectar con la base de datos
        return null;
    }

    // Escapar el ID de la persona para prevenir inyección de SQL
    $idPersona = $conn->real_escape_string($idPersona);

    // Construir la consulta para obtener el correo de la persona
    $consulta = "SELECT correo FROM persona WHERE id = '$idPersona' LIMIT 1";

    // Ejecutar la consulta
    $resultado = $conn->query($consulta);

    // Verificar si la consulta fue exitosa
    if ($resultado && $resultado->num_rows > 0) {
        // Obtener el resultado de la consulta
        $fila = $resultado->fetch_assoc();

        // Obtener el correo de la persona
        $correo = $fila['correo'];

        // Liberar los recursos del resultado y cerrar la conexión
        $resultado->free();
        $conn->close();

        return $correo;
    }

    // No se encontró la persona o hubo un error en la consulta
    $conn->close();
    return null;

}



// Función para enviar correo electrónico
function enviarCorreo($destinatario, $mensaje) {
    $asunto = "Confirmación de salida";

    // Cuerpo del mensaje
    $cuerpo = "Se ha registrado la salida. " . $mensaje;

    // Encabezados del correo
    $headers = "From: jordanespi07@gmail.com" . "\r\n" .
                "Reply-To: jordanespi07@gmail.com" . "\r\n" .
                "MIME-Version: 1.0" . "\r\n" .
                "Content-Type: text/html; charset=UTF-8" . "\r\n";

    // Enviar el correo electrónico
    if (mail($destinatario, $asunto, $cuerpo, $headers)) {
        echo "Correo enviado correctamente.";
    } else {
        echo "Error al enviar el correo.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <title>Registro de Salida</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"   crossorigin="anonymous">
    <!-- Option 1: Include in HTML -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>
<nav class="navbar navbar-expand navbar-dark" id="navbar">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarScroll">
        <img src="../img/Mesa de trabajo 1.png" alt="" width="50" height="50">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active; text-white; fs-5" aria-current="page" href="registroEntrada.php" id="menu">Ingreso</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active; text-white; fs-5" aria-current="page" href="registroSalidad.php" id="menu">Salida</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active; text-white; fs-5" href="informe.php" id="menu">Informe</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h1>Búsqueda para salida</h1>
        </div>
        <div class="col-12 d-flex justify-content-center">
            <form class="form-inline" method="GET" action="../Controlador/buscarPersona.php">
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg" name="codigoBarras" id="codigoBarras" placeholder="Buscar por código de barras" style="width: 500px;">
                    <button type="submit" class="btn btn-custom btn-lg">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<br>
<div id="bordeTable">
    <table class="table table-striped">
        <thead class="text-dark" id="tabla">
        <tr>
            <th scope="col">Foto</th>
            <th scope="col">Nombres</th>
            <th scope="col">Apellidos</th>
            <th scope="col">Placa</th>
            <th scope="col">Correo</th>
            <th scope="col">Cedula</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($persona['nombres'])): ?>
            <tr>
                <td><img width="100" height="100" src="data:image/jpg;base64,<?php echo base64_encode($persona['foto']); ?>"></td>
                <td><?php echo $persona['nombres']; ?></td>
                <td><?php echo $persona['apellidos']; ?></td>
                <td><?php echo $persona['placa']; ?></td>
                <td><?php echo $persona['correo']; ?></td>
                <td><?php echo $persona['cedula']; ?></td>
                <td>
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="idPersona" value="<?php echo $persona['id']; ?>">
                        <button type="submit" name="registrarSalida" class="btn btn-primary" onclick="showAlert()">
                            <i class="fas fa-envelope fa-lg"></i> <!-- Icono de salida -->
                        </button>
                    </form>
                </td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="7"><p><em>No existen datos registrados</em></p></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function showAlert() {
        var alertContainer = document.createElement('div');
        alertContainer.classList.add('alert', 'alert-success', 'alert-dismissible', 'fade', 'show', 'alert-sm');
        alertContainer.role = 'alert';
        alertContainer.innerHTML = 'El mensaje fue enviado';

        var closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.classList.add('btn-close');
        closeButton.setAttribute('data-bs-dismiss', 'alert');
        closeButton.setAttribute('aria-label', 'Close');

        alertContainer.appendChild(closeButton);

        document.body.appendChild(alertContainer);
    }
</script>


<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
