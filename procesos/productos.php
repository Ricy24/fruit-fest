<?php
session_start();

if (!isset($_SESSION['notificacion_mostrada'])) {
    $_SESSION['notificacion_mostrada'] = false;
}

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

// Función para agregar el mercado listo al carrito
function agregarMercadoListo() {
    $productos_basicos = [
        5, 2, 3, 11, 12, 13, 19, 23, 23, 23// IDs de productos básicos en la base de datos
    ];
    $conn = new mysqli('localhost', 'root', '', 'fruitfest');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    

    

    foreach ($productos_basicos as $producto_id) {
        $stmt = $conn->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
        $stmt->bind_param("i", $producto_id);
        $stmt->execute();
        $stmt->bind_result($nombre_producto, $precio_producto);

        if ($stmt->fetch()) {
            agregarAlCarrito($producto_id, $nombre_producto, $precio_producto);
        }

        $stmt->close();
    }

    $conn->close();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $conn = new mysqli('localhost', 'root', '', 'fruitfest');

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        if ($_POST['action'] == 'add' && isset($_POST['id'])) {
            $producto_id = $_POST['id'];

            $stmt = $conn->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
            $stmt->bind_param("i", $producto_id);
            $stmt->execute();
            $stmt->bind_result($nombre_producto, $precio_producto);

            if ($stmt->fetch()) {
                agregarAlCarrito($producto_id, $nombre_producto, $precio_producto);
                if (!$_SESSION['notificacion_mostrada']) {
                    $_SESSION['notificacion_mostrada'] = true;
                    echo json_encode(["status" => "success", "message" => "Producto agregado al carrito, ¡disfruta tus compras!"]);
                } else {
                    echo json_encode(["status" => "success", "message" => "Producto agregado al carrito"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Producto no encontrado"]);
            }

            $stmt->close();
        } elseif ($_POST['action'] == 'wishlist' && isset($_POST['id']) && isset($_SESSION['usuario_id'])) {
            $producto_id = $_POST['id'];
            $usuario_id = $_SESSION['usuario_id'];
            agregarALaListaDeDeseos($producto_id, $usuario_id);
            echo json_encode(["status" => "success", "message" => "Producto agregado a la lista de deseos"]);
        } elseif ($_POST['action'] == 'mercadoListo') {
            agregarMercadoListo();
            echo json_encode(["status" => "success", "message" => "Mercado listo agregado al carrito"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Acción no válida o datos insuficientes"]);
        }

        $conn->close();
        exit;
    }
}

$conn = new mysqli('localhost', 'root', '', 'fruitfest');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$categoria = isset($_GET['categoria']) ? $conn->real_escape_string($_GET['categoria']) : '';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 15;
$offset = ($page - 1) * $limit;

$sql = "SELECT id, nombre, categoria, descripcion, precio, imagen FROM productos";
if ($categoria) {
    $sql .= " WHERE categoria = ?";
}
$sql .= " LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if ($categoria) {
    $stmt->bind_param("sii", $categoria, $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();

$sql_count = "SELECT COUNT(id) AS total FROM productos";
if ($categoria) {
    $sql_count .= " WHERE categoria = ?";
}
$stmt_count = $conn->prepare($sql_count);
if ($categoria) {
    $stmt_count->bind_param("s", $categoria);
}
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_productos = $result_count->fetch_assoc()['total'];
$total_paginas = ceil($total_productos / $limit);
?>