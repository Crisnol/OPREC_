<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: iniciosesion.php");
    exit();
}

// Procesar la actualización del perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_perfil'])) {
    // Aquí irá la lógica de actualización en la base de datos
    $mensaje = "Perfil actualizado correctamente";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mi Perfil - PETIC</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #004d40;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
        }
        .role-badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: #004d40;
            color: white;
            border-radius: 15px;
            font-size: 0.9em;
            margin-left: 10px;
        }
        .btn {
            background-color: #004d40;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #00695c;
        }
        .profile-form {
            display: grid;
            gap: 20px;
            margin-top: 20px;
        }
        .form-group {
            display: grid;
            gap: 8px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-group input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .tabs {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }
        .tab.active {
            border-bottom-color: #004d40;
            color: #004d40;
        }
        .mensaje {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
        }
        .back-button {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: #004d40;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .back-button:hover {
            background-color: #00695c;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="listado.php" class="back-button">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        
        <div class="header">
            <div class="user-info">
                <div class="profile-picture">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h1><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                        <span class="role-badge"><?php echo htmlspecialchars($_SESSION['usuario_roll']); ?></span>
                    </h1>
                </div>
            </div>
            <a href="iniciosesion.php" class="btn">Cerrar Sesión</a>
        </div>

        <?php if(isset($mensaje)): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <div class="tabs">
            <div class="tab active">Información Personal</div>
            <div class="tab">Configuración</div>
        </div>
        
        <form class="profile-form" method="POST">
            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['usuario_email'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Rol</label>
                <input type="text" value="<?php echo htmlspecialchars($_SESSION['usuario_roll']); ?>" disabled>
            </div>

            <div class="form-group">
                <label>Nueva Contraseña (dejar en blanco para mantener la actual)</label>
                <input type="password" name="nueva_password">
            </div>

            <button type="submit" name="actualizar_perfil" class="btn">Guardar Cambios</button>
        </form>
    </div>

    <script>
        // Script para las pestañas
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html> 