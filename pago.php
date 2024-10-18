<?php include 'procesos/pago.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceso de Pago - Fruit Fest</title>
    <link rel="stylesheet" href="css/pago.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
    <?php include 'Estructura/header.php'; ?>
    </header>

    <main class="container">
    <h1>Proceso de Pago</h1>

    <table>
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Producto</th>
                <th>Vendedor</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($_SESSION['carrito'] as $id_producto => $detalles) {
                if (is_array($detalles)) {
                    $producto = obtenerDetallesProducto($id_producto);
                    $precio_producto = $producto['precio'];
                    $total_producto = $detalles['cantidad'] * $precio_producto;
                    
                    // Convertir precios a pesos colombianos
                    $precio_producto_pesos = $precio_producto * 1000;
                    $total_producto_pesos = $total_producto * 1000;

                    // Formatear precios
                    $precio_formateado = number_format($precio_producto_pesos, 0, ',', '.');
                    $total_producto_formateado = number_format($total_producto_pesos, 0, ',', '.');
            ?>
            <tr>
                <td>
                    <img src="uploads/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                        alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                </td>
                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                <td><?php echo htmlspecialchars($producto['vendedor']); ?></td>
                <td>$<?php echo $precio_formateado; ?> COP</td>
                <td><?php echo htmlspecialchars($detalles['cantidad']); ?></td>
                <td>$<?php echo $total_producto_formateado; ?> COP</td>
            </tr>
            <?php
                }
            }
            ?>
        </tbody>
        <tfoot>
            <?php
            // Convertir y formatear subtotal y descuento
            $subtotal_pesos = $subtotal * 1000;
            $descuento_pesos = $descuento * 1000;
            $total_pago_pesos = $subtotal_pesos - $descuento_pesos; // El total no incluye costo de envío
            
            $subtotal_formateado = number_format($subtotal_pesos, 0, ',', '.');
            $descuento_formateado = number_format($descuento_pesos, 0, ',', '.');
            $total_pago_formateado = number_format($total_pago_pesos, 0, ',', '.');
            ?>
            <tr>
                <td colspan="5" class="total">Subtotal</td>
                <td>$<?php echo $subtotal_formateado; ?> COP</td>
            </tr>
            <tr>
                <td colspan="5" class="total">Descuento Aplicado</td>
                <td>$<?php echo $descuento_formateado; ?> COP</td>
            </tr>
            <tr>
                <td colspan="5" class="total">Total</td>
                <td>$<?php echo $total_pago_formateado; ?> COP</td>
            </tr>
        </tfoot>
    </table>
</main>


        <form action="procesar_pago.php" method="post" class="form-container">
            <h2>Detalles de Pago</h2>

            <!-- Sección de Código Promocional -->
            <div class="form-section">
                <h3>Código Promocional</h3>
                <div class="form-group">
                    <label for="codigo_promocional">Código Promocional:</label>
                    <input type="text" id="codigo_promocional" name="codigo_promocional" value="<?php echo htmlspecialchars($codigo_promocional); ?>">
                    <button type="submit" name="aplicar_codigo" class="btn btn-secondary">Aplicar Código</button>
                </div>
            </div>

            <!-- Sección de Detalles de Pago -->
            <div class="form-section">
                <h3>Detalles de Pago</h3>
                <div class="form-group">
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="identificacion">Número de Identificación:</label>
                    <input type="text" id="identificacion" name="identificacion" required>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" required>
                </div>
                <div class="form-group">
                    <label for="metodo_pago">Método de Pago:</label>
                    <select id="metodo_pago" name="metodo_pago" required>
                        <option value="tarjeta_credito">Mercado pago</option>
                    </select>
                </div>
            </div>

            <!-- Botón de Enviar -->
            <button type="submit" class="btn btn-primary">Pagar</button>
        </form>

    </main>

    <footer>
    <?php include 'Estructura/footer.php'; ?>
    </footer>

    <div id="notification" class="notification"></div>

    <script>
        function mostrarNotificacion(tipo, mensaje) {
            var notificacion = document.getElementById('notification');
            notificacion.className = 'notification ' + tipo;
            notificacion.innerHTML = mensaje;
            notificacion.style.display = 'block';
            setTimeout(function() {
                notificacion.style.display = 'none';
            }, 5000);
        }

        <?php if (isset($codigo_promocional) && $porcentaje_descuento > 0): ?>
            mostrarNotificacion('success', 'Código promocional aplicado con éxito. ¡Descuento del <?php echo $porcentaje_descuento * 100; ?>%!');
        <?php elseif (isset($codigo_promocional) && $descuento == 0): ?>
            mostrarNotificacion('error', 'Código promocional inválido o expirado.');
        <?php endif; ?>
    </script>
</body>
</html>
