<?php

require_once '../../config/conexion.php';
// Obtener los datos enviados en el cuerpo de la solicitud
$json_data = file_get_contents('php://input');

// Crear una instancia de la clase de conexión
$conexion = new Conexion();
$conn = $conexion->getConnection();

// Decodificar el JSON recibido
$data = json_decode($json_data, true);

// Obtener los valores del pedido
$idCliente = isset($data['ID_cliente']) ? $data['ID_cliente'] : null;
$estado = isset($data['Estado']) ? $data['Estado'] : null;
$idUsuario = isset($data['ID_usuario']) ? $data['ID_usuario'] : null;

// Verificar si hay datos vacíos
if (empty($idCliente) || empty($estado) || empty($idUsuario)) {
    $response = [
        'status' => 'error',
        'message' => 'Todos los campos son requeridos.'
    ];
    http_response_code(400);
    echo json_encode($response);
    exit;
}

// Obtener los detalles del pedido
$detalles = isset($data['detalles']) ? $data['detalles'] : [];


// Verificar la conexión
if ($conn->connect_error) {
    $response = [
        'status' => 'error',
        'message' => 'Error en la conexión a la base de datos.'
    ];
    http_response_code(500);
    echo json_encode($response);
    exit;
}

// Insertar el pedido en la tabla "Pedidos"
$insertPedidoQuery = "INSERT INTO Pedidos (ID_cliente, Fecha_pedido, Estado, ID_usuario) 
                      VALUES ('$idCliente', NOW(), '$estado', '$idUsuario')";

if ($conn->query($insertPedidoQuery) === TRUE) {
    // Obtener el ID del pedido insertado
    $pedidoId = $conn->insert_id;

    // Insertar los detalles del pedido en la tabla "Detalles_pedido"
    foreach ($detalles as $detalle) {
        $idProducto = isset($detalle['ID_producto']) ? $detalle['ID_producto'] : null;
        $cantidad = isset($detalle['Cantidad']) ? $detalle['Cantidad'] : null;

        // Verificar si hay datos vacíos en los detalles
        if (empty($idProducto) || empty($cantidad)) {
            $response = [
                'status' => 'error',
                'message' => 'Todos los campos de los detalles son requeridos.'
            ];
            http_response_code(400);
            echo json_encode($response);
            exit;
        }

        $insertDetalleQuery = "INSERT INTO Detalles_pedido (ID_pedido, ID_formula, Cantidad)
                               VALUES ('$pedidoId', '$idProducto', '$cantidad')";
        $conn->query($insertDetalleQuery);
    }

    $response = [
        'status' => 'success',
        'message' => 'El pedido se ha insertado correctamente.'
    ];
    http_response_code(200);
    echo json_encode($response);
} else {
    $response = [
        'status' => 'error',
        'message' => 'Error al insertar el pedido en la base de datos.'
    ];
    http_response_code(500);
    echo json_encode($response);
}

// Cerrar la conexión
$conn->close();
?>
