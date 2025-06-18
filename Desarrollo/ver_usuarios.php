<?php
session_start();

// Verificar si hay una sesión activa y si el usuario es un docente
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Docente') {
    header("Location: iniciosesion.php");
    exit();
}

require_once 'conexion.php';

// Obtener la lista de usuarios con una consulta más específica
$sql = "SELECT id, nombre, correo, ROLL as rol FROM usuario ORDER BY nombre";
$resultado = $conexion->query($sql);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETIC - Lista de Usuarios</title>
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
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: var(--secondary-color);
        }

        .role-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: bold;
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

        @media (max-width: 768px) {
            th, td {
                padding: 0.5rem;
            }
            
            .role-badge {
                padding: 0.15rem 0.5rem;
                font-size: 0.8em;
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

        <div class="card">
            <h2><i class="fas fa-users"></i> Lista de Usuarios</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fila['id']); ?></td>
                                <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($fila['correo']); ?></td>
                                <td>
                                    <span class="role-badge role-<?php echo strtolower($fila['rol']); ?>">
                                        <?php echo htmlspecialchars($fila['rol']); ?>
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
// Cerrar la conexión
$conexion->close();
?> 