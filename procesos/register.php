<?php
require_once dirname(__DIR__) . '/secrets_loader.php';
require_once 'vendor/autoload.php';
require_once 'Database.php'; // Asegúrate de tener este archivo que contiene la clase Database

session_start();

$client = new Google_Client();
$client->setClientId(sf_secret('google_client_id'));
$client->setClientSecret(sf_secret('google_client_secret'));
$client->setRedirectUri('http://localhost/prueba/google-callback.php');
$client->addScope('email');
$client->addScope('profile');

$googleLoginUrl = $client->createAuthUrl();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Registro normal
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash de la contraseña

    $database = new Database();
    $db = $database->getConnection();

    // Verificar si el usuario ya existe
    $stmt = $db->prepare("SELECT id FROM usuarios WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "El correo electrónico ya está registrado.";
    } else {
        // Insertar nuevo usuario
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)");
        $stmt->bindParam(":nombre", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        if ($stmt->execute()) {
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $db->lastInsertId();
            $_SESSION["nombre"] = $name;
            header('Location: index.php');
            exit;
        } else {
            echo "Error al registrar el usuario.";
        }
    }
}
?>