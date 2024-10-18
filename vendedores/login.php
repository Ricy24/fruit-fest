<?php
session_start();
require '../vendor/autoload.php'; // Asegúrate de que la ruta sea correcta

use Twilio\Rest\Client;

$conn = new mysqli('localhost', 'root', '', 'fruitfest');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar intentos fallidos
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}

// Redirigir si se alcanzaron 6 intentos fallidos
if ($_SESSION['failed_attempts'] >= 6) {
    header("Location: forgot_pin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM vendedores WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vendedor = $result->fetch_assoc();

        // Verificar si el correo está verificado
        if ($vendedor['verificado'] == 0) {
            $error_message = "Por favor, verifica tu correo electrónico antes de iniciar sesión.";
        } else {
            // Verificar la contraseña
            if (password_verify($password, $vendedor['password'])) {
                // Generar un código de verificación
                $verification_code = bin2hex(random_bytes(3)); // Código de 6 caracteres

                // Guardar el código de verificación en la base de datos
                $update_sql = "UPDATE vendedores SET verification_code = ? WHERE email = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ss", $verification_code, $email);
                $update_stmt->execute();
                $update_stmt->close();

                // Enviar el código de verificación por SMS usando Twilio
                $account_sid = 'ACb9d606d19ed34995080e6f73547336a0';
                $auth_token = '94393b03c5fa5cc8b618ecf557c01dfa';
                $twilio_phone_number = '+1 803 443 0805'; // Número de teléfono de Twilio

                $client = new Client($account_sid, $auth_token);

                try {
                    $client->messages->create(
                        $vendedor['telefono'], // Número de teléfono del vendedor
                        [
                            'from' => $twilio_phone_number, // Tu número de Twilio
                            'body' => 'Tu código de verificación es: ' . $verification_code . '. Este código es válido solo por unos minutos.'
                        ]
                    );

                    $_SESSION['email'] = $email; // Guarda el email en sesión para verificar el código
                    echo '<div class="message info">Se ha enviado un código de verificación a tu teléfono. Verifícalo para completar el inicio de sesión.</div>';
                    echo '<script>setTimeout(function() { window.location.href = "verify_code.php"; }, 3000);</script>';
                } catch (Exception $e) {
                    echo "<div class='message error'>El mensaje no pudo ser enviado. Error: {$e->getMessage()}</div>";
                }

                // Resetear intentos fallidos
                $_SESSION['failed_attempts'] = 0;
            } else {
                $_SESSION['failed_attempts']++;
                $error_message = "Contraseña incorrecta.";
            }
        }
    } else {
        $_SESSION['failed_attempts']++;
        $error_message = "El vendedor no existe.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="css/loginn.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
</head>
<body>
<div class="container">
    <div class="image-section">
        <!-- Swiper -->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><img src="img/campesinos.jpg" alt="Imagen 1"></div>
                <div class="swiper-slide"><img src="img/campesino.jpg" alt="Imagen 2"></div>
                <div class="swiper-slide"><img src="img/campesino3.jpg" alt="Imagen 3"></div>
                <!-- Agrega más imágenes según sea necesario -->
            </div>
        </div>
    </div>
    <div class="form-section">
        <h1 class="main-title">FRUIT FEST</h1>
        <h2 class="subtitle">Vende con nosotros</h2>

        <?php if (isset($error_message)): ?>
            <div class="message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-submit">Iniciar Sesión</button>
        </form>
        <div class="login-link">
            ¿No tienes cuenta? <a href="register">Regístrate aquí</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script>
    // Inicializar Swiper
    const swiper = new Swiper('.swiper-container', {
        loop: true, 
        autoplay: {
            delay: 3000, 
        },
        effect: 'fade', 
    });
</script>
</body>
</html>
