<?php
$host = 'localhost'; // o tu servidor de base de datos
$db = 'fruitfest'; // nombre de tu base de datos
$user = 'root'; // tu usuario de base de datos
$pass = ''; // tu contraseña de base de datos

// Crear conexión
$connection = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($connection->connect_error) {
    die('Error de conexión: ' . $connection->connect_error);
}
?>
