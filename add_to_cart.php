<?php
session_start();
require_once 'conexion.php'; // Asegúrate de tener el archivo de conexión a la base de datos

$response = ['success' => false, 'message' => 'Error desconocido', 'num_productos_carrito' => 0];

// Verificar si los parámetros necesarios están presentes
if (isset($_POST['id']) && isset($_POST['cantidad']) && $_POST['action'] === 'add') {
    $id_producto = intval($_POST['id']);
    $cantidad = intval($_POST['cantidad']);
    
    if ($id_producto > 0 && $cantidad > 0) {
        // Obtener los detalles del producto
        $stmt = $conexion->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
        if ($stmt === false) {
            $response['message'] = 'Error al preparar la consulta.';
        } else {
            $stmt->bind_param("i", $id_producto);
            $stmt->execute();
            $stmt->bind_result($nombre_producto, $precio_producto);
            $stmt->fetch();
            $stmt->close();

            // Verificar si el producto existe
            if ($nombre_producto && $precio_producto) {
                // Inicializar el carrito si no existe
                if (!isset($_SESSION['carrito'])) {
                    $_SESSION['carrito'] = [];
                }

                // Agregar o actualizar el producto en el carrito
                $producto_existe = false;
                foreach ($_SESSION['carrito'] as &$item) {
                    if ($item['id'] == $id_producto) {
                        $item['cantidad'] += $cantidad;
                        $producto_existe = true;
                        break;
                    }
                }

                if (!$producto_existe) {
                    $_SESSION['carrito'][] = [
                        'id' => $id_producto,
                        'nombre' => $nombre_producto,
                        'precio' => $precio_producto,
                        'cantidad' => $cantidad
                    ];
                }

                // Contar el número total de productos en el carrito
                $num_productos_carrito = array_sum(array_column($_SESSION['carrito'], 'cantidad'));

                $response = [
                    'success' => true,
                    'message' => 'Producto añadido al carrito.',
                    'num_productos_carrito' => $num_productos_carrito
                ];
            } else {
                $response['message'] = 'Producto no encontrado.';
            }
        }
    } else {
        $response['message'] = 'ID de producto o cantidad inválidos.';
    }
} else {
    $response['message'] = 'Parámetros inválidos.';
}

// Enviar la respuesta JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cierra la conexión
$conexion->close();
?>
