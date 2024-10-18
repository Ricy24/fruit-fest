<?php include 'procesos/login.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - FRUIT FEST</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="header">
        <a href="index" class="fruit-fest">FRUIT FEST</a>
        <a href="register" class="register">Registrarse</a>
    </div>

    <div class="wrapper">
        <h1>Iniciar Sesión</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-box <?php echo (!empty($email_error)) ? 'error' : ''; ?>">
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email" required>
                <i class="fas fa-envelope"></i>
            </div>
            <div class="input-box <?php echo (!empty($password_error)) ? 'error' : ''; ?>">
                <input type="password" name="password" placeholder="Contraseña" required>
                <i class="fas fa-lock"></i>
            </div>
            <div class="error-message">
                <?php echo $email_error; ?><br>
                <?php echo $password_error; ?><br>
                <?php echo $error_message; ?>
            </div>
            <button type="submit" class="btn">Iniciar Sesión</button>
        </form>
        <div>
            <a href="<?php echo $googleLoginUrl; ?>" class="btn-google">
                <i class="fab fa-google"></i> Iniciar sesión con Google
            </a>
        </div>
        <div class="login-redirect">
            ¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>
        </div>
    </div>
</body>
</html>
