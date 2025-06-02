<?php
// pasteles.php

include '../conexion.php';  // Ajusta la ruta si es necesario

$conn = conectarDB();

if (!$conn) {
    die("Error de conexión a la base de datos.");
}

$query = "SELECT id, nombre, descripcion, precio, imagen, cantidad FROM pasteles WHERE categoria = 'Promocion'";
$resultado = mysqli_query($conn, $query);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pasteles - Delicias Gourmet</title>
    <link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
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

<main>
    <section class="card-section">
        <h2 class="card-titulo">Promociones</h2>
        <div class="card-tarjetas">
            <?php
            if (mysqli_num_rows($resultado) > 0) {
                while ($row = mysqli_fetch_assoc($resultado)) {
                    // Codificar la imagen BLOB a base64 para mostrarla inline
                    $imagenBase64 = base64_encode($row['imagen']);
                    ?>
                    <div class="tarjeta">
                        <img src="data:image/jpeg;base64,<?php echo $imagenBase64; ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>" class="card-img" />
                        <div class="card-info">
                          <h3 class="card-nombre"><?php echo htmlspecialchars($row['nombre']); ?></h3>
                          <p class="card-desc"><?php echo htmlspecialchars($row['descripcion']); ?></p>
                          <!-- <p class="card-desc">Cantidad: <?php echo intval($row['cantidad']); ?></p>
                          <p class="card-desc">Precio: $<?php echo number_format($row['precio'], 2); ?></p> -->
                            <a href="compra.php?id=<?php echo $row['id']; ?>" class="card-link">
                            <button class="card-btn">Comprar</button>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
              echo "<p class='no-disponible'>No hay promociones disponibles.</p>";
            }

            mysqli_free_result($resultado);
            mysqli_close($conn);
            ?>
        </div>
    </section>
</main>
<footer>
    <p>&copy; 2025 Delicias Gourmet. Todos los derechos reservados.</p>
</footer>
</body>
</html>
