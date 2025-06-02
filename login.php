<?php
require_once 'conexion.php';

session_start();

$mensaje = '';
if ($_POST) {
    $correo = $_POST['usuario'];
    $password = $_POST['password'];
    
    // Validación especial para administrador
    if ($correo === 'admin@gmail.com' && $password === 'admin') {
        $_SESSION['usuario_id'] = 0; // ID especial para admin
        $_SESSION['usuario'] = 'Administrador';
        $_SESSION['es_admin'] = true;
        header("Location: administrador.php");
        exit();
    }
    
    $conn = conectarDB();
    
    // Preparar consulta para evitar inyección SQL
    $stmt = $conn->prepare("SELECT id_usuario, username, email, password FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        
        // Verificar contraseña (comprobar si está hasheada o es texto plano)
        if (password_verify($password, $fila['password']) || $password === $fila['password']) {
            $_SESSION['usuario_id'] = $fila['id_usuario'];
            $_SESSION['usuario'] = $fila['username'];
            header("Location: pages/dashboard.php");
            exit();
        } else {
            $mensaje = "Credenciales incorrectas";
        }
    } else {
        $mensaje = "Correo no encontrado";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión - Delicias Gourmet</title>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2>Inicio de sesión</h2>
            <form method="POST">
                <input type="email" name="usuario" placeholder="Correo electrónico 📧" required>
                <input type="password" name="password" placeholder="Contraseña 🔒" required>
                <a href="registro.php" class="texto-registro">¿No tienes cuenta? Regístrate aquí</a>
                <button type="submit">Ingresar</button>
            </form>
            <?php if ($mensaje): ?>
                <p style="color: red; text-align: center;"><?php echo $mensaje; ?></p>
            <?php endif; ?>
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
        document.getElementById('login-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            if (username === 'admin' && password === 'admin') {
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
