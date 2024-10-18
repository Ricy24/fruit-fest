<?php
session_start(); // Asegúrate de iniciar la sesión

// Verificar si se recibieron datos del formulario
if (isset($_POST['ciudad']) && isset($_POST['direccion']) && isset($_POST['barrio'])) {
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $barrio = $_POST['barrio'];

    // Guardar la dirección en una cookie
    setcookie('direccion', "$ciudad, $direccion, $barrio", time() + (30 * 24 * 60 * 60), "/");

    // Enviar respuesta JSON
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
