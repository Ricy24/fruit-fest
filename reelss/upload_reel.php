<?php
// Iniciar sesión
session_start();

// Conectar a la base de datos
$host = "localhost"; 
$username = "root";  
$password = "";      
$database = "fruitfest"; 

$conn = mysqli_connect($host, $username, $password, $database);

// Verificar si la conexión fue exitosa
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verificar si el vendedor está logueado
if (!isset($_SESSION['vendedor_id'])) {
    header("Location: vendedores/login.php");
    exit;
}

// Obtener el ID del vendedor
$vendedor_id = $_SESSION['vendedor_id'];

// Verifica si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descripcion = $_POST['descripcion'];

    // Procesar el video subido
    $video_name = $_FILES['video']['name'];
    $target_dir = "uploads/"; // Ruta relativa a 'uploads/'
    $target_file = $target_dir . basename($video_name);

    // Verificar si el directorio existe, si no, crearlo
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Crear el directorio si no existe
    }

    // Mover el archivo subido al servidor
    if (move_uploaded_file($_FILES['video']['tmp_name'], $target_file)) {
        // Insertar los datos en la base de datos
        $sql = "INSERT INTO reels (video_url, descripcion, id_vendedor) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $video_name, $descripcion, $vendedor_id);
        
        if ($stmt->execute()) {
            echo "Video subido exitosamente.";
        } else {
            echo "Error al guardar los datos en la base de datos: " . mysqli_error($conn);
        }
        
        // Cerrar el statement
        $stmt->close();
    } else {
        echo "Error al subir el video.";
    }
} else {
    echo "Método de solicitud no válido.";
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
