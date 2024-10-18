<?php include 'procesos/index.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/inicio.css"> <!-- Asegúrate de que el CSS esté correctamente vinculado -->
    <title>Panel de Vendedor</title>
    
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="main-content">
        <div class="header-info">
            <h1 class="header-title">Bienvenido</h1> <!-- Título alineado a la izquierda -->
            <div style="display: flex; align-items: center;"> <!-- Contenedor para la foto y textos -->
                <img src="../uploads/<?php echo $vendedor_data['foto']; ?>" alt="Foto de perfil">
                <div>
                    <p class="username"><?php echo ucfirst($vendedor_data['nombre']); ?></p> <!-- Primera letra en mayúscula -->
                    <p class="role">Vendedor de Frutas y Verduras</p>
                    <a href="certificado.php" class="generate-button">
    Genera tu certificado
</a>

                </div>
            </div>
        </div>

        <div class="card-container">
            <div class="card">
                <i class="icon fas fa-money-bill-wave"></i> <!-- Icono para Ventas Totales -->
                <h3>Ventas Totales</h3>
                <p><?php echo '$' . number_format($total_ingresos, 2); ?></p>
                <p class="percentage">+10% desde la última vez</p> <!-- Porcentaje de aumento -->
            </div>
            <div class="card">
                <i class="icon fas fa-box"></i> <!-- Icono para Productos Activos -->
                <h3>Productos Activos</h3>
                <p>+<?php echo $total_productos_vendidos; ?></p> <!-- Signo más añadido -->
                <p class="percentage">+5% desde la última vez</p> <!-- Porcentaje de aumento -->
            </div>
            <div class="card">
                <i class="icon fas fa-users"></i> <!-- Icono para Clientes -->
                <h3>Clientes</h3>
                <p>+<?php echo $total_pedidos; ?></p> <!-- Signo más añadido -->
                <p class="percentage">+8% desde la última vez</p> <!-- Porcentaje de aumento -->
            </div>
            <div class="card">
                <i class="icon fas fa-clock"></i> <!-- Icono para Pedidos Pendientes -->
                <h3>Pedidos Pendientes</h3>
                <p>+<?php echo $total_pedidos; ?></p> <!-- Puedes modificar este conteo según tu lógica -->
                <p class="percentage">-2% desde la última vez</p> <!-- Porcentaje de aumento -->
            </div>
        </div>
<br>
<!-- Sección de Pedidos Recientes -->
<div class="recent-orders" style="padding: 20px; border: 1px solid #d3d3d3; border-radius: 8px; background-color: white;">
    <h2 style="color: #2E7D32;">Pedidos Recientes</h2> <!-- Color verde oscuro para el título -->
    <p>Tienes <?php echo $sold_result->num_rows; ?> pedidos para enviar hoy.</p>

    <table style="width: 100%; border-collapse: collapse;">
        <tbody>
            <?php while ($row = $sold_result->fetch_assoc()): ?>
                <tr style="border-bottom: none;"> <!-- Eliminamos bordes entre filas -->
                    <td style="padding: 20px 0;">
                        <!-- Información del cliente -->
                        <div class="customer-info" style="display: flex; align-items: center;">
                            <i class="fas fa-user" style="margin-right: 10px; font-size: 24px;"></i>
                            <div>
                                <strong style="font-size: 16px;"><?php echo htmlspecialchars($row['nombre_usuario']); ?></strong><br>
                                <span style="color: #228B22;"><?php echo htmlspecialchars($row['email']); ?></span> <!-- Verde oscuro -->
                            </div>
                        </div>
                    </td>
                    <td style="text-align: right; padding-right: 20px; font-size: 18px; font-weight: bold;">
                        $<?php echo number_format($row['total_pago'], 2); ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>


<br><br>
<div class="product-cards-container">
<h2 class="section-title">Mis Productos</h2>
    <div class="product-cards">
        <?php 
        // Mezclar el resultado y seleccionar solo 5 productos aleatorios
        $productos = $result->fetch_all(MYSQLI_ASSOC);
        shuffle($productos);
        $productos = array_slice($productos, 0, 4);
        foreach ($productos as $row): ?>
        <div class="product-card">
            <div class="product-info">
                <h3 class="product-name"><?php echo htmlspecialchars($row['nombre']); ?></h3>
                <p class="product-price"><?php echo htmlspecialchars(number_format($row['precio'] * 1000, 0, ',', '.')); ?> COP</p>
            </div>
            <div class="product-image">
                <img src="../uploads/<?php echo htmlspecialchars($row['imagen']); ?>" alt="Imagen del Producto">
                <button class="delete-btn" data-id="<?php echo htmlspecialchars($row['id']); ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?');">
                    <i class="fas fa-trash-alt"></i> <!-- Icono de papelera -->
                </button>
            </div>
            <div class="product-actions">
                <button class="details-btn">Ver Detalles</button>
                <button class="edit-btn">Editar</button>
            </div>
            <div class="product-details">
                <p class="stock">Stock: <?php echo htmlspecialchars($row['stock']); ?></p>
                <p class="sales">Ventas: <?php echo htmlspecialchars($row['ventas']); ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>


<div class="action-buttons">
<a href="mercado" class="sell-button">
    <i class="fas fa-recycle"></i> <!-- Icono de reciclaje -->
    Vende lo que no usas
</a>

    <button class="upload-button">
        <i class="fas fa-upload"></i> <!-- Icono de subir -->
        Subir producto
    </button>
</div>
<br>

    <!-- Asegúrate de incluir Font Awesome para los iconos -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>


