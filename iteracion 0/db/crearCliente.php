<?php
include 'conectar.php';

$conn = conectar();

// Obtener datos del formulario
$nombre = $_POST['name'];
$apellido = $_POST['lastname'];
$email = $_POST['mail'];
$contraseña = $_POST['password'];

// Cifrar la contraseña antes de guardarla
$contraseña_hash = password_hash($contraseña, PASSWORD_BCRYPT);

// Insertar datos
$sql = "INSERT INTO usuario (nombre_usuario, apellido_usuario, mail_usuario, contraseña_usuario) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nombre, $apellido, $email, $contraseña_hash);

if ($stmt->execute()) {
    echo "Nuevo registro creado exitosamente";
    header("Location: ../index.html"); // Redirigir a la página principal
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
