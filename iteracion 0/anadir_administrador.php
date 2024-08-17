<?php
session_start();
include 'db/conectar.php';
$conn = conectar();

// Verificar si el usuario tiene permiso para añadir administradores
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 3) {
    echo "No tienes permiso para acceder a esta página.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        // Registro de usuario
        $nombre = $_POST['name'];
        $apellido = $_POST['lastname'];
        $email = $_POST['mail'];
        $password = $_POST['password'];
        $tipo_usuario = $_POST['tipo_usuario'];

        // Encriptar la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (nombre_usuario, apellido_usuario, mail_usuario, contraseña_usuario, tipo_cuenta) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nombre, $apellido, $email, $hashed_password, $tipo_usuario);

        if ($stmt->execute()) {
            echo "Registro exitoso. El nuevo usuario ha sido creado.";
        } else {
            echo "Error al registrar: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Administrador</title>
    <link rel="stylesheet" href="styles/styles.css">
    <?php
    if ($_SESSION['tipo_usuario'] == 2) {
        echo '<link rel="stylesheet" href="styles/styletipo2.css">';
    } elseif ($_SESSION['tipo_usuario'] == 3) {
        echo '<link rel="stylesheet" href="styles/styletipo3.css">';
    }
    ?>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="catalogo.php">Catálogo</a></li>
            <li><a href="carrito.php">Carrito</a></li>
            <li><a href="anadir_producto.php">Añadir Producto</a></li>
            <li><a href="anadir_administrador.php">Añadir Administrador</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <h1>Añadir Nuevo Administrador/Usuario</h1>

    <form method="post" action="anadir_administrador.php">
        <fieldset>
            <legend>Registro</legend>
            <input type="text" name="name" placeholder="Nombre" required>
            <input type="text" name="lastname" placeholder="Apellido" required>
            <input type="email" name="mail" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>

            <label for="tipo_usuario">Tipo de Usuario:</label>
            <select name="tipo_usuario" id="tipo_usuario" required>
                <option value="1">Cliente</option>
                <option value="2">Personal de Ventas</option>
                <option value="3">Administrador de Ventas</option>
            </select>

            <button type="submit" name="register">Registrar</button>
        </fieldset>
    </form>
</body>
</html>
