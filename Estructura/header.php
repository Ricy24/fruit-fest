<?php
// Valor predeterminado
$nombre_usuario = "Invitado";
$direccion_usuario = "";

// Verificar si la dirección está en las cookies
if (isset($_COOKIE['direccion'])) {
    $direccion_usuario = $_COOKIE['direccion'];
}

// Verificar si el usuario está autenticado
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $nombre_usuario = $_SESSION["nombre"];
}

// Contar productos en el carrito
$num_productos_carrito = 0;
if (isset($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $producto) {
        // Verificar si $producto es un array con la clave 'cantidad'
        if (is_array($producto) && isset($producto['cantidad'])) {
            $num_productos_carrito += $producto['cantidad'];
        } else {
            // Si $producto no es un array, se asume que es una cantidad simple
            $num_productos_carrito += $producto;
        }
    }
}

// Contar productos en la lista de deseos
$num_productos_deseos = 0;
$conn = new mysqli('localhost', 'root', '', 'fruitfest');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $stmt = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE id_usuario = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->bind_result($num_productos_deseos);
    $stmt->fetch();
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruit Fest</title>
    <link rel="stylesheet" href="css/header.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@latest/css/boxicons.min.css'>

</head>
<body>
    <header>
        <div class="container-hero">
            <div class="container-logo">
                <h1 class="logo"><a href="index">Fruit Fest</a></h1>
                <div class="separator"></div>
                <img src="imagenes/logo.png" alt="Logo Secundario" class="logo-secondary">
            </div>


            <!-- Barra de búsqueda grande -->
            <form class="search-form" action="buscar" method="GET">
                <input type="search" name="query" placeholder="¿Qué quieres comer hoy? Busca aquí" aria-label="Buscar" required>
                <button class="btn-search" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>



            <div class="container-user">
                <?php if ($nombre_usuario === "Invitado") : ?>
                    <a href="login" class="btn-login">Ingresar</a>
                <?php else : ?>
                    <div class="user-profile">
                        <i class="fas fa-user user-icon"></i>
                        <span class="user-name"><?php echo htmlspecialchars($nombre_usuario); ?></span>
                        <div class="nav-dropdown">
                            <a href="your-data"><i class="fas fa-user-circle"></i> Mi perfil</a>
                            <a href="vendedores/"><i class="fas fa-money-bill-wave"></i> Trabaja con nosotros</a>
                            <a href="historial_pedidos"><i class="fas fa-history"></i> Historial de compras</a>
                            <a href="logout"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="content-shopping-cart">
                    <a href="ver_carrito">
                        <i class="fas fa-cart-plus"></i>
                        <span class="number" id="cartCount">(<?php echo $num_productos_carrito; ?>)</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Navbar con botón 'Trabaja con nosotros' -->
        <div class="container-navbar">
    <nav class="navbar container">
        <ul class="menu">
            <li><a href="index">Inicio</a></li>
            <li class="dropdown">
                <a href="productos" class="dropdown-toggle">Tienda</a>
                <div class="dropdown-menu">
                    <a href="productos?categoria=Frutas" class="category-item">
                        <i class="fa fa-apple category-icon"></i> 
                        <span class="category-text">Frutas</span>
                    </a>
                    <a href="productos?categoria=Verduras" class="category-item">
                        <i class="fa fa-carrot category-icon"></i> <!-- Icono de verdura -->
                        <span class="category-text">Verduras</span>
                    </a>
                </div>
            </li>
            <li><a href="acerca_de_nosotros">Contáctanos</a></li>

            <li>
    <a href="reelss/">
        <img src="estructura/icono.png" alt="Video Recording" style="width: 24px; height: 24px; color: #4CAF50;">
    </a>
</li>



        </ul>
    </nav>
</div>

        
        <div class="address-container" id="openAddressForm">
            <i class="fas fa-map-marker-alt"></i>
            <span id="addressText">
                <?php echo htmlspecialchars($direccion_usuario ? 'Se enviará a ' . $direccion_usuario : 'Pon tu dirección acá'); ?>
            </span>
        </div>

        <div class="address-form" id="addressForm">
            <span class="close" id="closeAddressForm">&times;</span>
            <form id="addressSubmitForm">
                <label for="ciudad">Ciudad:</label>
                <input type="text" id="ciudad" name="ciudad" required>
                
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required>

                <label for="barrio">Barrio:</label>
                <input type="text" id="barrio" name="barrio" required>

                <button type="submit">Guardar dirección</button>
            </form>
        </div>
    </header>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/header.js"></script>
</body>
</html>



<style>

box-icon {
    font-size: 24px; /* Tamaño del ícono */
    color: #ffffff; /* Color del ícono */
    transition: color 0.3s; /* Transición de color */
}

box-icon:hover {
    color: #f39c12; /* Color al pasar el cursor */
}

</style>