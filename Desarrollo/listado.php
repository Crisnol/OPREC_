<?php
require_once '../includes/permisos.php';

// Verificar que solo docentes y administradores puedan acceder
verificarPermiso(['Docente', 'Responsable']);

// Conectar a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "proyecto reciclajes");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Consulta SQL base
$sql = "SELECT id, nombre, correo, ROLL FROM usuario WHERE 1=1";

// Si es docente, solo mostrar estudiantes
if ($_SESSION['rol'] === 'Docente') {
    $sql .= " AND ROLL = 'Estudiante'";
}

$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios - Proyecto Reciclaje</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #2E8B57;
            text-align: center;
        }
        .welcome-banner {
            background-color: #e8f5e9;
            color: #2E8B57;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #2E8B57;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .acciones {
            display: flex;
            gap: 10px;
        }
        .boton {
            display: inline-block;
            padding: 8px 12px;
            background-color: #2E8B57;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .boton:hover {
            background-color: #1a5c3a;
        }
        .boton-eliminar {
            background-color: #dc3545;
        }
        .boton-eliminar:hover {
            background-color: #c82333;
        }
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .mensaje {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .mensaje.exito {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .mensaje.error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .user-info {
            text-align: right;
            margin-bottom: 10px;
        }
        .user-info span {
            font-weight: bold;
            color: #2E8B57;
        }
        .role-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: bold;
            display: inline-block;
        }
        .role-estudiante {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        .role-docente {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        .role-responsable {
            background-color: #fce4ec;
            color: #c2185b;
        }
    </style>
</head>
<body>
    <div class="user-info">
        Bienvenido, <span><?php echo htmlspecialchars($_SESSION['nombre']); ?></span> 
        (<?php echo htmlspecialchars($_SESSION['rol']); ?>)
        <a href="cerrar_sesion.php" class="boton">Cerrar Sesión</a>
    </div>

    <h1>Listado de Usuarios</h1>
    
    <?php if ($_SESSION['rol'] === 'Docente'): ?>
        <div class="welcome-banner">
            Como docente, puedes ver y gestionar la lista de estudiantes registrados.
        </div>
    <?php elseif ($_SESSION['rol'] === 'Responsable'): ?>
        <div class="welcome-banner">
            Como administrador, tienes acceso completo a la gestión de usuarios.
        </div>
    <?php endif; ?>

    <div class="header-actions">
        <div>
            <a href="inicio.php" class="boton">Volver al Inicio</a>
            <?php if (esAdministrador()): ?>
                <a href="admin/dashboard.php" class="boton">Panel de Administración</a>
            <?php endif; ?>
        </div>
        <?php if (esAdministrador()): ?>
            <a href="creacion.php" class="boton">Agregar Nuevo Usuario</a>
        <?php endif; ?>
    </div>

    <?php
    // Mostrar mensajes de éxito o error
    if (isset($_GET['mensaje'])) {
        if ($_GET['mensaje'] == 'editado') {
            echo "<div class='mensaje exito'>El usuario se ha actualizado correctamente.</div>";
        } elseif ($_GET['mensaje'] == 'eliminado') {
            echo "<div class='mensaje exito'>El usuario se ha eliminado correctamente.</div>";
        }
    }
    
    if (isset($_GET['error'])) {
        switch ($_GET['error']) {
            case '1':
                echo "<div class='mensaje error'>Error al eliminar el usuario.</div>";
                break;
            case '2':
                echo "<div class='mensaje error'>El usuario no existe.</div>";
                break;
            case '3':
                echo "<div class='mensaje error'>No puedes eliminar tu propia cuenta.</div>";
                break;
            case '4':
                echo "<div class='mensaje error'>No se puede eliminar el último administrador del sistema.</div>";
                break;
        }
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <?php if (esAdministrador()): ?>
                    <th>Acciones</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($fila['id']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($fila['correo']); ?></td>
                    <td>
                        <span class="role-badge role-<?php echo strtolower($fila['ROLL']); ?>">
                            <?php echo htmlspecialchars($fila['ROLL']); ?>
                        </span>
                    </td>
                    <?php if (esAdministrador()): ?>
                        <td class="acciones">
                            <a href="editar_usuario.php?id=<?php echo $fila['id']; ?>" class="boton">Editar</a>
                            <a href="eliminar_usuario.php?id=<?php echo $fila['id']; ?>" 
                               class="boton boton-eliminar" 
                               onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">
                                Eliminar
                            </a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php mysqli_close($conexion); ?>
</body>
</html>
