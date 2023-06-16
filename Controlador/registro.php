<?php



class Registro {
    public $id;
    public $idPersona;
    public $fechaEntrada;
    public $fechaSalida;

    public function guardar() {
        require_once '../Modelo/conexion.php';
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);
        // Obtener la fecha y hora actual
        $fechaEntrada = new DateTime();
        $fechaSalida = new DateTime();
        
        // Controlar la conexión
        if ($conn->connect_error) {
            die('Conexión fallida: ' . $conn->connect_error);
        }
        // Insertar el registro en la tabla
        $query = 'INSERT INTO registros (idPersona, fechaEntrada, fechaSalida) VALUES (?, ?, ?)';
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iss', $this->idPersona, $this->fechaEntrada, $this->fechaSalida);
        $resultado = $stmt->execute();
        
        // Cerrar el statement
        $stmt->close();
        
        return $resultado;
    }
}

?>
