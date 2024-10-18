<?php include 'procesos/detalle.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto - Fruit Fest</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="css/producto_detalle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="imagenes/product.png" type="image/x-icon">
</head>
<body>
<header>
<?php include 'Estructura/header.php'; ?>
</header>

<main class="container mt-5">
<div class="product-detail">
    <div class="product-image">
        <img src="uploads/<?php echo htmlspecialchars($imagen_producto); ?>" alt="<?php echo htmlspecialchars($nombre_producto); ?>">
    </div>
    <div class="product-info">
        <h1><?php echo htmlspecialchars($nombre_producto); ?></h1>
        <p><strong>Vendedor:</strong> <?php echo htmlspecialchars($vendedor_nombre); ?></p>
<!-- Información del vendedor -->
<div class="seller-info">
            <!-- Foto del vendedor -->
        <img src="uploads/<?php echo htmlspecialchars($vendedor_imagen); ?>" alt="<?php echo htmlspecialchars($vendedor_nombre); ?>">
        <!-- Enlaces de redes sociales -->
        <div class="social-links">
            <?php if (!empty($facebook)): ?>
                <a href="<?php echo htmlspecialchars($facebook); ?>" target="_blank" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
            <?php endif; ?>
            <?php if (!empty($twitter)): ?>
                <a href="<?php echo htmlspecialchars($twitter); ?>" target="_blank" title="Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
            <?php endif; ?>
            <?php if (!empty($instagram)): ?>
                <a href="<?php echo htmlspecialchars($instagram); ?>" target="_blank" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>

        <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="far fa-star"></i>
        </div>
        <p><?php echo htmlspecialchars($descripcion_producto); ?></p>
        <p class="price">Precio: <?php echo number_format($precio_producto * 1000, 0, ',', '.'); ?> COP <span style="font-size: 0.8em; color: #777;">por kilogramo</span></p>
        <div class="product-actions">
            <button class="add-to-cart" onclick="agregarAlCarrito(<?php echo $producto_id; ?>)"><i class="fas fa-cart-plus"></i> Agregar al Carrito</button>
            <!-- Formulario de pago ahora -->
            <form action="pago.php" method="POST" style="display: inline-block;">
                <input type="hidden" name="id_producto" value="<?php echo $producto_id; ?>">
                <input type="hidden" name="nombre_producto" value="<?php echo htmlspecialchars($nombre_producto); ?>">
                <input type="hidden" name="precio_producto" value="<?php echo htmlspecialchars($precio_producto * 1000); ?>">
                <button type="submit" class="pay-now"><i class="fas fa-credit-card"></i> Pagar Ahora</button>
            </form>
        </div>
    </div>
</div>




    <div class="comment-section">
        <h2>Comentarios</h2>
        <div class="comment-form">
            <textarea id="comment-text" placeholder="Escribe tu comentario..."></textarea>
            <button id="submit-comment">Enviar comentario</button>
        </div>
        <div id="comments-list">
            <?php while ($comentario = $comentarios->fetch_assoc()): ?>
                <div class="comment">
                    <div class="author"><?php echo htmlspecialchars($comentario['nombre']); ?></div>
                    <div class="date"><?php echo htmlspecialchars($comentario['created_at']); ?></div>
                    <div class="text"><?php echo htmlspecialchars($comentario['comentario']); ?></div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>

<br>
<footer>
<?php include 'Estructura/footer.php'; ?>
</footer>

<div id="notification" class="notification">
    <span class="checkmark">&#10003;</span> <span id="notification-message">Producto agregado al carrito</span>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$(document).ready(function() {
    $('#add-to-cart').click(function() {
        var producto_id = $(this).data('id');
        $.post('producto_detalle.php', { action: 'add', id: producto_id }, function(response) {
            var data = JSON.parse(response);
            alert(data.message);
        });
    });

    $('#add-to-wishlist').click(function() {
        var producto_id = $(this).data('id');
        $.post('producto_detalle.php', { action: 'wishlist', id: producto_id }, function(response) {
            var data = JSON.parse(response);
            alert(data.message);
        });
    });

    $('#submit-comment').click(function() {
        var comentario = $('#comment-text').val();
        var producto_id = <?php echo $producto_id; ?>;
        $.post('producto_detalle.php', { action: 'comment', comentario: comentario, id_producto: producto_id }, function(response) {
            var data = JSON.parse(response);
            alert(data.message);
            if (data.status === 'success') {
                $('#comments-list').prepend('<div class="comment"><div class="author">Tú</div><div class="date">Ahora</div><div class="text">' + comentario + '</div></div>');
                $('#comment-text').val('');
            }
        });
    });
});

function mostrarNotificacion(mensaje) {
        var notificacion = $('#notification');
        $('#notification-message').text(mensaje);
        notificacion.addClass('show');
        setTimeout(function() {
            notificacion.removeClass('show');
        }, 3000);
    }

    function agregarAlCarrito(id) {
        $.post('producto_detalle.php', {action: 'add', id: id}, function(response) {
            response = JSON.parse(response);
            if (response.status === 'success') {
                mostrarNotificacion(response.message);
            }
        });
    }
</script>
</body>
</html>
