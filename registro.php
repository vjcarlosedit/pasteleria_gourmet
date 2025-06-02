<?php
require_once 'conexion.php';

$mensaje = '';

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    
    $conn = conectarDB();
    
    // Verificar si el usuario ya existe
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $mensaje = "El usuario o email ya existe";
    } else {
        // Insertar nuevo usuario
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (username, password, nombre_completo, email, telefono) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $password_hash, $nombre_completo, $email, $telefono);
        
        if ($stmt->execute()) {
            $mensaje = "Usuario registrado exitosamente";
        } else {
            $mensaje = "Error al registrar usuario";
        }
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Delicias Gourmet</title>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2>Registro</h2>
            <form method="POST">
                <input type="text" name="nombre_completo" placeholder="Nombre Completo 👤" required>
                <input type="email" name="email" placeholder="Correo electrónico 📧" required>
                <input type="tel" name="telefono" placeholder="Número de teléfono 📱" required>
                <input type="password" name="password" placeholder="Contraseña 🔒" required>
                <input type="password" name="confirmar_password" placeholder="Confirmar contraseña 🔒" required>
                <!-- <select name="rol" required>
                    <option value="">Seleccionar rol 👑</option>
                    <option value="cliente">Cliente</option>
                    <option value="admin" disabled>Administrador</option>
                </select> -->
                <input type="hidden" name="username" id="username">
                
                
                <a href="login.php" class="texto-registro">¿Ya tienes cuenta? Inicia sesión aquí</a>
                <button type="submit">Registrarse</button>
                <?php if ($mensaje): ?>
                    <p style="color: #a18262; text-align: center; font-size: 14px; margin: 10px 0;"><?php echo $mensaje; ?></p>
                <?php endif; ?>
            </form>
        </div>

        <div class="image-container">
            <img src="assets/img/logo.jpg" alt="Cupcake">
        </div>
    </div>
    <style>

    .texto-registro {
        color: #a18262;
        text-decoration: none;
        font-size: 14px;
        text-align: center;
        margin-bottom: 15px;
        transition: color 0.3s ease;
    }

    .texto-registro:hover {
        color: #f8c9db;
        text-decoration: underline;
    }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff0f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            background-image: url('https://th.bing.com/th/id/OIP.EGWjoBTSX4ZRgVHVQblpfQHaEK?cb=iwp1&rs=1&pid=ImgDetMain.jpg');
            background-size: cover;
            background-position: center;
        }
        
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 80%;
            height: 70%;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
        }

        .login-container {
            width: 50%;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: bounce 2s infinite;
        }
        
        .login-container h2 {
            text-align: center;
            color: #444;
            font-size: 24px;
        }
        
        .login-container h2::before {
            content: '🔒';
            display: block;
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .login-container form {
            display: flex;
            flex-direction: column;
        }
        
        .login-container input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .login-container input::placeholder {
            font-size: 14px;
        }
        
        .login-container input:invalid {
            animation: shake 0.5s;
            border-color: #a18262;
        }

        .login-container button {
            padding: 10px;
            background-color: #a18262;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .login-container button:hover {
            background-color: #f8c9db;
            transform: scale(1.05);
        }

        .login-container button::after {
            content: ' 🍰';
        }

        .image-container {
            width: 50%;
            background-color: #f8c9db;
            height: 100%;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            animation: bounce 2s infinite;
        }
        
        .image-container img {
            width: 80%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .alert {
            background-color: #f8c9db;
            font-size: 14px;
            text-align: center;
            padding: 8px;
            margin-top: 10px;
            border-radius: 4px;
            display: none;
        }
        </style>        

    <script>
        document.querySelector('input[name="nombre_completo"]').addEventListener('input', function() {
            const nombreCompleto = this.value.trim();
            const palabras = nombreCompleto.split(' ');
            if (palabras.length >= 2) {
                const username = (palabras[0] + palabras[palabras.length - 1]).toLowerCase().replace(/[^a-z0-9]/g, '');
                document.getElementById('username').value = username;
            }
        });
    </script>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            if (username === 'admin' && password === '1234') {
                window.location.href = 'menu.html';
            } else {
                const alert = document.getElementById('alert');
                alert.style.display = 'block';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 3000);
            }
        });
    </script>





</body>
</html>
