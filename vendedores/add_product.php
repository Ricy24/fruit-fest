<?php
// Iniciar la sesión
session_start();

// Verificar si el vendedor está logueado
if (!isset($_SESSION['vendedor_id'])) {
    header("Location: login.php");
    exit;
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fruitfest"; // Cambia esto por tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$nombre = $_POST['nombreProducto'];
$descripcion = $_POST['descripcionProducto'];
$precio = $_POST['precioProducto'];
$categoria = $_POST['categoriaProducto'];
$tipo = $_POST['tipoProducto'];
$id_vendedor = $_SESSION['vendedor_id']; // ID del vendedor logueado

// Subir la imagen del producto
$imagen = $_FILES['imagenProducto']['name'];
$ruta_imagen = "../uploads/" . basename($imagen);
move_uploaded_file($_FILES['imagenProducto']['tmp_name'], $ruta_imagen);

// Insertar el nuevo producto en la base de datos
$sql = "INSERT INTO campesinos (nombre, descripcion, precio, categoria, tipo, imagen, id_vendedor)
        VALUES ('$nombre', '$descripcion', '$precio', '$categoria', '$tipo', '$ruta_imagen', '$id_vendedor')";

if ($conn->query($sql) === TRUE) {
    echo "Producto añadido exitosamente.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar la conexión
$conn->close();

// Redirigir al usuario a la página principal o donde prefieras
header("Location: mercado");
exit;
?>
