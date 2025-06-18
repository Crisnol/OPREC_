<?php
session_start();

// Verificar si hay una sesión activa y si el usuario tiene el rol correcto
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], ['Docente', 'Responsable'])) {
    header("Location: iniciosesion.php");
    exit();
}

require_once 'conexion.php';

// Obtener estadísticas
$stats = [];

// Total de usuarios por rol
$query_roles = "SELECT ROLL, COUNT(*) as total FROM usuario GROUP BY ROLL";
$result_roles = $conexion->query($query_roles);
$roles_data = [];
while ($row = $result_roles->fetch_assoc()) {
    $roles_data[$row['ROLL']] = $row['total'];
}

// Total de residuos por tipo
$query_residuos = "SELECT tipo, COUNT(*) as total FROM residuos GROUP BY tipo ORDER BY total DESC LIMIT 5";
$result_residuos = $conexion->query($query_residuos);
$residuos_data = [];
while ($row = $result_residuos->fetch_assoc()) {
    $residuos_data[$row['tipo']] = $row['total'];
}

// Distribución de espacios de recolección
$query_espacios = "SELECT espacio, COUNT(*) as total FROM lugar GROUP BY espacio";
$result_espacios = $conexion->query($query_espacios);
$espacios_data = [];
while ($row = $result_espacios->fetch_assoc()) {
    $espacios_data[$row['espacio']] = $row['total'];
}

// Promedio de impacto ambiental
$query_impacto = "SELECT AVG(CAST(REPLACE(Peso, ',', '.') AS DECIMAL(10,2))) as promedio_peso, 
                         COUNT(*) as total_mediciones 
                  FROM impacto_ambiental 
                  WHERE Peso IS NOT NULL";
$result_impacto = $conexion->query($query_impacto);
$impacto_data = $result_impacto->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETIC - Reportes y Estadísticas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .card {
            background: var(--white);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .card-icon {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .card-title {
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            box-shadow: var(--shadow);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin: 0.5rem 0;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
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

        @media (max-width: 768px) {
            .dashboard-grid {
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

        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-users card-icon"></i>
                <h3>Total Usuarios</h3>
                <div class="stat-number"><?php echo array_sum($roles_data); ?></div>
            </div>
            <div class="stat-card">
                <i class="fas fa-recycle card-icon"></i>
                <h3>Tipos de Residuos</h3>
                <div class="stat-number"><?php echo count($residuos_data); ?></div>
            </div>
            <div class="stat-card">
                <i class="fas fa-map-marker-alt card-icon"></i>
                <h3>Puntos de Recolección</h3>
                <div class="stat-number"><?php echo array_sum($espacios_data); ?></div>
            </div>
            <div class="stat-card">
                <i class="fas fa-chart-line card-icon"></i>
                <h3>Impacto Promedio</h3>
                <div class="stat-number"><?php echo number_format($impacto_data['promedio_peso'], 2); ?></div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-users card-icon"></i>
                    <h2 class="card-title">Distribución de Usuarios por Rol</h2>
                </div>
                <div class="chart-container">
                    <canvas id="rolesChart"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-trash card-icon"></i>
                    <h2 class="card-title">Top 5 Tipos de Residuos</h2>
                </div>
                <div class="chart-container">
                    <canvas id="residuosChart"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-map-marker-alt card-icon"></i>
                    <h2 class="card-title">Distribución de Espacios de Recolección</h2>
                </div>
                <div class="chart-container">
                    <canvas id="espaciosChart"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-bar card-icon"></i>
                    <h2 class="card-title">Estadísticas de Impacto Ambiental</h2>
                </div>
                <div class="chart-container">
                    <canvas id="impactoChart"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Configuración de colores
        const colors = [
            '#2E8B57', '#4CAF50', '#8BC34A', '#CDDC39', '#FFC107',
            '#FF9800', '#FF5722', '#795548', '#9E9E9E', '#607D8B'
        ];

        // Gráfico de Roles
        new Chart(document.getElementById('rolesChart'), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_keys($roles_data)); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_values($roles_data)); ?>,
                    backgroundColor: colors
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Gráfico de Residuos
        new Chart(document.getElementById('residuosChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($residuos_data)); ?>,
                datasets: [{
                    label: 'Cantidad',
                    data: <?php echo json_encode(array_values($residuos_data)); ?>,
                    backgroundColor: colors[0]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de Espacios
        new Chart(document.getElementById('espaciosChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_keys($espacios_data)); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_values($espacios_data)); ?>,
                    backgroundColor: colors
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Datos de ejemplo para el gráfico de impacto ambiental
        const impactoData = {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Impacto Ambiental',
                data: [65, 70, 75, 72, 78, <?php echo $impacto_data['promedio_peso']; ?>],
                borderColor: colors[0],
                fill: false
            }]
        };

        new Chart(document.getElementById('impactoChart'), {
            type: 'line',
            data: impactoData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html> 