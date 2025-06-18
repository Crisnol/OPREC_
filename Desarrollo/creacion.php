<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - Proyecto Reciclaje</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #2E8B57;
            text-align: center;
        }
        form {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        input[type="submit"] {
            background: #2E8B57;
            color: white;
            border: 0;
            padding: 10px;
            margin-top: 20px;
            cursor: pointer;
        }
        .mensaje-exito {
            background: #DFF0D8;
            color: #3C763D;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
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
        ul {
            list-style-type: disc;
            margin-left: 20px;
            margin-bottom: 15px;
        }
        .mensaje-exito ul {
            background-color: #ffffff;
            padding: 15px 30px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php
    // Verificar si el formulario fue enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener y limpiar los datos del formulario
        $nombre = htmlspecialchars(trim($_POST["Nombre"]));
        $correo = htmlspecialchars(trim($_POST["correo"]));
        $contraseña = htmlspecialchars(trim($_POST["contraseña"]));
        $rol = htmlspecialchars(trim($_POST["ROLL"]));
        
        // Validar campos obligatorios
        if (empty($nombre) || empty($correo) || empty($contraseña) || empty($rol)) {
            die("<div class='error'>Todos los campos son obligatorios.</div>");
        }
        
        // Validar formato de correo
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            die("<div class='error'>El formato del correo electrónico no es válido.</div>");
        }
        
        // Conectar a la base de datos
        $conexion = mysqli_connect("localhost", "root", "", "proyecto reciclajes");
        
        // Verificar conexión
        if (!$conexion) {
            die("<div class='error'>ERROR: No se pudo conectar a MySQL. " . mysqli_connect_error() . "</div>");
        }
        
        // Preparar la consulta SQL (mejor usar sentencias preparadas para seguridad)
        $sql = "INSERT INTO usuario (nombre, correo, contraseña, ROLL) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        
        if ($stmt) {
            // Vincular parámetros (más seguro que concatenar variables)
            mysqli_stmt_bind_param($stmt, "ssss", $nombre, $correo, $contraseña, $rol);
            
            // Ejecutar la consulta
            if (mysqli_stmt_execute($stmt)) {
                // Iniciar sesión y guardar datos importantes
                session_start();
                $_SESSION['usuario_id'] = mysqli_insert_id($conexion);
                $_SESSION['nombre'] = $nombre;
                $_SESSION['rol'] = $rol;

                echo "<div class='mensaje-exito'>";
                echo "<h1>¡Registro exitoso!</h1>";
                echo "<p><strong>Nombre:</strong> $nombre</p>";
                echo "<p><strong>Correo:</strong> $correo</p>";
                echo "<p><strong>Rol:</strong> $rol</p>";
                echo "<p>Tu registro se ha completado correctamente.</p>";
                
                // Enlaces específicos según el rol
                if ($rol == "Estudiante") {
                    echo "<p>Como estudiante, puedes:</p>";
                    echo "<ul>";
                    echo "<li>Ver los puntos de recolección</li>";
                    echo "<li>Registrar tus aportes de reciclaje</li>";
                    echo "<li>Ver tu historial personal</li>";
                    echo "</ul>";
                    echo "<p><a href='iniciosesion.php' class='boton'>Iniciar sesión</a></p>";
                } elseif ($rol == "Docente") {
                    echo "<p>Como docente, tienes acceso a:</p>";
                    echo "<ul>";
                    echo "<li>Ver y gestionar listados de estudiantes</li>";
                    echo "<li>Ver estadísticas de reciclaje</li>";
                    echo "<li>Gestionar actividades de reciclaje</li>";
                    echo "</ul>";
                    echo "<p><a href='iniciosesion.php' class='boton'>Iniciar sesión</a></p>";
                } else {
                    echo "<p>Como administrador, tienes acceso completo:</p>";
                    echo "<ul>";
                    echo "<li>Gestión completa de usuarios</li>";
                    echo "<li>Administración de puntos de recolección</li>";
                    echo "<li>Gestión de materiales reciclables</li>";
                    echo "<li>Ver reportes y estadísticas completas</li>";
                    echo "</ul>";
                    echo "<p><a href='iniciosesion.php' class='boton'>Iniciar sesión</a></p>";
                }
                echo "</div>";
            } else {
                echo "<div class='error'>Error al registrar: " . mysqli_error($conexion) . "</div>";
            }
            
            // Cerrar la sentencia
            mysqli_stmt_close($stmt);
        } else {
            echo "<div class='error'>Error al preparar la consulta: " . mysqli_error($conexion) . "</div>";
        }
        
        // Cerrar la conexión
        mysqli_close($conexion);
    } else {
        // Mostrar el formulario si no se ha enviado
    ?>
    <h1>Registro de Usuario</h1>
    <p>Por favor completa el siguiente formulario para registrarte en nuestro sistema.</p>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="Nombre">Nombre completo:</label>
        <input type="text" name="Nombre" required placeholder="Ej: Juan Pérez">
        
        <label for="correo">Correo electrónico:</label>
        <input type="email" name="correo" required placeholder="Ej: usuario@ejemplo.com">
        
        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" required placeholder="Mínimo 8 caracteres">
        
        <label for="ROLL">Tipo de usuario:</label>
        <select name="ROLL" required>
            <option value="">Seleccione un rol...</option>
            <option value="Estudiante">Estudiante</option>
            <option value="Docente">Docente</option>
            <option value="Responsable">Administrador</option>
        </select>
        
        <input type="submit" value="Registrarse">
    </form>
    
    <p>¿Ya tienes una cuenta? <a href="iniciosesion.php">Inicia sesión aquí</a></p>
    <?php } ?>
</body>
</html>