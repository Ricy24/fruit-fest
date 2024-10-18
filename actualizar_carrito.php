<?php
session_start();

// Contar productos en el carrito
$num_productos_carrito = 0;
if (isset($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $producto) {
        // Verificar si $producto es un array con la clave 'cantidad'
        if (is_array($producto) && isset($producto['cantidad'])) {
            $num_productos_carrito += $producto['cantidad'];
        } else {
            // Si $producto no es un array, se asume que es una cantidad simple
            $num_productos_carrito += $producto;
        }
    }
}

// Devolver el número total de productos en el carrito
echo $num_productos_carrito;
?>
