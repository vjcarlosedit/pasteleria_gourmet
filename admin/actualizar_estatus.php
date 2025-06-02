<?php
include '../conexion.php';
$conn = conectarDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id']);
  $estatus = mysqli_real_escape_string($conn, $_POST['estatus']);

  $valores_validos = ['Pendiente', 'Enviado', 'Cancelado', 'Entregado'];
  if (!in_array($estatus, $valores_validos)) {
    http_response_code(400);
    echo "Valor de estatus inválido.";
    exit;
  }

  $query = "UPDATE pedidos SET estatus = '$estatus' WHERE id = $id";
  if (mysqli_query($conn, $query)) {
    echo "Estatus actualizado correctamente.";
  } else {
    http_response_code(500);
    echo "Error al actualizar: " . mysqli_error($conn);
  }
} else {
  http_response_code(405);
  echo "Método no permitido.";
}
