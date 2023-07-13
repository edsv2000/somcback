<?php

require_once '../../config/conexion.php';

// Crear una instancia de la clase de conexión
$conexion = new Conexion();
$conn = $conexion->getConnection();

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}

// Consulta SQL para obtener todos los registros de la tabla
$sql = "SELECT * FROM Ingredientes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Crear un array para almacenar los registros
    $ingredientes = array();

    while ($row = $result->fetch_assoc()) {
        // Agregar cada registro al array
        $ingredientes[] = $row;
    }

    // Crear un objeto JSON con los registros de los ingredientes
    $response = array('success' => true, 'data' => $ingredientes);
    echo json_encode($response);
} else {
    // No se encontraron registros
    $response = array('success' => false, 'message' => 'No se encontraron registros de ingredientes');
    echo json_encode($response);
}

// Cerrar la conexión a la base de datos
$conn->close();

?>
