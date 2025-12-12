<?php
require_once __DIR__ . "/../../config/config.php";


$sql = "SELECT p.*, pr.nombre AS proveedor 
        FROM productos p
        LEFT JOIN proveedores pr ON p.proveedor_id = pr.id";
$productos = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lista de Productos</h2>
        <a href="createproducto.php" class="btn btn-primary">Agregar Producto</a>
    </div>

    <div class="card shadow">
        <div class="card-body">

            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Proveedor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($productos as $p): ?>
                    <tr>
                        <td><?= $p["id"]; ?></td>
                        <td>
                            <?php if (!empty($p['imagen']) && file_exists(__DIR__ . "/../../imagen/" . $p['imagen'])): ?>
                                <img src="../../imagen/<?= $p["imagen"]; ?>" width="70">
                            <?php else: ?>
                                <img src="/ProyectoWeb/imagen/default.jpg" width="70">
                            <?php endif; ?>
                        </td>
                        <td><?= $p["nombre"]; ?></td>
                        <td>$<?= $p["precio"]; ?></td>
                        <td><?= $p["stock"]; ?></td>
                        <td><?= $p["proveedor"]; ?></td>
                        <td>
                            <a href="updateproducto.php?id=<?= $p['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="deleteproducto.php?id=<?= $p['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>

        </div>
    </div>

</div>

</body>
</html>
