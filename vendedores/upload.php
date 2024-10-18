<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['vendedor_id'])) {
    header("Location: login.php");
    exit;
}

$current_user_id = $_SESSION['vendedor_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
    $foto = $_FILES['foto'];
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';

    if ($foto['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $foto['tmp_name'];
        $name = basename($foto['name']);
        $upload_dir = '../uploads/';
        $upload_file = $upload_dir . $name;

        // Verifica si el archivo es una imagen
        $file_type = mime_content_type($tmp_name);
        if (strpos($file_type, 'image') === false) {
            echo "El archivo subido no es una imagen.";
            exit;
        }

        if (move_uploaded_file($tmp_name, $upload_file)) {
            $sql = "INSERT INTO fotos (id_vendedor, url, descripcion) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $current_user_id, $name, $descripcion);
            if ($stmt->execute()) {
                header("Location: perfil.php");
                exit;
            } else {
                echo "Error al subir la foto.";
            }
            $stmt->close();
        } else {
            echo "Error al mover el archivo subido.";
        }
    } else {
        echo "Error en la carga del archivo.";
    }
}

$conn->close();
?>
