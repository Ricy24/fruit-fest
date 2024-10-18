<?php
require 'fpdf/fpdf.php';

// Iniciar sesión
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Validar el ID del pedido
$id_pedido = filter_input(INPUT_GET, 'id_pedido', FILTER_VALIDATE_INT);
if ($id_pedido === false) {
    die("ID de pedido no válido.");
}

// Función para conectar a la base de datos
function conectarDB() {
    $conn = new mysqli('localhost', 'root', '', 'fruitfest');
    if ($conn->connect_error) {
        error_log("Conexión fallida: " . $conn->connect_error);
        die("Se ha producido un error, por favor intente nuevamente más tarde.");
    }
    return $conn;
}

$conn = conectarDB();

// Obtener detalles del pedido
$stmt = $conn->prepare("
    SELECT p.id, p.nombre, p.identificacion, p.direccion, p.telefono, p.metodo_pago, p.subtotal, p.envio, p.descuento, p.total_pago, p.fecha, p.id_vendedor
    FROM pedidos p
    WHERE p.id = ?
");
if ($stmt === false) {
    error_log("Error al preparar la consulta: " . $conn->error);
    die("Se ha producido un error, por favor intente nuevamente más tarde.");
}
$stmt->bind_param("i", $id_pedido);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Pedido no encontrado.");
}
$pedido = $result->fetch_assoc();
$stmt->close();

// Obtener detalles de los productos del pedido
$stmt_detalles = $conn->prepare("
    SELECT dp.id_producto, p.nombre, dp.cantidad, dp.total
    FROM detalles_pedido dp
    JOIN productos p ON dp.id_producto = p.id
    WHERE dp.id_pedido = ?
");
if ($stmt_detalles === false) {
    error_log("Error al preparar la consulta: " . $conn->error);
    die("Se ha producido un error, por favor intente nuevamente más tarde.");
}
$stmt_detalles->bind_param("i", $id_pedido);
$stmt_detalles->execute();
$detalles_result = $stmt_detalles->get_result();
$stmt_detalles->close();
$conn->close();

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Factura de Pedido', 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Fecha: ' . $pedido['fecha'], 0, 1);
$pdf->Cell(0, 10, 'Nombre: ' . $pedido['nombre'], 0, 1);
$pdf->Cell(0, 10, 'Identificación: ' . $pedido['identificacion'], 0, 1);
$pdf->Cell(0, 10, 'Dirección: ' . $pedido['direccion'], 0, 1);
$pdf->Cell(0, 10, 'Teléfono: ' . $pedido['telefono'], 0, 1);
$pdf->Cell(0, 10, 'Método de Pago: ' . $pedido['metodo_pago'], 0, 1);
$pdf->Cell(0, 10, 'ID del Vendedor: ' . $pedido['id_vendedor'], 0, 1);

$pdf->Cell(0, 10, ' ', 0, 1); // Espacio en blanco

$pdf->Cell(80, 10, 'Producto', 1);
$pdf->Cell(30, 10, 'Cantidad', 1);
$pdf->Cell(30, 10, 'Total', 1);
$pdf->Ln();

while ($detalle = $detalles_result->fetch_assoc()) {
    $pdf->Cell(80, 10, $detalle['nombre'], 1);
    $pdf->Cell(30, 10, $detalle['cantidad'], 1);
    $pdf->Cell(30, 10, number_format($detalle['total'], 2), 1);
    $pdf->Ln();
}

$pdf->Cell(110, 10, 'Subtotal', 1);
$pdf->Cell(30, 10, number_format($pedido['subtotal'], 2), 1);
$pdf->Ln();
$pdf->Cell(110, 10, 'Envío', 1);
$pdf->Cell(30, 10, number_format($pedido['envio'], 2), 1);
$pdf->Ln();
$pdf->Cell(110, 10, 'Descuento', 1);
$pdf->Cell(30, 10, number_format($pedido['descuento'], 2), 1);
$pdf->Ln();
$pdf->Cell(110, 10, 'Total', 1);
$pdf->Cell(30, 10, number_format($pedido['total_pago'], 2), 1);

$pdf->Output();
?>
