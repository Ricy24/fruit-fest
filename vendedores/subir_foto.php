<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['vendedor_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
    $vendedor_id = $_SESSION['vendedor_id'];
    $descripcion = $_POST['descripcion'];
    $foto = $_FILES['foto'];

    $foto_nombre = basename($foto['name']);
    $foto_path = '../uploads/' . $foto_nombre;

    if (move_uploaded_file($foto['tmp_name'], $foto_path)) {
        $sql_insert = "INSERT INTO fotos (id_vendedor, url, descripcion) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iss", $vendedor_id, $foto_nombre, $descripcion);
        $stmt_insert->execute();
        $stmt_insert->close();
        echo "Foto subida con éxito.";
    } else {
        echo "Error al subir la foto.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Foto</title>
    <style>
        /* Agrega aquí estilos para el formulario de subir fotos */
    </style>
</head>
<body>
    <form action="subir_foto.php" method="post" enctype="multipart/form-data">
        <input type="file" name="foto" required>
        <textarea name="descripcion" placeholder="Descripción de la foto"></textarea>
        <button type="submit">Subir Foto</button>
    </form>
</body>
</html>
