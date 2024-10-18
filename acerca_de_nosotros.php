<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acerca de Nosotros - Fruit Fest</title>
    <!-- Tipografía personalizada -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="imagenes/contact.png" type="imagenes/favico.ico">
    <link rel="stylesheet" href="css/ac.css">
</head>
<body>
<?php include 'Estructura/header.php'; ?>

    <div class="container">
        <h1 class="main-title">Acerca de Nosotros</h1>
        
        <div class="row">
            <!-- Información y Mapa -->
            <div class="column">
                <h2>Información sobre Fruit Fest</h2>
                <p>Bienvenido a Fruit Fest, tu tienda en línea de frutas frescas y de calidad. Nos dedicamos a ofrecerte la mejor selección de frutas, entregadas directamente a tu puerta. Nuestro objetivo es proporcionar productos frescos, saludables y deliciosos para ti y tu familia.</p>
                <p><strong>Dirección:</strong> Kr 71-58-50 sur</p>
                <div id="map" class="map-container"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1015462.397027454!2d-76.856782921875!3d6.177586499999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3f9f3893558a21%3A0xe6c847ca9e09b5e3!2sColegio%20Angela%20Restrepo%20Moreno!5e0!3m2!1ses!2sco!4v1726351293491!5m2!1ses!2sco" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
            </div>

            <!-- Formulario de Contacto -->
            <div class="column contact-form">
                <h2> Contacto</h2>
                <form id="contactForm" action="procesos/procesar_contacto.php" method="post">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="tipo">Tipo de mensaje:</label>
                        <select class="form-control" id="tipo" name="tipo" required>
                            <option value="duda">Duda</option>
                            <option value="sugerencia">Sugerencia</option>
                            <option value="queja">Queja</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mensaje">Mensaje:</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required></textarea>
                    </div>
                    <button type="submit">Enviar</button>
                </form>
            </div>
        </div>
    </div>
<br><br>
<?php include 'Estructura/footer.php'; ?>

    <!-- Scripts al final del body -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="js/ac.js"></script>
</body>
</html>
