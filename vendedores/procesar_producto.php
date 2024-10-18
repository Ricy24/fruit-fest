<?php
session_start();

include '../config/database.php'; // Ajusta la ruta según tu estructura de carpetas

// Verificar si el vendedor está logueado
if (!isset($_SESSION['vendedor_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Inicia sesión para continuar.']);
    exit;
}

// Recoger datos del formulario y sanear entrada
$nombre = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$descripcion = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
$precio = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 
$categoria = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
$stock = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);
$id_vendedor = $_SESSION['vendedor_id'];

// Verificar si el producto ya existe
$sql_check = "SELECT * FROM productos WHERE nombre = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $nombre);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'El producto que intentas subir ya existe en nuestra base de datos. Por favor, intenta con un nombre diferente o sube un producto distinto. ¡Gracias por tu comprensión!']);
    exit;
}

// Procesar la imagen
$imagen = $_FILES['image']['name'];
$imagen_temp = $_FILES['image']['tmp_name'];
$imagen_size = $_FILES['image']['size'];
$imagen_ext = strtolower(pathinfo($imagen, PATHINFO_EXTENSION));
$ruta_imagen = '../uploads/' . basename($imagen); 

// Extensiones permitidas
$extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

// Validar extensión de archivo
if (!in_array($imagen_ext, $extensiones_permitidas)) {
    echo json_encode(['status' => 'error', 'message' => 'Solo se permiten imágenes con extensiones: jpg, jpeg, png, gif.']);
    exit;
}

// Verificar si la imagen se subió correctamente
if (move_uploaded_file($imagen_temp, $ruta_imagen)) {
    // Preparar y ejecutar la consulta SQL
    $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen, categoria, stock, id_vendedor)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssdssii", $nombre, $descripcion, $precio, $ruta_imagen, $categoria, $stock, $id_vendedor);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al subir el producto.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de la consulta.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al subir la imagen.']);
}

$conn->close();
?>
