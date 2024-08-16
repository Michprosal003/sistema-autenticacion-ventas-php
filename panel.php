
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración</title>
  <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: #f4f4f4;
        display: flex;
        justify-content: center; /* Centra el contenido en la página */
        align-items: center;
        height: 100vh;
    }
    .panel-container {
        background: rgba(255, 255, 255, 0.9);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 400px;
        text-align: center;
        position: relative;
    }
    .hide {
        display: none;
    }
    .logout-button {
        margin-top: 20px;
    }
    button {
        background-color: #007BFF;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }
    button:hover {
        background-color: #0056b3;
    }
    .greeting {
        color: gold; /* Color dorado para el saludo */
        font-size: 1.5em; /* Tamaño de fuente para que se destaque */
        font-weight: bold; /* Hacer el texto más destacado */
        margin-bottom: 20px; /* Espacio debajo del saludo */
    }
    .gif-container {
        margin-bottom: 20px; /* Espacio debajo del GIF */
    }
    .gif-container img {
        max-width: 100%; /* Ajusta el tamaño del GIF al contenedor */
        height: auto;
    }
  </style>
  <script>
    function hidePanel() {
        setTimeout(function() {
            document.querySelector('.panel-container').classList.add('hide');
            // Redirigir después de ocultar el panel
            setTimeout(function() {
                window.location.href = "registrar.html";
            }, 1000); // Tiempo para que la animación de ocultar se vea antes de redirigir
        }, 4000); // Ocultar después de 4 segundos
    }
    window.onload = hidePanel;
  </script>
</head>
<body>
  <div class="panel-container">
    <div class="greeting">Bienvenido, <?php echo htmlspecialchars($username); ?>!</div>
    <div class="gif-container">
        <img src="images/icono.gif" alt="Bienvenida GIF"> <!-- Ajusta la ruta si es necesario -->
    </div>
    <h1>Panel de Administración</h1>
    <p>Rol: <?php echo htmlspecialchars($role); ?></p>
    <div class="logout-button">
        <a href="logout.php"><button>Cerrar Sesión</button></a>
    </div>
  </div>
</body>
</html>
