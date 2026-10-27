<?php
require_once dirname(__DIR__) . '/secrets_loader.php';
session_start();
require '../vendor/autoload.php'; // Incluye el autoload de Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli('localhost', 'root', '', 'fruitfest');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Tiempo de espera en minutos
$wait_time_minutes = 20;

// Verificar el número de intentos fallidos y el tiempo de espera
if (isset($_SESSION['failed_attempts'])) {
    $failed_attempts = $_SESSION['failed_attempts'];
    $last_failed_time = isset($_SESSION['last_failed_time']) ? $_SESSION['last_failed_time'] : null;

    if ($failed_attempts >= 6) {
        if ($last_failed_time && (time() - strtotime($last_failed_time)) < ($wait_time_minutes * 60)) {
            $error_message = "Has superado el número máximo de intentos. Inténtalo nuevamente en " . ($wait_time_minutes - ((time() - strtotime($last_failed_time)) / 60)) . " minutos.";
        } else {
            // Restablecer intentos después del tiempo de espera
            $_SESSION['failed_attempts'] = 0;
            $_SESSION['last_failed_time'] = null;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($error_message)) {
    $email = $_POST['email'];

    // Verificar si el email existe en la base de datos
    $sql = "SELECT * FROM vendedores WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generar un nuevo código de verificación temporal
        $new_code = bin2hex(random_bytes(3)); // Código de 6 caracteres

        // Actualizar el código de verificación en la base de datos
        $update_sql = "UPDATE vendedores SET verification_code = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        if ($update_stmt === false) {
            die("Error en la preparación de la consulta de actualización: " . $conn->error);
        }

        $update_stmt->bind_param("ss", $new_code, $email);
        $update_stmt->execute();

        // Enviar el nuevo código de verificación al correo electrónico
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'fruitfestautomatico@outlook.com';
            $mail->Password = sf_secret('smtp.fruitfestautomatico@outlook.com');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('fruitfestautomatico@outlook.com', 'Fruit Fest');
            $mail->addAddress($email, $email); // Cambia por el nombre del vendedor si lo tienes
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de código - Fruit Fest';
            $mail->Body    = '<h2>Recuperación de código</h2>
                              <p>Tu nuevo código de verificación es: ' . $new_code . '</p>
                              <p>Por favor, utiliza este código para completar el inicio de sesión.</p>';

            $mail->send();
            $success_message = "Se ha enviado un nuevo código de verificación a tu correo electrónico.";

            // Restablecer intentos fallidos
            $_SESSION['failed_attempts'] = 0;
            $_SESSION['last_failed_time'] = null;
        } catch (Exception $e) {
            $error_message = "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}";
        }
    } else {
        // Incrementar el número de intentos fallidos
        $_SESSION['failed_attempts'] = isset($_SESSION['failed_attempts']) ? $_SESSION['failed_attempts'] + 1 : 1;
        $_SESSION['last_failed_time'] = date('Y-m-d H:i:s');
        $error_message = "No se encontró una cuenta asociada con este correo electrónico.";
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
    <title>Recuperar Código</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="container">
    <div class="form-section">
        <h1 class="main-title">Recuperar Código</h1>

        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <button type="submit" class="btn-submit">Recuperar Código</button>
        </form>

        <div class="login-link">
            ¿Ya recuerdas tu código? <a href="login.php">Inicia sesión aquí</a>
        </div>
    </div>
</div>

</body>
</html>
