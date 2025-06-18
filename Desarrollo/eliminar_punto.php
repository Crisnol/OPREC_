<?php
require_once 'includes/permisos.php';

// Verificar que sea administrador
verificarPermiso(['Responsable']);

// Conectar a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "proyecto reciclajes");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener el ID del punto a eliminar
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: puntorecoleccion.php');
    exit();
}

// Verificar que el punto existe antes de eliminarlo
$sql = "SELECT nombre FROM puntos_recoleccion WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($punto = mysqli_fetch_assoc($resultado)) {
    // El punto existe, proceder con la eliminación
    $sql_eliminar = "DELETE FROM puntos_recoleccion WHERE id = ?";
    $stmt_eliminar = mysqli_prepare($conexion, $sql_eliminar);
    mysqli_stmt_bind_param($stmt_eliminar, "i", $id);
    
    if (mysqli_stmt_execute($stmt_eliminar)) {
        header('Location: puntorecoleccion.php?mensaje=eliminado');
    } else {
        header('Location: puntorecoleccion.php?error=1');
    }
} else {
    header('Location: puntorecoleccion.php?error=2');
}

mysqli_close($conexion);
exit(); 