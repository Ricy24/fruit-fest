<?php
session_start();
require_once 'conexion.php'; // Asegúrate de tener el archivo de conexión a la base de datos

// Verificar si el carrito está vacío
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "El carrito está vacío.";
    exit;
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Obtener el ID del usuario desde la sesión
$usuario_id = $_SESSION['id'];

// Verifica la conexión a la base de datos
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener el carrito de la sesión
$carrito = $_SESSION['carrito'] ?? [];

// Calcular el total del carrito y preparar los datos para insertar
$total = 0;
$productos = [];
$id_productos = [];
$precios = [];
$cantidades = [];
foreach ($carrito as $item) {
    // Validar que cada producto tenga todos los campos necesarios
    if (isset($item['id'], $item['precio'], $item['cantidad'], $item['nombre'])) {
        $total += $item['precio'] * $item['cantidad'];
        $productos[] = $item['nombre'];
        $id_productos[] = $item['id'];
        $precios[] = $item['precio'];
        $cantidades[] = $item['cantidad'];
    } else {
        die("El carrito contiene un producto con información incompleta.");
    }
}

// Obtener datos del formulario de envío
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
$localidad = mysqli_real_escape_string($conexion, $_POST['localidad']);
$barrio = mysqli_real_escape_string($conexion, $_POST['barrio']);
$tipo_calle = mysqli_real_escape_string($conexion, $_POST['tipo_calle']);
$direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
$numero = mysqli_real_escape_string($conexion, $_POST['numero']);
$piso = mysqli_real_escape_string($conexion, $_POST['piso']);
$ubicacion_tipo = mysqli_real_escape_string($conexion, $_POST['ubicacion_tipo']);
$telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
$correo_electronico = mysqli_real_escape_string($conexion, $_POST['correo_electronico'] ?? ''); // Maneja el campo opcional
$referencias = mysqli_real_escape_string($conexion, $_POST['referencias'] ?? ''); // Maneja el campo opcional
$metodo_pago = mysqli_real_escape_string($conexion, $_POST['metodo_pago']);

// Consulta para insertar en la tabla de historial de compras
$query_historial = "
    INSERT INTO historial_compras (usuario_id, fecha, productos, total) 
    VALUES (?, NOW(), ?, ?)";

// Prepara la consulta
$stmt_historial = $conexion->prepare($query_historial);
if ($stmt_historial === false) {
    die("Error al preparar la consulta de historial: " . $conexion->error);
}

// Convierte el carrito a JSON para almacenar los productos
$productos_json = json_encode($carrito);

// Asigna los parámetros y ejecuta la consulta
$stmt_historial->bind_param('iss', $usuario_id, $productos_json, $total);
if (!$stmt_historial->execute()) {
    die("Error al ejecutar la consulta de historial: " . $stmt_historial->error);
}

// Consulta para insertar en la tabla detalles_envio
$query_detalles_envio = "
    INSERT INTO detalles_envio (
        nombre, localidad, barrio, tipo_calle, direccion, numero, piso, ubicacion_tipo, telefono, correo_electronico, referencias, metodo_pago, productos, precios, cantidades, id_productos, total
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )";

// Prepara la consulta
$stmt_detalles_envio = $conexion->prepare($query_detalles_envio);
if ($stmt_detalles_envio === false) {
    die("Error al preparar la consulta de detalles de envío: " . $conexion->error);
}

// Convierte los detalles de los productos a JSON
$productos_json = json_encode($productos);
$precios_json = json_encode($precios);
$cantidades_json = json_encode($cantidades);
$id_productos_json = json_encode($id_productos);

// Asigna los parámetros y ejecuta la consulta
$stmt_detalles_envio->bind_param(
    'sssssssssssssssd', // Asegúrate de que coincida con el número de parámetros
    $nombre, $localidad, $barrio, $tipo_calle, $direccion, $numero, $piso, $ubicacion_tipo, $telefono, $correo_electronico, $referencias, $metodo_pago, $productos_json, $precios_json, $cantidades_json, $id_productos_json, $total
);

if (!$stmt_detalles_envio->execute()) {
    die("Error al ejecutar la consulta de detalles de envío: " . $stmt_detalles_envio->error);
}

// Cierra las declaraciones y la conexión
$stmt_historial->close();
$stmt_detalles_envio->close();
$conexion->close();

// Redirige al usuario a una página de confirmación
header('Location: confirmacion_compra.php');
unset($_SESSION['carrito']);
exit();
?>
