<?php
// Conectar a la base de datos
$conn = mysqli_connect("localhost", "root", "", "calceta_db");

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Obtener el ID del registro a modificar
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

// Si no se ha enviado el ID, redirigir a la página principal
if ($id <= 0) {
    header("Location: view.php");
    exit();
}

// Consultar el registro a modificar
$sql = "SELECT * FROM ventas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$registro = $result->fetch_assoc();

// Si no se encuentra el registro, redirigir a la página principal
if (!$registro) {
    header("Location: view.php");
    exit();
}

// Procesar el formulario de modificación
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Obtener y sanitizar datos del formulario
    $comprador = mysqli_real_escape_string($conn, $_POST['comprador']);
    $variedad = mysqli_real_escape_string($conn, $_POST['variedad']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);
    $venta = mysqli_real_escape_string($conn, $_POST['venta']);
    $quintalaje = mysqli_real_escape_string($conn, $_POST['quintalaje']);
    $valor = mysqli_real_escape_string($conn, $_POST['valor']);
    $fecha = mysqli_real_escape_string($conn, $_POST['fecha']);

    // Actualizar el registro
    $sql = "UPDATE ventas SET comprador = ?, variedad = ?, estado = ?, venta = ?, quintalaje = ?, valor = ?, fecha = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $comprador, $variedad, $estado, $venta, $quintalaje, $valor, $fecha, $id);

    if ($stmt->execute()) {
        header("Location: view.php"); // Redirige a la página principal después de la actualización
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Cerrar conexión
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Venta</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .currency-input {
            display: flex;
            align-items: center;
        }
        .currency-input span {
            margin-right: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .centered-link {
            text-align: center;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modificar Venta</h1>
        <form method="POST" action="modificar.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            
            <div class="form-group">
                <label for="comprador">Comprador:</label>
                <input type="text" id="comprador" name="comprador" value="<?php echo htmlspecialchars($registro['comprador']); ?>" required>
            </div>

            <div class="form-group">
                <label for="variedad">Variedad:</label>
                <input type="text" id="variedad" name="variedad" value="<?php echo htmlspecialchars($registro['variedad']); ?>" required>
            </div>

            <div class="form-group">
                <label for="estado">Estado:</label>
                <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($registro['estado']); ?>" required>
            </div>

            <div class="form-group">
                <label for="venta">Venta:</label>
                <input type="text" id="venta" name="venta" value="<?php echo htmlspecialchars($registro['venta']); ?>" required>
            </div>

            <div class="form-group">
                <label for="quintalaje">Qq:</label>
                <input type="text" id="quintalaje" name="quintalaje" value="<?php echo htmlspecialchars($registro['quintalaje']); ?>" required pattern="[0-9,.]+">
            </div>

            <div class="form-group">
                <label for="valor">Valor:</label>
                <div class="currency-input">
                    <span>$</span>
                    <input type="text" id="valor" name="valor" value="<?php echo htmlspecialchars($registro['valor']); ?>" required pattern="[0-9,.]+">
                </div>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha de Registro:</label>
                <input type="date" id="fecha" name="fecha" value="<?php echo htmlspecialchars($registro['fecha']); ?>" required>
            </div>

            <button type="submit" name="submit">Actualizar</button>
        </form>
        <a href="view.php" class="centered-link">Volver a la lista</a>
    </div>
</body>
</html>









