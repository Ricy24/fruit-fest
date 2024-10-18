<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/header.css">
    <title>Panel del Vendedor</title>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <h2>Fruit Fest</h2>
        </div>
        <div class="nav-menu">
            <a href="index"><i class="fas fa-th-large"></i> Panel</a>
            <a href="producto"><i class="fas fa-box"></i> Productos</a>
            <a href="pedidos"><i class="fas fa-receipt"></i> Pedidos</a>
            <a href="mercado"><i class="fas fa-store-alt"></i> Mercado</a>
            <a href="tips"><i class="fas fa-graduation-cap"></i> Información</a>
        </div>
        <div class="logout">
            <a href="logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>
    </div>
    
    <div class="header-horizontal">
        <div class="header-title mobile-hidden" style="color: #2E7D32; font-size: 24px; font-weight: bold;">
            Dashboard del Vendedor
        </div>
        <div class="header-icons">
            <i class="fas fa-bell"></i>
            <a href="configuracion.php" style="text-decoration: none; color: inherit;">
                <i class="fas fa-user"></i>
            </a>
        </div>
        
        <!-- Icono de menú -->
        <div class="menu-icon" id="menuToggle">
            <i class="fas fa-bars"></i>
        </div>
    </div>

    <script>
        const sidebar = document.querySelector('.sidebar');
        const menuToggle = document.getElementById('menuToggle');

        menuToggle.addEventListener('click', () => {
            // Alternar la clase para mostrar/ocultar el menú lateral
            if (sidebar.style.transform === 'translateX(0%)') {
                sidebar.style.transform = 'translateX(-100%)'; // Ocultar el menú
            } else {
                sidebar.style.transform = 'translateX(0%)'; // Mostrar el menú
            }
        });
    </script>
</body>
</html>
