<?php
session_start(); // Asegúrate de que la sesión está iniciada

// Conexión a la base de datos
$mysqli = new mysqli('localhost', 'root', '', 'fruitfest');

// Verifica la conexión
if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Obtén el id del vendedor de la sesión
if (!isset($_SESSION['vendedor_id'])) {
    die("Error: No estás logueado.");
}
$vendedor_id = $_SESSION['vendedor_id'];

// Inicializa variables para la búsqueda y categoría
$search_term = isset($_POST['search']) ? $_POST['search'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : 'all';

// Prepara la consulta base para seleccionar productos del vendedor
$query = 'SELECT * FROM productos WHERE id_vendedor = ?';

// Agrega condiciones para búsqueda y filtrado por categoría
if ($search_term !== '') {
    $query .= ' AND nombre LIKE ?'; // Filtrado por nombre
}

if ($category !== 'all') {
    $query .= ' AND categoria = ?'; // Filtrado por categoría
}

// Prepara la consulta
$stmt = $mysqli->prepare($query);

// Inicializa el array para los tipos de parámetros y los valores a enlazar
$params = [];
$param_types = 'i'; // Comenzamos con el tipo de vendedor_id (entero)
$params[] = $vendedor_id; // Agregamos el id del vendedor a los parámetros

// Agrega condiciones para búsqueda y filtrado por categoría
if ($search_term !== '') {
    $params[] = '%' . $search_term . '%'; // Para buscar por nombre
    $param_types .= 's'; // Agregamos el tipo para el término de búsqueda (string)
}

if ($category !== 'all') {
    $params[] = $category; // Agregamos la categoría
    $param_types .= 's'; // Agregamos el tipo para la categoría (string)
}

// Bind dinámicamente los parámetros
$stmt->bind_param($param_types, ...$params); // Usa el operador de expansión para pasar los parámetros

// Ejecuta la consulta
$stmt->execute();
$result = $stmt->get_result();

// Verifica si hay productos
$productos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row; // Almacena productos en el array
    }
} else {
    echo '<p>No se encontraron productos.</p>';
}

// Cierra la declaración
$stmt->close();

// Consulta para contar el total de productos activos del vendedor
$totalProductosQuery = "SELECT COUNT(*) as total FROM productos WHERE id_vendedor = ?";
$stmt = $mysqli->prepare($totalProductosQuery);
$stmt->bind_param("i", $vendedor_id);
$stmt->execute();
$result = $stmt->get_result();
$totalProductos = $result->fetch_assoc()['total'];

// Consulta para contar productos con bajo stock (menos de 5)
$productosBajoStockQuery = "SELECT COUNT(*) as bajo_stock FROM productos WHERE id_vendedor = ? AND stock < 5";
$stmt = $mysqli->prepare($productosBajoStockQuery);
$stmt->bind_param("i", $vendedor_id);
$stmt->execute();
$result = $stmt->get_result();
$productosBajoStock = $result->fetch_assoc()['bajo_stock'];

// Consulta para obtener el valor total del inventario
$valorTotalQuery = "SELECT SUM(precio * stock) as total_valor FROM productos WHERE id_vendedor = ?";
$stmt = $mysqli->prepare($valorTotalQuery);
$stmt->bind_param("i", $vendedor_id);
$stmt->execute();
$result = $stmt->get_result();
$valorTotal = $result->fetch_assoc()['total_valor'];

// Cierra la declaración
$stmt->close();

// Cierra la conexión
$mysqli->close();
?>
