<?php
// Iniciar sesión
session_start();

// Verificar si el vendedor está logueado
if (!isset($_SESSION['vendedor_id'])) {
    header("Location: login.php");
    exit;
}

// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'fruitfest');

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$vendedor_id = $_SESSION['vendedor_id'];

// Obtener la información del vendedor
$vendedor_query = $conn->prepare("SELECT nombre, email, telefono, direccion, ciudad, foto, facebook, twitter, instagram FROM vendedores WHERE id = ?");
if ($vendedor_query === false) {
    die("Error en la consulta: " . $conn->error);
}
$vendedor_query->bind_param("i", $vendedor_id);
$vendedor_query->execute();
$vendedor_result = $vendedor_query->get_result();
$vendedor_data = $vendedor_result->fetch_assoc();
$vendedor_query->close();

// Procesar el formulario de actualización de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad']; // Agregar ciudad
    $facebook = $_POST['facebook'];
    $twitter = $_POST['twitter'];
    $instagram = $_POST['instagram'];

    // Manejo de archivo de imagen
    $foto = $vendedor_data['foto']; // Mantener la foto actual si no se sube una nueva

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        // Validar el archivo
        $foto = basename($_FILES['foto']['name']);
        $target_dir = "../uploads/";
        $target_file = $target_dir . $foto;

        // Mover el archivo subido
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $error_message = "Error al subir la imagen.";
        }
    }

    // Actualizar la información del vendedor
    $update_query = $conn->prepare("UPDATE vendedores SET nombre = ?, email = ?, telefono = ?, direccion = ?, ciudad = ?, foto = ?, facebook = ?, twitter = ?, instagram = ? WHERE id = ?");
    if ($update_query === false) {
        die("Error en la consulta de actualización: " . $conn->error);
    }
    $update_query->bind_param("sssssssssi", $nombre, $email, $telefono, $direccion, $ciudad, $foto, $facebook, $twitter, $instagram, $vendedor_id);

    if ($update_query->execute()) {
        $success_message = "Datos actualizados exitosamente.";
    } else {
        $error_message = "Error al actualizar los datos: " . $update_query->error;
    }
    $update_query->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración del Vendedor</title>
    <link rel="stylesheet" href="css/configuracion.css">

</head>
<body>

<div class="header">
    <div class="profile">
        <?php if (!empty($vendedor_data['foto'])): ?>
            <img src="../uploads/<?php echo htmlspecialchars($vendedor_data['foto']); ?>" alt="Foto de Perfil">
        <?php else: ?>
            <img src="../uploads/default.jpg" alt="Foto de Perfil">
        <?php endif; ?>
        <h2 class="nombre">¡Hola, <?php echo htmlspecialchars($vendedor_data['nombre']); ?>! Configura tu cuenta</h2>
    </div>
    <div class="header-nav">
        <a href="index" class="nav-link">Panel</a>
        <a href="logout" class="nav-link">Cerrar Sesión</a>
    </div>
</div>
<br>
<div class="header">
    <h2 class="nombre">¡Hola, <?php echo htmlspecialchars($vendedor_data['nombre']); ?>! Configura tus datos.</h2>
</div>
<div class="container">
    <h1>Configuración de Cuenta</h1>

    <?php if (isset($success_message)): ?>
        <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <div class="profile-pic-container">
        <?php if (!empty($vendedor_data['foto'])): ?>
            <img src="../uploads/<?php echo htmlspecialchars($vendedor_data['foto']); ?>" alt="Foto de Perfil">
        <?php else: ?>
            <img src="../uploads/default.jpg" alt="Foto de Perfil">
        <?php endif; ?>
        <div>
            <h2><?php echo htmlspecialchars($vendedor_data['nombre']); ?></h2>
            <p>Correo: <?php echo htmlspecialchars($vendedor_data['email']); ?></p>
            <p>Teléfono: <?php echo htmlspecialchars($vendedor_data['telefono']); ?></p>
            <p>Dirección: <?php echo htmlspecialchars($vendedor_data['direccion']); ?></p>
            <p>Ciudad: <?php echo htmlspecialchars($vendedor_data['ciudad']); ?></p>
        </div>
    </div>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($vendedor_data['nombre']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($vendedor_data['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($vendedor_data['telefono']); ?>" required>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($vendedor_data['direccion']); ?>" required>
        </div>
        <div class="form-group">
            <label for="ciudad">Ciudad:</label>
            <input type="text" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($vendedor_data['ciudad']); ?>" required>
        </div>
        <div class="form-group">
            <label for="foto">Foto de Perfil:</label>
            <input type="file" id="foto" name="foto">
        </div>
        <div class="form-group">
            <label for="facebook">Facebook:</label>
            <input type="text" id="facebook" name="facebook" value="<?php echo htmlspecialchars($vendedor_data['facebook']); ?>">
        </div>
        <div class="form-group">
            <label for="twitter">Twitter:</label>
            <input type="text" id="twitter" name="twitter" value="<?php echo htmlspecialchars($vendedor_data['twitter']); ?>">
        </div>
        <div class="form-group">
            <label for="instagram">Instagram:</label>
            <input type="text" id="instagram" name="instagram" value="<?php echo htmlspecialchars($vendedor_data['instagram']); ?>">
        </div>
        <div class="form-group">
            <input type="submit" value="Actualizar Datos" class="btn-submit">
        </div>
    </form>
</div>

</body>
</html>
