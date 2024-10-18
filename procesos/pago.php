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

// Verificar si el carrito está vacío
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: productos.php");
    exit;
}

// Contar productos en el carrito y calcular el total
$total_carrito = 0;
foreach ($_SESSION['carrito'] as $id_producto => $detalles) {
    if (is_array($detalles)) {
        $producto = obtenerDetallesProducto($id_producto);
        $total_producto = $detalles['cantidad'] * $producto['precio'];
        $total_carrito += $total_producto;
    }
}

// Costo de envío
$envio = 26.00;
$subtotal = $total_carrito;
$total_pago = $subtotal + $envio;

// Verificar si se ha enviado un código promocional
$codigo_promocional = isset($_POST['codigo_promocional']) ? $_POST['codigo_promocional'] : '';
$descuento = 0;

// Función para validar el código promocional en la base de datos
function validarCodigoPromocional($codigo) {
    $conn = new mysqli('localhost', 'root', '', 'fruitfest');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("SELECT descuento, fecha_inicio, fecha_fin 
                             FROM codigos_promocionales 
                             WHERE codigo = ?");
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $stmt->bind_result($descuento_porcentaje, $fecha_inicio, $fecha_fin);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    $hoy = date('Y-m-d');
    if ($descuento_porcentaje && $hoy >= $fecha_inicio && $hoy <= $fecha_fin) {
        return $descuento_porcentaje / 100;
    } else {
        return 0;
    }
}

$porcentaje_descuento = validarCodigoPromocional($codigo_promocional);
$descuento = $subtotal * $porcentaje_descuento;
$total_pago -= $descuento;
?>