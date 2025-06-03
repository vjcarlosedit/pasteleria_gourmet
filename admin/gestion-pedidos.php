<?php
include '../conexion.php';
$conn = conectarDB();

if (!$conn) {
  die("Error al conectar a la base de datos.");
}

$query = "
  SELECT 
    p.id, p.pastel_id, p.nombre_pastel, p.cantidad, p.precio_unitario, p.total,
    p.direccion, p.fecha_compra, p.estatus, p.clave_rastreo,
    u.id_usuario, u.username, u.nombre_completo, u.email, u.telefono
  FROM pedidos p
  LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario
  ORDER BY p.fecha_compra DESC
";

$result = mysqli_query($conn, $query);
if (!$result) {
  die("Error en la consulta: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon"/>
  <link rel="stylesheet" href="../css/style.css"/>
  <title>Gestión de Pedidos</title>
  <style>
    .page-title {
      color: #c7516a;
      text-align: center;
      font-size: 28px;
      margin: 25px 0;
    }
    .products-table {
      border-collapse: collapse;
      width: 1200px;
      margin: auto;
      background: #fff;
    }
    .table-header, .table-cell {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }
    select {
      padding: 5px;
      font-family: 'Open Sans', Arial, sans-serif;
    }
  </style>
</head>
<body class="container">
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

  <h1 class="page-title">Gestión de Pedidos</h1>

  <table class="products-table">
    <thead>
      <tr>
        <th class="table-header">Nombre Pastel</th>
        <th class="table-header">Cantidad</th>
        <th class="table-header">Precio Unitario</th>
        <th class="table-header">Total</th>
        <th class="table-header">Dirección</th>
        <th class="table-header">Fecha Compra</th>
        <th class="table-header">Clave Rastreo</th>
        <th class="table-header">Usuario</th>
        <th class="table-header">Email Usuario</th>
        <th class="table-header">Teléfono Usuario</th>
        <th class="table-header">Estatus</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($pedido = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td class="table-cell"><?= htmlspecialchars($pedido['nombre_pastel']) ?></td>
          <td class="table-cell"><?= (int)$pedido['cantidad'] ?></td>
          <td class="table-cell">$<?= number_format($pedido['precio_unitario'], 2) ?></td>
          <td class="table-cell">$<?= number_format($pedido['total'], 2) ?></td>
          <td class="table-cell"><?= htmlspecialchars($pedido['direccion']) ?></td>
          <td class="table-cell"><?= htmlspecialchars($pedido['fecha_compra']) ?></td>
          <td class="table-cell"><?= htmlspecialchars($pedido['clave_rastreo']) ?></td>
          <td class="table-cell"><?= htmlspecialchars($pedido['nombre_completo'] ?: $pedido['username'] ?: 'Sin usuario') ?></td>
          <td class="table-cell"><?= htmlspecialchars($pedido['email'] ?: '-') ?></td>
          <td class="table-cell"><?= htmlspecialchars($pedido['telefono'] ?: '-') ?></td>
          <td class="table-cell">
            <select class="estatus-select" data-id="<?= $pedido['id'] ?>">
              <?php
              $opciones = ['Pendiente', 'Enviado', 'Cancelado', 'Entregado'];
              foreach ($opciones as $opcion) {
                $selected = $pedido['estatus'] === $opcion ? 'selected' : '';
                echo "<option value='$opcion' $selected>$opcion</option>";
              }
              ?>
            </select>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <footer>
    <p style="text-align: center; margin-top: 40px;">&copy; 2025 Delicias Gourmet. Todos los derechos reservados.</p>
  </footer>

  
  <style>
    .page-title {
      color: #c7516a;
      text-align: center;
      font-size: 28px;
      padding: 10px;
      margin: 25px 0;
      font-family: 'Open Sans', Arial, sans-serif;
    }
    .products-table {
      border-collapse: collapse;
      width: 1200px;
      background: #fff;
      margin: 0 auto 30px auto;
      font-family: 'Open Sans', Arial, sans-serif;
    }
    .table-header {
      padding: 10px;
      border: 1px solid #ccc;
      background-color: #eee;
      font-weight: bold;
    }
    .table-cell {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }
    .btn {
      padding: 5px 10px;
      background-color: #e75480;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .btn:hover {
      background-color: #d13c6a;
    }
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    footer {
      margin-top: auto;
      text-align: center;
      padding: 10px;
    }
  </style>

  <script>
    document.querySelectorAll('.estatus-select').forEach(select => {
      select.addEventListener('change', () => {
        const pedidoId = select.dataset.id;
        const nuevoEstatus = select.value;

        fetch('actualizar_estatus.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: `id=${pedidoId}&estatus=${encodeURIComponent(nuevoEstatus)}`
        })
        .then(response => response.text())
        .then(msg => console.log(msg))
        .catch(err => console.error('Error al actualizar:', err));
      });
    });
  </script>
</body>
</html>
