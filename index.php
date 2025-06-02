<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8" />
    <title>Delicias Gourmet</title>
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="css/style.css"/>
</head>
<body>
    <header>
        <nav>
            <div>
                <h2 class="title-inicio">Delicias Gourmet</h2> 
            </div>
        </nav>
    </header>
    <main class="hero">
        <section class="inicio-container">
            <div class="inicio-info-box">
                <p><span class="brand-name">Delicias Gourmet</span> nació del sueño de una joven emprendedora apasionada por los helados y postres artesanales. Lo que comenzó como una idea casera, respaldada por el cariño de familiares y amigos, hoy es un espacio lleno de sabor, dedicación y autenticidad.</p>
                <p>Aquí no solo encontrarás postres únicos y frescos, sino también una comunidad que comparte el amor por lo dulce, lo artesanal y lo hecho con el corazón.</p>
                <p>🍰 Regístrate hoy y sé parte de nuestra familia gourmet:</p>
                <p>🎁 Recibe ofertas exclusivas</p>
                <p>🥇 Sé el primero en conocer nuestras novedades</p>
                <p>👉 ¡Haz clic en Iniciar sesión Registrate y déjate conquistar por nuestros sabores!</p>
                <button class="login-btn" onclick="window.location.href='login.php'">Iniciar Sesión</button>
            </div>
            <div class="inicio-img-box">
                <img src="assets/img/logo.jpg" width=1920 alt="Pastelería Delicias Gourmet">
            </div>
        </section>  
        <img src="assets/img/background.png" alt="Delicias Gourmet Logo" class="background-logo" />
    </main>
    <footer>
        <p>&copy; 2025 Delicias Gourmet. Todos los derechos reservados.</p>
    </footer>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: transparent;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .background-logo {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
    </style>
</body>
</html>

















