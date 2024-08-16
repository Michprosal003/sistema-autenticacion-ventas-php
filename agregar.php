<?php
// Conectar a la base de datos
$conn = mysqli_connect("localhost", "root", "", "calceta_db");

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Obtener datos del formulario y escapar caracteres especiales
$comprador = $_POST['comprador'];
$variedad = $_POST['variedad'];
$estado = $_POST['estado'];
$venta = $_POST['venta'];
$quintalaje = $_POST['quintalaje'];
$valor = $_POST['valor'];
$fecha = $_POST['fecha'];

// Consultar para insertar datos usando consultas preparadas
$stmt = $conn->prepare("INSERT INTO ventas (comprador, variedad, estado, venta, quintalaje, valor, fecha) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $comprador, $variedad, $estado, $venta, $quintalaje, $valor, $fecha);

if ($stmt->execute()) {
    // Redirigir a view.php si la inserción es exitosa
    header("Location: view.php");
    exit(); // Asegura que el script se detenga después de la redirección
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la declaración y la conexión
$stmt->close();
mysqli_close($conn);
?>

