<?php
// Conexión a la base de datos
$servername = "localhost"; // Cambia esto si tu servidor es diferente
$username = "root"; // Tu nombre de usuario de MySQL
$password = ""; // Tu contraseña de MySQL
$dbname = "fruitfest"; // Cambia a tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// ID del vendedor (puede ser dinámico dependiendo de tu sistema)
$vendedor_id = 6; // Cambia esto según el vendedor actual

// Consulta SQL para obtener las estadísticas agrupadas por mes
$stats_query = $conn->prepare("
    SELECT 
        MONTH(fecha) AS mes,
        COUNT(id) AS total_pedidos,
        SUM(subtotal) AS total_ingresos
    FROM pedidos
    WHERE id_vendedor = ?
    GROUP BY MONTH(fecha)
    ORDER BY MONTH(fecha)
");

if ($stats_query === false) {
    die('Error en la consulta SQL: ' . $conn->error);
}

$stats_query->bind_param("i", $vendedor_id);
$stats_query->execute();
$stats_result = $stats_query->get_result();

// Inicializar arrays para los datos del gráfico
$meses = [];
$total_pedidos = [];
$total_ingresos = [];

// Almacenar los resultados en arrays para pasarlos a JavaScript
while ($row = $stats_result->fetch_assoc()) {
    $meses[] = DateTime::createFromFormat('!m', $row['mes'])->format('F'); // Convertir número de mes a nombre de mes (ejemplo: "January")
    $total_pedidos[] = $row['total_pedidos'];
    $total_ingresos[] = $row['total_ingresos'];
}

// Preparar los datos para JavaScript
$ventas_datos = [
    'meses' => $meses,
    'total_pedidos' => $total_pedidos,
    'total_ingresos' => $total_ingresos
];

// Cerrar la conexión
$conn->close();
?>

<!-- Código HTML donde se mostrará el gráfico -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Ventas por Mes</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <style>
        /* Estilos para centrar el gráfico */
        #chartContainer {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <h1>Estadísticas de Ventas por Mes</h1>

    <div id="chartContainer">
        <!-- Gráfico de Barras -->
        <canvas id="ventasChart" width="300" height="150"></canvas>
    </div>

    <script>
        // Pasar los datos de PHP a JavaScript
        const ventasDatos = <?php echo json_encode($ventas_datos); ?>;

        // Crear el gráfico usando Chart.js
        const ctx = document.getElementById('ventasChart').getContext('2d');
        const ventasChart = new Chart(ctx, {
            type: 'bar', // Tipo de gráfico: Barras
            data: {
                labels: ventasDatos.meses, // Etiquetas de los meses
                datasets: [
                    {
                        label: 'Total Pedidos',
                        data: ventasDatos.total_pedidos, // Datos de total pedidos
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total Ingresos',
                        data: ventasDatos.total_ingresos, // Datos de total ingresos
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true, // Hacer el gráfico responsivo
                scales: {
                    y: {
                        beginAtZero: true, // Comenzar en cero
                        ticks: {
                            autoSkip: false, // Mostrar todos los valores en el eje Y
                        }
                    }
                },
                plugins: {
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        formatter: (value) => value,
                        color: 'black',
                    }
                }
            }
        });
    </script>
</body>
</html>
