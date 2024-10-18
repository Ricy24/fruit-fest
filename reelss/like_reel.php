<?php
// Conectar a la base de datos
$host = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "fruitfest"; 

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verifica si se recibió un ID de reel
if (isset($_POST['id'])) {
    $reel_id = $_POST['id'];
    
    // Incrementar los "me gusta" en la base de datos
    $sql = "UPDATE reels SET me_gustas = me_gustas + 1 WHERE id = $reel_id";
    if (mysqli_query($conn, $sql)) {
        // Obtener la nueva cantidad de "me gusta"
        $result = mysqli_query($conn, "SELECT me_gustas FROM reels WHERE id = $reel_id");
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['likes' => $row['me_gustas']]);
    } else {
        echo json_encode(['error' => 'Error al actualizar']);
    }
} else {
    echo json_encode(['error' => 'ID no proporcionado']);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
