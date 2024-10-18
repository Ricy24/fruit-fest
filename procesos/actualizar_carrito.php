<?php
session_start();
include 'ver_carrito.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_producto'], $_POST['cantidad'])) {
    $id_producto = intval($_POST['id_producto']);
    $cantidad = intval($_POST['cantidad']);
    
    actualizarCantidadProducto($id_producto, $cantidad);

    echo "Cantidad actualizada"; // Puedes devolver una respuesta si es necesario
}
?>
