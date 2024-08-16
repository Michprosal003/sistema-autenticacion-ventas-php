
<?php
// Incluir archivo de conexión
include 'connection.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Preparar la consulta SQL para verificar el usuario
    $sql = "SELECT * FROM usuarios WHERE username = ? AND password = ? AND role = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Enlazar los parámetros
        $stmt->bind_param("sss", $username, $password, $role);

        // Ejecutar la declaración
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontró el usuario
        if ($result->num_rows === 1) {
            // Guardar la información en la sesión
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Mostrar mensaje de bienvenida y redirigir
            echo '<!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Inicio de Sesión</title>
                <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        background-color: #f8f9fa;
                    }
                    .container {
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>Bienvenido, ' . htmlspecialchars($username) . '!</h1>
                    <p>Redirigiendo a la página de registro...</p>
                    <script>
                        setTimeout(function() {
                            window.location.href = "registrar.html";
                        }, 4000); // Redirige después de 4 segundos
                    </script>
                </div>
                <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            </body>
            </html>';

        } else {
            // Mostrar mensaje de error en caso de credenciales incorrectas
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        document.getElementById("error-message").innerText = "Nombre de usuario, contraseña o rol incorrectos.";
                    });
                  </script>';
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error al preparar la declaración: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "Método de solicitud no permitido.";
}
?>

