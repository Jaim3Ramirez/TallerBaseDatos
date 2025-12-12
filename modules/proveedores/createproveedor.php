<?php
require_once __DIR__ . "/../../config/config.php";


$upload_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $empresa = $_POST['empresa'] ?? '';

    $upload_error = '';

    // manejo de imagen
    $imagenNombre = 'default.jpg';
    if (!empty($_FILES['imagen']['name']) && isset($_FILES['imagen'])) {
        if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            $upload_error = 'Error al subir la imagen (código: ' . $_FILES['imagen']['error'] . ').';
        } else {
            $originalName = basename($_FILES['imagen']['name']);
            $ext = pathinfo($originalName, PATHINFO_EXTENSION);
            $base = pathinfo($originalName, PATHINFO_FILENAME);
            $safeBase = preg_replace('/[^A-Za-z0-9_.-]/', '_', $base);
            $timePrefix = time() . '_';
            $maxLen = 50 - strlen($timePrefix) - strlen($ext) - 1; // -1 for dot
            if ($maxLen < 1) $maxLen = 1;
            $safeBase = substr($safeBase, 0, $maxLen);
            $safeName = $timePrefix . $safeBase . '.' . $ext;
            $destino = __DIR__ . "/../../imagen/" . $safeName;

            if (!is_dir(__DIR__ . "/../../imagen/")) {
                mkdir(__DIR__ . "/../../imagen/", 0755, true);
            }

            // Validar que sea una imagen real
            $checkInfo = @getimagesize($_FILES['imagen']['tmp_name']);
            if ($checkInfo !== false) {
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                    if (file_exists($destino)) {
                        $imagenNombre = $safeName;
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

    $sql = "INSERT INTO proveedores (nombre, email, telefono, direccion, empresa, imagen)
            VALUES (:nombre, :email, :telefono, :direccion, :empresa, :imagen)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':email' => $email,
        ':telefono' => $telefono,
        ':direccion' => $direccion,
        ':empresa' => $empresa,
        ':imagen' => $imagenNombre
    ]);

    // Log for debugging
    error_log('createproveedor: imagen guardada = ' . $imagenNombre);

    // Confirm what was inserted in DB
    try {
        $lastId = $conn->lastInsertId();
        if ($lastId) {
            $check = $conn->prepare('SELECT imagen FROM proveedores WHERE id = ?');
            $check->execute([$lastId]);
            $stored = $check->fetchColumn();
            error_log('createproveedor: imagen en BD = ' . $stored);
        }
    } catch (Exception $e) {
        error_log('createproveedor: error comprobando BD - ' . $e->getMessage());
    }

    if (!empty($upload_error)) {
        error_log('createproveedor: upload_error: ' . $upload_error);
        error_log('createproveedor: FILES: ' . print_r($_FILES, true));
        // Mantener en la misma página y mostrar error debajo del título
    } else {
        header("Location: readproveedor.php");
        exit();
    }

    if (!empty($upload_error)) {
        // Mantener en la misma página y mostrar error debajo del título
    } else {
        header("Location: readproveedor.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nuevo Proveedor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="card shadow-sm mx-auto" style="max-width:720px;">
    <div class="card-header bg-success text-white">
      <h5 class="mb-0">Registrar proveedor</h5>
    </div>
    <div class="card-body">
      <?php if (!empty($upload_error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($upload_error) ?></div>
      <?php endif; ?>
      <form method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Nombre</label>
            <input name="nombre" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Empresa</label>
            <input name="empresa" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Teléfono</label>
            <input name="telefono" class="form-control" required>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Dirección</label>
            <input name="direccion" class="form-control" required>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Imagen</label>
            <input name="imagen" type="file" accept="image/*" class="form-control">
          </div>
        </div>

        <div class="d-flex gap-2">
          <button class="btn btn-success">Guardar</button>
          <a href="readproveedor.php" class="btn btn-secondary">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
