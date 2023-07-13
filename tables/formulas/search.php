<?php
require_once '../../config/conexion.php';

// Obtener el ID de la fórmula desde la solicitud en formato JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Verificar si se recibió el ID de la fórmula
if (isset($data['idFormula'])) {
    // Crear una instancia de la clase de conexión
    $conexion = new Conexion();
    $conn = $conexion->getConnection();

    // Verificar si la conexión fue exitosa
    if ($conn->connect_error) {
        $response = array('success' => false, 'message' => 'Error en la conexión a la base de datos: ' . $conn->connect_error);
        echo json_encode($response);
        exit;
    }

    try {
        // Obtener el ID de la fórmula
        $idFormula = $data['idFormula'];

        // Consultar la fórmula por su ID
        $selectFormula = "SELECT * FROM Formulas WHERE ID_formula = '$idFormula'";
        $resultFormula = $conn->query($selectFormula);

        if ($resultFormula->num_rows > 0) {
            // Obtener los datos de la fórmula
            $formulaData = $resultFormula->fetch_assoc();

            // Consultar los ingredientes de la fórmula
            $selectIngredientes = "SELECT * FROM Ingredientes_Formulas WHERE ID_formula = '$idFormula'";
            $resultIngredientes = $conn->query($selectIngredientes);

            // Crear un array para almacenar los ingredientes
            $ingredientesArray = array();

            if ($resultIngredientes->num_rows > 0) {
                while ($rowIngrediente = $resultIngredientes->fetch_assoc()) {
                    // Agregar cada ingrediente al array
                    $ingrediente = array(
                        'idIngrediente' => $rowIngrediente['ID_ingrediente'],
                        'cantidad' => $rowIngrediente['Cantidad']
                    );
                    $ingredientesArray[] = $ingrediente;
                }
            }

            // Combinar los datos de la fórmula y los ingredientes en un array
            $response = array(
                'success' => true,
                'formulaData' => $formulaData,
                'ingredientes' => $ingredientesArray
            );

            echo json_encode($response);
        } else {
            $response = array('success' => false, 'message' => 'No se encontró ninguna fórmula con ese ID');
            echo json_encode($response);
        }
    } catch (Exception $e) {
        $response = array('success' => false, 'message' => 'Error al obtener la fórmula: ' . $e->getMessage());
        echo json_encode($response);
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    $response = array('success' => false, 'message' => 'Falta el ID de la fórmula');
    echo json_encode($response);
}
?>
