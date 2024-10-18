<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'fruitfest');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$message = ''; // Variable para el mensaje

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    // Verificar el token y si no ha expirado
    $sql = "SELECT id FROM vendedores WHERE token = ? AND token_expiry > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vendedor = $result->fetch_assoc();

        // Actualizar la contraseña y limpiar el token
        $sql = "UPDATE vendedores SET password = ?, token = NULL, token_expiry = NULL WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_password, $vendedor['id']);
        $stmt->execute();

        $message = 'Tu contraseña ha sido actualizada exitosamente. Serás redirigido a la página de inicio de sesión.';
        $redirect = true;
    } else {
        $message = 'El enlace de restablecimiento de contraseña es inválido o ha expirado.';
        $redirect = false;
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
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="css/login.css">
    <style>
        .notification {
            width: 80%;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            color: #fff;
            font-size: 1.2em;
            background-color: #4CAF50; /* Verde para éxito */
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .notification.error {
            background-color: #f44336; /* Rojo para error */
        }
        .notification p {
            margin: 0;
        }
        .redirect {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($message)): ?>
            <div class="notification <?php echo $redirect ? '' : 'error'; ?>">
                <p><?php echo htmlspecialchars($message); ?></p>
                <?php if ($redirect): ?>
                    <p>Redirigiendo...</p>
                    <script>
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 3000); // Redirigir después de 3 segundos
                    </script>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
