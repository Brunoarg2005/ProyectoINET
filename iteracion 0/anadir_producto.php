<?php
session_start();
include 'db/conectar.php';
$conn = conectar();

// Verificar si el usuario tiene permiso para añadir productos
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] < 2) {
    echo "No tienes permiso para acceder a esta página.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_producto = $_POST['nombre_producto'];
    $descripcion_producto = $_POST['descripcion_producto'];
    $precio_producto = $_POST['precio_producto'];
    
    // Verificar si se subió una imagen
    if (isset($_FILES['imagen_producto']) && $_FILES['imagen_producto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen_producto']['tmp_name'];
        $fileName = $_FILES['imagen_producto']['name'];
        $fileSize = $_FILES['imagen_producto']['size'];
        $fileType = $_FILES['imagen_producto']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Establecer el nombre del archivo como [nombreproducto].[extension]
        $newFileName = $nombre_producto . '.' . $fileExtension;
        $uploadFileDir = './productos/';
        $dest_path = $uploadFileDir . $newFileName;

        // Mover el archivo a la carpeta ./productos/
        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            $ruta_imagen = $dest_path;

            // Insertar producto en la base de datos
            $sql = "INSERT INTO producto (nombre_producto, descripcion_producto, precio_producto, ruta_imagen) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssds", $nombre_producto, $descripcion_producto, $precio_producto, $ruta_imagen);

            if ($stmt->execute()) {
                echo "Producto añadido exitosamente.";
            } else {
                echo "Error al añadir el producto: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Hubo un error moviendo el archivo al directorio de productos.";
        }
    } else {
        echo "Por favor sube una imagen válida.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Producto</title>
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
            <?php
            if ($_SESSION['tipo_usuario'] == 2) {
                echo '<li><a href="anadir_producto.php">Añadir Producto</a></li>';
            } elseif ($_SESSION['tipo_usuario'] == 3) {
                echo '<li><a href="anadir_producto.php">Añadir Producto</a></li>';
                echo '<li><a href="anadir_administrador.php">Añadir Administrador</a></li>';
            }
            echo '<li><a href="logout.php">Cerrar Sesión</a></li>';
            ?>
        </ul>
    </nav>

    <h1>Añadir Nuevo Producto</h1>

    <form method="POST" action="anadir_producto.php" enctype="multipart/form-data">
        <label for="nombre_producto">Nombre del Producto:</label>
        <input type="text" name="nombre_producto" id="nombre_producto" required>

        <label for="descripcion_producto">Descripción del Producto:</label>
        <textarea name="descripcion_producto" id="descripcion_producto" required></textarea>

        <label for="precio_producto">Precio del Producto:</label>
        <input type="number" step="0.01" name="precio_producto" id="precio_producto" required>

        <label for="imagen_producto">Imagen del Producto:</label>
        <input type="file" name="imagen_producto" id="imagen_producto" accept="image/*" required>

        <button type="submit">Añadir Producto</button>
    </form>
</body>
</html>
