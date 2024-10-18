<?php
// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'fruitfest');

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $sql = "SELECT id FROM vendedores WHERE token=? AND verificado=0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $sql_update = "UPDATE vendedores SET verificado=1, token='' WHERE token=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("s", $token);
        $stmt_update->execute();
        echo "Correo verificado. Ahora puedes iniciar sesion.";
        header("Location: login.php?email=" . urlencode($email));
        exit();
    } else {
        echo "Token inválido o la cuenta ya ha sido verificada.";
    }
} else {
    echo "No se proporcionó un token.";
}

$conn->close();
?>
