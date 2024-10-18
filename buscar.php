<?php include 'procesos/buscar.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda - Fruit Fest</title>
    <!-- Incluir CSS de Owl Carousel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/buscar.css">
</head>
<body>

    <!-- Sección de búsquedas populares -->
    <?php if ($popular_searches_result && $popular_searches_result->num_rows > 0): ?>
        <div class="popular-searches">
            <h3>Lo más buscado:</h3>
            <ul>
                <?php while ($row = $popular_searches_result->fetch_assoc()): ?>
                    <li><a href="buscar.php?query=<?php echo urlencode($row['search_query']); ?>"><?php echo htmlspecialchars($row['search_query']); ?></a></li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Incluir JS de Owl Carousel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
    $(document).ready(function(){
        $(".owl-carousel").owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            responsive:{
                0:{ items: 1 },
                600:{ items: 2 },
                1000:{ items: 4 }
            }
        });
    });
    </script>
</body>
</html>
