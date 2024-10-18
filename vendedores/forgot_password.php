<?php
session_start();
require '../vendor/autoload.php'; // Asegúrate de que la ruta es correcta

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli('localhost', 'root', '', 'fruitfest');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $sql = "SELECT id FROM vendedores WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vendedor = $result->fetch_assoc();
        $token = bin2hex(random_bytes(16));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // El token expira en 1 hora

        // Guardar el token y la fecha de expiración en la base de datos
        $sql = "UPDATE vendedores SET token = ?, token_expiry = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $token, $expiry, $vendedor['id']);
        $stmt->execute();

        // Enviar correo electrónico con el enlace de restablecimiento
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'fruitfestautomatico@outlook.com';
            $mail->Password = 'REMOVED_SMTP_PASSWORD';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('fruitfestautomatico@outlook.com', 'Fruit Fest');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Restablece tu contraseña';
            $mail->Body    = '<h2>Restablecimiento de Contraseña</h2>
                              <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace para crear una nueva contraseña:</p>
                              <p><a href="http://localhost/prueba/vendedores/reset_password.php?token=' . $token . '">Restablecer mi contraseña</a></p>
                              <p>Este enlace expirará en 1 hora.</p>';

            $mail->send();
            echo 'Se ha enviado un enlace para restablecer tu contraseña a tu correo electrónico.';
        } catch (Exception $e) {
            echo "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}";
        }
    } else {
        $error_message = "No se encontró ninguna cuenta con ese correo electrónico.";
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
    <title>Olvidé mi Contraseña</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container">
        <h2>Recuperar Contraseña</h2>
        <?php if (isset($error_message)): ?>
            <div class="message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <button type="submit" class="btn-submit">Enviar Enlace de Restablecimiento</button>
        </form>
    </div>
</body>
</html>
