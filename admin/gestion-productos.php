<?php
include '../conexion.php';
$conn = conectarDB();

// Agregar pastel
if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $cantidad = $_POST['cantidad'];
    $imagen = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
    }

    $stmt = $conn->prepare("INSERT INTO pasteles (nombre, descripcion, precio, categoria, cantidad, imagen) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsis", $nombre, $descripcion, $precio, $categoria, $cantidad, $imagen);
    $stmt->send_long_data(5, $imagen);
    $stmt->execute();
    $stmt->close();
    header("Location: gestion-productos.php");
}

// Eliminar pastel
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM pasteles WHERE id = $id");
    header("Location: gestion-productos.php");
}

// Actualizar pastel
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $cantidad = $_POST['cantidad'];
    $imagen = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
        $stmt = $conn->prepare("UPDATE pasteles SET nombre=?, descripcion=?, precio=?, categoria=?, cantidad=?, imagen=? WHERE id=?");
        $stmt->bind_param("ssdsisi", $nombre, $descripcion, $precio, $categoria, $cantidad, $imagen, $id);
        $stmt->send_long_data(5, $imagen);
    } else {
        $stmt = $conn->prepare("UPDATE pasteles SET nombre=?, descripcion=?, precio=?, categoria=?, cantidad=? WHERE id=?");
        $stmt->bind_param("ssdsii", $nombre, $descripcion, $precio, $categoria, $cantidad, $id);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: gestion-productos.php");
}

// Obtener todos los pasteles
$resultado = $conn->query("SELECT * FROM pasteles");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="../css/style.css"/>
</head>
<body>
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

<div class="container">
    <h2 class="page-title">Agregar Nuevo Pastel</h2>
    <form method="POST" enctype="multipart/form-data" class="product-form">
        <label class="form-label">Nombre:</label>
        <input type="text" name="nombre" class="form-input" required>
        <label class="form-label">Descripción:</label>
        <textarea name="descripcion" rows="3" class="form-textarea"></textarea>
        <label class="form-label">Precio:</label>
        <input type="number" name="precio" step="0.01" class="form-input" required>
        <label class="form-label">Categoría:</label>
        <select name="categoria" class="form-select" required>
            <option value="Pastel">Pastel</option>
            <option value="Cheesecake">Cheesecake</option>
            <option value="Cupcake">Cupcake</option>
            <option value="Promocion">Promoción</option>
        </select>
        <label class="form-label">Cantidad:</label>
        <input type="number" name="cantidad" min="0" class="form-input" required>
        <label class="form-label">Imagen:</label>
        <input type="file" name="imagen" accept="image/*" class="form-file" required>
        <button type="submit" name="agregar" class="btn btn-primary">Agregar</button>
    </form>

    <h2 class="page-title">Lista De Productos</h2>
    <table class="products-table">
        <tr>
            <th class="table-header">Nombre</th>
            <th class="table-header">Descripción</th>
            <th class="table-header">Precio</th>
            <th class="table-header">Categoría</th>
            <th class="table-header">Cantidad</th>
            <th class="table-header">Imagen</th>
            <th class="table-header">Acciones</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr class="table-row">
            <form method="POST" enctype="multipart/form-data">
                <td class="table-cell">
                    <input type="text" name="nombre" value="<?= htmlspecialchars($fila['nombre']) ?>" class="form-input">
                    <input type="hidden" name="id" value="<?= $fila['id'] ?>">
                </td>
                <td class="table-cell"><textarea name="descripcion" rows="2" class="form-textarea"><?= htmlspecialchars($fila['descripcion']) ?></textarea></td>
                <td class="table-cell"><input type="number" step="0.01" name="precio" value="<?= $fila['precio'] ?>" class="form-input"></td>
                <td class="table-cell">
                    <select name="categoria" class="form-select">
                        <option value="Pastel" <?= $fila['categoria'] == 'Pastel' ? 'selected' : '' ?>>Pastel</option>
                        <option value="Cheesecake" <?= $fila['categoria'] == 'Cheesecake' ? 'selected' : '' ?>>Cheesecake</option>
                        <option value="Cupcake" <?= $fila['categoria'] == 'Cupcake' ? 'selected' : '' ?>>Cupcake</option>
                        <option value="Promocion" <?= $fila['categoria'] == 'Promocion' ? 'selected' : '' ?>>Promoción</option>
                    </select>
                </td>
                <td class="table-cell"><input type="number" name="cantidad" min="0" value="<?= $fila['cantidad'] ?>" class="form-input"></td>
                <td class="table-cell">
                    <?php if (!empty($fila['imagen'])): ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($fila['imagen']) ?>" alt="Imagen pastel" class="product-image">
                    <?php else: ?>
                        <span class="no-image">Sin imagen</span>
                    <?php endif; ?>
                    <br>
                    <input type="file" name="imagen" accept="image/*" class="form-file">
                </td>
                <td class="table-cell actions">
                    <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
                    <a href="?eliminar=<?= $fila['id'] ?>" onclick="return confirm('¿Eliminar este pastel?')" class="btn btn-danger">Eliminar</a>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<style>
    .container {
        font-family: 'Open Sans', Arial, sans-serif;
        background-color: #f8f8f8;
    }
    .page-title {
        color: #c7516a;
        text-align: center;
        font-size: 28px;
        padding: 10px;
        margin: 25px 0;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    .product-form {
        margin-bottom: 20px;
        background: #fff;
        padding: 15px;
        border: 1px solid #ddd;
        width: 1200px;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        text-align: left;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    .form-input, .form-textarea, .form-select, .form-file {
        width: 100%;
        padding: 6px;
        margin: 5px 0 10px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    .products-table {
        border-collapse: collapse;
        width: 1200px;
        background: #fff;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 30px;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    
    .table-cell.actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        justify-content: center;
        align-items: center;
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
        vertical-align: top;
        text-align: center;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    .product-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #ddd;
        display: block;
        margin: 0 auto;
    }
    .no-image {
        display: inline-block;
        width: 80px;
        height: 80px;
        background-color: #f0f0f0;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-align: center;
        line-height: 80px;
        font-size: 12px;
        color: #666;
        margin: 0 auto;
        font-family: 'Open Sans', Arial, sans-serif;
    }
    .actions {
        display: flex;
        gap: 10px;
        justify-content: center;
        align-items: center;
    }
    
    .btn {
        padding: 6px 12px;
        color: white;
        text-decoration: none;
        border: none;
        cursor: pointer;
        border-radius: 4px;
        width: 100px;
        font-size: 1rem;
        font-family: 'Open Sans', Arial, sans-serif;
        display: inline-block;
        text-align: center;
        transition: background 0.2s;
    }
    
    .btn-primary {
        background: #e75480;
    }
    
    .btn-primary:hover {
        background: #d13c6a;
    }
    
    .btn-danger {
        background-color: #dc3545;
    }
    
    .btn-danger:hover {
        background-color: #c82333;
    }
    
    .form-textarea {
        height: 150px;
        width: 100%;
        padding: 6px;
        margin: 5px 0 10px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
        resize: vertical;
        font-family: 'Open Sans', Arial, sans-serif;
    }
</style>


  <footer>
        <p>&copy; 2025 Delicias Gourmet. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

<?php cerrarConexion($conn); ?>
