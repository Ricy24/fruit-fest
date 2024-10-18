
<?php
header('Content-Type: application/json');
require '../vendor/autoload.php'; // PHPMailer

$conn = new mysqli('localhost', 'root', '', 'fruitfest'); 

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$departamento_id = isset($_GET['departamento_id']) ? intval($_GET['departamento_id']) : 0;

if ($departamento_id > 0) {
    $sql = "SELECT id, nombre FROM ciudades WHERE departamento_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $departamento_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $ciudades = array();
        while ($row = $result->fetch_assoc()) {
            $ciudades[] = $row;
        }

        echo json_encode($ciudades);
        $stmt->close();
    } else {
        echo json_encode(array('error' => 'Error en la consulta.'));
    }
} else {
    echo json_encode(array('error' => 'ID de departamento inválido.'));
}

$conn->close();
?>
