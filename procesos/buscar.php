<?php
// Iniciar sesión
session_start();

// Incluir el header
include 'Estructura/header.php'; 

// Mostrar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'fruitfest');

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el usuario está autenticado
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

// Verificar si se ha recibido una consulta
if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Sanear la entrada del usuario para evitar inyección SQL
    $query = $conn->real_escape_string($query);

    // Registrar la búsqueda en el historial si el usuario está autenticado
    if ($id_usuario) {
        $insert_search = "INSERT INTO search_history (id_usuario, search_query) VALUES ('$id_usuario', '$query')";
        $conn->query($insert_search);
    }

    // Registrar o actualizar la búsqueda popular
    $popular_search_query = "INSERT INTO popular_searches (search_query) VALUES ('$query')
                             ON DUPLICATE KEY UPDATE search_count = search_count + 1";
    $conn->query($popular_search_query);

    // Ejecutar la consulta de búsqueda
    $sql = "SELECT * FROM productos WHERE nombre LIKE '%$query%' OR descripcion LIKE '%$query%'";
    $result = $conn->query($sql);

    // Verificar si se obtuvieron resultados
    if ($result && $result->num_rows > 0) {
        echo "<h1 class='result-title'>Resultados de Búsqueda para '$query'</h1>";
        echo "<div class='owl-carousel'>";
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['id'];
            $image = 'uploads/' . $row['imagen'];
            $name = $row['nombre'];
            $description = $row['descripcion'];
            $price = number_format($row['precio'], 3, '.', '.'); // Formatear el precio
            
            echo "<div class='product-card' onclick=\"location.href='producto_detalle.php?id=$product_id'\">";
            echo "<img src='$image' alt='$name' class='product-image'>";
            echo "<div class='product-info'>";
            echo "<h2>$name</h2>";
            echo "<p>$description</p>";
            echo "<p class='price'>Precio: $$price</p>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        // Mostrar sugerencias si no se encuentran resultados
        echo "<p class='no-results'>No se encontraron resultados para '$query'</p>";
        echo "<h3>Sugerencias de productos:</h3>";
        $suggestion_sql = "SELECT * FROM productos ORDER BY RAND() LIMIT 4"; // Sugerir 4 productos aleatorios
        $suggestion_result = $conn->query($suggestion_sql);

        if ($suggestion_result && $suggestion_result->num_rows > 0) {
            echo "<div class='owl-carousel'>";
            while ($row = $suggestion_result->fetch_assoc()) {
                $product_id = $row['id'];
                $image = 'uploads/' . $row['imagen'];
                $name = $row['nombre'];
                $description = $row['descripcion'];
                $price = number_format($row['precio'], 3, '.', '.'); // Formatear el precio
                
                echo "<div class='product-card' onclick=\"location.href='producto_detalle.php?id=$product_id'\">";
                echo "<img src='$image' alt='$name' class='product-image'>";
                echo "<div class='product-info'>";
                echo "<h2>$name</h2>";
                echo "<p>$description</p>";
                echo "<p class='price'>Precio: $$price</p>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>No hay sugerencias disponibles en este momento.</p>";
        }
    }
} else {
    echo "<p class='no-query'>No se proporcionó ninguna consulta de búsqueda.</p>";
}

// Obtener búsquedas populares
$popular_searches_sql = "SELECT search_query FROM popular_searches ORDER BY search_count DESC LIMIT 5";
$popular_searches_result = $conn->query($popular_searches_sql);

// Cerrar la conexión a la base de datos
$conn->close();

// Incluir el footer

?>