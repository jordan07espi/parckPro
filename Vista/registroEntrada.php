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

// Verificar si se hizo clic en el botón de registrar entrada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrarEntrada'])) {
    if (!empty($_POST['idPersona'])) {
        $registro = new Registro();
        $registro->idPersona = $_POST['idPersona'];
        $registro->fechaEntrada = date('Y-m-d H:i:s');
        $registro->fechaSalida = date('Y-m-d H:i:s');

        // Guardar el registro en la base de datos
        if ($registro->guardar()) {
            // Enviar correo con el registro
            enviarCorreo($persona['correo'], "Se ha registrado la entrada de la persona con ID: " . $persona['id']);

            // Redirigir a la página de éxito
            header('Location: ../Vista/registroEntrada.php');
            exit();
        } else {
            // Error al guardar el registro
            $errorMensaje = 'Error al guardar el registro.';
        }
    } else {
        // ID de persona no proporcionado
        $errorMensaje = 'No se proporcionó el ID de persona.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/formularios.css">
    <title>Registro de Entrada</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"   crossorigin="anonymous">
    <!-- Option 1: Include in HTML -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>
<nav class="navbar navbar-expand navbar-dark" id="navbar">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarScroll">
            <img src="../../img/Logo.png" alt="" width="100" height="50">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active; text-white; fs-5" aria-current="page" href="registroEntrada.php" id="menu">Ingreso</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active; text-white; fs-5" aria-current="page" href="registroSalidad.php" id="menu">Salida</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active; text-white; fs-5" href="../Asistencia/nuevaAsistencia.php" id="menu">Informe</a>
                </li>
            </ul>
            <ul class="nav nav-pills">
                <li class="nav-item dropdown; position-absolute top-0 end-0" id="botonBien">
                    <a class="nav-link dropdown-toggle; fs-5" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" id="menu">Bienvenido</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div>
    <h1>Barra de Búsqueda</h1>
    <form method="GET" action="../Controlador/buscarPersona.php">
        <label for="codigoBarras">Buscar por código de barras:</label>
        <input type="text" name="codigoBarras" id="codigoBarras">
        <input type="submit" value="Buscar">
    </form>
</div>

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
                        <input type="submit" name="registrarEntrada" value="Registrar Entrada">
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

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
