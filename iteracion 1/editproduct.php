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

if (isset($_GET['id'])) {
    $id_producto = intval($_GET['id']);

    // Verificar que el id_producto es válido
    if ($id_producto > 0) {
        // Obtener los detalles del producto
        $sql = "SELECT * FROM producto WHERE id_producto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $producto = $result->fetch_assoc();
        } else {
            echo "Producto no encontrado.";
            exit();
        }
    } else {
        echo "Número de producto no válido.";
        exit();
    }
} else {
    echo "No se proporcionó un ID de producto.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_producto = $_POST['nombre_producto'];
    $descripcion_producto = $_POST['descripcion_producto'];
    $precio_producto = $_POST['precio_producto'];

    // Actualizar el producto en la base de datos
    $sql = "UPDATE producto SET nombre_producto = ?, descripcion_producto = ?, precio_producto = ? WHERE id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $nombre_producto, $descripcion_producto, $precio_producto, $id_producto);

    if ($stmt->execute()) {
        // Redirigir a admincatalogo.php después de guardar los cambios
        header("Location: admincatalogo.php");
        exit();
    } else {
        echo "Error al actualizar el producto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
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
        } else {
            echo '<li><a href="catalogo.php">Catálogo</a></li>';
            echo '<li><a href="carrito.php">Carrito</a></li>';
            echo '<li><a href="Login.php">Iniciar Sesión</a></li>';
        }
        ?>
    </ul>
</nav>

<?php if (isset($producto)) { ?>
    <h2>Editar Producto</h2>
    <form action="editProduct.php?id=<?php echo $id_producto; ?>" method="post">
        <label for="nombre_producto">Nombre:</label>
        <input type="text" name="nombre_producto" id="nombre_producto" value="<?php echo $producto['nombre_producto']; ?>" required><br>

        <label for="descripcion_producto">Descripción:</label>
        <textarea name="descripcion_producto" id="descripcion_producto" required><?php echo $producto['descripcion_producto']; ?></textarea><br>

        <label for="precio_producto">Precio:</label>
        <input type="text" name="precio_producto" id="precio_producto" value="<?php echo $producto['precio_producto']; ?>" required><br>

        <input type="submit" value="Guardar Cambios">
    </form>
<?php } ?>

</body>
</html>
