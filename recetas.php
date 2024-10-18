<?php include 'procesos/recetas.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recetas - Fruit Fest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/recetas.css">
</head>
<body>
<?php include 'Estructura/header.php'; ?>

    <div class="banner">
        Recetas
    </div>

    <main class="container">
        <!-- Sección de Ver Recetas -->
        <div class="recipe-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($receta = $result->fetch_assoc()) {
                    echo '<div class="recipe-card">';
                    echo '<img src="' . htmlspecialchars($receta['imagen_url']) . '" alt="Imagen de la receta">';
                    echo '<div class="recipe-content">';
                    echo '<h3>' . htmlspecialchars($receta['titulo']) . '</h3>';
                    echo '<p><strong>Preparación:</strong> ' . htmlspecialchars($receta['preparacion']) . '</p>';
                    echo '<p><strong>Dificultad:</strong> ' . htmlspecialchars($receta['dificultad']) . '</p>';
                    echo '<p><strong>Porciones:</strong> ' . htmlspecialchars($receta['porciones']) . '</p>';
                    echo '<p><strong>Tiempo:</strong> ' . htmlspecialchars($receta['tiempo']) . '</p>';
                    echo '</div></div>';
                }
            } else {
                echo "<p>No hay recetas disponibles.</p>";
            }
            ?>
        </div>

        <!-- Sección para Agregar Receta -->
        <div class="form-container">
            <h2>Agregar Receta</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" id="titulo" name="titulo" required>
                </div>
                <div class="form-group">
                    <label for="ingredientes">Ingredientes</label>
                    <textarea id="ingredientes" name="ingredientes" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="preparacion">Preparación</label>
                    <textarea id="preparacion" name="preparacion" rows="6" required></textarea>
                </div>
                <div class="form-group">
                    <label for="imagen_url">URL de Imagen</label>
                    <input type="text" id="imagen_url" name="imagen_url" required>
                </div>
                <div class="form-group">
                    <label for="dificultad">Dificultad</label>
                    <input type="text" id="dificultad" name="dificultad" required>
                </div>
                <div class="form-group">
                    <label for="cocinada_por">Cocinada por</label>
                    <input type="text" id="cocinada_por" name="cocinada_por" required>
                </div>
                <div class="form-group">
                    <label for="porciones">Porciones</label>
                    <input type="number" id="porciones" name="porciones" required>
                </div>
                <div class="form-group">
                    <label for="tiempo">Tiempo de Preparación</label>
                    <input type="text" id="tiempo" name="tiempo" required>
                </div>
                <button type="submit" name="submit_receta" class="add-recipe-button"><i class="fas fa-plus"></i> Agregar Receta</button>
            </form>
        </div>
    </main>

    <?php include 'Estructura/footer.php'; ?>

    <?php
    $conn->close();
    ?>
</body>
</html>
