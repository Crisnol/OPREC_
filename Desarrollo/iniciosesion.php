<?php
session_start();

// Si ya hay una sesión activa, redirigir según el rol
if (isset($_SESSION['usuario_id']) && isset($_SESSION['rol'])) {
    header("Location: inicio.php");
    exit();
}

$conexion = mysqli_connect("localhost", "root", "", "proyecto reciclajes");

if(!$conexion) {
    echo '<div class="mensaje error">Error de conexión: ' . mysqli_connect_error() . '</div>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $contraseña = mysqli_real_escape_string($conexion, $_POST['contraseña']);

    $query = "SELECT * FROM usuario WHERE correo = ? AND contraseña = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ss", $correo, $contraseña);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['ROLL'];
        $_SESSION['correo'] = $usuario['correo'];

        // Redirigir a todos los usuarios a inicio.php
        header("Location: inicio.php");
        exit();
    } else {
        $error = "Correo o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inicio de Sesión</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            background-color: #2E8B57;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .btn:hover {
            background-color: #1a5c3a;
        }
        .mensaje {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
        }
        .error {
            background-color: #ffebee;
            color: #c62828;
        }
        .links {
            margin-top: 15px;
            text-align: center;
        }
        .links a {
            color: #2E8B57;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 style="text-align: center;">Inicio de Sesión</h2>
        
        <?php if (isset($error)): ?>
            <div class="mensaje error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <div class="form-group">
                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" required>
            </div>
            <button type="submit" class="btn">Iniciar Sesión</button>
        </form>

        <div class="links">
            <a href="../inicio_petic_2.html">Volver al Inicio</a> | 
            <a href="creacion.php">Registrarse</a>
        </div>
    </div>
    <?php mysqli_close($conexion); ?>
</body>
</html>