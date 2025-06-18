<?php
require_once 'includes/permisos.php';

// Verificar que sea administrador
verificarPermiso(['Responsable']);

// Conectar a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "proyecto reciclajes");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener el ID del punto a editar
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: puntorecoleccion.php');
    exit();
}

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars(trim($_POST["nombre"]));
    $direccion = htmlspecialchars(trim($_POST["direccion"]));
    $horario = htmlspecialchars(trim($_POST["horario"]));
    $materiales = htmlspecialchars(trim($_POST["materiales"]));
    
    if (empty($nombre) || empty($direccion) || empty($horario) || empty($materiales)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        $sql = "UPDATE puntos_recoleccion SET nombre = ?, direccion = ?, horario = ?, materiales = ? WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $direccion, $horario, $materiales, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            header('Location: puntorecoleccion.php?mensaje=editado');
            exit();
        } else {
            $error = "Error al actualizar: " . mysqli_error($conexion);
        }
    }
}

// Obtener datos actuales del punto
$sql = "SELECT * FROM puntos_recoleccion WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($punto = mysqli_fetch_assoc($resultado)) {
    // Los datos se usarán en el formulario
} else {
    header('Location: puntorecoleccion.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Punto de Recolección - Proyecto Reciclaje</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #2E8B57;
            text-align: center;
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
        .formulario input, .formulario textarea {
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
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .header-actions {
            margin-bottom: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Editar Punto de Recolección</h1>

    <div class="header-actions">
        <a href="puntorecoleccion.php" class="boton">Volver a Puntos de Recolección</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="formulario">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id); ?>">
            <label for="nombre">Nombre del Punto:</label>
            <input type="text" name="nombre" required 
                   value="<?php echo htmlspecialchars($punto['nombre']); ?>">

            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" required 
                   value="<?php echo htmlspecialchars($punto['direccion']); ?>">

            <label for="horario">Horario de Atención:</label>
            <input type="text" name="horario" required 
                   value="<?php echo htmlspecialchars($punto['horario']); ?>">

            <label for="materiales">Materiales Aceptados:</label>
            <textarea name="materiales" required rows="3"><?php echo htmlspecialchars($punto['materiales']); ?></textarea>

            <button type="submit" class="boton">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>

<?php mysqli_close($conexion); ?> 