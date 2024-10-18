<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Conectar a la base de datos
function conectarDB() {
    $conn = new mysqli('localhost', 'root', '', 'fruitfest');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    return $conn;
}

$conn = conectarDB();
$id_usuario = $_SESSION['id'];

// Recibir datos del formulario
$id_vendedor = $_POST['id_vendedor'];
$id_pedido = $_POST['id_pedido'];
$mensaje = $_POST['mensaje'];

// Insertar mensaje en la base de datos
$stmt = $conn->prepare("
    INSERT INTO mensajes (id_usuario, id_vendedor, id_pedido, mensaje, fecha)
    VALUES (?, ?, ?, ?, NOW())
");
if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}
$stmt->bind_param("iiis", $id_usuario, $id_vendedor, $id_pedido, $mensaje);
$stmt->execute();
$stmt->close();

$conn->close();

// Redirigir al historial de pedidos con un mensaje de éxito
header('Location: historial_pedidos.php?mensaje=Mensaje enviado con éxito');
exit();
?>
