<?php
session_start();

// Inicializar el carrito si no está definido
if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Contar los productos en el carrito
$num_productos_carrito = count($_SESSION['carrito']);

// Devolver el conteo en formato JSON
header('Content-Type: application/json');
echo json_encode(['count' => $num_productos_carrito]);
?>
