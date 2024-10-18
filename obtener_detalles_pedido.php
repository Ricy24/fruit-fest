<?php
require_once 'conexion.php';

// Verificar si se ha proporcionado un ID de pedido
if (!isset($_GET['pedido_id']) || !is_numeric($_GET['pedido_id'])) {
    echo json_encode(['error' => 'ID de pedido no válido.']);
    exit;
}

$pedido_id = (int)$_GET['pedido_id'];

// Preparar y ejecutar la consulta para obtener los detalles del pedido
$query = "
    SELECT nombre_producto, cantidad
    FROM pedido_detalles
    WHERE pedido_id = ?
";

$stmt = $conexion->prepare($query);
$stmt->bind_param('i', $pedido_id);
$stmt->execute();
$result = $stmt->get_result();

$detalles = [];
while ($row = $result->fetch_assoc()) {
    $detalles[] = $row;
}

$stmt->close();
$conexion->close();

// Devolver los detalles en formato JSON
echo json_encode($detalles);
