<?php
require_once '../Modelo/conexion.php';

if (isset($_GET['codigoBarras']) && !empty(trim($_GET['codigoBarras']))) {
    $codigoBarras = $_GET['codigoBarras'];
    $query = 'SELECT * FROM persona WHERE codigoBarras = ?';

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('s', $codigoBarras);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $idPersona = $row['Id'];
                $foto = $row['foto'];
                $nombres = $row['nombres'];
                $apellidos = $row['apellidos'];
                $placa = $row['placa'];
                $correo = $row['correo'];
                $cedula = $row['cedula'];

                session_start();
                $_SESSION['Id'] = $idPersona;
                $_SESSION['foto'] = $foto;
                $_SESSION['nombres'] = $nombres;
                $_SESSION['apellidos'] = $apellidos;
                $_SESSION['placa'] = $placa;
                $_SESSION['correo'] = $correo;
                $_SESSION['cedula'] = $cedula;

                // Verificar desde qué página se está realizando la búsqueda
                $referer = $_SERVER['HTTP_REFERER'];

                if (strpos($referer, 'registroSalidad.php') !== false) {
                    header('Location: ../Vista/registroSalidad.php');
                } else {
                    header('Location: ../Vista/registroEntrada.php');
                }

                exit();
            } else {
                // No se encontró ninguna persona
                header('Location: ../Vista/registroEntrada.php');
                exit();
            }
        } else {
            echo 'No se ejecutó la consulta';
            exit();
        }
        $stmt->close();
    } else {
        header('Location: ../Vista/registroEntrada.php');
        exit();
    }
} else {
    header('Location: ../Vista/registroEntrada.php');
    exit();
}
?>
