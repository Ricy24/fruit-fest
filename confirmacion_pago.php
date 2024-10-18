<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir el autoload de Composer
require 'vendor/autoload.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Verificar si el ID del pedido está presente en la URL
if (!isset($_GET['id_pedido']) || empty($_GET['id_pedido'])) {
    header("Location: productos.php");
    exit();
}

$id_pedido = intval($_GET['id_pedido']);

// Función para conectar a la base de datos
function conectarDB() {
    $conn = new mysqli('localhost', 'root', '', 'fruitfest');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    return $conn;
}

$conn = conectarDB();

// Obtener los detalles del pedido y el correo electrónico del usuario
$stmt = $conn->prepare("
    SELECT p.nombre, p.identificacion, p.direccion, p.telefono, p.metodo_pago, p.subtotal, p.envio, p.descuento, p.total_pago, p.fecha, u.email
    FROM pedidos p
    JOIN usuarios u ON p.id_usuario = u.id
    WHERE p.id = ?");
if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}
$stmt->bind_param("i", $id_pedido);
$stmt->execute();
$stmt->bind_result($nombre, $identificacion, $direccion, $telefono, $metodo_pago, $subtotal, $envio, $descuento, $total_pago, $fecha, $email);
$stmt->fetch();
$stmt->close();
$conn->close();

// Ajustar el subtotal, descuento y total para multiplicar por 1000
$subtotal *= 1000;
$descuento *= 1000;
$total_pago = ($subtotal - $descuento); // Total ya ajustado para incluir el descuento

