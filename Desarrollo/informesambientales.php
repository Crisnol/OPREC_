<?php
// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "proyecto reciclajes");

// Verificar conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Consulta unificada con INNER JOIN
$query_unificado = "
    SELECT 
        i.Comparativo,
        i.Peso,
        i.Modo_implementacion,
        r.Nombre as Responsable_Nombre,
        r.Cargo as Responsable_Cargo,
        res.Nombre as Residuo_Nombre,
        res.tipo as Residuo_Tipo
    FROM `impacto_ambiental` i
    INNER JOIN responsable r ON i.`idimpacto ambientalid` = r.id
    INNER JOIN residuos res ON r.id = res.usuario_idusuario
    LIMIT 10";

$resultado_unificado = mysqli_query($conexion, $query_unificado);

if (!$resultado_unificado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes Ambientales - PETIC</title>
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
            background-color: #004d40;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: white;
            font-size: 2rem;
            font-weight: bold;
            text-decoration: none;
        }

        .main-content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .back-button {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: #004d40;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .back-button:hover {
            background-color: #00695c;
        }

        .table-section {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .table-section h2 {
            color: #004d40;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            min-width: 800px;
        }

        .data-table th,
        .data-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .data-table th {
            background-color: #004d40;
            color: white;
            position: sticky;
            top: 0;
        }

        .data-table tr:hover {
            background-color: #f5f5f5;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .section-divider {
            border-left: 2px solid #ddd;
        }

        .table-description {
            margin: 1rem 0;
            padding: 1rem;
            background-color: #e8f5e9;
            border-radius: 5px;
            border-left: 4px solid #004d40;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="inicio.php" class="logo">
            <i class="fas fa-leaf"></i>
            PETIC
        </a>
    </header>

    <main class="main-content">
        <a href="inicio.php" class="back-button">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        
        <h1>Informes Ambientales Unificados</h1>

        <div class="table-description">
            <p><i class="fas fa-info-circle"></i> Esta tabla muestra una vista unificada del impacto ambiental, los responsables asignados y los tipos de residuos gestionados.</p>
        </div>

        <section class="table-section">
            <h2><i class="fas fa-database"></i> Datos Integrados</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th colspan="3">Impacto Ambiental</th>
                        <th class="section-divider" colspan="2">Responsable</th>
                        <th class="section-divider" colspan="2">Residuo</th>
                    </tr>
                    <tr>
                        <th>Comparativo</th>
                        <th>Peso</th>
                        <th>Modo de Implementación</th>
                        <th class="section-divider">Nombre</th>
                        <th>Cargo</th>
                        <th class="section-divider">Nombre</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($resultado_unificado)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Comparativo']); ?></td>
                        <td><?php echo htmlspecialchars($row['Peso']); ?></td>
                        <td><?php echo htmlspecialchars($row['Modo_implementacion']); ?></td>
                        <td class="section-divider"><?php echo htmlspecialchars($row['Responsable_Nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['Responsable_Cargo']); ?></td>
                        <td class="section-divider"><?php echo htmlspecialchars($row['Residuo_Nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['Residuo_Tipo']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php
    // Cerrar la conexión
    mysqli_close($conexion);
    ?>
</body>
</html>