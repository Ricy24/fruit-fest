<?php
// Iniciar sesión
session_start();

// Deshabilitar todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de inicio o a donde desees
header("location: index.php");
exit;
?>
