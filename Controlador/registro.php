<?php
require_once '../Modelo/conexion.php';

class Registro {
    public $idPersona;
    public $fechaEntrada;
    public $fechaSalida;

    public function guardar() {
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

        // Controlar la conexión
        if ($conn->connect_error) {
            die('Conexión fallida: ' . $conn->connect_error);
        }

        // Obtener la fecha y hora actual
        $fechaEntrada = new DateTime();
        $fechaSalida = new DateTime();

        // Insertar el registro en la tabla
        $query = 'INSERT INTO registro (idPersona, fechaEntrada, fechaSalida) VALUES (?, ?, ?)';
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iss', $this->idPersona, $fechaEntrada->format('Y-m-d H:i:s'), $fechaSalida->format('Y-m-d H:i:s'));
        $resultado = $stmt->execute();

        // Cerrar el statement
        $stmt->close();

        return $resultado;
    }

    public function actualizarHoraSalida() {
        global $conn; 

        // Obtener el último registro de la persona por su ID
        $query = 'SELECT id FROM registro WHERE idPersona = ? ORDER BY fechaEntrada DESC LIMIT 1';

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $this->idPersona);

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    $idRegistro = $row['id'];

                    // Actualizar la fecha y hora de salida del último registro
                    $queryUpdate = 'UPDATE registro SET fechaSalida = ? WHERE id = ?';

                    if ($stmtUpdate = $conn->prepare($queryUpdate)) {
                        $fechaSalida = date('Y-m-d H:i:s');
                        $stmtUpdate->bind_param('si', $fechaSalida, $idRegistro);

                        if ($stmtUpdate->execute()) {
                            $stmtUpdate->close();
                            $conn->close();
                            return true;
                        } else {
                            $stmtUpdate->close();
                            $conn->close();
                            return false;
                        }
                    } else {
                        $conn->close();
                        return false;
                    }
                } else {
                    $conn->close();
                    return false;
                }
            } else {
                $conn->close();
                return false;
            }
        } else {
            $conn->close();
            return false;
        }
    }
    
}







?>
