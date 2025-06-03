<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');     // Usuario
define('DB_PASS', '');         // Contraseña
define('DB_NAME', 'pasteleria_gourmet');

function conectarDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
    return $conn;
}

function cerrarConexion($conn) {
    $conn->close();
}

// Intentar conectar a la base de datos
// try {
//     $conn = conectarDB();
//     echo "Conexión exitosa";
//     cerrarConexion($conn);
// } catch (Exception $e) {
//     echo "Error de conexión: " . $e->getMessage();
// }
?>
