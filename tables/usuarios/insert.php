<?php

require_once '../../config/conexion.php';

// Obtener los datos del usuario en formato JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Verificar si se recibieron los datos requeridos
if (
    isset($data['nombre_usuario']) &&
    isset($data['contrasena']) &&
    isset($data['puesto']) &&
    isset($data['correo_electronico']) &&
    isset($data['numero_telefono'])
) {
    // Crear una instancia de la clase de conexión
    $conexion = new Conexion();
    $conn = $conexion->getConnection();
    
    // Verificar si la conexión fue exitosa
    if ($conn->connect_error) {
        die("Error en la conexión a la base de datos: " . $conn->connect_error);
    }
    
    // Preparar los datos del usuario
    $nombre_usuario = $data['nombre_usuario'];
    $contrasena = password_hash($data['contrasena'], PASSWORD_DEFAULT);
    $puesto = $data['puesto'];
    $correo_electronico = $data['correo_electronico'];
    $numero_telefono = $data['numero_telefono'];

    //Encriptacion
    $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    
    // Preparar la consulta SQL para insertar el usuario
    $sql = "INSERT INTO Usuarios (Nombre_usuario, Contrasena, Puesto, Correo_electronico, Numero_telefono, Fecha_creacion, Ultimo_inicio_sesion)
            VALUES ('$nombre_usuario', '$contrasena', '$puesto', '$correo_electronico', '$numero_telefono', CURDATE(), NOW())";
    
    // Ejecutar la consulta SQL
    if ($conn->query($sql) === TRUE) {
        $response = array('success' => true, 'message' => 'Usuario registrado exitosamente');
        echo json_encode($response);
    } else {
        $response = array('success' => false, 'message' => 'Error al registrar el usuario: ' . $conn->error);
        echo json_encode($response);
    }
    
    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    $response = array('success' => false, 'message' => 'Faltan datos requeridos');
    echo json_encode($response);
}

?>
