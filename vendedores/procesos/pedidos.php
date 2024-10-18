<?php
// Iniciar sesión
session_start();

// Verificar si el vendedor está logueado
if (!isset($_SESSION['vendedor_id'])) {
    header("Location: login.php");
    exit;
}

// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'fruitfest');

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$vendedor_id = $_SESSION['vendedor_id'];

// Obtener la información del vendedor
$vendedor_query = $conn->prepare("SELECT nombre, foto, facebook, twitter, instagram FROM vendedores WHERE id = ?");
$vendedor_query->bind_param("i", $vendedor_id);
$vendedor_query->execute();
$vendedor_result = $vendedor_query->get_result();
$vendedor_data = $vendedor_result->fetch_assoc();
$vendedor_query->close();

// Almacenar las redes sociales en variables
$facebook = $vendedor_data['facebook'];
$twitter = $vendedor_data['twitter'];
$instagram = $vendedor_data['instagram'];

// Inicializar contadores
$total_pedidos = 0;
$pendientes = 0; // Inicializar como 0
$entregados = 0; // Inicializar como 0

// Obtener los productos vendidos por el vendedor
$sold_query = $conn->prepare("
    SELECT p.nombre, p.id AS id_producto, p.descripcion, p.precio, p.categoria, p.imagen,
           pd.id AS id_pedido, pd.metodo_pago, pd.total_pago, pd.fecha, dp.cantidad AS cantidad_producto,
           dp.estado_entrega, u.email, u.nombre AS nombre_usuario, pd.identificacion, pd.direccion, u.id AS usuario_id
    FROM pedidos pd
    INNER JOIN detalles_pedido dp ON pd.id = dp.id_pedido
    INNER JOIN productos p ON dp.id_producto = p.id
    INNER JOIN usuarios u ON pd.id_usuario = u.id
    WHERE p.id_vendedor = ?
");
$sold_query->bind_param("i", $vendedor_id);
$sold_query->execute();
$sold_result = $sold_query->get_result();

// Contar los pedidos y sus estados
while ($pedido = $sold_result->fetch_assoc()) {
    $total_pedidos++; // Contar cada pedido
    if ($pedido['estado_entrega'] == 'Pendiente') {
        $pendientes++; // Contar pedidos pendientes
    } elseif ($pedido['estado_entrega'] == 'Entregado') {
        $entregados++; // Contar pedidos entregados
    }
}

// Obtener la cuenta de pedidos pendientes directamente de la tabla detalles_pedido
$pendientes_query = $conn->prepare("
    SELECT COUNT(*) as total_pendientes 
    FROM detalles_pedido dp
    INNER JOIN pedidos pd ON dp.id_pedido = pd.id
    INNER JOIN productos p ON dp.id_producto = p.id
    WHERE dp.estado_entrega = 'Pendiente' AND p.id_vendedor = ?
");
$pendientes_query->bind_param("i", $vendedor_id);
$pendientes_query->execute();
$pendientes_result = $pendientes_query->get_result();
$pendientes_data = $pendientes_result->fetch_assoc();
$pendientes = $pendientes_data['total_pendientes']; // Asignar el conteo a la variable

// Cerrar la conexión
$conn->close();
?>