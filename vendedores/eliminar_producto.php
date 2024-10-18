<?php
// Habilitar la visualización de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Obtener el ID del producto a eliminar
$productId = isset($_POST['productId']) ? intval($_POST['productId']) : 0;

$response = array('status' => 'error', 'message' => '');

// Verificar si el producto está en la tabla 'detalles_pedido'
$query = $conn->prepare("SELECT COUNT(*) FROM detalles_pedido WHERE id_producto = ?");
$query->bind_param("i", $productId);
$query->execute();
$query->bind_result($count);
$query->fetch();
$query->close();

// Si el producto está en 'detalles_pedido', no se puede eliminar
if ($count > 0) {
    $response['message'] = 'No se puede eliminar el producto porque está en un pedido.';
} else {
    // Si no está en 'detalles_pedido', proceder a eliminar de 'historial_productos' y luego de 'productos'
    $conn->begin_transaction();

    try {
        // Eliminar de historial_productos
        $query = $conn->prepare("DELETE FROM historial_productos WHERE id_producto = ?");
        $query->bind_param("i", $productId);
        if (!$query->execute()) {
            throw new Exception($query->error);
        }
        $query->close();

        // Eliminar de productos
        $query = $conn->prepare("DELETE FROM productos WHERE id = ? AND id_vendedor = ?");
        $query->bind_param("ii", $productId, $vendedor_id);
        if (!$query->execute()) {
            throw new Exception($query->error);
        }
        $query->close();

        $conn->commit();
        $response['status'] = 'success';
        $response['message'] = 'Producto eliminado exitosamente.';
    } catch (Exception $e) {
        $conn->rollback();
        $response['message'] = 'Error al eliminar el producto: ' . $e->getMessage();
    }
}

$conn->close();

// Devolver respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>
