<?php
require_once dirname(__DIR__) . '/secrets_loader.php';
session_start();

// Incluir archivo de configuración de la base de datos
require_once '../config/database.php';

// Verificar conexión a la base de datos
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del vendedor (ciudad)
$vendedor_id = $_SESSION['vendedor_id'];
$sql_vendedor = "SELECT ciudad FROM vendedores WHERE id = ?";
$stmt_vendedor = $conn->prepare($sql_vendedor);
if (!$stmt_vendedor) {
    die("Error en la consulta de vendedor: " . $conn->error);
}
$stmt_vendedor->bind_param("i", $vendedor_id);
$stmt_vendedor->execute();
$result_vendedor = $stmt_vendedor->get_result();
$vendedor = $result_vendedor->fetch_assoc();

// Si no se obtiene el vendedor, salir
if (!$vendedor) {
    die("No se encontró al vendedor.");
}
$ciudad = $vendedor['ciudad'];

// Función para obtener el pronóstico del tiempo para 7 días
function obtenerPronosticoTiempo($ciudad) {
    $api_key = sf_secret('openweathermap_api_key');

    // Codificar correctamente la ciudad
    $ciudad_codificada = urlencode($ciudad);

    // URL de la API de 7 días
    $url = "https://api.openweathermap.org/data/2.5/forecast?q={$ciudad_codificada}&appid={$api_key}&lang=es&units=metric";

    // Obtener el contenido de la URL
    $response = file_get_contents($url);
    if ($response === FALSE) {
        return "No se pudo obtener el pronóstico del tiempo.";
    }

    // Decodificar la respuesta JSON
    $weather_data = json_decode($response, true);
    if (isset($weather_data['list'])) {
        return $weather_data;
    }

    return null;
}

// Obtener el pronóstico del tiempo
$pronostico = obtenerPronosticoTiempo($ciudad);

// Obtener los precios de los productos más reconocidos
function obtenerPreciosCultivos($conn) {
    $sql = "SELECT nombre, precio FROM productos ORDER BY RAND() LIMIT 5";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $precios = [];
        while ($row = $result->fetch_assoc()) {
            $precios[] = $row;
        }
        return $precios;
    }
    return [];
}

// Obtener los precios de los productos
$precios = obtenerPreciosCultivos($conn);

// Obtener los 5 productos más buscados basados en search_count
function obtenerProductosMasBuscados($conn) {
    $sql = "SELECT search_query, search_count FROM popular_searches ORDER BY search_count DESC LIMIT 5";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        return $productos;
    }
    return [];
}

// Obtener los productos más buscados
$productos_busquedas = obtenerProductosMasBuscados($conn);

// Obtener comentarios y productos
$sql_productos = "
    SELECT p.id, p.nombre AS producto, p.imagen AS imagen
    FROM productos p
    WHERE p.id_vendedor = ?
";
$stmt_productos = $conn->prepare($sql_productos);
if (!$stmt_productos) {
    die("Error en la preparación de la consulta de productos: " . $conn->error);
}
$stmt_productos->bind_param("i", $vendedor_id);
$stmt_productos->execute();
$productos_result = $stmt_productos->get_result();

// Obtener comentarios
$sql_comentarios = "
    SELECT p.id AS producto_id, p.nombre AS producto, p.imagen AS imagen,
           u.nombre AS usuario, c.comentario
    FROM comentarios c
    INNER JOIN productos p ON c.id_producto = p.id
    INNER JOIN usuarios u ON c.id_usuario = u.id
    WHERE p.id_vendedor = ?
";
$stmt_comentarios = $conn->prepare($sql_comentarios);
if (!$stmt_comentarios) {
    die("Error en la preparación de la consulta de comentarios: " . $conn->error);
}
$stmt_comentarios->bind_param("i", $vendedor_id);
$stmt_comentarios->execute();
$comentarios_result = $stmt_comentarios->get_result();

// Organizar comentarios por producto
$comentarios_por_producto = [];
while ($row_comentario = $comentarios_result->fetch_assoc()) {
    $producto_id = $row_comentario['producto_id'];
    if (!isset($comentarios_por_producto[$producto_id])) {
        $comentarios_por_producto[$producto_id] = [
            'producto' => $row_comentario['producto'],
            'imagen' => $row_comentario['imagen'],
            'comentarios' => []
        ];
    }

    $comentarios_por_producto[$producto_id]['comentarios'][] = [
        'usuario' => $row_comentario['usuario'],
        'comentario' => $row_comentario['comentario']
    ];
}

