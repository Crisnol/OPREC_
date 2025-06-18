<?php
require_once '../includes/permisos.php';

// Verificar que el usuario esté logueado
verificarSesion();

// Conectar a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "proyecto reciclajes");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Procesar el formulario de agregar punto (solo para administradores)
if (esAdministrador() && $_SERVER["REQUEST_METHOD"] == "POST") {
    $botes = (int)$_POST["botes"];
    $espacio = htmlspecialchars(trim($_POST["espacio"]));
    $fecha = htmlspecialchars(trim($_POST["fecha"]));
    
    // Obtener el siguiente ID disponible
    $result = mysqli_query($conexion, "SELECT MAX(idlugar) as max_id FROM lugar");
    $row = mysqli_fetch_assoc($result);
    $next_id = $row['max_id'] + 1;
    
    $sql = "INSERT INTO lugar (idlugar, botes_basura, espacio, fecha) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "iiss", $next_id, $botes, $espacio, $fecha);
    
    if (mysqli_stmt_execute($stmt)) {
        $mensaje = "Punto de recolección agregado exitosamente.";
    } else {
        $error = "Error al agregar el punto de recolección: " . mysqli_error($conexion);
    }
}

// Obtener todos los puntos de recolección
$resultado = mysqli_query($conexion, "SELECT * FROM lugar ORDER BY idlugar");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puntos de Recolección - Proyecto Reciclaje</title>
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
        .grid-puntos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .punto-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
        }
        .formulario {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .formulario label {
            display: block;
            margin-top: 10px;
        }
        .formulario input, .formulario select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .boton {
            display: inline-block;
            padding: 10px 15px;
            background-color: #2E8B57;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        .boton:hover {
            background-color: #1a5c3a;
        }
        .mensaje {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .exito {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .header-actions {
            margin-bottom: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Puntos de Recolección</h1>

    <div class="header-actions">
        <a href="inicio.php" class="boton">Volver al Inicio</a>
    </div>

    <?php
    // Mostrar mensajes de éxito o error
    if (isset($_GET['mensaje'])) {
        if ($_GET['mensaje'] == 'editado') {
            echo "<div class='mensaje exito'>El punto de recolección se ha actualizado correctamente.</div>";
        } elseif ($_GET['mensaje'] == 'eliminado') {
            echo "<div class='mensaje exito'>El punto de recolección se ha eliminado correctamente.</div>";
        }
    }
    
    if (isset($_GET['error'])) {
        if ($_GET['error'] == '1') {
            echo "<div class='mensaje error'>Error al eliminar el punto de recolección.</div>";
        } elseif ($_GET['error'] == '2') {
            echo "<div class='mensaje error'>El punto de recolección no existe.</div>";
        }
    }
    ?>

    <?php if (isset($mensaje)): ?>
        <div class="mensaje exito"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="mensaje error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (esAdministrador()): ?>
        <div class="formulario">
            <h2>Agregar Nuevo Punto de Recolección</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="botes">Número de Botes:</label>
                <input type="number" name="botes" required min="1">

                <label for="espacio">Tamaño del Espacio:</label>
                <select name="espacio" required>
                    <option value="">Seleccione un tamaño...</option>
                    <option value="Pequeño">Pequeño</option>
                    <option value="Mediano">Mediano</option>
                    <option value="Grande">Grande</option>
                </select>

                <label for="fecha">Fecha de Instalación:</label>
                <input type="date" name="fecha" required>

                <button type="submit" class="boton">Agregar Punto</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="grid-puntos">
        <?php while ($punto = mysqli_fetch_assoc($resultado)): ?>
            <div class="punto-card">
                <h3>Punto de Recolección #<?php echo htmlspecialchars($punto['idlugar']); ?></h3>
                <p><strong>Botes de Basura:</strong> <?php echo htmlspecialchars($punto['botes_basura']); ?></p>
                <p><strong>Tamaño del Espacio:</strong> <?php echo htmlspecialchars($punto['espacio']); ?></p>
                <p><strong>Fecha de Instalación:</strong> <?php echo htmlspecialchars($punto['fecha']); ?></p>
                <?php if (esAdministrador()): ?>
                    <a href="editar_punto.php?id=<?php echo $punto['idlugar']; ?>" class="boton">Editar</a>
                    <a href="eliminar_punto.php?id=<?php echo $punto['idlugar']; ?>" 
                       class="boton" style="background-color: #dc3545;"
                       onclick="return confirm('¿Estás seguro de que deseas eliminar este punto de recolección?')">
                        Eliminar
                    </a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <?php mysqli_close($conexion); ?>
</body>
</html>