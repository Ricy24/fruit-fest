<?php
// Incluye el archivo de conexión a la base de datos
include 'conexion.php';
session_start();

// Obtener la búsqueda del usuario
$searchTerm = $_GET['search'] ?? '';

// Verificar si el usuario está logueado
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];

    // Consulta para obtener el historial de pedidos del usuario
    $historialSql = "SELECT p.id, p.nombre, p.precio, p.imagen, hp.fecha_visto
                     FROM historial_productos hp
                     JOIN productos p ON hp.id_producto = p.id
                     WHERE hp.id_usuario = ?
                     ORDER BY hp.fecha_visto DESC";
    $historialStmt = $connection->prepare($historialSql);
    if ($historialStmt === false) {
        die("Error en la preparación de la consulta del historial: " . $connection->error);
    }
    $historialStmt->bind_param("i", $user_id);
    $historialStmt->execute();
    $historialResult = $historialStmt->get_result();
    $historialPedidos = $historialResult->fetch_all(MYSQLI_ASSOC);
    $historialStmt->close();

    // Filtrar productos únicos
    $historialPedidos = array_map("unserialize", array_unique(array_map("serialize", $historialPedidos)));
} else {
    $historialPedidos = [];
}

// Consulta para obtener solo 5 productos
$productosSql = "SELECT id, nombre, precio, imagen 
                 FROM productos 
                 LIMIT 5";
$productosStmt = $connection->prepare($productosSql);
if ($productosStmt === false) {
    die("Error en la preparación de la consulta de productos: " . $connection->error);
}
$productosStmt->execute();
$productosResult = $productosStmt->get_result();
$productosNuevos = $productosResult->fetch_all(MYSQLI_ASSOC);
$productosStmt->close();

// Cierra la conexión
$connection->close();
?>