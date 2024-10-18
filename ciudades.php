<?php
include 'db_connection.php';

$departamento_id = $_GET['departamento_id'];
$result = $conn->query("SELECT id, nombre FROM ciudades WHERE departamento_id = $departamento_id");

$ciudades = array();
while($row = $result->fetch_assoc()) {
    $ciudades[] = $row;
}

echo json_encode($ciudades);
?>
