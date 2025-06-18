<?php
// Parámetros de conexión a la base de datos
$servidor = "localhost";  // El servidor es local según los archivos SQL
$usuario = "root";       // Usuario por defecto en XAMPP
$password = "";          // Contraseña por defecto en XAMPP
$base_datos = "proyecto reciclajes";  // Nombre de la base de datos según los archivos SQL

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer el conjunto de caracteres
$conexion->set_charset("utf8mb4");
?> 