<?php

require_once '../../config/conexion.php';

// Obtener los datos del usuario en formato JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Verificar si se recibieron los datos requeridos
if (isset($data['correo_electronico']) && isset($data['contrasena'])) {
    // Crear una instancia de la clase de conexión
    $conexion = new Conexion();
    $conn = $conexion->getConnection();
    
    // Verificar si la conexión fue exitosa
    if ($conn->connect_error) {
        die("Error en la conexión a la base de datos: " . $conn->connect_error);
    }
    
    // Obtener los datos del correo electrónico y la contraseña
    $correo_electronico = $data['correo_electronico'];
    $contrasena = $data['contrasena'];
    
    // Consultar el usuario en la base de datos
    $sql = "SELECT * FROM Usuarios WHERE Correo_electronico = '$correo_electronico'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($contrasena, $row['Contrasena'])) {
            // Contraseña válida
            $response = array('success' => true, 'message' => 'Inicio de sesión exitoso');
            echo json_encode($response);
        } else {
            // Contraseña inválida
            $response = array('success' => false, 'message' => 'Contraseña incorrecta');
            echo json_encode($response);
        }
    } else {
        // No se encontró el usuario
        $response = array('success' => false, 'message' => 'Usuario no encontrado');
        echo json_encode($response);
    }
    
    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    $response = array('success' => false, 'message' => 'Faltan datos requeridos');
    echo json_encode($response);
}

?>
