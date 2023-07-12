<?php

require_once 'config/conexion.php';

// Obtener los datos del ingrediente en formato JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Verificar si se recibieron los datos requeridos
if (
    isset($data['nombre_ingrediente']) &&
    isset($data['descripcion']) &&
    isset($data['unidad_medida']) &&
    isset($data['proveedor'])
) {
    // Crear una instancia de la clase de conexión
    $conexion = new Conexion();
    $conn = $conexion->getConnection();
    
    // Verificar si la conexión fue exitosa
    if ($conn->connect_error) {
        die("Error en la conexión a la base de datos: " . $conn->connect_error);
    }
    
    // Preparar los datos del ingrediente
    $nombre_ingrediente = $data['nombre_ingrediente'];
    $descripcion = $data['descripcion'];
    $unidad_medida = $data['unidad_medida'];
    $proveedor = $data['proveedor'];
    
    // Preparar la consulta SQL para insertar el ingrediente
    $sql = "INSERT INTO Ingredientes (Nombre_ingrediente, Descripcion, Unidad_medida, Proveedor)
            VALUES ('$nombre_ingrediente', '$descripcion', '$unidad_medida', '$proveedor')";
    
    // Ejecutar la consulta SQL
    if ($conn->query($sql) === TRUE) {
        $response = array('success' => true, 'message' => 'Ingrediente agregado exitosamente');
        echo json_encode($response);
    } else {
        $response = array('success' => false, 'message' => 'Error al agregar el ingrediente: ' . $conn->error);
        echo json_encode($response);
    }
    
    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    $response = array('success' => false, 'message' => 'Faltan datos requeridos');
    echo json_encode($response);
}

?>
