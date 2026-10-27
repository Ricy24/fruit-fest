<?php
require_once dirname(__DIR__) . '/secrets_loader.php';
require '../vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli('localhost', 'root', '', 'fruitfest'); // Conexión DB

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario aquí
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $departamento = $_POST['departamento'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $token = bin2hex(random_bytes(16));
    $verification_code = bin2hex(random_bytes(3));

    // Redes sociales opcionales
    $facebook = isset($_POST['facebook']) ? $_POST['facebook'] : null;
    $instagram = isset($_POST['instagram']) ? $_POST['instagram'] : null;
    $twitter = isset($_POST['twitter']) ? $_POST['twitter'] : null;

    // Verificar si se ha subido una foto de perfil
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = basename($_FILES['foto']['name']); // Obtener nombre de archivo
        $target_dir = "../uploads/";
        $target_file = $target_dir . $foto;

        // Comprobar si la carpeta 'uploads' existe, si no, crearla
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Mover el archivo a la carpeta 'uploads'
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            echo "Archivo subido correctamente.";
        } else {
            echo "<div class='message error'>Error al subir la imagen.</div>";
            exit; // Detener el script si falla la subida
        }
    } else {
        echo "<div class='message error'>Es obligatorio subir una foto de perfil.</div>";
        exit; // Salir si no se sube una imagen
    }

    // Inserción en la base de datos
    $sql = "INSERT INTO vendedores (nombre, email, password, telefono, direccion, ciudad, departamento, cedula, token, verificado, ip_address, verification_code, facebook, instagram, twitter, foto) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssssssssss", $nombre, $email, $password, $telefono, $direccion, $ciudad, $departamento, $cedula, $token, $ip_address, $verification_code, $facebook, $instagram, $twitter, $foto);
        if ($stmt->execute()) {
            // Envío del correo de verificación
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.office365.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'fruitfestautomatico@outlook.com';
                $mail->Password = sf_secret('smtp.fruitfestautomatico@outlook.com');
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('fruitfestautomatico@outlook.com', 'Fruit Fest');
                $mail->addAddress($email, $nombre);
                $mail->isHTML(true);
                $mail->Subject = 'Verifica tu correo - Fruit Fest';
                $mail->Body    = "<h2>Gracias por registrarte en Fruit Fest</h2>
                                  <p>Por favor, verifica tu correo:</p>
                                  <p><a href='http://localhost/prueba/vendedores/verify.php?token={$token}'>Verificar mi correo</a></p>
                                  <p>Tu código de verificación es: {$verification_code}</p>";
                $mail->send();
                echo "<div class='message success'>Registro exitoso. Verifica tu correo.</div>";
                echo "<script>setTimeout(function() { window.location.href = 'login.php'; }, 3000);</script>";
            } catch (Exception $e) {
                echo "<div class='message error'>El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}</div>";
            }
        } else {
            echo "<div class='message error'>Error en la consulta: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='message error'>Error en la preparación de la consulta: " . $conn->error . "</div>";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Paso a Paso - Fruit Fest</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/registerr.css">
</head>

<body>

    <div class="container">
        <div class="image-side">
            <img src="../imagenes/registro-fondo.jpg" alt="Registro Fondo">
        </div>
        <div class="form-side">
            <div class="form-container">
                <h2>Regístrate</h2>

                <form id="multiStepForm" enctype="multipart/form-data" method="POST">
                    <!-- Paso 1 -->
                    <div class="step active-step">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" required>

                        <label for="cedula">Cédula</label>
                        <input type="text" id="cedula" name="cedula" required>

                        <button type="button" class="next-btn">Siguiente</button>
                    </div>

                    <!-- Paso 2 -->
                    <div class="step">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" required>

                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required>

                        <button type="button" class="prev-btn">Anterior</button>
                        <button type="button" class="next-btn">Siguiente</button>
                    </div>

                    <!-- Paso 3 -->
                    <div class="step">
                        <label for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" required>

                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" required>

                        <label for="departamento">Departamento</label>




<select id="departamento" name="departamento" required>
    <option value="">Selecciona un departamento</option>
    <?php
    $sql = "SELECT id, nombre FROM departamentos";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
    }
    ?>
</select>
<br>
<label for="ciudad">Ciudad</label>
<select id="ciudad" name="ciudad" required>
<script>
document.getElementById('departamento').addEventListener('change', function() {
    const departamentoId = this.value;
    
    // Limpiar el select de ciudades
    const ciudadSelect = document.getElementById('ciudad');
    ciudadSelect.innerHTML = '<option value="">Selecciona una ciudad</option>';

    if (departamentoId) {
        fetch(`get_ciudades.php?departamento_id=${departamentoId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(ciudad => {
                    const option = document.createElement('option');
                    option.value = ciudad.id;
                    option.textContent = ciudad.nombre;
                    ciudadSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    }
});
</script>

</select>


<br>
                        <button type="button" class="prev-btn">Anterior</button>
                        <button type="button" class="next-btn">Siguiente</button>
                    </div>

                    <!-- Paso 4 -->
                    <div class="step">
                        <label for="foto">Foto de Perfil (obligatoria)</label>
                        <input type="file" id="foto" name="foto" accept="image/*" required>

                        <label for="facebook">Facebook </label>
<input type="text" id="facebook" name="facebook" placeholder="https://www.facebook.com/Tu perfil (Opcional)">

<label for="instagram">Instagram </label>
<input type="text" id="instagram" name="instagram" placeholder="https://www.instagram.com/Tu perfil (Opcional)">


                        <button type="button" class="prev-btn">Anterior</button>
                        <button type="submit" class="next-btn">Registrar</button>
                    </div>
                </form>

                <div class="login-link">
                    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
                </div>
            </div>
        </div>
    </div>





    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const steps = document.querySelectorAll('.step');
            let currentStep = 0;

            const showStep = (stepIndex) => {
                steps.forEach((step, index) => {
                    step.classList.toggle('active-step', index === stepIndex);
                });
            };

            document.querySelectorAll('.next-btn').forEach((button) => {
                button.addEventListener('click', () => {
                    if (currentStep < steps.length - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });

            document.querySelectorAll('.prev-btn').forEach((button) => {
                button.addEventListener('click', () => {
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

            showStep(currentStep);
        });
    </script>


</body>
</html>
