<?php
    define('SERVERNAME','localhost');
    define('USERNAME','root');
    define('PASSWORD','');
    define('DBNAME','parkpro');
    
    //Creación de la conexión a la base de datos usando mysqli
    $conn= new mysqli(SERVERNAME, USERNAME,PASSWORD, DBNAME);
    
    //Controlar la conexión
    if ($conn-> connect_error) {
    die('Conexión fallida: '.$conn->connect_error);
    }
?>