// Enviar el correo de confirmación
$mail = new PHPMailer(true);
try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'frutasverdurasfruitfest@outlook.com';
    $mail->Password = 'REMOVED_SMTP_PASSWORD';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Remitente y destinatario
    $mail->setFrom('frutasverdurasfruitfest@outlook.com', 'Fruit Fest');
    $mail->addAddress($email, $nombre); // Usar el correo electrónico obtenido
    $mail->isHTML(true);
    $mail->Subject = 'Confirmación de Pago - Fruit Fest';

    // Contenido del correo
    $mail->Body    = '
    <html>
    <head>
        <meta charset="UTF-8"> <!-- Asegura la codificación en UTF-8 -->
        <style>
            body {
                font-family: Arial, sans-serif;
                color: #333;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }
            .container {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background: #ffffff;
                border: 1px solid #dddddd;
                border-radius: 4px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            .header {
                text-align: center;
                padding: 10px;
                background-color: #4CAF50;
                color: white;
                border-radius: 4px 4px 0 0;
            }
            .content {
                padding: 20px;
            }
            .content h2 {
                color: #333;
            }
            .content p {
                font-size: 16px;
                line-height: 1.5;
            }
            .button {
                display: inline-block;
                padding: 10px 20px;
                margin: 20px 0;
                background-color: #4CAF50;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                font-weight: bold;
                text-align: center;
            }
            .footer {
                text-align: center;
                padding: 10px;
                font-size: 12px;
                color: #888;
                border-top: 1px solid #dddddd;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>Fruit Fest</h1>
            </div>
                   <div class="content">
            <h2>¡Gracias por tu compra, ' . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . '!</h2>
            <p>Tu pedido ha sido procesado con éxito. Aquí están los detalles de tu pedido:</p>
            <ul>
                <li><strong>Nombre:</strong> ' . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . '</li>
                <li><strong>Identificación:</strong> ' . htmlspecialchars($identificacion, ENT_QUOTES, 'UTF-8') . '</li>
                <li><strong>Dirección:</strong> ' . htmlspecialchars($direccion, ENT_QUOTES, 'UTF-8') . '</li>
                <li><strong>Teléfono:</strong> ' . htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8') . '</li>
                <li><strong>Método de Pago:</strong> ' . htmlspecialchars($metodo_pago, ENT_QUOTES, 'UTF-8') . '</li>
                <li><strong>Subtotal:</strong> ' . number_format($subtotal, 0, ',', '.') . ' COP</li>
                <li><strong>Descuento:</strong> ' . number_format($descuento, 0, ',', '.') . ' COP</li>
                <li><strong>Total a Pagar:</strong> ' . number_format($total_pago, 0, ',', '.') . ' COP</li>
                <li><strong>Fecha:</strong> ' . htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8') . '</li>
            </ul>
            <a href="historial_pedidos.php" class="button">Ver Historial de Pedidos</a>
            <a href="index.php" class="button">Volver al inicio</a>
        </div>

            <div class="footer">
                <p>&copy; ' . date('Y') . ' Fruit Fest. Todos los derechos reservados.</p>
                <p>Si tienes alguna pregunta, no dudes en <a href="mailto:support@fruitfest.com">contactarnos</a>.</p>
            </div>
        </div>
    </body>
    </html>
    ';

    $mail->send();
    echo '<div class="message info">Se ha enviado la confirmación de tu pedido a tu correo electrónico.</div>';
} catch (Exception $e) {
    echo "<div class='message error'>El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}</div>";
}
?>
<style>
/* Estilos generales para las notificaciones */
.message {
    padding: 12px 20px;
    margin: 15px 0;
    border-radius: 4px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 15px;
    text-align: center;
    color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Estilo para mensajes de éxito */
.message.info {
    background-color: #4caf50; /* Verde suave para éxito */
    border-left: 5px solid #388e3c; /* Borde izquierdo más oscuro */
}

/* Estilo para mensajes de error */
.message.error {
    background-color: #f44336; /* Rojo suave para error */
    border-left: 5px solid #d32f2f; /* Borde izquierdo más oscuro */
}

/* Estilo opcional para los iconos (si se usan) */
.message::before {
    content: "";
    display: inline-block;
    width: 24px;
    height: 24px;
    margin-right: 10px;
    vertical-align: middle;
    background-size: contain;
}

/* Iconos específicos para cada tipo de mensaje */
.message.info::before {
    background: url('path/to/success-icon.png') no-repeat center center; /* Reemplaza con la URL del icono de éxito */
}

.message.error::before {
    background: url('path/to/error-icon.png') no-repeat center center; /* Reemplaza con la URL del icono de error */
}


</style>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pago</title>
    <link rel="stylesheet" href="css/confirmacion.css"> <!-- Enlace a tu archivo CSS -->
</head>
<body>
    <header>
        <h1>Confirmación de Pago</h1>
    </header>
    <main>
    <div class="confirmacion">
        <h2>¡Gracias por tu compra, <?php echo htmlspecialchars($nombre); ?>!</h2>
        <p>Tu pedido ha sido procesado con éxito. Aquí están los detalles de tu pedido:</p>
        <ul>
            <li><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre); ?></li>
            <li><strong>Identificación:</strong> <?php echo htmlspecialchars($identificacion); ?></li>
            <li><strong>Dirección:</strong> <?php echo htmlspecialchars($direccion); ?></li>
            <li><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono); ?></li>
            <li><strong>Método de Pago:</strong> <?php echo htmlspecialchars($metodo_pago); ?></li>
            <li><strong>Subtotal:</strong> <?php echo number_format($subtotal, 0, ',', '.'); ?> COP</li>
            <li><strong>Descuento:</strong> <?php echo number_format($descuento, 0, ',', '.'); ?> COP</li>
            <li><strong>Total a Pagar:</strong> <?php echo number_format($total_pago, 0, ',', '.'); ?> COP</li>
            <li><strong>Fecha:</strong> <?php echo htmlspecialchars($fecha); ?></li>
        </ul>
        <a href="historial_pedidos.php" class="button">Ver Historial de Pedidos</a>
        <a href="index.php" class="button">Volver al inicio</a>
    </div>
</main>

</body>
</html>
