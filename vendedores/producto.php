<?php include 'procesos/producto.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/productoss.css"> <!-- Asegúrate de que el CSS esté correctamente vinculado -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">

    <title>Panel de productos</title>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="main-content">
        <div class="tabs">
            <button class="tab active" onclick="openTab(event, 'products')">Gestión de Productos</button>
            <button class="tab" onclick="openTab(event, 'analytics')">Análisis de Ventas</button>
        </div>

        <div id="products" class="tab-content active">
            <div class="grid">
                <!-- Product Management Section -->
                <div class="product-list">
                    <div class="search-bar">
                        <form method="POST" action="">
                            <input type="text" name="search" placeholder="Busca tus productos..." class="search-input" value="<?php echo htmlspecialchars($search_term); ?>" id="search-input">
                        </form>
                    </div>

                    <!-- Mostrar productos -->
                    <?php if (!empty($productos)): ?>
                        <?php foreach ($productos as $producto): ?>
                            <div class="product-card">
                                <div class="product-info">
                                <img src="../uploads/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Producto" class="product-image">
                                    <div class="product-details">
                                        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                                        <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                        <div class="price-stock">
                                            <span>$<?php echo number_format($producto['precio'], 2); ?>/kg</span>
                                            <span>Stock: <?php echo htmlspecialchars($producto['stock']); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-actions">
                                <button class="edit-btn" onclick="openEditModal(<?php echo $producto['id']; ?>)">Editar</button>
                                <button class="delete-btn" onclick="confirmDelete(<?php echo $producto['id']; ?>)">Eliminar</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No se encontraron productos.</p>
                    <?php endif; ?>
                </div>

<!-- Modal para editar producto -->
<div id="editModal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Editar Producto</h2>
        <form id="editForm" onsubmit="submitEditForm(event);" enctype="multipart/form-data">
            <input type="hidden" id="productId" name="productId">
            <label for="productName">Nombre:</label>
            <input type="text" id="productName" name="productName" required>
            
            <label for="productPrice">Precio:</label>
            <input type="number" id="productPrice" name="productPrice" step="0.01" required>
            
            <label for="productDescription">Descripción:</label>
            <textarea id="productDescription" name="productDescription" required></textarea>
            
            <label for="productStock">Stock:</label>
            <input type="number" id="productStock" name="productStock" required>
            
            <label for="productImage">Imagen:</label>
            <input type="file" id="productImage" name="productImage" onchange="previewImage(event);">
            
            <div id="imagePreviewContainer" style="display:none;">
                <img id="imagePreview" src="" alt="Previsualización de la imagen" style="max-width: 100%; height: auto;">
            </div>
            
            <button class="submit-btn" type="submit">Guardar Cambios</button>
        </form>
    </div>
</div>




<!-- Add New Product Section -->
<div class="add-product">
    <h2>Añadir Nuevo Producto</h2>
    <form id="productForm" method="POST" enctype="multipart/form-data">
        <label for="name">Nombre del Producto</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Descripción</label>
        <textarea id="description" name="description" required></textarea>

        <div class="price-quantity-container">
            <div class="price-field">
                <label for="price">Precio (por kg)</label>
                <input type="number" id="price" name="price" min="0" step="0.01" required>
            </div>

            <div class="quantity-field">
                <label for="quantity">Stock</label>
                <input type="number" id="quantity" name="quantity" min="0" required>
            </div>
        </div>

        <label for="category">Categoría</label>
        <select id="category" name="category" required>
            <option value="fruits">Frutas</option>
            <option value="vegetables">Verduras</option>
            <option value="herbs">Hierbas</option>
        </select>

        <label for="image">Imagen del Producto</label>
        <input type="file" id="image" name="image" accept="image/*" required>
        <img id="image-preview" src="#" alt="Vista previa de la imagen" style="display: none; width: 100%; max-width: 200px; margin-top: 10px;"/>

        <button type="submit" class="submit-btn">
            <i class="fas fa-upload"></i> Subir Producto
        </button>
        
        <div class="action-buttons">
        <a href="mercado" class="sell-button">
    <i class="fas fa-recycle"></i> <!-- Icono de reciclaje -->
    Vende lo que no usas
</a>
            <div class="inventory-summary">
    <h2>Resumen de Inventario</h2>
    <p>Total de productos activos: <?php echo $totalProductos; ?></p>
    <p>Productos con bajo stock: <?php echo $productosBajoStock; ?></p>
    <p>Valor total del inventario: $<?php echo number_format($valorTotal, 2); ?></p>
    </div>
        </div>
    </form>

    <div id="resultMessage"></div>
    <div id="successNotification">Producto subido con éxito</div>
</div>


            </div>
        </div>




        <!-- Estadisticas de ventas -->
        <div id="analytics" class="tab-content">
            <div class="analytics-container">
                <!-- Cuadro de Resumen de Ventas -->
                <div class="sales-summary-container">
                    <h2>Resumen de Ventas</h2>
                    <div class="sales-summary">
                        <p>Total de Ventas</p>
                        <span>500 kg</span>

                        <p>Producto Más Vendido</p>
                        <span>
                            <span class="product">Manzanas</span> - 150 kg
                        </span>

                        <p>Ingresos Totales</p>
                        <span>$2000</span>
                    </div>
                </div>
                <!-- Cuadro de Rendimiento por Categoría -->
                <div class="category-performance-container">
                    <h2>Rendimiento por Categoría</h2>
                    <div class="category-performance">
                        <div class="category">
                            <span>Frutas</span>
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: 50%;">
                                    <span class="percentage">50%</span>
                                </div>
                            </div>
                        </div>
                        <div class="category">
                            <span>Verduras</span>
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: 30%;">
                                    <span class="percentage">30%</span>
                                </div>
                            </div>
                        </div>
                        <div class="category">
                            <span>Hierbas</span>
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: 20%;">
                                    <span class="percentage">20%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<br>
        <!-- Asegúrate de incluir Font Awesome para los iconos -->
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <script src="js/producto.js"></script>
    </div>
</body>
</html>

