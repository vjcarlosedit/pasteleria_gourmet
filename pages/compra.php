<?php
// compra.php
include '../conexion.php';
session_start();

$conn = conectarDB();

if (!$conn) {
    die("Error de conexión a la base de datos.");
}

// Verifica que el usuario haya iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    die("Debes iniciar sesión para realizar una compra.");
}

$usuario_id = $_SESSION['usuario_id'];

// ✅ Inicializamos variables
$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pastel_id = intval($_POST['pastel_id'] ?? 0);
    $nombre_pastel = trim($_POST['nombre'] ?? '');
    $cantidad = intval($_POST['cantidad'] ?? 0);
    $precio_unitario = floatval($_POST['precio_unitario'] ?? 0);
    $direccion = trim($_POST['direccion'] ?? '');
    $clave_rastreo = trim($_POST['clave_rastreo'] ?? '');

    if ($pastel_id > 0 && $nombre_pastel !== '' && $cantidad > 0 && $precio_unitario > 0 && $direccion && $clave_rastreo !== '') {
        $total = $cantidad * $precio_unitario;
        $fecha_compra = date('Y-m-d H:i:s');
        $estatus = 'pendiente';

        // Insertar pedido con id_usuario
        $stmt = $conn->prepare("INSERT INTO pedidos (pastel_id, nombre_pastel, cantidad, precio_unitario, total, direccion, fecha_compra, estatus, clave_rastreo, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isiddssssi", $pastel_id, $nombre_pastel, $cantidad, $precio_unitario, $total, $direccion, $fecha_compra, $estatus, $clave_rastreo, $usuario_id);

        if ($stmt->execute()) {
            $mensaje = "Compra realizada exitosamente.";
        } else {
            $error = "Error al guardar la compra: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Por favor, complete todos los campos correctamente.";
    }
}

