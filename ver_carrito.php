<?php include 'procesos/carrito.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Fruit Fest</title>
    <link rel="stylesheet" href="css/carrito.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
    <?php include 'Estructura/header.php'; ?>
    </header>

    <main class="container">
    <h1>Carrito de Compras</h1>

    <table>
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Vendedor</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody id="carrito-body">
            <?php
            $total_carrito = 0;
            foreach ($_SESSION['carrito'] as $id_producto => $detalles) {
                if (is_array($detalles)) {
                    $producto = obtenerDetallesProducto($id_producto);
                    $precio_producto = $producto['precio'];
                    $total_producto = $detalles['cantidad'] * $precio_producto;
                    $total_carrito += $total_producto;

                    // Convertir precios a pesos colombianos
                    $precio_producto_pesos = $precio_producto * 1000;
                    $total_producto_pesos = $total_producto * 1000;
                    $total_carrito_pesos = $total_carrito * 1000;
                    
                    // Formatear precios
                    $precio_formateado = number_format($precio_producto_pesos, 0, ',', '.');
                    $total_producto_formateado = number_format($total_producto_pesos, 0, ',', '.');
                    $total_carrito_formateado = number_format($total_carrito_pesos, 0, ',', '.');
            ?>
            <tr data-id="<?php echo $id_producto; ?>">
                <td>
                    <img src="uploads/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                        alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                </td>
                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                <td>$<?php echo $precio_formateado; ?> COP</td>
                <td>
                    <div class="quantity-controls">
                        <button class="btn btn-primary minus" onclick="actualizarCantidad(<?php echo $id_producto; ?>, -1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="text" value="<?php echo $detalles['cantidad']; ?>" readonly>
                        <button class="btn btn-primary plus" onclick="actualizarCantidad(<?php echo $id_producto; ?>, 1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </td>
                <td class="total-producto">$<?php echo $total_producto_formateado; ?> COP</td>
                <td><?php echo htmlspecialchars($producto['vendedor']); ?></td>
                <td>
                    <form method="POST" action="ver_carrito.php" class="delete-form">
                        <input type="hidden" name="delete_id_producto" value="<?php echo $id_producto; ?>">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <?php
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="total">Total</td>
                <td id="total-carrito">$<?php echo $total_carrito_formateado; ?> COP</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <form method="POST" action="ver_carrito.php" class="vaciar-form">
        <input type="hidden" name="vaciar_carrito" value="1">
        <button type="submit" class="btn btn-secondary">Vaciar Carrito</button>
    </form>

    <br>
    <div class="btn-pay-container">
        <a href="pago.php" class="btn-pay">Proceder al Pago
        <div class="truck"></div>
    </a>
    </div>

    <div class="btn-continue-container">
        <a href="productos.php" class="btn-continue" id="btn-continue">
            <i class="fas fa-shopping-cart icon"></i> <!-- Ícono del carrito -->
            <div class="tooltip" id="tooltip">
                <div class="tooltip-arrow"></div>
                ¿Quieres seguir comprando?
            </div>
        </a>
    </div>
</main>


    <footer>
    <?php include 'Estructura/footer.php'; ?>
    </footer>

    <script>
        function actualizarCantidad(idProducto, cambio) {
            const cantidadInput = document.querySelector(`tr[data-id="${idProducto}"] input`);
            let cantidad = parseInt(cantidadInput.value, 10);
            cantidad = Math.max(cantidad + cambio, 1); // Evitar valores negativos o cero
            cantidadInput.value = cantidad;

            // Enviar la actualización al servidor
            fetch('actualizar_cantidad.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    id_producto: idProducto,
                    cantidad: cantidad
                })
            }).then(response => response.text())
              .then(data => {
                  // Actualizar el total del producto
                  const precio = parseFloat(document.querySelector(`tr[data-id="${idProducto}"] td:nth-child(3)`).textContent.replace('$', ''));
                  const totalProducto = precio * cantidad;
                  document.querySelector(`tr[data-id="${idProducto}"] .total-producto`).textContent = `$${totalProducto.toFixed(2)}`;
                  
                  // Actualizar el total del carrito
                  const totalCarritoElement = document.getElementById('total-carrito');
                  const filas = document.querySelectorAll('#carrito-body tr');
                  let totalCarrito = 0;
                  filas.forEach(fila => {
                      const totalFila = parseFloat(fila.querySelector('.total-producto').textContent.replace('$', ''));
                      totalCarrito += totalFila;
                  });
                  totalCarritoElement.textContent = `$${totalCarrito.toFixed(2)}`;
              }).catch(error => {
                  console.error('Error al actualizar la cantidad:', error);
              });
        }


    </script>
</body>
</html>
