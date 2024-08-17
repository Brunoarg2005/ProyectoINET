<?php
session_start();
include 'db/conectar.php';
$conn = conectar();

// Definir estilos por defecto
$styleFile = 'styles/styletipo1.css';
if (isset($_SESSION['tipo_usuario'])) {
    if ($_SESSION['tipo_usuario'] == 2) {
        $styleFile = 'styles/styletipo2.css';
    } elseif ($_SESSION['tipo_usuario'] == 3) {
        $styleFile = 'styles/styletipo3.css';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="<?php echo $styleFile; ?>">
</head>
<body>
<nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            
            <?php
            if (isset($_SESSION['id_usuario'])) {

                if ($_SESSION['tipo_usuario'] == 2) {
                    echo '<li><a href="admincatalogo.php">Catálogo</a></li>';
                    echo '<li><a href="anadir_producto.php">Añadir Producto</a></li>';
                } elseif ($_SESSION['tipo_usuario'] == 3) {
                    echo '<li><a href="admincatalogo.php">Catálogo</a></li>';
                    echo '<li><a href="anadir_producto.php">Añadir Producto</a></li>';
                    echo '<li><a href="anadir_administrador.php">Añadir Administrador</a></li>';
                }
                echo '<li><a href="logout.php">Cerrar Sesión</a></li>';
            } elseif(($_SESSION['tipo_usuario'] == 1)){
                echo '<li><a href="catalogo.php">Catálogo</a></li>';
                echo '<li><a href="carrito.php">Carrito</a></li>';
                echo '<li><a href="Login.php">Iniciar Sesión</a></li>';
            }else{
                echo '<li><a href="catalogo.php">Catálogo</a></li>';
                echo '<li><a href="Login.php">Iniciar Sesión</a></li>';
            }
            ?>
        </ul>
    </nav>

    <h1>Bienvenido al E-commerce</h1>
    <!-- Resto del contenido de la página -->
</body>
</html>