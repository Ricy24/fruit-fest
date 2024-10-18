<?php
session_start();

// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'fruitfest');

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha enviado el ID del pedido
if (isset($_POST['id_detalle_pedido'])) {
    $id_detalle_pedido = $_POST['id_detalle_pedido'];

    // Obtener el id_pedido asociado al id_detalle_pedido
    $pedido_query = $conn->prepare("SELECT id_pedido FROM detalles_pedido WHERE id = ?");
    $pedido_query->bind_param("i", $id_detalle_pedido);
    $pedido_query->execute();
    $pedido_result = $pedido_query->get_result();

    if ($pedido_result->num_rows > 0) {
        $pedido_row = $pedido_result->fetch_assoc();
        $id_pedido = $pedido_row['id_pedido'];

        // Actualizar el estado de entrega del detalle del pedido
        $update_query = $conn->prepare("UPDATE detalles_pedido SET estado_entrega = 'Completado' WHERE id = ?");
        $update_query->bind_param("i", $id_detalle_pedido);
        
        if ($update_query->execute()) {
            // Redirigir a la página de productos vendidos con un mensaje de éxito
            header("Location: productos_vendidos.php?success=Pedido marcado como completado.");
            exit;
        } else {
            echo "Error al marcar el pedido: " . $conn->error;
        }
    } else {
        echo "Pedido no encontrado.";
    }
} else {
    echo "ID de detalle de pedido no válido.";
}

// Cerrar la conexión
$conn->close();
?>
