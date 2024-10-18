<?php
session_start();

// Verificar si el vendedor está logueado
if (!isset($_SESSION['vendedor_id'])) {
    die("Acceso no autorizado.");
}

// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'fruitfest');

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$productId = isset($_POST['productId']) ? intval($_POST['productId']) : 0;
$productName = isset($_POST['productName']) ? $_POST['productName'] : '';
$productPrice = isset($_POST['productPrice']) ? floatval($_POST['productPrice']) : 0.0;
$productDescription = isset($_POST['productDescription']) ? $_POST['productDescription'] : '';
$productStock = isset($_POST['productStock']) ? intval($_POST['productStock']) : 0;
$productCategory = isset($_POST['productCategory']) ? $_POST['productCategory'] : ''; // Nueva categoría
$productFeatured = isset($_POST['productFeatured']) ? intval($_POST['productFeatured']) : 0; // is_featured
$productHighlighted = isset($_POST['productHighlighted']) ? intval($_POST['productHighlighted']) : 0; // destacado

// Manejo de la imagen
$imageName = '';
if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
    $targetDir = '../uploads/';
    $imageName = basename($_FILES['productImage']['name']);
    $targetFilePath = $targetDir . $imageName;
    
    // Mover la imagen a la carpeta de uploads
    if (move_uploaded_file($_FILES['productImage']['tmp_name'], $targetFilePath)) {
        // Imagen cargada correctamente
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al subir la imagen.']);
        exit;
    }
} else {
    // Si no se subió una nueva imagen, mantener la anterior
    $stmt = $conn->prepare("SELECT imagen FROM productos WHERE id = ? AND id_vendedor = ?");
    $stmt->bind_param("ii", $productId, $_SESSION['vendedor_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imageName = $row['imagen']; // Mantener la imagen anterior
    }
}

// Actualizar el producto en la base de datos
$query = $conn->prepare("UPDATE productos SET nombre = ?, precio = ?, descripcion = ?, stock = ?, imagen = ?, categoria = ?, is_featured = ?, destacado = ? WHERE id = ? AND id_vendedor = ?");
$query->bind_param("sdsisiiisi", $productName, $productPrice, $productDescription, $productStock, $imageName, $productCategory, $productFeatured, $productHighlighted, $productId, $_SESSION['vendedor_id']);

if ($query->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Producto actualizado exitosamente.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el producto: ' . $query->error]);
}

$query->close();
$conn->close();
?>
