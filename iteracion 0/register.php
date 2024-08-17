<?php
session_start();
include 'db/conectar.php';
$conn = conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        // Registro de usuario
        $nombre = $_POST['name'];
        $apellido = $_POST['lastname'];
        $email = $_POST['mail'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encriptar la contraseña

        $sql = "INSERT INTO usuario (nombre_usuario, apellido_usuario, mail_usuario, contraseña_usuario) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $apellido, $email, $password);

        if ($stmt->execute()) {
            echo "Registro exitoso. Puedes iniciar sesión ahora.";
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
    <title>Registrarse</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/styletipo1.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="catalogo.php">Catálogo</a></li>
            <li><a href="carrito.php">Carrito</a></li>
            <?php
            if (isset($_SESSION['id_usuario'])) {
                echo '<li><a href="logout.php">Cerrar Sesión</a></li>';
            } else {
                echo '<li><a href="Login.php">Iniciar Sesión</a></li>';
            }
            ?>
        </ul>
    </nav>

    <h1>Registrarse</h1>

    <form method="post" action="register.php">
        <fieldset>
            <legend>Registro</legend>
            <input type="text" name="name" placeholder="Nombre" required>
            <input type="text" name="lastname" placeholder="Apellido" required>
            <input type="email" name="mail" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" name="register">Registrar</button>
        </fieldset>
    </form>

	<h2>¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a> </h2>
</body>
</html>
