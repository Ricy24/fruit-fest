<?php
require_once dirname(__DIR__) . '/secrets_loader.php';
session_start();
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Database {
    private $host = "localhost";
    private $db_name = "fruitfest";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// Configuración de Google Client
$client = new Google_Client();
$client->setClientId(sf_secret('google_client_id'));
$client->setClientSecret(sf_secret('google_client_secret'));
$client->setRedirectUri('http://localhost/prueba/google-callback.php');
$client->addScope('email');
$client->addScope('profile');

// Verificar si el usuario ya está autenticado
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if (isset($_SESSION['redirect_url'])) {
        $redirect_url = $_SESSION['redirect_url'];
        unset($_SESSION['redirect_url']);
        header("location: $redirect_url");
        exit;
    } else {
        header("location: index.php");
        exit;
    }
}

$googleLoginUrl = $client->createAuthUrl();

$email = $password = '';
$email_error = $password_error = '';

$error_message = '';
if (isset($_GET['message'])) {
    $error_message = htmlspecialchars($_GET['message']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["email"]))) {
        $email_error = "Por favor ingrese su email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_error = "Por favor ingrese su contraseña.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($email_error) && empty($password_error)) {
        $database = new Database();
        $db = $database->getConnection();
        $stmt = $db->prepare("SELECT id, nombre, email, password FROM usuarios WHERE email = :email");

        $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
        $param_email = $email;

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $id = $row["id"];
                $nombre = $row["nombre"];
                $email = $row["email"];
                $hashed_password = $row["password"];
                if (password_verify($password, $hashed_password)) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["nombre"] = $nombre;

                    // Enviar correo electrónico
                    $mail = new PHPMailer(true);
                    try {
                        // Configuración del servidor
                        $mail->isSMTP();
                        $mail->Host = 'smtp.office365.com';  // Cambia esto por tu servidor SMTP
                        $mail->SMTPAuth = true;
                        $mail->Username = 'frutasverdurasfruitfest@outlook.com';  // Cambia esto por tu email
                        $mail->Password = sf_secret('smtp.frutasverdurasfruitfest@outlook.com');  // Cambia esto por tu contraseña de email
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // Remitente y destinatario
                        $mail->setFrom('frutasverdurasfruitfest@outlook.com', 'Fruit Fest');
                        $mail->addAddress($email);

                        // Contenido del correo
                        $mail->isHTML(true);
                        $mail->Subject = 'Inicio de sesión en Fruit Fest';
                        $mail->Body    = '<p>Hola ' . htmlspecialchars($nombre) . ',</p>
                                          <p>Tu inicio de sesión en Fruit Fest ha sido exitoso.</p>
                                          <p>Ubicación: <a href="https://www.google.com/maps?q=kr+71-58.50+sur" target="_blank">Nuestra Ubicación</a></p>
                                          <p>Gracias por usar nuestros servicios.</p>';
                        $mail->AltBody = 'Hola ' . htmlspecialchars($nombre) . ', Tu inicio de sesión en Fruit Fest ha sido exitoso. Ubicación: Nuestra Ubicación (https://www.google.com/maps?q=kr+71-58.50+sur). Gracias por usar nuestros servicios.';

                        $mail->send();
                    } catch (Exception $e) {
                        echo "No se pudo enviar el correo electrónico. Mailer Error: {$mail->ErrorInfo}";
                    }

                    if (isset($_SESSION['redirect_url'])) {
                        $redirect_url = $_SESSION['redirect_url'];
                        unset($_SESSION['redirect_url']);
                        header("location: $redirect_url");
                    } else {
                        header("location: index.php");
                    }
                    exit;
                } else {
                    $password_error = "La contraseña que ha ingresado no es válida.";
                }
            } else {
                $email_error = "No se encontró ninguna cuenta con ese email.";
            }
        } else {
            echo "Oops! Algo salió mal. Por favor inténtelo de nuevo más tarde.";
        }
        unset($stmt);
        unset($db);
    }
}
?>
