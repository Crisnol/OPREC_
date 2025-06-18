<?php
require_once '../includes/permisos.php';

// Verificar que sea administrador
verificarPermiso(['Responsable']);

// Conectar a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "proyecto reciclajes");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener estadísticas básicas
$stats = [
    'usuarios' => mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as total FROM usuario"))['total'],
    'estudiantes' => mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as total FROM usuario WHERE ROLL = 'Estudiante'"))['total'],
    'docentes' => mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as total FROM usuario WHERE ROLL = 'Docente'"))['total'],
    'puntos' => mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as total FROM lugar"))['total']
];

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Proyecto Reciclaje</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: #2E8B57;
            text-align: center;
            margin-bottom: 30px;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 2em;
            color: #2E8B57;
            font-weight: bold;
            margin: 10px 0;
        }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .action-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .action-card h3 {
            color: #2E8B57;
            margin-top: 0;
        }
        .boton {
            display: inline-block;
            padding: 10px 15px;
            background-color: #2E8B57;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .boton:hover {
            background-color: #1a5c3a;
        }
        .header-actions {
            text-align: right;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-actions">
            <a href="../index.php" class="boton">Volver al Inicio</a>
        </div>

        <h1>Panel de Administración</h1>

        <div class="dashboard-grid">
            <div class="stat-card">
                <h3>Total Usuarios</h3>
                <div class="stat-number"><?php echo $stats['usuarios']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Estudiantes</h3>
                <div class="stat-number"><?php echo $stats['estudiantes']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Docentes</h3>
                <div class="stat-number"><?php echo $stats['docentes']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Puntos de Recolección</h3>
                <div class="stat-number"><?php echo $stats['puntos']; ?></div>
            </div>
        </div>

        <div class="actions-grid">
            <div class="action-card">
                <h3>Gestión de Usuarios</h3>
                <p>Administra los usuarios del sistema.</p>
                <a href="../listado.php" class="boton">Ver Usuarios</a>
                <a href="../creacion.php" class="boton">Agregar Usuario</a>
            </div>
            <div class="action-card">
                <h3>Puntos de Recolección</h3>
                <p>Gestiona los puntos de recolección de reciclaje.</p>
                <a href="../puntorecoleccion.php" class="boton">Ver Puntos</a>
            </div>
            <div class="action-card">
                <h3>Reportes y Estadísticas</h3>
                <p>Visualiza estadísticas y genera reportes.</p>
                <a href="../reportes.php" class="boton">Ver Reportes</a>
            </div>
            <div class="action-card">
                <h3>Configuración del Sistema</h3>
                <p>Ajusta la configuración general del sistema.</p>
                <a href="configuracion.php" class="boton">Configuración</a>
            </div>
        </div>
    </div>
</body>
</html> 