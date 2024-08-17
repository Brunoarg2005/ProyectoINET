<?php
session_start();
include 'db/conectar.php';
$conn = conectar();

// Verificar si el usuario tiene permisos para eliminar productos
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] < 2) {
    echo "No tienes permiso para acceder a esta página.";
    exit();
}

$id_producto = $_GET['id'];

// Eliminar el producto de la base de datos
$sql = "DELETE FROM producto WHERE id_producto=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_producto);

if ($stmt->execute()) {
    echo "Producto eliminado exitosamente.";
    header("Location: admincatalogo.php");
} else {
    echo "Error al eliminar el producto: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
