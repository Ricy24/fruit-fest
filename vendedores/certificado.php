<?php
require('../fpdf/fpdf.php'); // Incluye la biblioteca FPDF

// Función para conectar a la base de datos
function conectarDB() {
    $conn = new mysqli('localhost', 'root', '', 'fruitfest');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    return $conn;
}

// Iniciar sesión
session_start();

// Verificar si el vendedor está logueado
if (!isset($_SESSION['vendedor_id'])) {
    die('ID de vendedor no especificado. Asegúrate de iniciar sesión correctamente.');
}

$vendedor_id = $_SESSION['vendedor_id']; // Obtener el ID del vendedor desde la sesión

// Conectar a la base de datos
$conn = conectarDB();

// Obtener la información del vendedor
$vendedor_query = $conn->prepare("SELECT nombre, fecha_registro FROM vendedores WHERE id = ?");
if ($vendedor_query === false) {
    die("Error en la consulta: " . $conn->error);
}
$vendedor_query->bind_param("i", $vendedor_id);
$vendedor_query->execute();
$vendedor_result = $vendedor_query->get_result();
$vendedor = $vendedor_result->fetch_assoc();
$vendedor_query->close();
$conn->close();

if (!$vendedor) {
    die('Vendedor no encontrado.');
}

// Crear una instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Añadir el texto principal
$pdf->Cell(0, 10, 'Certificado de Afiliación', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 10, 
    "Por la presente, se certifica que el Sr./Sra. " . $vendedor['nombre'] . 
    ", con fecha de registro " . $vendedor['fecha_registro'] . 
    ", es miembro activo de la prestigiosa familia Fruit Fest. Este certificado se emite con la finalidad de acreditar su pertenencia y colaboración con nuestra empresa, destacando su compromiso y profesionalismo en el desempeño de sus funciones. Fruit Fest agradece su contribución y le desea un continuo éxito en sus actividades profesionales.",
    0, 'C');
$pdf->Ln(20);

// Configuración de la marca de agua
$pdf->SetFont('Arial', 'B', 70);
$pdf->SetTextColor(144, 238, 144); // Color verde claro para la marca de agua
$pdf->SetXY(30, 100); // Posicionar en el centro de la página (ajustar según sea necesario)
$pdf->Cell(0, 0, 'FRUIT FEST', 0, 0, 'C');

// Guardar el PDF en el servidor
$certificado_dir = 'certificados';
if (!is_dir($certificado_dir)) {
    mkdir($certificado_dir, 0755, true); // 0755 es un conjunto de permisos estándar
}
$pdf_output = $certificado_dir . '/certificado_' . $vendedor_id . '.pdf';
$pdf->Output('F', $pdf_output);

// Forzar la descarga del archivo PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . basename($pdf_output) . '"');
readfile($pdf_output);
exit();
?>
