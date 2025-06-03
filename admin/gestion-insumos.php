<?php
include '../conexion.php';
$conn = conectarDB();

// Inicializar mensajes
$mensaje = '';
$error = '';

// Validación y guardado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $cantidad = floatval($_POST['cantidad'] ?? -1); // por defecto -1 si vacío
    $unidad = trim($_POST['unidad'] ?? '');

    if ($nombre !== '' && $unidad !== '' && $cantidad >= 0) {
        $stmt = $conn->prepare("INSERT INTO insumos (nombre, cantidad, unidad) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $nombre, $cantidad, $unidad);

        if ($stmt->execute()) {
            $mensaje = "Insumo guardado correctamente.";
        } else {
            $error = "Error al guardar el insumo: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Todos los campos son obligatorios y la cantidad no puede ser negativa.";
    }
}

// Obtener lista de insumos
$result = mysqli_query($conn, "SELECT nombre, cantidad, unidad FROM insumos ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
        <link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="../css/style.css"/>

    <title>Gestión de Insumos</title>
    <header>
    <nav>
      <ul class="navbar">
        <li><a href="../administrador.php">Dashboard</a></li>
        <li><a href="gestion-productos.php">Gestionar Productos</a></li>
        <li><a href="gestion-pedidos.php">Gestionar Pedidos</a></li>
        <li><a href="gestion-insumos.php">Gestionar Insumos</a></li>
      </ul>
    </nav>
</header>

</head>
<body>

<div class="insumos-container">
    <h2 class="page-title">Gestión de Insumos</h2>

    <?php if ($mensaje): ?>
        <div class="mensaje exito"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php elseif ($error): ?>
        <div class="mensaje error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form class="form-insumos" method="POST">
        <label for="nombre">Nombre del insumo:</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" step="0.01" min="0" required>

        <label for="unidad">Unidad (kg, litros, piezas...):</label>
        <input type="text" name="unidad" id="unidad" required>

        <button type="submit">Guardar insumo</button>
    </form>

    <h3 class=page-title>Insumos registrados</h3>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Unidad</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                <td><?php echo htmlspecialchars($fila['cantidad']); ?></td>
                <td><?php echo htmlspecialchars($fila['unidad']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<style>
    .insumos-container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    
    .form-insumos {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        font-family: 'Open Sans', Arial, sans-serif;
    }
    
    .form-insumos input, 
    .form-insumos select, 
    .form-insumos button {
        width: 100%;
        margin-top: 10px;
        padding: 10px;
        box-sizing: border-box;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    
    .mensaje {
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
        text-align: center;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    .exito { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin: 20px 0;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        text-align: left;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    th {
        background-color: #f4f4f4;
    }

    .page-title {
        color: #c7516a;
        text-align: center;
        font-size: 28px;
        padding: 10px;
        margin: 25px 0;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    .insumos-table {
        border-collapse: collapse;
        width: 100%;
        background: #fff;
        margin: 20px auto 30px auto;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    .table-header {
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #eee;
        font-weight: bold;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    .table-cell {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: center;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    .btn {
        padding: 5px 10px;
        background-color: #e75480;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    .btn:hover {
        background-color: #d13c6a;
    }
</style>
  <footer>
    <p>&copy; 2025 Delicias Gourmet. Todos los derechos reservados.</p>
  </footer>
</body>
</html>
