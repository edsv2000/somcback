<?php
require_once '../../config/conexion.php';

// Crear una instancia de la clase de conexión
$conexion = new Conexion();
$conn = $conexion->getConnection();

// Consultar los registros de la tabla "Pedidos" con sus detalles y nombre del cliente
$sql_pedidos = "SELECT P.*, D.*, C.Nombre_cliente
                FROM Pedidos P
                INNER JOIN Detalles_pedido D ON P.ID_pedido = D.ID_pedido
                INNER JOIN Clientes C ON P.ID_cliente = C.ID_cliente";
                
$result_pedidos = $conn->query($sql_pedidos);
$pedidos = [];
if ($result_pedidos->num_rows > 0) {
    while ($row = $result_pedidos->fetch_assoc()) {
        $pedido_id = $row['ID_pedido'];
        if (!isset($pedidos[$pedido_id])) {
            // Crear una entrada para el pedido si no existe
            $pedidos[$pedido_id] = [
                "ID_pedido" => $row['ID_pedido'],
                "ID_cliente" => $row['ID_cliente'],
                "Nombre_cliente" => $row['Nombre_cliente'],
                "Fecha_pedido" => $row['Fecha_pedido'],
                "Estado" => $row['Estado'],
                "ID_usuario" => $row['ID_usuario'],
                "detalles" => []
            ];
        }
        // Agregar los detalles del pedido
        $pedidos[$pedido_id]['detalles'][] = [
            "ID_detalle" => $row['ID_detalle'],
            "ID_formula" => $row['ID_formula'],
            "Cantidad" => $row['Cantidad']
        ];
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Convertir los datos a formato JSON y enviar la respuesta
$datos = array_values($pedidos); // Reorganizar el arreglo para eliminar las claves de ID_pedido
header('Content-Type: application/json');
echo json_encode($datos, JSON_PRETTY_PRINT);

