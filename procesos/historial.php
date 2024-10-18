<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Función para conectar a la base de datos
function conectarDB() {
    $conn = new mysqli('localhost', 'root', '', 'fruitfest');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    return $conn;
}

$conn = conectarDB();
$id_usuario = $_SESSION['id'];

// Obtener historial de pedidos con estado de entrega
$stmt = $conn->prepare("
    SELECT p.id, p.fecha, p.total_pago, p.id_vendedor, dp.estado_entrega
    FROM pedidos p
    INNER JOIN detalles_pedido dp ON p.id = dp.id_pedido
    WHERE p.id_usuario = ?
    ORDER BY p.fecha DESC
");
if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Obtener los nombres de los productos por pedido
$pedidos = [];
while ($pedido = $result->fetch_assoc()) {
    $id_pedido = $pedido['id'];

    // Obtener los nombres de los productos para este pedido
    $stmt_productos = $conn->prepare("
        SELECT p.nombre, dp.estado_entrega
        FROM detalles_pedido dp
        JOIN productos p ON dp.id_producto = p.id
        WHERE dp.id_pedido = ?
    ");
    $stmt_productos->bind_param("i", $id_pedido);
    $stmt_productos->execute();
    $result_productos = $stmt_productos->get_result();

    $nombres_productos = [];
    $estado_entrega = '';
    while ($producto = $result_productos->fetch_assoc()) {
        $nombres_productos[] = $producto['nombre'];
        $estado_entrega = $producto['estado_entrega']; // Asumir que todos los productos tienen el mismo estado
    }

    // Obtener el nombre del vendedor
    $id_vendedor = $pedido['id_vendedor'];
    $stmt_vendedor = $conn->prepare("
        SELECT nombre
        FROM vendedores
        WHERE id = ?
    ");
    $stmt_vendedor->bind_param("i", $id_vendedor);
    $stmt_vendedor->execute();
    $result_vendedor = $stmt_vendedor->get_result();
    $nombre_vendedor = $result_vendedor->fetch_assoc()['nombre'];
    $stmt_vendedor->close();

    $pedidos[] = [
        'id' => $id_pedido,
        'productos' => implode(', ', $nombres_productos),
        'fecha' => $pedido['fecha'],
        'total_pago' => $pedido['total_pago'],
        'vendedor' => $nombre_vendedor,
        'estado_entrega' => $estado_entrega,
        'id_vendedor' => $id_vendedor
    ];
}

$conn->close();
?>