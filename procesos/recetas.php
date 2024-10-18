<?php
session_start();
require_once 'config/database.php';

// Palabras prohibidas (en minúsculas para comparación)
$palabras_prohibidas = ['maldición', 'grosería', 'insulto', 'pene', 'vagina', 'verga', 'malparido', 'hijueputa', 'gonorrea'];

// Convertir todas las palabras prohibidas a minúsculas
$palabras_prohibidas = array_map('strtolower', $palabras_prohibidas);

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_receta'])) {
    // Verificar si las claves existen en $_POST y limpiar datos
    $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
    $ingredientes = isset($_POST['ingredientes']) ? $_POST['ingredientes'] : '';
    $preparacion = isset($_POST['preparacion']) ? $_POST['preparacion'] : '';
    $imagen_url = isset($_POST['imagen_url']) ? $_POST['imagen_url'] : '';
    $dificultad = isset($_POST['dificultad']) ? $_POST['dificultad'] : '';
    $cocinada_por = isset($_POST['cocinada_por']) ? $_POST['cocinada_por'] : '';
    $porciones = isset($_POST['porciones']) ? intval($_POST['porciones']) : 0;
    $tiempo = isset($_POST['tiempo']) ? $_POST['tiempo'] : '';

    // Convertir el texto a minúsculas para la validación
    $texto_completo = strtolower($titulo . ' ' . $ingredientes . ' ' . $preparacion);

    // Verificar palabras prohibidas
    foreach ($palabras_prohibidas as $palabra) {
        if (strpos($texto_completo, $palabra) !== false) {
            echo "<p>El contenido contiene palabras inapropiadas. Por favor, corrige el texto.</p>";
            exit;
        }
    }

    // Insertar datos en la base de datos
    $sql = "INSERT INTO recetas (titulo, ingredientes, preparacion, imagen_url, dificultad, cocinada_por, porciones, tiempo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssssss", $titulo, $ingredientes, $preparacion, $imagen_url, $dificultad, $cocinada_por, $porciones, $tiempo);

        if ($stmt->execute()) {
            echo "<p>Receta agregada exitosamente.</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Error en la preparación de la consulta.</p>";
    }
}

// Verificar si hay recetas para mostrar
$sql = "SELECT * FROM recetas";
$result = $conn->query($sql);

if ($result === false) {
    echo "<p>Error en la consulta SQL: " . $conn->error . "</p>";
}
?>
