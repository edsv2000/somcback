<?php
require_once '../../config/conexion.php';

// Obtener los datos del ingrediente en formato JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Verificar si se recibió el ID del ingrediente
if (isset($data['idIngrediente'])) {
    // Crear una instancia de la clase de conexión
    $conexion = new Conexion();
    $conn = $conexion->getConnection();

    // Verificar si la conexión fue exitosa
    if ($conn->connect_error) {
        die("Error en la conexión a la base de datos: " . $conn->connect_error);
    }

    // Obtener el ID del ingrediente
    $idIngrediente = $data['idIngrediente'];

    // Consultar el ingrediente por su ID
    $query = "SELECT * FROM Ingredientes WHERE ID_ingrediente = $idIngrediente";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // El ingrediente existe, obtener los datos
        $row = $result->fetch_assoc();

        // Construir el array de respuesta
        $response = array(
            'success' => true,
            'data' => array(
                'ID_ingrediente' => $row['ID_ingrediente'],
                'Nombre_ingrediente' => $row['Nombre_ingrediente'],
                'Descripcion' => $row['Descripcion'],
                'Unidad_medida' => $row['Unidad_medida'],
                'Proveedor' => $row['Proveedor']
            )
        );

        echo json_encode($response);
    } else {
        // No se encontró el ingrediente
        $response = array('success' => false, 'message' => 'El ingrediente no existe');
        echo json_encode($response);
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    $response = array('success' => false, 'message' => 'ID de ingrediente no proporcionado');
    echo json_encode($response);
}
?>
