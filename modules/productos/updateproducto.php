<?php
require_once __DIR__ . "/../../config/config.php";


$id = $_GET["id"];

// Obtener producto
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = :id");
$stmt->execute([":id" => $id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener proveedores
$proveedores = $conn->query("SELECT * FROM proveedores")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $proveedor_id = $_POST["proveedor_id"];

    // Verificar que el proveedor exista
    $checkProv = $conn->prepare("SELECT id FROM proveedores WHERE id = ?");
    $checkProv->execute([$proveedor_id]);
    if (!$checkProv->fetchColumn()) {
        die("Proveedor no válido o inexistente.");
    }
    $imagen = $p["imagen"]; 

    if (!empty($_FILES["imagen"]["name"])) {
        $nombreImagen = time() . "_" . $_FILES["imagen"]["name"];
        $rutaDestino = __DIR__ . "/../../imagen/" . $nombreImagen;

        if (!is_dir(__DIR__ . "/../../imagen/")) {
            mkdir(__DIR__ . "/../../imagen/", 0755, true);
        }

        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
            if (!empty($p['imagen']) && file_exists(__DIR__ . "/../../imagen/" . $p['imagen'])) {
                @unlink(__DIR__ . "/../../imagen/" . $p['imagen']);
            }
            $imagen = $nombreImagen;
        }
    }

    $sql = "UPDATE productos SET 
                nombre=:nombre,
                descripcion=:descripcion,
                precio=:precio,
                stock=:stock,
                imagen=:imagen,
                proveedor_id=:proveedor_id
            WHERE id=:id";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ":nombre" => $nombre,
        ":descripcion" => $descripcion,
        ":precio" => $precio,
        ":stock" => $stock,
        ":imagen" => $imagen,
        ":proveedor_id" => $proveedor_id,
        ":id" => $id
    ]);

    header("Location: readproducto.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

<div class="card shadow">
    <div class="card-header bg-warning text-dark">
        <h4>Editar Producto</h4>
    </div>

    <div class="card-body">

        <form method="POST" enctype="multipart/form-data">

            <label>Nombre:</label>
            <input type="text" class="form-control" name="nombre" value="<?= $p['nombre']; ?>" required>

            <label>Descripción:</label>
            <textarea class="form-control" name="descripcion" required><?= $p['descripcion']; ?></textarea>

            <label>Precio:</label>
            <input type="number" step="0.01" class="form-control" name="precio" value="<?= $p['precio']; ?>" required>

            <label>Stock:</label>
            <input type="number" class="form-control" name="stock" value="<?= $p['stock']; ?>" required>

            <label>Proveedor:</label>
            <select class="form-select" name="proveedor_id" required>
                <?php foreach ($proveedores as $prov): ?>
                    <option value="<?= $prov['id']; ?>" <?= $prov["id"] == $p["proveedor_id"] ? "selected" : "" ?>>
                        <?= $prov["nombre"]; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Imagen actual:</label><br>
            <img src="../../imagen/<?= $p['imagen']; ?>" width="150" class="rounded">
            <br><br>

            <label>Cambiar imagen:</label>
            <input type="file" class="form-control" name="imagen">

            <button class="btn btn-warning mt-3">Guardar Cambios</button>
            <a href="readproducto.php" class="btn btn-secondary mt-3">🔙 Volver</a>

        </form>

    </div>
</div>

</div>

</body>
</html>
