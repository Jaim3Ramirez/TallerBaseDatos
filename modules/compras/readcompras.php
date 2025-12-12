<?php
require_once __DIR__ . "/../../config/config.php";


// Consulta con JOIN para mostrar nombre del producto
$sql = "SELECT compras.*, productos.nombre AS producto
        FROM compras
        INNER JOIN productos ON compras.producto_id = productos.id";

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
    <title>Lista de Compras</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-4">
    <h2 class="mb-4 text-center">Lista de Compras</h2>

    <a href="createcompra.php" class="btn btn-success mb-3">Nueva Compra</a>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Fecha de Compra</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <?php setlocale(LC_TIME, "es_ES.UTF-8"); ?>

<td><?= date("d/m/Y", strtotime($row['fecha_compra'])) ?></td>

                        <td><?= $row['producto'] ?></td>
                        <td><?= $row['cantidad'] ?></td>
                        <td>$<?= number_format($row['precio'], 2) ?></td>

                        <td>
                            <a href="updatecompra.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="deletecompra.php?id=<?= $row['id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Seguro de eliminar esta compra?')">
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
