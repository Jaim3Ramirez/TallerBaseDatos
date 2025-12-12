<?php
require_once __DIR__ . "/../../config/config.php";

// Consulta SQL: incluir nombre del cliente y producto
$sql = "SELECT v.*, c.nombre AS cliente_nombre, p.nombre AS producto_nombre FROM ventas v LEFT JOIN clientes c ON v.cliente_id = c.id LEFT JOIN productos p ON v.producto_id = p.id";

try {
    $result = $conn->query($sql);
} catch (PDOException $e) {
    die("Error en la consulta SQL: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas Registradas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-4">
    <h2 class="mb-4 text-center">Ventas Registradas</h2>

    <a href="createventas.php" class="btn btn-success mb-3">Agregar Venta</a>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Producto</th>
                        <th>Fecha Venta</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['cliente_nombre'] ?? 'Cliente eliminado') ?></td>
                        <td><?= htmlspecialchars($row['producto_nombre'] ?? 'Producto eliminado') ?></td>
                        <td><?= $row['fecha_venta'] ?></td>
                        <td>$<?= number_format($row['total'], 2) ?></td>
                        <td><?= $row['estado'] ?></td>

                        <td>
                            <a href="updateventas.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="deleteventas.php?id=<?= $row['id'] ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Seguro de eliminar esta venta?')">
                               Eliminar
                            </a>
                        </td>
                    </tr>
                <?php } ?>

                </tbody>

            </table>
        </div>
    </div>
</div>

</body>
</html>
