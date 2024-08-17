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


if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    // Consulta para obtener los productos del carrito del usuario
    $sql = "SELECT p.nombre_producto, p.precio_producto, p.ruta_imagen, c.cantidad
            FROM carrito c
            JOIN producto p ON c.id_producto = p.id_producto
            WHERE c.id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
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
                if ($_SESSION['tipo_usuario'] == 2) {
                    echo '<li><a href="anadir_producto.php">Añadir Producto</a></li>';
                } elseif ($_SESSION['tipo_usuario'] == 3) {
                    echo '<li><a href="anadir_producto.php">Añadir Producto</a></li>';
                    echo '<li><a href="anadir_administrador.php">Añadir Administrador</a></li>';
                }
                echo '<li><a href="logout.php">Cerrar Sesión</a></li>';
            } else {
                echo '<li><a href="Login.php">Iniciar Sesión</a></li>';
            }
            ?>
        </ul>
    </nav>
    <h1>Carrito de Compras</h1>
    <div class="productos-carrito">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="producto">';
                if (!empty($row['ruta_imagen'])) {
                    echo '<img src="' . $row['ruta_imagen'] . '" alt="' . $row['nombre_producto'] . '" style="height: 100%;">';
                }
                echo '<h2>' . $row['nombre_producto'] . '</h2>';
                echo '<p>Cantidad: ' . $row['cantidad'] . '</p>';
                echo '<p>Precio total: $' . ($row['precio_producto'] * $row['cantidad']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>El carrito está vacío.</p>';
        }
        ?>
    </div>
    <a href="catalogo.php">Volver al catálogo</a>
</body>
</html>
<?php
    $conn->close();
} else {
    echo "
    <head>
    <title>Carrito</title>
    <link rel=stylesheet href=styles/styles.css>
    <link rel=stylesheet href=styles/styletipo1.css>
</head>
    <body>
    <nav>
        <ul>
            <li><a href=index.php>Inicio</a></li>
            <li><a href=catalogo.php>Catálogo</a></li>
            <li><a href=carrito.php>Carrito</a></li>
           <li><a href=./Login.php>Iniciar Sesión</a></li>

        </ul>
    </nav>
    <h2>
    Debe iniciar sesión para ver su carrito.
    </h2>
    <a href=login.php><button>iniciar sesión</button></a>
    <h3>¿No tiene una cuenta?</h3>
    <a href=login.php><button>crear cuenta</button></a>
    </body>
    ";
}
?>
