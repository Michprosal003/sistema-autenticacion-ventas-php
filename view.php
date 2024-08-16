<?php
// Conectar a la base de datos
$conn = mysqli_connect("localhost", "root", "", "calceta_db");

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Procesar la eliminación si se ha enviado un ID
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($id > 0) {
        // Eliminar el registro
        $sql = "DELETE FROM ventas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Registro eliminado exitosamente.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}

// Procesar la búsqueda
$search_query = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = trim($_GET['search']);
}

// Configuración de la paginación
$limit = 10; // Número de registros por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Consultar los registros con búsqueda
$sql = "SELECT * FROM ventas WHERE comprador LIKE ? LIMIT ? OFFSET ?";
$search_term = "%$search_query%";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $search_term, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Contar el total de registros para la paginación
$sql_total = "SELECT COUNT(*) FROM ventas WHERE comprador LIKE ?";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param("s", $search_term);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_rows = $result_total->fetch_array()[0];
$total_pages = ceil($total_rows / $limit);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Lista de Ventas</title>
    <style>
        /* Ajustes adicionales para mejorar la visualización en dispositivos pequeños */
        .btn-custom {
            width: 100%;
            text-align: center;
        }

        .table-responsive {
            margin-bottom: 1rem;
        }

        .alert {
            margin-top: 1rem;
        }

        .text-center {
            text-align: center;
        }

        @media (max-width: 768px) {
            .input-group {
                flex-direction: column;
            }

            .input-group .form-control {
                margin-bottom: 0.5rem;
            }

            .input-group .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Lista de Ventas</h1>
        
        <!-- Formulario de búsqueda -->
        <form method="GET" action="view.php" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar..." value="<?php echo htmlspecialchars($search_query); ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                    <a href="view.php" class="btn btn-secondary ml-2">Limpiar</a>
                </div>
            </div>
        </form>

        <!-- Tabla de resultados -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Comprador</th>
                        <th>Variedad</th>
                        <th>Estado</th>
                        <th>Venta</th>
                        <th>Qq</th>
                        <th>Valor</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['comprador']); ?></td>
                            <td><?php echo htmlspecialchars($row['variedad']); ?></td>
                            <td><?php echo htmlspecialchars($row['estado']); ?></td>
                            <td><?php echo htmlspecialchars($row['venta']); ?></td>
                            <td><?php echo htmlspecialchars($row['quintalaje']); ?></td>
                            <td><?php echo htmlspecialchars($row['valor']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                            <td>
                                <form method="POST" action="view.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?');">Eliminar</button>
                                </form>
                                <form method="POST" action="modificar.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button type="submit" class="btn btn-warning btn-sm">Modificar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <nav>
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="view.php?page=<?php echo ($page - 1); ?>">Anterior</a>
                    </li>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="view.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="view.php?page=<?php echo ($page + 1); ?>">Siguiente</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <a href="registrar.html" class="btn btn-primary btn-custom">Registrar nueva venta</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Cerrar conexión
mysqli_close($conn);
?>


