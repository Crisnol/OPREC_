<?php
session_start();

// Verificar si hay una sesión activa
if (!isset($_SESSION['usuario_id'])) {
    header("Location: iniciosesion.php");
    exit();
}

$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETIC - Panel Principal</title>
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
            max-width: 1400px;
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

        .user-nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            color: var(--white);
            text-align: right;
        }

        .user-role {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .main-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .card {
            background: var(--white);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .card-icon {
            font-size: 2rem;
            color: var(--primary-color);
        }

        .card-title {
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        .card-content {
            color: var(--text-color);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .btn-secondary {
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .btn-secondary:hover {
            background-color: #e0e0e0;
        }

        .logout-btn {
            background-color: var(--secondary-color);
            color: var(--white);
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #164e31;
        }

        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .user-nav {
                width: 100%;
                justify-content: space-between;
            }
        }

        .image-card {
            grid-column: 1 / -1;
            padding: 0;
            overflow: hidden;
            max-height: 300px;
        }

        .image-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
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
            <nav class="user-nav">
                <div class="user-info">
                    <div>
                        <div><?php echo htmlspecialchars($_SESSION['nombre']); ?></div>
                        <div class="user-role"><?php echo htmlspecialchars($_SESSION['rol']); ?></div>
                    </div>
                </div>
                <a href="cerrar_sesion.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </nav>
        </div>
    </header>

    <main class="main-container">
        <div class="dashboard">
            <!-- Imagen de Conciencia Ambiental -->
            <div class="card image-card">
                <img src="assets/eco-image.jpg" alt="Imagen sobre conciencia ambiental" 
                     onerror="this.src='https://images.pexels.com/photos/3232542/pexels-photo-3232542.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'">
            </div>

            <?php if ($rol === 'Responsable'): ?>
            <!-- Tarjeta de Gestión de Usuarios -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-users card-icon"></i>
                    <h2 class="card-title">Gestión de Usuarios</h2>
                </div>
                <div class="card-content">
                    <p>Administra los usuarios del sistema, asigna roles y gestiona permisos.</p>
                    <div class="action-buttons">
                        <a href="listado.php" class="btn btn-primary">
                            <i class="fas fa-user-cog"></i> Gestionar Usuarios
                        </a>
                    </div>
                </div>
            </div>
            <?php elseif ($rol === 'Docente'): ?>
            <!-- Tarjeta de Visualización de Usuarios para Docentes -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-users card-icon"></i>
                    <h2 class="card-title">Lista de Usuarios</h2>
                </div>
                <div class="card-content">
                    <p>Visualiza la lista de usuarios registrados en el sistema.</p>
                    <div class="action-buttons">
                        <a href="ver_usuarios.php" class="btn btn-primary">
                            <i class="fas fa-list"></i> Ver Usuarios
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Tarjeta de Puntos de Recolección -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-map-marker-alt card-icon"></i>
                    <h2 class="card-title">Puntos de Recolección</h2>
                </div>
                <div class="card-content">
                    <p>Encuentra y gestiona los puntos de recolección de residuos.</p>
                    <div class="action-buttons">
                        <a href="puntorecoleccion.php" class="btn btn-primary">
                            <i class="fas fa-map"></i> Ver Puntos
                        </a>
                        <?php if ($rol === 'Responsable'): ?>
                        
                           
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Reciclaje -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-recycle card-icon"></i>
                    <h2 class="card-title">Reciclaje</h2>
                </div>
                <div class="card-content">
                    <p>Aprende sobre la correcta clasificación de residuos y contribuye al medio ambiente.</p>
                    <div class="action-buttons">
                        <a href="reciclaje.php" class="btn btn-primary">
                            <i class="fas fa-info-circle"></i> Información
                        </a>
                        <?php if ($rol === 'Estudiante'): ?>
                        <a href="mis_aportes.php" class="btn btn-secondary">
                            <i class="fas fa-star"></i> Mis Aportes
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Informes Ambientales -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-line card-icon"></i>
                    <h2 class="card-title">Informes Ambientales</h2>
                </div>
                <div class="card-content">
                    <p>Consulta los informes y estadísticas sobre el impacto ambiental.</p>
                    <div class="action-buttons">
                        <a href="informesambientales.php" class="btn btn-primary">
                            <i class="fas fa-file-alt"></i> Ver Informes
                        </a>
                    </div>
                </div>
            </div>

            <?php if ($rol === 'Responsable' || $rol === 'Docente'): ?>
            <!-- Tarjeta de Reportes -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-bar card-icon"></i>
                    <h2 class="card-title">Reportes y Estadísticas</h2>
                </div>
                <div class="card-content">
                    <p>Visualiza estadísticas y genera reportes sobre la actividad de reciclaje.</p>
                    <div class="action-buttons">
                        <a href="reportes.php" class="btn btn-primary">
                            <i class="fas fa-file-alt"></i> Ver Reportes
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
