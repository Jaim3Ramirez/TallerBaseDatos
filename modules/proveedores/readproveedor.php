<?php
require_once __DIR__ . "/../../config/config.php";


$stmt = $conn->query("SELECT * FROM proveedores ORDER BY id DESC");
$proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Proveedores</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Proveedores</h3>
    <a href="createproveedor.php" class="btn btn-primary">+ Nuevo proveedor</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-dark">
            <tr class="text-center">
              <th>ID</th>
              <th>Imagen</th>
              <th>Nombre</th>
              <th>Empresa</th>
              <th>Email</th>
              <th>Teléfono</th>
              <th>Dirección</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($proveedores) === 0): ?>
              <tr><td colspan="8" class="text-center p-4">No hay proveedores registrados.</td></tr>
            <?php else: ?>
              <?php foreach ($proveedores as $p): ?>
                <tr>
                  <td class="text-center"><?= htmlspecialchars($p['id']) ?></td>
                  <td class="text-center">
                    <?php $imgPath = __DIR__ . "/../../imagen/" . $p['imagen'];
                          $defaultPath = __DIR__ . "/../../imagen/default.jpg";
                          if (!empty($p['imagen']) && file_exists($imgPath)): ?>
                      <img src="../../imagen/<?= htmlspecialchars($p['imagen']) ?>" alt="" width="64" class="rounded" title="<?= htmlspecialchars($p['imagen']) ?>">
                    <?php elseif (file_exists($defaultPath)): ?>
                      <img src="../../imagen/default.jpg" alt="default" width="64" class="rounded" title="default.jpg">
                    <?php else: ?>
                      <span class="text-muted small">Sin imagen</span>
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars($p['nombre']) ?></td>
                  <td><?= htmlspecialchars($p['empresa']) ?></td>
                  <td><?= htmlspecialchars($p['email']) ?></td>
                  <td><?= htmlspecialchars($p['telefono']) ?></td>
                  <td><?= htmlspecialchars($p['direccion']) ?></td>
                  <td class="text-center">
                    <a href="updateproveedor.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="deleteproveedor.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar proveedor?')">Eliminar</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>
