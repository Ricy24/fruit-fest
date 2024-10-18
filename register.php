<?php include 'procesos/register.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro en FRUIT FEST</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/registe.css">

</head>
<body>
    <div class="container">
        <h1>Registro en FRUIT FEST</h1>

        <!-- Formulario de registro normal -->
        <form action="" method="POST">
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required><br>
            <button type="submit" name="register">Registrarse</button>
        </form>

        <!-- Enlace para registro con Google -->
        <div class="google-login">
            <a href="<?php echo $googleLoginUrl; ?>">
                <i class="fab fa-google"></i>
                Iniciar sesión con Google
            </a>
        </div>

        <!-- Mensaje para usuarios con cuenta existente -->
        <div class="login-redirect">
            ¿Ya tienes una cuenta? <a href="login">Inicia sesión aquí</a>.
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>

