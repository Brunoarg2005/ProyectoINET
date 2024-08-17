<?php
session_start();
include 'db/conectar.php';
$conn = conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Inicio de sesión
        $email = $_POST['login_email'];
        $password = $_POST['login_password'];

        // Modificamos la consulta para obtener también la contraseña y tipo_usuario
        $sql = "SELECT id_usuario, tipo_usuario, contraseña_usuario FROM usuario WHERE mail_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verificar la contraseña ingresada con la almacenada
            if (password_verify($password, $user['contraseña_usuario'])) {
                $_SESSION['id_usuario'] = $user['id_usuario'];
                $_SESSION['tipo_usuario'] = $user['tipo_usuario']; // Guardamos el tipo de usuario en la sesión
                header("Location: index.php");
                exit();
            } else {
                echo "Email o contraseña incorrectos.";
            }
        } else {
            echo "Email o contraseña incorrectos.";
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
    <title>Iniciar Sesión</title>
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

    <h1>Iniciar Sesión</h1>

    <form method="post" action="Login.php">
        <fieldset>
            <legend>Inicio de Sesión</legend>
            <input type="email" name="login_email" placeholder="Email" required>
            <input type="password" name="login_password" placeholder="Contraseña" required>
            <button type="submit" name="login">Iniciar Sesión</button>
        </fieldset>
    </form>
    <h3>¿No tenes una cuenta? <a href="register.php">Registrate acá</a></h3>
</body>
</html>
