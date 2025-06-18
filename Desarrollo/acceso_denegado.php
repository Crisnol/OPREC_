<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado - Proyecto Reciclaje</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
        }
        .error-container {
            background-color: #ffebee;
            border: 1px solid #ef9a9a;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        h1 {
            color: #c62828;
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
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Acceso Denegado</h1>
        <p>Lo sentimos, no tienes permisos para acceder a esta página.</p>
        <p>Si crees que esto es un error, por favor contacta al administrador.</p>
    </div>
    <p>
        <a href="index.php" class="boton">Volver al Inicio</a>
        <a href="iniciosesion.php" class="boton">Iniciar Sesión</a>
    </p>
</body>
</html> 