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

// Obtener productos de la base de datos
$sql = "SELECT id_producto, nombre_producto, precio_producto, ruta_imagen FROM producto";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo</title>
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
    <h2>Catálogo de productos</h2>
    <div class="producto">
        <?php
        if ($result->num_rows > 0) {
		echo '<div class="productoContainer">';
    while ($row = $result->fetch_assoc()) {
        echo '<div class="producto">';
        echo '<img src="' . $row['ruta_imagen'] . '" alt="' . $row['nombre_producto'] . '" style="height: 100%;">';
        echo '<h2>' . $row['nombre_producto'] . '</h2>';
        echo '<p>Precio: $' . $row['precio_producto'] . '</p>';
        echo '<button onclick="addToCart(' . $row['id_producto'] . ')">Añadir al carrito</button>';
        echo '</div>';
		echo '</div>';
    }
} else {
    echo '<p>No hay productos disponibles.</p>';
}

        $conn->close();
        ?>
    </div>
    <script>
function addToCart(productId) {
    fetch('./db/addToCart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id_producto=' + productId
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // Muestra un mensaje de confirmación
    })
    .catch(error => console.error('Error:', error));
}
</script>

</body>
</html>
