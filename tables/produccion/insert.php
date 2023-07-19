<?php
require_once '../../config/conexion.php';

// Obtener el JSON enviado mediante la solicitud POST
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Verificar si el JSON fue decodificado correctamente y si los datos no están vacíos
if (!$data || empty($data['ID_pedido'])) {
  echo json_encode(array('status' => 'error', 'message' => 'Error en el formato del JSON o falta el ID del pedido.'));
  exit;
}

// Crear una instancia de la clase de conexión
$conexion = new Conexion();
$conn = $conexion->getConnection();

// Obtener el ID del pedido
$ID_pedido = intval($data['ID_pedido']); // Convertir a entero para mayor seguridad

// Obtener los detalles de pedido de la tabla Detalles_pedido utilizando una consulta preparada
$sql = "SELECT * FROM Detalles_pedido WHERE ID_pedido = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $ID_pedido);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontraron detalles de pedido
if ($result->num_rows == 0) {
  echo json_encode(array('status' => 'error', 'message' => 'No se encontraron detalles de pedido para el ID del pedido proporcionado.'));
  $stmt->close();
  $conn->close();
  exit;
}

// Recorrer los detalles de pedido y llenar la tabla Produccion
while ($detalle = $result->fetch_assoc()) {
  $ID_formula = $detalle['ID_formula'];
  $Cantidad_producida = $detalle['Cantidad'];
  $Fecha_produccion = date('Y-m-d'); // Asignamos la fecha actual
  $ID_usuario = $detalle['ID_usuario'];

  // Insertar los datos en la tabla Produccion utilizando una consulta preparada
  $sql = "INSERT INTO Produccion (ID_formula, Cantidad_producida, Fecha_produccion, ID_usuario, ID_pedido)
          VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('iiisi', $ID_formula, $Cantidad_producida, $Fecha_produccion, $ID_usuario, $ID_pedido);
  $stmt->execute();
}

$stmt->close();
$conn->close();

echo json_encode(array('status' => 'success', 'message' => 'Datos insertados correctamente en la tabla Produccion.'));
?>
