<?php
session_start();

// Función para conectar a la base de datos
function conectarDB() {
    $conn = new mysqli('localhost', 'root', '', 'fruitfest');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    return $conn;
}

// Función para validar el código promocional
function validarCodigoPromocional($codigo) {
    $conn = conectarDB();
    $stmt = $conn->prepare("SELECT descuento, fecha_inicio, fecha_fin FROM codigos_promocionales WHERE codigo = ?");
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

// Verificar si el carrito está vacío
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: productos.php");
    exit;
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['id'];

// Conectar a la base de datos
$conn = conectarDB();

// Obtener un vendedor aleatorio
$stmt_vendedor = $conn->prepare("SELECT id FROM vendedores ORDER BY RAND() LIMIT 1");
if ($stmt_vendedor === false) {
    die("Error al preparar la consulta: " . $conn->error);
}
$stmt_vendedor->execute();
$stmt_vendedor->bind_result($id_vendedor);
$stmt_vendedor->fetch();
$stmt_vendedor->close();

// Obtener datos del formulario
$nombre = $_POST['nombre'] ?? '';
$identificacion = $_POST['identificacion'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$metodo_pago = $_POST['metodo_pago'] ?? '';
$codigo_promocional = $_POST['codigo_promocional'] ?? '';

// Validar datos del formulario
if (empty($nombre) || empty($identificacion) || empty($direccion) || empty($telefono) || empty($metodo_pago)) {
    header("Location: pago.php?error=datos_incompletos");
    exit;
}

// Calcular el subtotal en pesos colombianos (COP)
$subtotal = 0;
foreach ($_SESSION['carrito'] as $id_producto => $detalles) {
    if (is_array($detalles)) {
        $stmt = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $stmt->bind_result($precio_producto);
        $stmt->fetch();
        $stmt->close();

        // Convertir el precio a pesos colombianos (COP) multiplicando por 1,000
        $precio_producto_cop = $precio_producto * 1000;
        $subtotal += $detalles['cantidad'] * $precio_producto_cop;
    }
}

// Calcular el descuento y el total a pagar (sin envío)
$descuento = validarCodigoPromocional($codigo_promocional) * $subtotal;
$total_pago = $subtotal - $descuento;

// Guardar los detalles del pedido en la base de datos
$stmt = $conn->prepare("
    INSERT INTO pedidos (id_usuario, id_vendedor, nombre, identificacion, direccion, telefono, metodo_pago, subtotal, envio, descuento, total_pago, fecha) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?, NOW())
"); // Envio se define como 0
if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}

// Definición de tipos para bind_param: i (entero), s (cadena), d (decimal)
$stmt->bind_param("iissssssdd", $id_usuario, $id_vendedor, $nombre, $identificacion, $direccion, $telefono, $metodo_pago, $subtotal, $descuento, $total_pago);
if ($stmt->execute()) {
    $id_pedido = $stmt->insert_id;
    
    // Insertar los productos del carrito en la tabla de detalles del pedido
    foreach ($_SESSION['carrito'] as $id_producto => $detalles) {
        if (is_array($detalles)) {
            $cantidad = $detalles['cantidad'];
            $total_producto = $cantidad * ($precio_producto * 1000); // Precio en COP

            $stmt_detalle = $conn->prepare("INSERT INTO detalles_pedido (id_pedido, id_producto, id_vendedor, cantidad, total) VALUES (?, ?, ?, ?, ?)");
            if ($stmt_detalle === false) {
                die("Error al preparar la consulta: " . $conn->error);
            }
            $stmt_detalle->bind_param("iiidi", $id_pedido, $id_producto, $id_vendedor, $cantidad, $total_producto);
            $stmt_detalle->execute();
            $stmt_detalle->close();
        }
    }

    $stmt->close();
    $conn->close();

    // Limpiar el carrito
    unset($_SESSION['carrito']);
    // Redirigir al usuario a una página de confirmación
    header("Location: confirmacion_pago.php?id_pedido=" . $id_pedido);
    exit;
} else {
    die("Error al procesar el pago: " . $stmt->error);
}
?>
