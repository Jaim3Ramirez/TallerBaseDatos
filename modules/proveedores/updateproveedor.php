<?php
require_once __DIR__ . "/../../config/config.php";


$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header("Location: readproveedor.php"); exit; }

// obtener registro
$stmt = $conn->prepare("SELECT * FROM proveedores WHERE id = :id");
$stmt->execute([':id' => $id]);
$prov = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$prov) { header("Location: readproveedor.php"); exit; }

$upload_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $empresa = $_POST['empresa'] ?? '';

    $upload_error = '';
    $imagenNombre = $prov['imagen'];

    if (!empty($_FILES['imagen']['name']) && isset($_FILES['imagen'])) {
        if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            $upload_error = 'Error al subir la imagen (código: ' . $_FILES['imagen']['error'] . ').';
        } else {
            // subir nueva imagen y eliminar anterior si existe
            $originalName = basename($_FILES['imagen']['name']);
            $ext = pathinfo($originalName, PATHINFO_EXTENSION);
            $base = pathinfo($originalName, PATHINFO_FILENAME);
            $safeBase = preg_replace('/[^A-Za-z0-9_.-]/', '_', $base);
            $timePrefix = time() . '_';
            $maxLen = 50 - strlen($timePrefix) - strlen($ext) - 1; // -1 for dot
            if ($maxLen < 1) $maxLen = 1;
            $safeBase = substr($safeBase, 0, $maxLen);
            $nuevo = $timePrefix . $safeBase . '.' . $ext;
            $destino = __DIR__ . "/../../imagen/" . $nuevo;
            if (!is_dir(__DIR__ . "/../../imagen/")) {
                mkdir(__DIR__ . "/../../imagen/", 0755, true);
            }

            $checkInfo = @getimagesize($_FILES['imagen']['tmp_name']);
            if ($checkInfo !== false) {
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                    if (file_exists($destino)) {
                        // eliminar anterior si existe y no esté vacío
                        if (!empty($prov['imagen']) && file_exists(__DIR__ . "/../../imagen/" . $prov['imagen'])) {
                            @unlink(__DIR__ . "/../../imagen/" . $prov['imagen']);
                        }
                        $imagenNombre = $nuevo;
                    } else {
                        $upload_error = 'El archivo no apareció en la carpeta destino.';
                    }
                } else {
                    $upload_error = 'No se pudo mover el archivo a la carpeta destino.';
                }
            } else {
                $upload_error = 'El archivo no es una imagen válida.';
            }
        }
    }

    $upd = $conn->prepare("UPDATE proveedores SET nombre=:nombre, email=:email, telefono=:telefono, direccion=:direccion, empresa=:empresa, imagen=:imagen WHERE id=:id");
    $upd->execute([
        ':nombre' => $nombre,
        ':email' => $email,
        ':telefono' => $telefono,
        ':direccion' => $direccion,
        ':empresa' => $empresa,
        ':imagen' => $imagenNombre,
        ':id' => $id
    ]);

    error_log('updateproveedor: imagen guardada = ' . $imagenNombre);

    header("Location: readproveedor.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Proveedor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="card shadow-sm mx-auto" style="max-width:720px;">
    <div class="card-header bg-warning">
      <h5 class="mb-0">Editar proveedor</h5>
    </div>
    <div class="card-body">
      <?php if (!empty($upload_error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($upload_error) ?></div>
      <?php endif; ?>
      <form method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Nombre</label>
            <input name="nombre" value="<?= htmlspecialchars($prov['nombre']) ?>" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Empresa</label>
            <input name="empresa" value="<?= htmlspecialchars($prov['empresa']) ?>" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" value="<?= htmlspecialchars($prov['email']) ?>" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Teléfono</label>
            <input name="telefono" value="<?= htmlspecialchars($prov['telefono']) ?>" class="form-control" required>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Dirección</label>
            <input name="direccion" value="<?= htmlspecialchars($prov['direccion']) ?>" class="form-control" required>
          </div>

          <div class="col-12 mb-3">
            <label class="form-label">Imagen actual</label><br>
            <?php if (!empty($prov['imagen']) && file_exists(__DIR__ . "/../../imagen/".$prov['imagen'])): ?>
              <img src="../../imagen/<?= htmlspecialchars($prov['imagen']) ?>" width="120" class="rounded mb-2">
            <?php else: ?>
              <div class="text-muted small mb-2">Sin imagen</div>
            <?php endif; ?>

            <input name="imagen" type="file" accept="image/*" class="form-control">
          </div>
        </div>

        <div class="d-flex gap-2">
          <button class="btn btn-warning">Actualizar</button>
          <a class="btn btn-secondary" href="readproveedor.php">🔙 Volver</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
