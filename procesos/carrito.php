<?php
session_start();

// Función para obtener detalles del producto por ID
function obtenerDetallesProducto($id_producto) {
    $conn = new mysqli('localhost', 'root', '', 'fruitfest');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("SELECT p.nombre, p.precio, p.imagen, v.nombre AS vendedor
                             FROM productos p
                             JOIN vendedores v ON p.id_vendedor = v.id
                             WHERE p.id = ?");
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $stmt->bind_result($nombre_producto, $precio_producto, $imagen_producto, $nombre_vendedor);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
    return [
        'nombre' => $nombre_producto,
        'precio' => $precio_producto,
        'imagen' => $imagen_producto,
        'vendedor' => $nombre_vendedor
    ];
}

// Función para actualizar la cantidad de un producto en el carrito
function actualizarCantidadProducto($id_producto, $cantidad) {
    if (isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['carrito'][$id_producto]['cantidad'] = $cantidad;
        if ($cantidad <= 0) {
            eliminarProductoCarrito($id_producto);
        }
    }
}

// Función para eliminar un producto del carrito
function eliminarProductoCarrito($id_producto) {
    if (isset($_SESSION['carrito'][$id_producto])) {
        unset($_SESSION['carrito'][$id_producto]);
    }
}

// Manejar la eliminación de productos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id_producto'])) {
    $id_producto = intval($_POST['delete_id_producto']);
    eliminarProductoCarrito($id_producto);
}

// Manejar el vaciado del carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vaciar_carrito'])) {
    unset($_SESSION['carrito']);
    header("Location: ver_carrito.php");
    exit;
}

// Verificar si el carrito está vacío y redirigir a productos.php si es así
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: productos.php");
    exit;
}

// Contar productos en el carrito
$num_productos_carrito = 0;
foreach ($_SESSION['carrito'] as $detalles) {
    if (is_array($detalles) && isset($detalles['cantidad'])) {
        $num_productos_carrito += $detalles['cantidad'];
    }
}
?>
