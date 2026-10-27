<?php
require_once __DIR__ . '/secrets_loader.php';
session_start();
require_once 'vendor/autoload.php';
require_once 'Database.php'; // Incluye el archivo de la clase Database

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$client = new Google_Client();
$client->setClientId(sf_secret('google_client_id'));
$client->setClientSecret(sf_secret('google_client_secret'));
$client->setRedirectUri('http://localhost/prueba/google-callback.php');
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    $google_service = new Google_Service_Oauth2($client);
    $data = $google_service->userinfo->get();

    $email = $data['email'];
    $name = $data['name'];

    $database = new Database();
    $db = $database->getConnection();

    // Verificar si el usuario ya existe en la base de datos
    $stmt = $db->prepare("SELECT id, nombre FROM usuarios WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION["loggedin"] = true;
        $_SESSION["id"] = $row["id"];
        $_SESSION["nombre"] = $row["nombre"];
    } else {
        // Si no existe, insertar nuevo usuario
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)");
        $stmt->bindParam(":nombre", $name);
        $stmt->bindParam(":email", $email);
        if ($stmt->execute()) {
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $db->lastInsertId();
            $_SESSION["nombre"] = $name;

            // Enviar correo electrónico
            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor
                $mail->isSMTP();                                    // Configurar el uso de SMTP
                $mail->Host       = 'smtp.office365.com';           // Servidor SMTP de Outlook
                $mail->SMTPAuth   = true;                           // Habilitar autenticación SMTP
                $mail->Username   = 'fruitfestautomatico@outlook.com'; // SMTP username (tu correo de Outlook)
                $mail->Password   = sf_secret('smtp.fruitfestautomatico@outlook.com');        // SMTP password (tu contraseña de Outlook)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Habilitar cifrado TLS
                $mail->Port       = 587;                            // Puerto TCP para TLS

                // Destinatarios
                $mail->setFrom('fruitfestautomatico@outlook.com', 'FRUIT FEST'); // Cambia el correo remitente
                $mail->addAddress($email, $name); // Agregar destinatario

               // Contenido
               $mail->isHTML(true);                                  // Establecer el formato del correo a HTML
               $mail->Subject = '¡Bienvenido a FRUIT FEST!';
               $mail->Body    = '
               <html>
               <head>
                   <style>
                       body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
                       .container { width: 90%; margin: 0 auto; background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
                       .header { background: #28a745; color: #ffffff; padding: 15px; border-radius: 8px 8px 0 0; text-align: center; }
                       .header h1 { margin: 0; font-size: 24px; }
                       .content { padding: 20px; }
                       .content p { font-size: 16px; color: #333333; }
                       .footer { text-align: center; padding: 15px 0; background: #28a745; color: #ffffff; border-radius: 0 0 8px 8px; font-size: 14px; }
                       .footer a { color: #ffffff; text-decoration: none; }
                   </style>
               </head>
               <body>
                   <div class="container">
                       <div class="header">
                           <h1>¡Bienvenido a FRUIT FEST!</h1>
                       </div>
                       <div class="content">
                           <p>Hola ' . htmlspecialchars($name) . ',</p>
                           <p>¡Gracias por registrarte en FRUIT FEST! Estamos encantados de tenerte con nosotros. Disfruta explorando nuestra amplia selección de frutas y verduras frescas.</p>
                           <p>Si tienes alguna pregunta o necesitas asistencia, no dudes en contactarnos.</p>
                           <p>Saludos cordiales,<br>El equipo de FRUIT FEST</p>
                       </div>
                       <div class="footer">
                           &copy; ' . date('Y') . ' FRUIT FEST. Todos los derechos reservados.
                           <br><a href="http://localhost/prueba">Visita nuestro sitio web</a>
                       </div>
                   </div>
               </body>
               </html>';
               $mail->AltBody = 'Hola ' . htmlspecialchars($name) . ',\n\nGracias por registrarte en FRUIT FEST! Estamos encantados de tenerte con nosotros. Disfruta explorando nuestra amplia selección de frutas y verduras frescas.\n\nSaludos cordiales,\nEl equipo de FRUIT FEST';

                $mail->send();
            } catch (Exception $e) {
                echo "Error al enviar el correo: {$mail->ErrorInfo}";
            }
        }
    }

    header('Location: index.php');
    exit;
} else {
    header('Location: login.php');
    exit;
}
?>
