<?php
require_once 'includes/permisos.php';

// Verificar que el usuario esté logueado
verificarSesion();

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "proyecto reciclajes");

// Verificar conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener los tipos de residuos
$query = "SELECT DISTINCT tipo FROM residuos ORDER BY tipo";
$tipos_resultado = mysqli_query($conexion, $query);

// Obtener todos los residuos
$query_residuos = "SELECT * FROM residuos ORDER BY tipo, Nombre";
$residuos_resultado = mysqli_query($conexion, $query_residuos);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reciclaje Responsable - PETIC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            line-height: 1.6;
        }

        .header {
            background-color: #2E8B57;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .logo {
            color: white;
            font-size: 2rem;
            font-weight: bold;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .recycling-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .recycling-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .recycling-card h3 {
            color: #2E8B57;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.2rem;
        }

        .recycling-card ul {
            list-style-type: none;
            padding-left: 1rem;
        }

        .recycling-card li {
            margin-bottom: 0.5rem;
            position: relative;
            padding-left: 1.5rem;
        }

        .recycling-card li:before {
            content: "•";
            color: #2E8B57;
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        .boton {
            display: inline-block;
            padding: 10px 15px;
            background-color: #2E8B57;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .boton:hover {
            background-color: #1a5c3a;
        }

        .header-actions {
            margin-bottom: 20px;
        }

        .page-title {
            color: #2E8B57;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        .icon {
            color: #2E8B57;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="inicio.php" class="logo">
            <i class="fas fa-leaf"></i>
            PETIC
        </a>
        <div class="header-actions">
            <a href="inicio.php" class="boton">
                <i class="fas fa-arrow-left"></i> Volver al Inicio
            </a>
        </div>
    </header>

    <main class="main-content">
        <h1 class="page-title">Guía de Reciclaje Responsable</h1>

        <div class="recycling-grid">
            <?php
            // Reiniciar el puntero del resultado
            mysqli_data_seek($tipos_resultado, 0);
            
            // Para cada tipo de residuo
            while ($tipo = mysqli_fetch_assoc($tipos_resultado)) {
                echo '<div class="recycling-card">';
                // Seleccionar el icono apropiado según el tipo
                $icono = match (strtolower(trim($tipo['tipo']))) {
                    'residuos reciclables' => 'fa-recycle',
                    'residuos biodegradables' => 'fa-seedling',
                    'residuos peligrosos' => 'fa-exclamation-triangle',
                    'residuos inorgánicos' => 'fa-trash',
                    'residuos reutilizables / textiles' => 'fa-tshirt',
                    'residuos peligrosos / electrónicos' => 'fa-laptop',
                    default => 'fa-trash-alt'
                };
                
                echo '<h3><i class="fas ' . $icono . ' icon"></i> ' . htmlspecialchars($tipo['tipo']) . '</h3>';
                echo '<ul>';
                
                // Reiniciar el puntero del resultado de residuos
                mysqli_data_seek($residuos_resultado, 0);
                
                // Listar todos los residuos de este tipo
                while ($residuo = mysqli_fetch_assoc($residuos_resultado)) {
                    if (trim($residuo['tipo']) === trim($tipo['tipo'])) {
                        echo '<li>' . htmlspecialchars($residuo['Nombre']) . '</li>';
                    }
                }
                
                echo '</ul>';
                echo '</div>';
            }
            ?>
        </div>
    </main>

    <?php mysqli_close($conexion); ?>
</body>
</html>