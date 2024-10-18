<?php
session_start();

if (!isset($_SESSION['vendedor_id'])) {
    die("Acceso no autorizado.");
}

$conn = new mysqli('localhost', 'root', '', 'fruitfest');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($productId > 0) {
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ? AND id_vendedor = ?");
    $stmt->bind_param("ii", $productId, $_SESSION['vendedor_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
        echo json_encode($producto);
    } else {
        echo json_encode(null);
    }
    $stmt->close();
}

$conn->close();
?>
