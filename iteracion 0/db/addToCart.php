<?php
session_start();
include 'conectar.php';
$conn = conectar();

if (isset($_POST['id_producto']) && isset($_SESSION['id_usuario'])) {
    $id_producto = $_POST['id_producto'];
    $id_usuario = $_SESSION['id_usuario'];

    // Verifica si el producto ya está en el carrito del usuario
    $sql = "SELECT cantidad FROM carrito WHERE id_usuario = ? AND id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si el producto ya está en el carrito, incrementa la cantidad
        $row = $result->fetch_assoc();
        $nueva_cantidad = $row['cantidad'] + 1;

        $sql = "UPDATE carrito SET cantidad = ? WHERE id_usuario = ? AND id_producto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $nueva_cantidad, $id_usuario, $id_producto);
        $stmt->execute();
    } else {
        // Si no está, inserta el producto en el carrito
        $sql = "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_producto);
        $stmt->execute();
    }

    echo "Producto añadido al carrito";
} else {
    echo "Debe iniciar sesión para añadir productos al carrito";
}

$conn->close();
?>
