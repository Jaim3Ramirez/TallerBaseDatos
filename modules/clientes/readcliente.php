<?php
require_once __DIR__ . "/../../config/config.php";

$sql = "SELECT * FROM clientes";
$stmt = $conn->query($sql);
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Clientes Registrados</h4>
        </div>

        <div class="card-body">

            <a href="createcliente.php" class="btn btn-success mb-3">
                ➕ Agregar Cliente
            </a>

            <table class="table table-hover table-striped text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= $cliente['id'] ?></td>

                        <td>
                            <img src="../../imagen/<?= $cliente['imagen'] ?>" width="60" class="rounded shadow-sm">
                        </td>

                        <td><?= $cliente['nombre'] ?></td>
                        <td><?= $cliente['email'] ?></td>
                        <td><?= $cliente['telefono'] ?></td>
                        <td><?= $cliente['direccion'] ?></td>

                        <td>
                            <a href="updatecliente.php?id=<?= $cliente['id'] ?>" class="btn btn-warning btn-sm">✏ Editar</a>
                            <a href="deletecliente.php?id=<?= $cliente['id'] ?>" class="btn btn-danger btn-sm">🗑 Eliminar</a>
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
