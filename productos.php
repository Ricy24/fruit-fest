<?php include 'procesos/productos.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Fruit Fest</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="css/header2.css">
    <link rel="stylesheet" href="css/productos.css">
    <link rel="stylesheet" href="css/producctos2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="imagenes/product.png" type="image/x-icon">
</head>
<body>
<header>
<?php include 'Estructura/header.php'; ?>
</header>


  <!--baner de la imagen de tienda -->
<main class="container mt-5">
    <!-- Banner -->
    <div class="banner">
        <img src="imagenes/sliderss.jpg" alt="Banner Imagen">
        <div class="banner-text">
            <h1>TIENDA</h1>
            <p>Frutas / Verduras</p>
        </div>
    </div>

  <!-- contenedor de recetas, ofertas, mercado -->
 <div class="range-slider">
    <p>Usa nuestro Mercado Listo, ahorra tiempo y deja que nosotros preparemos tu compra.</p>
    <p>Conoce nuestras recetas y ofertas especiales</p>
    <button class="add-to-cart" onclick="agregarMercadoListo()">Agrega tu Mercado Listo <i class="fas fa-shopping-cart"></i></button>
    <button class="styled-special-offers">Ver Ofertas Especiales <i class="fas fa-tag"></i></button>
    <a href="recetas" class="styled-recipes">
    Explorar Recetas <i class="fas fa-utensils"></i>
</a>
</div>

<!-- Filtro de Categoría -->
<div class="filter-form">
        <form method="GET" action="productos">
            <input type="radio" id="categoria_todas" name="categoria" value="" <?php if (!$categoria) echo 'checked'; ?>>
            <label for="categoria_todas">
                <i class="fas fa-th-large"></i> Todas
            </label>
            <input type="radio" id="categoria_frutas" name="categoria" value="Frutas" <?php if ($categoria == 'Frutas') echo 'checked'; ?>>
            <label for="categoria_frutas">
                <i class="fas fa-apple-alt"></i> Frutas
            </label>
            <input type="radio" id="categoria_verduras" name="categoria" value="Verduras" <?php if ($categoria == 'Verduras') echo 'checked'; ?>>
            <label for="categoria_verduras">
                <i class="fas fa-carrot"></i> Verduras
            </label>
            <button type="submit">
                <i class="fas fa-sliders-h"></i> Filtrar
            </button>
        </form>
    </div>
<!-- productos -->
<div class="container-products">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Obtener el precio como un valor flotante
            $precio = floatval($row['precio']);
            
            // Multiplicar el precio por 1,000
            $precio_multiplicado = $precio * 1000;
            
            // Formatear el precio con puntos como separador de miles y comas como separador decimal
            $precio_formateado = number_format($precio_multiplicado, 0, ',', '.');
            
            echo '<div class="card-product" onclick="window.location.href=\'producto_detalle?id=' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '\'">';
            echo '    <div class="container-img">';
            echo '        <img src="uploads/' . htmlspecialchars($row['imagen'], ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') . '" />';
            echo '        <div class="button-group">';
            echo '            <span onclick="event.stopPropagation(); window.location.href=\'producto_detalle?id=' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '\'"><i class="fa-regular fa-eye"></i></span>';
            echo '            <span onclick="event.stopPropagation(); agregarWishlist(' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . ')"><i class="fa-regular fa-heart"></i></span>';
            echo '        </div>';
            echo '    </div>';
            echo '    <div class="content-card-product">';
            echo '        <div class="stars">';
            echo '            <i class="fa-solid fa-star"></i>';
            echo '            <i class="fa-solid fa-star"></i>';
            echo '            <i class="fa-solid fa-star"></i>';
            echo '            <i class="fa-solid fa-star"></i>';
            echo '            <i class="fa-regular fa-star"></i>';
            echo '        </div>';
            echo '        <h3>' . htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') . '</h3>';
            echo '        <p>' . htmlspecialchars($row['descripcion'], ENT_QUOTES, 'UTF-8') . '</p>';
            echo '        <p class="price">$' . $precio_formateado . ' <span style="font-size: 0.8em; color: #777;">COP</span></p>';
            echo '        <button class="styled-add-to-cart" onclick="event.stopPropagation(); agregarAlCarrito(' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . ')"><i class="fas fa-cart-plus"></i> Agregar al carrito</button>';
            echo '    </div>';
            echo '</div>';
        }
    } else {
        echo '<p>No hay productos en esta categoría.</p>';
    }
    ?>
</div>



    <!-- Paginación -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?categoria=<?php echo urlencode($categoria); ?>&page=<?php echo $page - 1; ?>" class="prev">« Anterior</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="?categoria=<?php echo urlencode($categoria); ?>&page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        <?php if ($page < $total_paginas): ?>
            <a href="?categoria=<?php echo urlencode($categoria); ?>&page=<?php echo $page + 1; ?>" class="next">Siguiente »</a>
        <?php endif; ?>
    </div>
</main>



<!-- Notificación -->
<div class="notification" id="notification">
    <span class="checkmark">&#10004;</span>
    <span id="notification-message">Producto agregado al carrito</span>
</div>

  <!-- pie de paguina -->
<footer>
<?php include 'Estructura/footer.php'; ?>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="js/productos.js"></script>
</body>
</html>
  <!-- cierre de sesión -->
<?php
$stmt->close();
?>
