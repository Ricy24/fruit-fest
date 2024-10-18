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

// Obtener parámetros de la URL
$id_vendedor = isset($_GET['id_vendedor']) ? (int)$_GET['id_vendedor'] : 0;
$id_pedido = isset($_GET['id_pedido']) ? (int)$_GET['id_pedido'] : 0;

// Obtener los mensajes entre el usuario y el vendedor para este pedido
$stmt = $conn->prepare("
    SELECT m.id, m.id_usuario, m.id_vendedor, m.id_pedido, m.mensaje, m.fecha, m.leido, u.nombre AS nombre_usuario, v.nombre AS nombre_vendedor
    FROM mensajes m
    LEFT JOIN usuarios u ON m.id_usuario = u.id
    LEFT JOIN vendedores v ON m.id_vendedor = v.id
    WHERE (m.id_usuario = ? AND m.id_vendedor = ?) OR (m.id_usuario = ? AND m.id_vendedor = ?)
    ORDER BY m.fecha ASC
");
if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}
$stmt->bind_param("iiii", $id_usuario, $id_vendedor, $id_vendedor, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$mensajes = [];
while ($mensaje = $result->fetch_assoc()) {
    // Marcar como leído si el mensaje es del vendedor y no ha sido leído
    if ($mensaje['id_vendedor'] === $id_usuario && !$mensaje['leido']) {
        $stmt_update = $conn->prepare("
            UPDATE mensajes
            SET leido = 1
            WHERE id = ?
        ");
        $stmt_update->bind_param("i", $mensaje['id']);
        $stmt_update->execute();
        $stmt_update->close();
    }
    $mensajes[] = $mensaje;
}

// Procesar el envío de un nuevo mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mensaje'])) {
    $mensaje_texto = trim($_POST['mensaje']);
    if (!empty($mensaje_texto)) {
        // Verificar si el usuario o el vendedor están enviando el mensaje
        $id_receptor = ($id_usuario === $id_vendedor) ? $id_vendedor : $id_usuario;
        $stmt_insert = $conn->prepare("
            INSERT INTO mensajes (id_usuario, id_vendedor, id_pedido, mensaje, fecha, leido)
            VALUES (?, ?, ?, ?, NOW(), 0)
        ");
        $stmt_insert->bind_param("iiis", $id_usuario, $id_receptor, $id_pedido, $mensaje_texto);
        $stmt_insert->execute();
        $stmt_insert->close();

        // Redirigir para evitar reenvío del formulario
        header("Location: chat.php?id_vendedor=$id_vendedor&id_pedido=$id_pedido");
        exit();
    }
}

$conn->close();
?>