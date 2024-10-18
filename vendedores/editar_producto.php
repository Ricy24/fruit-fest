<?php
// Iniciar sesión
session_start();

// Verificar si el vendedor está logueado
if (!isset($_SESSION['vendedor_id'])) {
    die("Acceso no autorizado.");
}

// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'fruitfest');

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$productId = $_POST['productId'];
$productName = $_POST['productName'];
$productPrice = $_POST['productPrice'];

// Actualizar el producto
$sql = "UPDATE productos SET nombre = ?, precio = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sdi", $productName, $productPrice, $productId);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
