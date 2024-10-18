<?php
session_start();

// Incluir el archivo de configuración de la base de datos
include 'config/database.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id'];
$success_message = $error_message = "";

// Procesar actualización de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_data'])) {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];

        $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
        if ($stmt === false) {
            $error_message = "Error al preparar la consulta: " . $conn->error;
        } else {
            $stmt->bind_param("ssi", $nombre, $email, $user_id);
            if ($stmt->execute()) {
                $_SESSION['nombre'] = $nombre;
                $success_message = "Datos actualizados exitosamente.";
            } else {
                $error_message = "Error al actualizar los datos: " . $stmt->error;
            }
            $stmt->close();
        }
    } elseif (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $repeat_new_password = $_POST['repeat_new_password'];

        $stmt = $conn->prepare("SELECT password FROM usuarios WHERE id = ?");
        if ($stmt === false) {
            $error_message = "Error al preparar la consulta: " . $conn->error;
        } else {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            $stmt->close();

            if (password_verify($current_password, $hashed_password)) {
                if ($new_password === $repeat_new_password) {
                    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
                    if ($stmt === false) {
                        $error_message = "Error al preparar la consulta: " . $conn->error;
                    } else {
                        $stmt->bind_param("si", $new_hashed_password, $user_id);
                        if ($stmt->execute()) {
                            $success_message = "Contraseña cambiada exitosamente.";
                        } else {
                            $error_message = "Error al cambiar la contraseña: " . $stmt->error;
                        }
                        $stmt->close();
                    }
                } else {
                    $error_message = "Las contraseñas no coinciden.";
                }
            } else {
                $error_message = "Contraseña actual incorrecta.";
            }
        }
    }
}

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
if ($stmt === false) {
    $error_message = "Error al preparar la consulta: " . $conn->error;
} else {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($nombre, $email);
    $stmt->fetch();
    $stmt->close();
}

$conn->close();
?>