<?php
require_once '../../config/conexion.php';

// Crear una instancia de la clase de conexión
$conexion = new Conexion();
$conn = $conexion->getConnection();

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}

// Consultar todas las fórmulas
$query = "SELECT * FROM Formulas";
$result = $conn->query($query);

// Verificar si se encontraron resultados
if ($result->num_rows > 0) {
    $formulas = array();

    // Recorrer los resultados y agregarlos al array de fórmulas
    while ($row = $result->fetch_assoc()) {
        $formula = array(
            'ID_formula' => $row['ID_formula'],
            'Descripcion' => $row['Descripcion'],
            'Fecha_creacion' => $row['Fecha_creacion'],
            'ID_usuario' => $row['ID_usuario']
        );
        $formulas[] = $formula;
    }

    // Devolver las fórmulas como respuesta JSON
    echo json_encode($formulas);
} else {
    echo "No se encontraron fórmulas";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
