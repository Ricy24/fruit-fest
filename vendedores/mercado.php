<?php
// Conexión a la base de datos (modifica con tus credenciales)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fruitfest"; // Cambia esto por el nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejo de la búsqueda y filtros
$search = isset($_POST['search']) ? $_POST['search'] : '';
$category_filter = isset($_POST['category_filter']) ? $_POST['category_filter'] : '';
$type_filter = isset($_POST['type_filter']) ? $_POST['type_filter'] : '';

// Consulta para obtener los productos de la tabla campesinos
$sql = "SELECT id, nombre, descripcion, precio, categoria, tipo, imagen, id_vendedor FROM campesinos WHERE nombre LIKE '%$search%'";

if ($category_filter) {
    $sql .= " AND categoria = '$category_filter'";
}
if ($type_filter) {
    $sql .= " AND tipo = '$type_filter'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/mercado.css"> <!-- Asegúrate de que el CSS esté correctamente vinculado -->
    <title>Mercado</title>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="main-content">
        <div class="container">
            <h1>Productos del Mercado Campesino</h1>

            <!-- Filtros y búsqueda -->
            <div class="filters">
                <form method="POST" action="">
                    <input type="text" name="search" placeholder="Buscar producto..." value="<?php echo htmlspecialchars($search); ?>">
                    <select name="category_filter">
                        <option value="">Seleccionar categoría</option>
                        <option value="frutas" <?php echo ($category_filter === 'frutas') ? 'selected' : ''; ?>>Frutas</option>
                        <option value="verduras" <?php echo ($category_filter === 'verduras') ? 'selected' : ''; ?>>Verduras</option>
                        <option value="otros" <?php echo ($category_filter === 'otros') ? 'selected' : ''; ?>>Otros</option>
                    </select>
                    <select name="type_filter">
                        <option value="">Seleccionar tipo</option>
                        <option value="ecológico" <?php echo ($type_filter === 'ecológico') ? 'selected' : ''; ?>>Ecológico</option>
                        <option value="convencional" <?php echo ($type_filter === 'convencional') ? 'selected' : ''; ?>>Convencional</option>
                    </select>
                    <input type="submit" value="Filtrar">
                    <!-- Botón para abrir el modal -->
<button type="button" class="btn btn-dark" id="openModalButton">
    Publicar Anuncio
  </button>
                </form>
            </div>

          

<div class="product-container">
    <?php
    if ($result->num_rows > 0) {
        // Mostrar los productos
        while ($row = $result->fetch_assoc()) {
            // Obtener la información del vendedor con id_vendedor
            $id_vendedor = $row['id_vendedor'];
            $sql_vendedor = "SELECT nombre, ciudad, departamento, telefono, foto FROM vendedores WHERE id = $id_vendedor";
            $result_vendedor = $conn->query($sql_vendedor);
            $vendedor = $result_vendedor->fetch_assoc();

            echo "<div class='product'>";
            echo "<img src='" . htmlspecialchars($row["imagen"]) . "' alt='" . htmlspecialchars($row["nombre"]) . "'>";
            echo "<div class='product-content'>";
            echo "<h2>" . htmlspecialchars($row["nombre"]) . "</h2>";
            echo "<button class='product-type'>" . htmlspecialchars($row["tipo"]) . "</button>";
            echo "<p class='descripcion'>" . htmlspecialchars($row["descripcion"]) . "</p>";
            echo "<div class='price-category'>";
            echo "<p class='precio'>Precio: $" . number_format($row["precio"], 2) . "</p>";
            echo "<p class='categoria'>" . htmlspecialchars($row["categoria"]) . "</p>";
            echo "</div>"; // Fin price-category

            // Mostrar la información del vendedor
            echo "<div class='vendedor-info'>";
            echo "<p class='ubicacion'><i class='fas fa-map-marker-alt'></i> " . htmlspecialchars($vendedor['ciudad']) . ", " . htmlspecialchars($vendedor['departamento']) . "</p>";
            echo "<div class='vendedor-contacto'>";
            echo "<img class='vendedor-foto' src='../uploads/" . htmlspecialchars($vendedor['foto']) . "' alt='Foto del vendedor'>";
            echo "<p class='vendedor-nombre'>" . htmlspecialchars($vendedor['nombre']) . "</p>";
            echo "</div>";
            echo "<div class='vendedor-botones'>";

            // Botones con íconos e integración de WhatsApp
            echo "<a href='tel:" . htmlspecialchars($vendedor['telefono']) . "' class='boton-llamar'>";
            echo "<i class='fas fa-phone'></i> Llamar";
            echo "</a>";

            // Crear enlace de WhatsApp con el número y nombre del producto
            $mensajeWhatsApp = "Hola, estoy interesado en el producto: " . urlencode($row["nombre"]);
            $whatsappUrl = "https://api.whatsapp.com/send?phone=" . htmlspecialchars($vendedor['telefono']) . "&text=" . $mensajeWhatsApp;

            echo "<a href='" . $whatsappUrl . "' target='_blank' class='boton-mensaje'>";
            echo "<i class='fas fa-comment-dots'></i> Mensaje";
            echo "</a>";

            echo "</div>";
            echo "</div>"; // Fin vendedor-info

            echo "</div>"; // Fin product-content
            echo "</div>"; // Fin product
        }
    } else {
        echo "<p>No hay productos disponibles.</p>";
    }

    // Cerrar la conexión
    $conn->close();
    ?>
</div>






  <!-- Modal para añadir producto -->
  <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header" style="background-color: black; color: white;">
          <h5 class="modal-title" id="addProductModalLabel">Añadir Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addProductForm" method="POST" action="add_product.php" enctype="multipart/form-data">
            <!-- Nombre del producto -->
            <div class="mb-3">
              <label for="nombreProducto" class="form-label">Nombre del Producto</label>
              <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" required>
            </div>
            <!-- Descripción del producto -->
            <div class="mb-3">
              <label for="descripcionProducto" class="form-label">Descripción</label>
              <textarea class="form-control" id="descripcionProducto" name="descripcionProducto" rows="3" required></textarea>
            </div>
            <!-- Precio -->
            <div class="mb-3">
              <label for="precioProducto" class="form-label">Precio</label>
              <input type="number" class="form-control" id="precioProducto" name="precioProducto" step="0.01" required>
            </div>
            <!-- Categoría -->
            <div class="mb-3">
              <label for="categoriaProducto" class="form-label">Categoría</label>
              <input type="text" class="form-control" id="categoriaProducto" name="categoriaProducto" required>
            </div>
            <!-- Tipo -->
            <div class="mb-3">
              <label for="tipoProducto" class="form-label">Tipo</label>
              <input type="text" class="form-control" id="tipoProducto" name="tipoProducto" required>
            </div>
            <!-- Imagen -->
            <div class="mb-3">
              <label for="imagenProducto" class="form-label">Imagen</label>
              <input type="file" class="form-control" id="imagenProducto" name="imagenProducto" accept="image/*" required>
            </div>
            <!-- ID del Vendedor (oculto) -->
            <input type="hidden" name="id_vendedor" value="<?php echo $_SESSION['vendedor_id']; ?>">

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-dark">Añadir Producto</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

    <!-- Asegúrate de incluir Font Awesome para los iconos -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
      // Obtener referencia al botón de abrir modal
      var openModalButton = document.getElementById('openModalButton');
      var addProductModal = new bootstrap.Modal(document.getElementById('addProductModal'));

      // Mostrar modal al hacer clic en el botón
      openModalButton.addEventListener('click', function() {
        addProductModal.show();
      });


    });
  </script>