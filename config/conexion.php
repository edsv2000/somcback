<?php
class Conexion {
    private $host = 'localhost'; // Cambiar por la dirección del servidor de la base de datos
    private $dbname = 'somc'; // Cambiar por el nombre de tu base de datos
    private $usuario = 'root'; // Cambiar por el nombre de usuario de tu base de datos
    private $contrasena = 'root'; // Cambiar por la contraseña de tu base de datos

    protected $conexion;

    public function __construct() {
        // Establecer la conexión a la base de datos utilizando MySQLi
        $this->conexion = new mysqli($this->host, $this->usuario, $this->contrasena, $this->dbname);

        // Verificar si hay errores de conexión
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function getConnection() {
        // Retornar la instancia de la conexión
        return $this->conexion;
    }
}
?>
