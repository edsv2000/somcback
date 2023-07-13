<?php
// Incluir el archivo de conexión a la base de datos
require_once '../../config/conexion.php';

$conexion = new Conexion();

// Obtener la conexión a la base de datos
$db = $conexion->getConnection();

// Obtener el cuerpo de la solicitud JSON
$jsonData = file_get_contents('php://input');

// Decodificar el JSON en un arreglo asociativo
$data = json_decode($jsonData, true);

// Obtener los datos del arreglo asociativo (id_ingrediente, cantidad)
$idIngrediente = $data['id_ingrediente'];
$cantidad = $data['cantidad'];

// Actualizar el inventario en la base de datos
actualizarInventario($idIngrediente, $cantidad);

// Función para actualizar el inventario en la base de datos
function actualizarInventario($idIngrediente, $cantidad) {
    global $db;

    // Escapar el ID del ingrediente para evitar inyección SQL
    $idIngrediente = mysqli_real_escape_string($db, $idIngrediente);

    // Obtener el inventario actual del ingrediente
    $inventarioActual = obtenerInventario($idIngrediente);

    // Calcular el nuevo inventario sumando la cantidad recibida
    $nuevoInventario = $inventarioActual + $cantidad;

    // Actualizar el inventario en la base de datos
    $sql = "UPDATE Ingredientes SET Cantidad = $nuevoInventario WHERE ID_ingrediente = $idIngrediente";
    $result = mysqli_query($db, $sql);

    // Verificar si se actualizó correctamente
    if ($result) {
        $response = array(
            'success' => true,
            'message' => 'Inventario actualizado exitosamente'
        );
    } else {
        $response = array(
            'success' => false,
            'message' => 'Error al actualizar el inventario'
        );
    }

    // Convertir la respuesta a formato JSON
    $jsonResponse = json_encode($response);

    // Enviar la respuesta JSON
    header('Content-type: application/json');
    echo $jsonResponse;
}

// Función para obtener el inventario actual del ingrediente desde la base de datos
function obtenerInventario($idIngrediente) {
    global $db;

    // Escapar el ID del ingrediente para evitar inyección SQL
    $idIngrediente = mysqli_real_escape_string($db, $idIngrediente);

    // Realizar la consulta para obtener el inventario actual del ingrediente
    $sql = "SELECT Cantidad FROM Ingredientes WHERE ID_ingrediente = $idIngrediente";
    $result = mysqli_query($db, $sql);

    // Verificar si se obtuvo el resultado correctamente
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $inventarioActual = $row['Cantidad'];
    } else {
        $inventarioActual = 0;
    }

    // Retornar el inventario actual
    return $inventarioActual;
}
?>
