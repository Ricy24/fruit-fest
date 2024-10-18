<?php
session_start(); // Si necesitas manejar sesiones

// Incluir el archivo de configuración o cualquier otro archivo necesario
// include 'config.php';
// include 'header.php'; // Incluye tu encabezado si es necesario
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad | Fruit Fest</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Ajusta el enlace al archivo CSS según sea necesario -->
    <link rel="stylesheet" href="../css/privacy-policy.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="imagenes/favicon.ico" type="imagenes/favico.ico">
</head>
<body>
    
    <!-- Elementos de fondo de frutas -->
    <div class="fruit" style="background-image: url('images/apple.png'); left: 10%; animation-duration: 10s;"></div>
    <div class="fruit" style="background-image: url('images/banana.png'); left: 30%; animation-duration: 12s;"></div>
    <div class="fruit" style="background-image: url('images/cherry.png'); left: 50%; animation-duration: 14s;"></div>
    <div class="fruit" style="background-image: url('images/orange.png'); left: 70%; animation-duration: 16s;"></div>
 
    <header>
         <!-- Logo -->
    <a id="logo" href="index.php">FRUIT FEST</a>

<!-- Botón de volver a la tienda -->
<a id="back-button" href="../index">
    <i class="fa fa-arrow-left"></i> Volver a la tienda
</a>
    </header>

    <div class="container mt-5">
        <h1 class="mb-4">Política de Privacidad</h1>
        <p><strong>Última actualización:</strong></p>
        
        <h2>1. Introducción</h2>
        <p>En Fruit Fest, valoramos tu privacidad y nos comprometemos a proteger tu información personal. Esta Política de Privacidad explica cómo recopilamos, usamos, almacenamos y protegemos tus datos cuando visitas nuestro sitio web y utilizas nuestros servicios.</p>

        <h2>2. Información que Recopilamos</h2>
        <p>Recopilamos los siguientes tipos de información:</p>
        <ul>
            <li><strong>Información Personal:</strong> Incluye tu nombre, dirección de correo electrónico, número de teléfono, dirección de envío, y cualquier otra información que proporciones al crear una cuenta o realizar una compra.</li>
            <li><strong>Información de Pago:</strong> Incluye detalles sobre métodos de pago como tarjetas de crédito/débito, pero no almacenamos los números de tarjeta de crédito en nuestro sistema.</li>
            <li><strong>Información de Navegación:</strong> Incluye tu dirección IP, el tipo de navegador, el sistema operativo, y las páginas que visitas en nuestro sitio.</li>
            <li><strong>Información de Cookies:</strong> Utilizamos cookies para mejorar tu experiencia en nuestro sitio web. Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo y ayudan a recordar tus preferencias y actividades.</li>
        </ul>

        <h2>3. Cómo Usamos Tu Información</h2>
        <p>Utilizamos la información que recopilamos para:</p>
        <ul>
            <li>Procesar y gestionar tus pedidos y pagos.</li>
            <li>Mejorar nuestro sitio web y nuestros servicios.</li>
            <li>Enviarte actualizaciones sobre tu pedido, ofertas especiales y otras comunicaciones relevantes, siempre que hayas dado tu consentimiento.</li>
            <li>Personalizar tu experiencia en nuestro sitio.</li>
            <li>Cumplir con nuestras obligaciones legales y resolver cualquier disputa.</li>
        </ul>

        <h2>4. Cómo Compartimos Tu Información</h2>
        <p>No vendemos, alquilamos ni compartimos tu información personal con terceros, excepto en las siguientes circunstancias:</p>
        <ul>
            <li><strong>Con proveedores de servicios:</strong> Podemos compartir tu información con proveedores de servicios de terceros que nos ayuden a operar nuestro sitio web, procesar pagos y enviar pedidos.</li>
            <li><strong>Por razones legales:</strong> Podemos divulgar tu información si es requerido por la ley o en respuesta a solicitudes legales válidas.</li>
        </ul>

        <h2>5. Seguridad de Tu Información</h2>
        <p>Implementamos medidas de seguridad adecuadas para proteger tu información personal contra el acceso no autorizado, la divulgación, la alteración o la destrucción. Sin embargo, ninguna transmisión de datos a través de Internet es completamente segura, y no podemos garantizar la seguridad absoluta de tus datos.</p>

        <h2>6. Tus Derechos</h2>
        <p>Tienes los siguientes derechos con respecto a tu información personal:</p>
        <ul>
            <li><strong>Acceso:</strong> Puedes solicitar una copia de la información personal que tenemos sobre ti.</li>
            <li><strong>Corrección:</strong> Puedes solicitar la corrección de información incorrecta o incompleta.</li>
            <li><strong>Eliminación:</strong> Puedes solicitar la eliminación de tu información personal, sujeto a ciertas excepciones legales.</li>
            <li><strong>Oposición:</strong> Puedes oponerte al procesamiento de tu información personal para fines de marketing directo.</li>
        </ul>
        <p>Para ejercer estos derechos, contáctanos a través de la información de contacto proporcionada a continuación.</p>

        <h2>7. Cambios en la Política de Privacidad</h2>
        <p>Podemos actualizar esta Política de Privacidad de vez en cuando para reflejar cambios en nuestras prácticas o por razones legales. Te notificaremos sobre cualquier cambio importante publicando la nueva política en nuestro sitio web. Te recomendamos revisar periódicamente esta política para estar informado sobre cómo protegemos tu información.</p>

        <h2>8. Contacto</h2>
        <p>Si tienes alguna pregunta sobre esta Política de Privacidad o sobre cómo manejamos tu información, por favor contáctanos en:</p>
        <p><strong>Fruit Fest</strong><br>
           [Chapinero]<br>
           [fruitfestinfo@gmail.com]<br>
           [3208927624]</p>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Fruit Fest. Todos los derechos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