if (!isset($mensaje) || !$mensaje) {
    if (!isset($_GET['id'])) {
        die("No se especificó un pastel.");
    }

    $id = intval($_GET['id']);
    $query = "SELECT id, nombre, precio, imagen, cantidad FROM pasteles WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) === 0) {
        die("Pastel no encontrado.");
    }

    $pastel = mysqli_fetch_assoc($result);
    $imagenBase64 = base64_encode($pastel['imagen']);
    $cantidadDisponible = intval($pastel['cantidad']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
          <link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="../css/style.css"/>

  <title>Compra - Delicias Gourmet</title>
      <header>
    <nav>
      <ul class="navbar">
            <li><a href="dashboard.php">Inicio</a></li>
            <li><a href="menu.php">Menú</a></li>
            <li><a href="promociones.php">Promociones</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <li><a href="acerca-de.php">Acerca de</a></li>
      </ul>
    </nav>
  </header>
    <style>
      body {
    font-family: 'Open Sans', Arial, sans-serif;
    /* background-color: #f9f9f9; */
    margin: 0;
    padding: 0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  .page-title {
    color: #c7516a;
    text-align: center;
    font-size: 28px;
    margin: 25px 0;
  }

  .card-titulo {
    margin: 40px 0;
    font-size: 34px;
  }

  .compra-contenedor {
    max-width: 550px;
    background: #fff;
    margin: 0px auto 60px auto; /* 👈 Agregado margen inferior de 40px para separar del footer */
    padding: 30px;
    border-radius: 10px;
    /* box-shadow: 0 2px 8px rgba(0,0,0,0.1); */
  }

  .compra-exitosa-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 60vh;
  }

  .compra-contenedor img {
    width: 300px;
    height: 300px;
    object-fit: cover;
    border-radius: 5px;
    margin: 0 auto 20px auto;
    display: block;
  }

  .compra-contenedor label {
    font-weight: bold;
    display: block;
    margin-top: 15px;
    color: #333;
  }

  .compra-contenedor input,
  .compra-contenedor select,
  .compra-contenedor button {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-family: 'Open Sans', Arial, sans-serif;
  }

  .compra-contenedor button {
    background-color: #c7516a;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 20px;
  }

  .compra-contenedor button:hover {
    background-color: #a83f56;
  }

  .mensaje {
    max-width: 600px;
    margin: 20px auto;
    padding: 15px;
    border-radius: 10px;
    font-family: 'Open Sans', Arial, sans-serif;
    text-align: center;
  }

  .exito {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }

  .error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }

  .total-box {
    background-color: #f1f1f1;
    padding: 10px;
    text-align: center;
    margin: 15px 0;
    border-radius: 5px;
    font-size: 18px;
    font-weight: bold;
    color: #333;
  }

  .info-producto {
    margin-bottom: 15px;
  }

  .info-pago {
    margin-top: 20px;
    margin-bottom: 15px;
  }

  .btn-volver {
    display: inline-block;
    padding: 10px 50px;
    background-color: #c7516a;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease;
  }

  .btn-volver:hover {
    background-color: #a83f56;
  }

  footer {
    margin-top: auto;
  }

    </style>
  </head>
  <body>

  <?php if ($mensaje): ?>
    <div class="compra-exitosa-container">
      <div class="mensaje exito"><?php echo htmlspecialchars($mensaje); ?></div>
      <div class="compra-contenedor" style="text-align:center;">
        <a href="dashboard.php" class="btn-volver">Volver al inicio</a>
      </div>
    </div>
  <?php else: ?>

    <?php if ($error): ?>
      <div class="mensaje error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <h2 class="card-titulo">Resumen de Compra</h2>
    <div class="compra-contenedor">
      <img src="data:image/jpeg;base64,<?php echo $imagenBase64; ?>" alt="Pastel">
      <div class="info-producto">
        <p><strong>Producto:</strong> <?php echo htmlspecialchars($pastel['nombre']); ?></p>
      </div>
      <div class="info-producto">
        <p><strong>Precio unitario:</strong> $<span id="precio"><?php echo number_format($pastel['precio'], 2); ?></span></p>
      </div>

      <form action="compra.php?id=<?php echo $pastel['id']; ?>" method="POST">
        <input type="hidden" name="pastel_id" value="<?php echo $pastel['id']; ?>">
        <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($pastel['nombre']); ?>">
        <input type="hidden" name="precio_unitario" value="<?php echo $pastel['precio']; ?>">

        <label for="cantidad">Cantidad:</label>
        <select name="cantidad" id="cantidad" onchange="actualizarTotal()">
          <?php for ($i = 1; $i <= $cantidadDisponible; $i++): ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php endfor; ?>
        </select>

        <div class="total-box">
          <strong>Total: $<span id="total"></span></strong>
        </div>

        <label for="direccion">Dirección de entrega:</label>
        <input type="text" name="direccion" id="direccion" placeholder="Dirección completa" required>
        
        <!-- <h3>Información de pago</h3> -->
        <div class="info-pago">
          <p style="font-size: 14px; color: #666;">Para completar su compra, transfiera el monto total a la siguiente cuenta:</p>
        </div>
        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center; margin: 10px 0;">
            <p><strong>Número de cuenta:</strong></p>
            <p style="font-size: 18px; font-weight: bold; color: #007bff;">722969070950169327</p>
        </div>
        <p style="font-size: 14px; color: #666;">
          <strong>Nota:</strong> Una vez realizada la transferencia, su pedido será procesado y enviado a la dirección indicada.
        </p>
          
        <label for="clave_rastreo">Clave de rastreo de la transferencia:</label>
        <input type="text" name="clave_rastreo" id="clave_rastreo" placeholder="Ej. 123ABC456" required>
        
        <button type="submit">Confirmar compra</button>
      </form>
    </div>

    <script>
      const precioUnitario = <?php echo $pastel['precio']; ?>;

      function actualizarTotal() {
        const cantidad = document.getElementById('cantidad').value;
        const total = precioUnitario * cantidad;
        document.getElementById('total').textContent = total.toFixed(2);
      }

      actualizarTotal();
    </script>

  <?php endif; ?>

  <footer>
      <p>&copy; 2025 Delicias Gourmet. Todos los derechos reservados.</p>
  </footer>
  </body>
  </html>
