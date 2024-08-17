<?php
session_start();
include 'db/conectar.php';
$conn = conectar();

$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT producto.nombre_producto, carrito.cantidad, producto.precio_producto 
        FROM carrito 
        JOIN producto ON carrito.id_producto = producto.id_producto 
        WHERE carrito.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$carrito = array();
while ($row = $result->fetch_assoc()) {
    $carrito[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($carrito);
?>
