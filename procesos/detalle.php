<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id'];
$tasa_conversion = 4500; // Tasa de conversión a COP

// Función para agregar un producto al carrito
function agregarAlCarrito($id_producto, $nombre_producto, $precio_producto) {
    if (isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['carrito'][$id_producto]['cantidad']++;
    } else {
        $_SESSION['carrito'][$id_producto] = [
            'nombre' => $nombre_producto,
            'precio' => $precio_producto,
            'cantidad' => 1
        ];
    }
}

// Función para agregar un comentario
function agregarComentario($usuario_id, $producto_id, $comentario) {
    $conn = new mysqli('localhost', 'root', '', 'fruitfest');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO comentarios (id_usuario, id_producto, comentario) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Error en la consulta: " . $conn->error);
    }
    $stmt->bind_param("iis", $usuario_id, $producto_id, $comentario);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

// Función para agregar una visita al historial
function agregarHistorialProducto($usuario_id, $producto_id) {
    $conn = new mysqli('localhost', 'root', '', 'fruitfest');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificar si el historial ya contiene el registro
    $stmt = $conn->prepare("SELECT COUNT(*) FROM historial_productos WHERE id_usuario = ? AND id_producto = ?");
    $stmt->bind_param("ii", $usuario_id, $producto_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    // Si no existe, insertar el nuevo registro
    if ($count == 0) {
        $stmt = $conn->prepare("INSERT INTO historial_productos (id_usuario, id_producto) VALUES (?, ?)");
        if ($stmt === false) {
            die("Error en la consulta: " . $conn->error);
        }
        $stmt->bind_param("ii", $usuario_id, $producto_id);
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();
}


// Función para agregar a la lista de deseos
function agregarALaListaDeDeseos($producto_id, $usuario_id) {
    $conn = new mysqli('localhost', 'root', '', 'fruitfest');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO wishlist (id_usuario, id_producto) VALUES (?, ?)");
    if ($stmt === false) {
        die("Error en la consulta: " . $conn->error);
    }
    $stmt->bind_param("ii", $usuario_id, $producto_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

// Procesar solicitudes POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $conn = new mysqli('localhost', 'root', '', 'fruitfest');

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        if ($_POST['action'] == 'add' && isset($_POST['id'])) {
            $producto_id = $_POST['id'];

            $stmt = $conn->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
            if ($stmt === false) {
                die("Error en la consulta: " . $conn->error);
            }
            $stmt->bind_param("i", $producto_id);
            $stmt->execute();
            $stmt->bind_result($nombre_producto, $precio_producto);

            if ($stmt->fetch()) {
                agregarAlCarrito($producto_id, $nombre_producto, $precio_producto);
                if (!isset($_SESSION['notificacion_mostrada'])) {
                    $_SESSION['notificacion_mostrada'] = true;
                    echo json_encode(["status" => "success", "message" => "Producto agregado al carrito, ¡disfruta tus compras!"]);
                } else {
                    echo json_encode(["status" => "success", "message" => "Producto agregado al carrito"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Producto no encontrado"]);
            }

            $stmt->close();
        } elseif ($_POST['action'] == 'wishlist' && isset($_POST['id']) && isset($_SESSION['id'])) {
            $producto_id = $_POST['id'];
            $usuario_id = $_SESSION['id'];
            agregarALaListaDeDeseos($producto_id, $usuario_id);
            echo json_encode(["status" => "success", "message" => "Producto agregado a la lista de deseos"]);
        } elseif ($_POST['action'] == 'comment' && isset($_POST['comentario']) && isset($_POST['id_producto'])) {
            $comentario = $_POST['comentario'];
            $producto_id = $_POST['id_producto'];
            agregarComentario($user_id, $producto_id, $comentario);
            echo json_encode(["status" => "success", "message" => "Comentario enviado"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Acción no válida o datos insuficientes"]);
        }

        exit;
    }
}

$conn = new mysqli('localhost', 'root', '', 'fruitfest');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$producto_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT p.nombre, p.categoria, p.descripcion, p.precio, p.imagen, p.stock, v.nombre AS vendedor_nombre, v.foto AS vendedor_imagen, v.facebook, v.twitter, v.instagram 
                        FROM productos p 
                        JOIN vendedores v ON p.id_vendedor = v.id 
                        WHERE p.id = ?");
if ($stmt === false) {
    die("Error en la consulta: " . $conn->error);
}
$stmt->bind_param("i", $producto_id);
$stmt->execute();
$stmt->bind_result($nombre_producto, $categoria_producto, $descripcion_producto, $precio_producto, $imagen_producto, $stock_producto, $vendedor_nombre, $vendedor_imagen, $facebook, $twitter, $instagram);

if ($stmt->fetch()) {
    // Convertir el precio a pesos colombianos y formatear
    $precio_en_cop = $precio_producto * $tasa_conversion;
    $precio_formateado = "$" . number_format($precio_en_cop, 0, ',', '.');

    // Agregar al historial después de obtener los detalles del producto
    agregarHistorialProducto($user_id, $producto_id);

} else {
    die("Producto no encontrado");
}

$stmt->close();

// Obtener comentarios del producto
$stmt = $conn->prepare("SELECT c.comentario, u.nombre, c.created_at 
                        FROM comentarios c 
                        JOIN usuarios u ON c.id_usuario = u.id 
                        WHERE c.id_producto = ? 
                        ORDER BY c.created_at DESC");
$stmt->bind_param("i", $producto_id);
$stmt->execute();
$comentarios = $stmt->get_result();
$stmt->close();
$conn->close();
?>
