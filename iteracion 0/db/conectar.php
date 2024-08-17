<?php
function conectar() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "testecommerce";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    return $conn;
}
?>
