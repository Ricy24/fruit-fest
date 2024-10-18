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




// Consulta SQL para obtener las estadísticas agrupadas por mes
$stats_query = $conn->prepare("
    SELECT 
        MONTH(fecha) AS mes,
        COUNT(id) AS total_pedidos,
        SUM(subtotal) AS total_ingresos
    FROM pedidos
    WHERE id_vendedor = ?
    GROUP BY MONTH(fecha)
    ORDER BY MONTH(fecha)
");

if ($stats_query === false) {
    die('Error en la consulta SQL: ' . $conn->error);
}

$stats_query->bind_param("i", $vendedor_id);
$stats_query->execute();
$stats_result = $stats_query->get_result();

// Inicializar arrays para los datos del gráfico
$meses = [];
$total_pedidos = [];
$total_ingresos = [];

// Almacenar los resultados en arrays para pasarlos a JavaScript
while ($row = $stats_result->fetch_assoc()) {
    $meses[] = DateTime::createFromFormat('!m', $row['mes'])->format('F'); // Convertir número de mes a nombre de mes (ejemplo: "January")
    $total_pedidos[] = $row['total_pedidos'];
    $total_ingresos[] = $row['total_ingresos'];
}

// Preparar los datos para JavaScript
$ventas_datos = [
    'meses' => $meses,
    'total_pedidos' => $total_pedidos,
    'total_ingresos' => $total_ingresos
];


// Almacenar las redes sociales en variables
$facebook = $vendedor_data['facebook'];
$twitter = $vendedor_data['twitter'];
$instagram = $vendedor_data['instagram'];

// Verificar si se envió una solicitud de actualización por AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_product') {
    $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $imagen = isset($_POST['imagen']) ? $_POST['imagen'] : ''; // Mantener la imagen actual si no se sube una nueva

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = basename($_FILES['imagen']['name']);
        $target_dir = "../uploads/";
        $target_file = $target_dir . $imagen;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
            echo json_encode(['status' => 'error', 'message' => 'Error al subir la imagen.']);
            exit();
        }
    }

   
    $stmt->close();
    exit();
}



// Obtener todos los productos del vendedor
$product_query = $conn->prepare("SELECT * FROM productos WHERE id_vendedor = ?");
if ($product_query === false) {
    die('Error en la consulta SQL: ' . $conn->error);
}
$product_query->bind_param("i", $vendedor_id);
$product_query->execute();
$result = $product_query->get_result();

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
if ($sold_query === false) {
    die('Error en la consulta SQL: ' . $conn->error);
}
$sold_query->bind_param("i", $vendedor_id);
$sold_query->execute();
$sold_result = $sold_query->get_result();

// Calcular estadísticas de ventas
$stats_query = $conn->prepare("
    SELECT 
        COUNT(DISTINCT pd.id) AS total_pedidos,
        COUNT(dp.id_producto) AS total_productos_vendidos,
        SUM(pd.total_pago) AS total_ingresos
    FROM pedidos pd
    INNER JOIN detalles_pedido dp ON pd.id = dp.id_pedido
    INNER JOIN productos p ON dp.id_producto = p.id
    WHERE p.id_vendedor = ?
");
if ($stats_query === false) {
    die('Error en la consulta SQL: ' . $conn->error);
}
$stats_query->bind_param("i", $vendedor_id);
$stats_query->execute();
$stats_result = $stats_query->get_result();
$stats = $stats_result->fetch_assoc();

$total_pedidos = $stats['total_pedidos'];
$total_productos_vendidos = $stats['total_productos_vendidos'];
$total_ingresos = $stats['total_ingresos'];





// Cerrar la conexión
$conn->close();
?>