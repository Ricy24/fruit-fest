<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'fruitfest');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $verification_code = $_POST['verification_code'];
    $email = $_SESSION['email'];

    $sql = "SELECT * FROM vendedores WHERE email = ? AND verification_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $verification_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $update_sql = "UPDATE vendedores SET verification_code = NULL WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $email);
        $update_stmt->execute();
        $update_stmt->close();

        $_SESSION['vendedor_id'] = $result->fetch_assoc()['id'];
        header("Location: index");
        exit();
    } else {
        $error_message = "Código de verificación incorrecto.";
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
    <title>Verificación de Código</title>
    <link rel="stylesheet" href="css/loginn.css">
</head>
<body>
<div class="container">
    <div class="form-section">
        <h1 class="main-title">Verificación de Código</h1>

        <?php if (isset($error_message)): ?>
            <div class="message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Código de Verificación:</label>
                <input type="text" name="verification_code" required>
            </div>
            <button type="submit" class="btn-submit">Verificar Código</button>
        </form>
    </div>
</div>
</body>
</html>
