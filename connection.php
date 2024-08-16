<?php
// Conectar a la base de datos
$conn = mysqli_connect("localhost", "root", "", "calceta_db");

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
} else {
    echo "Conexión exitosa";
}
?>
