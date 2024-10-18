<?php include 'procesos/pedidos.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/pedidos.css"> <!-- Asegúrate de que el CSS esté correctamente vinculado -->
    <title>Panel de Vendedor</title>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="main-content">
        <h2 style="color: #004d00; display: inline-block;">Productos Vendidos</h2>
    
        <div class="tarjetas">
            <div class="tarjeta">
                <div class="contenido-tarjeta">
                    <h3>Total Pedidos</h3>
                    <p style="font-size: 20px; color: #004d00;"><?php echo $total_pedidos; ?></p>
                </div>
                <i class="fas fa-box icono-tarjeta"></i>
            </div>
            <div class="tarjeta">
                <div class="contenido-tarjeta">
                    <h3>Pendientes</h3>
                    <p style="font-size: 20px; color: #004d00;"><?php echo $pendientes; ?></p>
                </div>
                <i class="fas fa-hourglass-start icono-tarjeta"></i>
            </div>
            <div class="tarjeta">
                <div class="contenido-tarjeta">
                    <h3>Entregados</h3>
                    <p style="font-size: 20px; color: #004d00;"><?php echo $entregados; ?></p>
                </div>
                <i class="fas fa-check-circle icono-tarjeta"></i>
            </div>
            <div class="tarjeta">
                <div class="contenido-tarjeta">
                    <h3>Cancelados</h3>
                    <p style="font-size: 20px; color: #004d00;">2</p> <!-- Este es fijo -->
                </div>
                <i class="fas fa-ban icono-tarjeta"></i>
            </div>
        </div>

        <!-- Información de los pedidos vendidos -->
        <div class="table-responsive mt-4">
            <h3>Detalles de los Pedidos</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Total</th>
                        <th>Método de Pago</th>
                        <th>Estado de Entrega</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Reiniciar el puntero del resultado para volver a recorrerlo
                    $sold_result->data_seek(0); 
                    while ($pedido = $sold_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $pedido['id_pedido']; ?></td>
                            <td><?php echo $pedido['nombre']; ?></td>
                            <td><?php echo $pedido['cantidad_producto']; ?></td>
                            <td><?php echo $pedido['total_pago']; ?> $</td>
                            <td><?php echo $pedido['metodo_pago']; ?></td>
                            <td><?php echo $pedido['estado_entrega']; ?></td>
                            <td><?php echo $pedido['fecha']; ?></td>
                            <td><?php echo $pedido['nombre_usuario']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<script>
    // Función para mostrar/ocultar los detalles del pedido
    document.querySelectorAll('.btn-detalles').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const detallesRow = document.getElementById("detalles-" + id);
            if (detallesRow.style.display === "none") {
                detallesRow.style.display = "table-row"; // Mostrar detalles
            } else {
                detallesRow.style.display = "none"; // Ocultar detalles
            }
        });
    });

    // Función para eliminar un pedido
    function eliminarPedido(id) {
        // Aquí implementarías la lógica para eliminar el pedido, tal vez una llamada AJAX
        alert(`El pedido con ID ${id} ha sido eliminado.`);
    }
</script>

