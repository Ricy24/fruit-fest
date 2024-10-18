<?php
session_start();

// Verificar si se recibió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'];

    if (!empty($address)) {
        // Guardar la dirección en la sesión
        $_SESSION['direccion'] = $address;
        
        // Redirigir a la página principal
        header('Location: index.php');
        exit();
    } else {
        echo "Por favor, completa todos los campos.";
    }
}
?>
