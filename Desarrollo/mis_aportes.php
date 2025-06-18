<?php
session_start();

// Verificar si hay una sesión activa y si el usuario es estudiante
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Estudiante') {
    header("Location: iniciosesion.php");
    exit();
}

require_once 'conexion.php';

// Procesar el formulario de nuevo aporte
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'registrar') {
        $tipo_residuo = $conexion->real_escape_string($_POST['tipo_residuo']);
        $lugar_id = $conexion->real_escape_string($_POST['lugar_id']);
        $usuario_id = $_SESSION['usuario_id'];

        // Obtener el siguiente ID disponible
        $result = $conexion->query("SELECT MAX(idresiduos) as max_id FROM residuos");
        $row = $result->fetch_assoc();
        $nuevo_id = ($row['max_id'] ?? 0) + 1;

        // Insertar el nuevo aporte
        $sql = "INSERT INTO residuos (idresiduos, Nombre, tipo, usuario_idusuario, lugar_idlugar) 
                VALUES (?, 'Aporte de estudiante', ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("isii", $nuevo_id, $tipo_residuo, $usuario_id, $lugar_id);
        
        if ($stmt->execute()) {
            $mensaje = "Aporte registrado exitosamente";
            $tipo_mensaje = "exito";
        } else {
            $mensaje = "Error al registrar el aporte: " . $stmt->error;
            $tipo_mensaje = "error";
        }
    }
}

// Obtener los aportes del estudiante
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT r.idresiduos, r.tipo, l.espacio as nombre_lugar 
        FROM residuos r 
        LEFT JOIN lugar l ON r.lugar_idlugar = l.idlugar 
        WHERE r.usuario_idusuario = ? 
        ORDER BY r.idresiduos DESC";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

// Obtener lugares de recolección para el formulario
$lugares = $conexion->query("SELECT idlugar, espacio FROM lugar ORDER BY espacio");

// Obtener tipos de residuos únicos
$tipos_residuos = $conexion->query("SELECT DISTINCT tipo FROM residuos WHERE tipo != '' ORDER BY tipo");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETIC - Mis Aportes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #2E8B57;
            --secondary-color: #1a5c3a;
            --background-color: #f4f4f4;
            --text-color: #333;
            --white: #ffffff;
            --shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .header {
            background-color: var(--primary-color);
            padding: 1rem 2rem;
            box-shadow: var(--shadow);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: var(--white);
            font-size: 1.8rem;
            font-weight: bold;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .main-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .card {
            background: var(--white);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--primary-color);
            color: var(--white);
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: var(--primary-color);
            color: var(--white);
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .back-btn:hover {
            background-color: var(--secondary-color);
        }

        .mensaje {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .mensaje.exito {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .mensaje.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="nav-container">
            <a href="inicio.php" class="logo">
                <i class="fas fa-leaf"></i>
                PETIC
            </a>
        </div>
    </header>

    <main class="main-container">
        <a href="inicio.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Volver al inicio
        </a>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2><i class="fas fa-plus-circle"></i> Registrar Nuevo Aporte</h2>
            <form method="POST" action="">
                <input type="hidden" name="accion" value="registrar">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="tipo_residuo">Tipo de Residuo:</label>
                        <select name="tipo_residuo" id="tipo_residuo" class="form-control" required>
                            <option value="">Seleccione un tipo</option>
                            <?php while ($tipo = $tipos_residuos->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($tipo['tipo']); ?>">
                                    <?php echo htmlspecialchars($tipo['tipo']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="lugar_id">Lugar de Entrega:</label>
                        <select name="lugar_id" id="lugar_id" class="form-control" required>
                            <option value="">Seleccione un lugar</option>
                            <?php while ($lugar = $lugares->fetch_assoc()): ?>
                                <option value="<?php echo $lugar['idlugar']; ?>">
                                    <?php echo htmlspecialchars($lugar['espacio']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Registrar Aporte
                </button>
            </form>
        </div>

        <div class="card">
            <h2><i class="fas fa-history"></i> Historial de Aportes</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo de Residuo</th>
                            <th>Lugar de Entrega</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fila['idresiduos']); ?></td>
                                <td><?php echo htmlspecialchars($fila['tipo']); ?></td>
                                <td><?php echo htmlspecialchars($fila['nombre_lugar'] ?? 'No especificado'); ?></td>
                                <td>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Registrado
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
<?php
$conexion->close();
?> 