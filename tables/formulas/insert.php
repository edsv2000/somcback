<?php
require_once '../../config/conexion.php';

// Obtener los datos de la fórmula en formato JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Verificar si se recibieron los datos requeridos
if (
    isset($data['descripcion']) &&
    isset($data['idUsuario']) &&
    isset($data['ingredientes'])
) {
    // Crear una instancia de la clase de conexión
    $conexion = new Conexion();
    $conn = $conexion->getConnection();

    // Verificar si la conexión fue exitosa
    if ($conn->connect_error) {
        die("Error en la conexión a la base de datos: " . $conn->connect_error);
    }

    // Preparar los datos de la fórmula
    $descripcion = $data['descripcion'];
    $fechaCreacion = date('Y-m-d');
    $idUsuario = $data['idUsuario'];
    $ingredientes = $data['ingredientes'];

    // Iniciar una transacción para garantizar la integridad de los datos
    $conn->begin_transaction();

    try {
        // Insertar la fórmula en la tabla "Formulas"
        $insertFormula = "INSERT INTO Formulas (Descripcion, Fecha_creacion, ID_usuario)
                          VALUES ('$descripcion', '$fechaCreacion', '$idUsuario')";
        $conn->query($insertFormula);

        // Obtener el ID de la fórmula insertada
        $idFormula = $conn->insert_id;

        // Insertar los ingredientes en la tabla "Ingredientes_Formulas"
        foreach ($ingredientes as $ingrediente) {
            $idIngrediente = $ingrediente['idIngrediente'];
            $cantidad = $ingrediente['cantidad'];

            $insertIngredienteFormula = "INSERT INTO Ingredientes_Formulas (ID_formula, ID_ingrediente, Cantidad)
                                         VALUES ('$idFormula', '$idIngrediente', '$cantidad')";
            $conn->query($insertIngredienteFormula);
        }

        // Confirmar la transacción
        $conn->commit();

        $response = array('success' => true, 'message' => 'Fórmula agregada exitosamente');
        echo json_encode($response);
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();

        $response = array('success' => false, 'message' => 'Error al agregar la fórmula: ' . $e->getMessage());
        echo json_encode($response);
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    $response = array('success' => false, 'message' => 'Faltan datos requeridos');
    echo json_encode($response);
}
?>