$stmt_productos->close();
$stmt_comentarios->close();
$conn->close();

// Preparar datos para el gráfico
$nombres_productos = [];
$busquedas_productos = [];

foreach ($productos_busquedas as $producto) {
    $nombres_productos[] = $producto['search_query'];
    $busquedas_productos[] = $producto['search_count'];
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pronóstico y Recomendaciones</title>
    <link rel="stylesheet" href="css/tipss.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

<div class="main-content">
<div class="container">
<div class="weather-container">
    <!-- Header -->
    <div class="weather-header">
        <div class="weather-title">
            <img src="https://cdn-icons-png.flaticon.com/512/2698/2698240.png" alt="Clima" width="30">
            <h2>Pronóstico del Clima</h2>
        </div>
    </div>

    <!-- Ubicación -->
    <div class="location">
        <img src="https://cdn-icons-png.flaticon.com/512/684/684908.png" alt="Ubicación" class="location-icon" width="20">
        <p><?php echo $ciudad; ?></p>
    </div>

    <!-- Información principal del clima -->
    <div class="weather-main">
        <img id="weather-icon" class="weather-icon" src="" alt="Ícono Clima">
        <div class="weather-temp">
            <span id="temp">Cargando...</span>°C
        </div>
        <div class="weather-info">
            <p>Humedad: <span id="humidity">--</span>%</p>
            <p>Viento: <span id="wind-speed">--</span> km/h</p>
        </div>
    </div>

    <!-- Pronóstico por horas -->
    <h3>Pronóstico por horas</h3>
    <div class="hourly-forecast" id="hourly-forecast">
        <p>Cargando pronóstico...</p>
    </div>

    <!-- Recomendaciones para agricultores -->
    <div class="recommendation">
        <br>
        <h3>Recomendaciones para Agricultores</h3>
        <p id="recommendation">Cargando recomendación...</p>
    </div>
</div>

<br>

<!-- Nuevo cuadro para "Precios de Cultivos" -->
<div class="weather-container">
    <!-- Header -->
    <div class="weather-header">
        <div class="weather-title">
            <!-- Nueva imagen de precios de cultivos -->
            <img src="https://cdn-icons-png.flaticon.com/512/3600/3600545.png" alt="Precios Cultivos" width="30">
            <h2>Precios de Cultivos</h2>
        </div>
    </div>

    <!-- Lista de precios de cultivos -->
    <div class="weather-main">
        <div class="crop-prices">
            <div class="price-row">
                <span class="product-name">Tomates</span>
                <span class="product-price">$8,500 COP / kg</span>
            </div>
            <div class="price-row">
                <span class="product-name">Papas</span>
                <span class="product-price">$3,200 COP / kg</span>
            </div>
            <div class="price-row">
                <span class="product-name">Zanahorias</span>
                <span class="product-price">$4,000 COP / kg</span>
            </div>
            <div class="price-row">
                <span class="product-name">Lechuga</span>
                <span class="product-price">$1,500 COP / unidad</span>
            </div>
            <div class="price-row">
                <span class="product-name">Maíz</span>
                <span class="product-price">$2,800 COP / kg</span>
            </div>
        </div>
    </div>
</div>
</div>

<div class="grafico-container" style="width: 70%; margin: 0 auto;">
    <canvas id="graficoProductosBuscados"></canvas>
</div>

<script>
    // Obtener los datos desde PHP
    const nombresProductos = <?php echo json_encode($nombres_productos); ?>;
    const busquedasProductos = <?php echo json_encode($busquedas_productos); ?>;

    // Configuración del gráfico
    const ctx = document.getElementById('graficoProductosBuscados').getContext('2d');
    const graficoProductosBuscados = new Chart(ctx, {
        type: 'bar', // Tipo de gráfico: barras
        data: {
            labels: nombresProductos, // Nombres de los productos
            datasets: [{
                label: 'Búsquedas',
                data: busquedasProductos, // Cantidad de búsquedas de los productos
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color de fondo de las barras
                borderColor: 'rgba(75, 192, 192, 1)', // Color del borde de las barras
                borderWidth: 1 // Grosor del borde
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true // Comenzar el eje Y desde 0
                }
            },
            plugins: {
                legend: {
                    display: true, // Mostrar la leyenda
                    position: 'top'
                }
            }
        }
    });
</script>
<br>
<h1 class="titulo-comentarios">Comentarios de Productos</h1>

<div class="comentarios-container">
    <?php foreach ($comentarios_por_producto as $producto): ?>
        <div class="producto">
            <img src="../uploads/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['producto']); ?>" class="imagen-producto">
            <div class="producto-info">
                <h2 class="nombre-producto"><?php echo htmlspecialchars($producto['producto']); ?></h2>
                <div class="comentarios">
                    <?php foreach ($producto['comentarios'] as $comentario): ?>
                        <div class="comentario">
                            <strong class="usuario-comentario"><?php echo htmlspecialchars($comentario['usuario']); ?>:</strong>
                            <span class="texto-comentario"><?php echo htmlspecialchars($comentario['comentario']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>






<script>
// Insertar el pronóstico del tiempo
const pronostico = <?php echo json_encode($pronostico); ?>;
const weatherIcon = document.getElementById('weather-icon');
const tempEl = document.getElementById('temp');
const humidityEl = document.getElementById('humidity');
const windSpeedEl = document.getElementById('wind-speed');
const hourlyForecastContainer = document.getElementById('hourly-forecast');
const recommendationEl = document.getElementById('recommendation');

// Iconos de clima de Flaticon (se asume que este código ya ha sido adaptado para OpenWeatherMap)
const iconosClima = {
    'clear': 'https://cdn-icons-png.flaticon.com/512/869/869869.png',
    'clouds': 'https://cdn-icons-png.flaticon.com/512/414/414825.png',
    'rain': 'https://cdn-icons-png.flaticon.com/512/1163/1163624.png',
    'snow': 'https://cdn-icons-png.flaticon.com/512/896/896203.png',
    'thunderstorm': 'https://cdn-icons-png.flaticon.com/512/1146/1146860.png',
    'drizzle': 'https://cdn-icons-png.flaticon.com/512/1063/1063801.png',
    'mist': 'https://cdn-icons-png.flaticon.com/512/4005/4005807.png'
};

if (pronostico && pronostico.list) {
    // Usar los datos actuales del clima
    const climaActual = pronostico.list[0];
    const temp = climaActual.main.temp;
    const iconCode = climaActual.weather[0].main.toLowerCase();
    const humidity = climaActual.main.humidity;
    const windSpeed = climaActual.wind.speed;

    // Asignar datos al DOM
    tempEl.textContent = Math.round(temp);
    humidityEl.textContent = humidity;
    windSpeedEl.textContent = Math.round(windSpeed * 3.6); // Convertir m/s a km/h

    // Asignar el ícono del clima
    if (iconosClima[iconCode]) {
        weatherIcon.src = iconosClima[iconCode];
    } else {
        weatherIcon.src = iconosClima['clear']; // Valor por defecto
    }

    // Crear pronóstico por horas (solo las próximas 5 horas como ejemplo)
    const forecastList = pronostico.list.slice(0, 5);
    hourlyForecastContainer.innerHTML = ''; // Limpiar
    forecastList.forEach(forecast => {
        const hour = new Date(forecast.dt * 1000).getHours();
        const hourTemp = Math.round(forecast.main.temp);
        const hourIcon = forecast.weather[0].main.toLowerCase();

        const forecastDiv = document.createElement('div');
        forecastDiv.innerHTML = `
            <p>${hour}:00</p>
            <img src="${iconosClima[hourIcon] || iconosClima['clear']}" alt="icono" width="40">
            <p>${hourTemp}°C</p>
        `;
        hourlyForecastContainer.appendChild(forecastDiv);
    });

    // Recomendaciones para agricultores (Ejemplo básico)
    if (temp > 30) {
        recommendationEl.textContent = "El clima es muy caluroso, asegúrate de regar tus cultivos temprano.";
    } else if (temp < 5) {
        recommendationEl.textContent = "El clima es muy frío, protege tus cultivos del frío extremo.";
    } else if (iconCode === 'rain') {
        recommendationEl.textContent = "Lluvia esperada, revisa el sistema de drenaje en tus campos.";
    } else {
        recommendationEl.textContent = "El clima es favorable para el cultivo hoy.";
    }
} else {
    tempEl.textContent = '--';
    humidityEl.textContent = '--';
    windSpeedEl.textContent = '--';
    recommendationEl.textContent = "No se pudo obtener el pronóstico del clima.";
}
</script>

</body>
</html>







