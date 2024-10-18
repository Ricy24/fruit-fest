<?php include 'procesos/index.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruit Fest</title>
    <link rel="stylesheet" href="css/incio1.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico:wght@400&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/81581fb069.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="icon" href="imagenes/favicon.ico" type="image/x-icon">
</head>

    <header>
    <?php include 'Estructura/header.php'; ?>
    </header>
<body>
      <!-- pantalla de carga -->
<div class="loader">
        <div class="background-animation"></div>
        <div class="fruit-icon">
            <i class="fas fa-apple-alt"></i>
        </div>
        <h1>FRUIT FEST</h1>
        <p>Estamos preparando algo delicioso para ti...</p>
        <div class="spinner"></div>
    </div>

  <!-- espacio entre el encabezado y el carrousel -->
<br>
      <!-- inicio carrusel -->
    <div class="container">
    <div class="promo-container">
        <div class="promo-text">
            <h1>¡Bienvenido a Fruit Fest marketplace!</h1>
            <br>
            <p>Compra frutas frescas o únete como vendedor en nuestro marketplace. ¡Vende y gana!</p>
            <a href="productos" class="buy-now-button">Comprar ahora</a>
            <a href="vendedores/" class="buy-now-button">Trabaja con nosotros</a>
        </div>
        <div class="promo-image swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="imagenes/promociones.jpg" alt="Promoción 1">
                </div>
                <div class="swiper-slide">
                    <img src="imagenes/novedades.jpg" alt="Promoción 2">
                </div>
                <div class="swiper-slide">
                    <img src="imagenes/novedadess.jpg" alt="Promoción 3">
                </div>
                <!-- Agrega más slides aquí si es necesario -->
            </div>
            <!-- Botones de navegación -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <!-- Puntos de navegación -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</div>

  <!-- targetas de iconos -->

<div class="card-container">
    <div class="card">
        <div class="card-icon"><i class="fas fa-cart-shopping"></i></div>
        <div class="card-text">Compra frutas frescas y locales</div>
    </div>
    <div class="card">
        <div class="card-icon"><i class="fas fa-seedling"></i></div>
        <div class="card-text">Vende tus productos fácilmente</div>
    </div>
    <div class="card">
        <div class="card-icon"><i class="fas fa-truck"></i></div>
        <div class="card-text">Envíos rápidos y seguros</div>
    </div>
</div>

  <!-- Categorias -->
        <div class="category-container">
            <h2 class="category-title">Categorías</h2>
            <div class="category-cards">
                <a href="productos?categoria=Verduras" class="category-card">
                    <div class="category-image">
                    </div>
                    <div class="category-text">Verduras Tropicales</div>
                    <div class="category-subtext">¡Las más exóticas!</div>
                </a>
                <a href="productos?categoria=Frutas" class="category-card">
                    <div class="category-image">
                    </div>
                    <div class="category-text">Frutas Orgánicas</div>
                    <div class="category-subtext">¡Orgánicas y saludables!</div>
                </a>
            </div>
        </div>


        <div class="product-cards-container">
    <h2>Productos Disponibles</h2>
    <div class="product-cards">
        <?php if (!empty($productosNuevos)): ?>
            <?php foreach ($productosNuevos as $producto): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="uploads/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <p class="product-price">$<?php echo htmlspecialchars($producto['precio'] * 1000.); ?></p> <!-- Multiplicamos el precio por 5,000 -->
                    </div>
                    <a href="producto_detalle.php?id=<?php echo $producto['id']; ?>" class="buy-button">Ver Detalle</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos para mostrar.</p>
        <?php endif; ?>
    </div>
</div>



<!-- HISTORIAL PRODUCTOS-->

        <div class="promotion-container">
            <div class="promotion-text">
                <h1>¡Promoción Especial!</h1>
                <p>¡Descuentos increíbles en nuestra selección de frutas!</p>
                <a href="productos" class="promotion-button">Ver promociones</a>
            </div>
        </div>

        <div class="slider">
            <div class="slides">
                <div class="slide">
                    <img src="imagenes/slide1.jpg" alt="Slide 1">
                    <div class="caption">
                        <h2>Frutas Frescas</h2>
                        <p>Las mejores frutas para tu salud</p>
                        <a href="productos" class="btn">Comprar ahora</a>
                    </div>
                </div>
                <div class="slide">
                    <img src="imagenes/slide2.jpg" alt="Slide 2">
                    <div class="caption">
                        <h2>Ofertas Especiales</h2>
                        <p>Descubre nuestras ofertas</p>
                        <a href="productos" class="btn">Ver ofertas</a>
                    </div>
                </div>
                <div class="slide">
                    <img src="imagenes/slide3.jpg" alt="Slide 3">
                    <div class="caption">
                        <h2>Compra en Línea</h2>
                        <p>Compra desde la comodidad de tu hogar</p>
                        <a href="promociones.php" class="btn">Compra ahora</a>
                    </div>
                </div>
            </div>
        </div>
    </div>




     <!-- HISTORIAL pedidos-->
    <div class="product-cards-container">
    <h2>Historial de Pedidos</h2>
    <div class="product-cards">
        <?php if (!empty($historialPedidos)): ?>
            <?php foreach ($historialPedidos as $producto): ?>
                <div class="product-card">
                    <div class="product-image">
                    <img src="uploads/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <p class="product-price">$<?php echo htmlspecialchars($producto['precio']); ?></p>
                    </div>
                    <a href="producto_detalle.php?id=<?php echo $producto['id']; ?>" class="buy-button">Volver a comprar</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No tienes historial de pedidos.</p>
        <?php endif; ?>
    </div>
</div>



          <!-- HISTORIAL PRODUCTOS-->
<br><br><br>
<div class="seccion-historial">
    <h2>Historial de productos</h2>
    <div class="historial">
        <?php if (isset($_SESSION['id'])): ?>
            <?php if (!empty($historialPedidos)): ?>
                <div id="historial-slider" class="slider">
                    <?php foreach ($historialPedidos as $pedido): ?>
                        <div class="tarjeta">
                            <img src="uploads/<?php echo htmlspecialchars($pedido['imagen']); ?>" alt="<?php echo htmlspecialchars($pedido['nombre']); ?>">
                            <div class="contenido">
                                <h3><?php echo htmlspecialchars($pedido['nombre']); ?></h3>
                                <?php
                                // Obtener el precio como un valor flotante
                                $precio = floatval($pedido['precio']);
                                
                                // Multiplicar el precio por 1,000
                                $precio_multiplicado = $precio * 1000;
                                
                                // Formatear el precio con puntos como separador de miles y comas como separador decimal
                                $precio_formateado = number_format($precio_multiplicado, 0, ',', '.');
                                ?>
                                <p>Precio: <?php echo "$" . $precio_formateado; ?> COP</p>
                                <p>Fecha: <?php echo htmlspecialchars($pedido['fecha_visto']); ?></p>
                                <a href="producto_detalle?id=<?php echo htmlspecialchars($pedido['id']); ?>" class="boton-detalle">Ver Detalle</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No tienes historial de productos.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Inicia sesión para ver tu historial de productos.</p>
            <a href="login" class="button">Iniciar sesión</a>
        <?php endif; ?>
    </div>
</div>




<br><br>
          <!-- pie de paguina-->
    <footer>
      <?php include 'Estructura/footer.php'; ?>
    </footer>

    <script>
    $(document).ready(function(){
        $('#historial-slider').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true,
            dots: true,
            arrows: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
</script>




        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
        <script src="js/index.js"></script>
</body>
</html>

