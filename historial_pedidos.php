<?php include 'procesos/historial.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Pedidos</title>
    <link rel="stylesheet" href="css/historial_pedidos.css"> <!-- Enlace a tu archivo CSS -->
</head>
<body>
    <header>
        <h1>Historial de Pedidos</h1>
    </header>
    <main>
        <div class="historial">
            <h2>Tu Historial de Pedidos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Productos</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Vendedor</th>
                        <th>Estado de Entrega</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pedido['productos']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                        <td><?php echo number_format($pedido['total_pago'], 2); ?></td>
                        <td><?php echo htmlspecialchars($pedido['vendedor']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['estado_entrega']); ?></td>
                        <td>
                            <a href="generar_pdf.php?id_pedido=<?php echo htmlspecialchars($pedido['id']); ?>" class="boton boton-imprimir">Imprimir Factura</a>
                            <a href="chat.php?id_vendedor=<?php echo htmlspecialchars($pedido['id_vendedor']); ?>&id_pedido=<?php echo htmlspecialchars($pedido['id']); ?>" class="boton">Chat con Vendedor</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="index.php" class="boton">Volver al inicio</a>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Fruit Fest. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
