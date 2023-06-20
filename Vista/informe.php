<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/navbar.css">
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
                    <a class="nav-link active; text-white; fs-5" href="informe.php" id="menu">Informe</a>
                </li>
            </ul>
            <ul class="nav nav-pills">
                <li class="nav-item dropdown; position-absolute top-0 end-0" id="botonBien">
                    <a class="nav-link dropdown-toggle; fs-5" data-bs-toggle="dropdown" href="../index.html" role="button" aria-expanded="false" id="menu">Salir</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<br><br>
<div class="container">
    <div class="text-center">
        <h1>Informe de Clientes</h1>
    </div>
</div>
<div id="bordeTable">
    <table class="table table-striped">
        <thead class="text-dark" id="tabla">
            <tr>
                <th scope="col">Foto</th>
                <th scope="col">Nombres y Apellidos</th>
                <th scope="col">Placa</th>
                <th scope="col">Fecha de Entrada</th>
                <th scope="col">Fecha de Salida</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once '../Modelo/conexion.php';

            // Establecer la conexión con la base de datos
            $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);
            if ($conn->connect_errno) {
                // Manejar el error de conexión
                die('Error al conectar con la base de datos: ' . $conn->connect_error);
            }

            // Consulta SQL con INNER JOIN
            $query = 'SELECT persona.foto, persona.nombres, persona.apellidos, persona.placa, registro.fechaEntrada, registro.fechaSalida
                      FROM persona
                      INNER JOIN registro ON persona.id = registro.idPersona';
            $result = $conn->query($query);

            // Manejar el resultado de la consulta
            if ($result) {
                // Imprimir los datos en la tabla
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td><img width="100" height="100" src="data:image/jpg;base64,' . base64_encode($row['foto']) . '"></td>';
                    echo '<td>' . $row['nombres'] . ' ' . $row['apellidos'] . '</td>';
                    echo '<td>' . $row['placa'] . '</td>';
                    echo '<td>' . $row['fechaEntrada'] . '</td>';
                    echo '<td>' . $row['fechaSalida'] . '</td>';
                    echo '</tr>';
                }
                // Liberar el conjunto de resultados
                $result->free();
            } else {
                // Manejar el error de la consulta
                echo 'Error al ejecutar la consulta: ' . $conn->error;
            }
            // Cerrar la conexión con la base de datos
            $conn->close();
            ?>
        </tbody>
    </table>
    <div>
    <button type="button" class="btn btn-primary">Imprimir informe</button>
    </div>
</div>

</body>
</html>