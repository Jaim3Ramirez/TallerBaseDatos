<?php
require_once __DIR__ . "/../../config/config.php";


// Obtener lista de proveedores
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

    // Procesar imagen
    $imagen = "default.jpg";
    if (!empty($_FILES["imagen"]["name"])) {
        $nombreImagen = time() . "_" . $_FILES["imagen"]["name"];
        $rutaDestino = __DIR__ . "/../../imagen/" . $nombreImagen;

        if (!is_dir(__DIR__ . "/../../imagen/")) {
            mkdir(__DIR__ . "/../../imagen/", 0755, true);
        }

        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
            $imagen = $nombreImagen;
        }
    }

    $sql = "INSERT INTO productos(nombre, descripcion, precio, stock, imagen, proveedor_id)
            VALUES(:nombre, :descripcion, :precio, :stock, :imagen, :proveedor_id)";
    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ":nombre" => $nombre,
        ":descripcion" => $descripcion,
        ":precio" => $precio,
        ":stock" => $stock,
        ":imagen" => $imagen,
        ":proveedor_id" => $proveedor_id
    ]);

    header("Location: readproducto.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Agregar Producto</h4>
        </div>
        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">

                <label>Nombre:</label>
                <input type="text" class="form-control" name="nombre" required>

                <label>Descripción:</label>
                <textarea class="form-control" name="descripcion" required></textarea>

                <label>Precio:</label>
                <input type="number" step="0.01" class="form-control" name="precio" required>

                <label>Stock:</label>
                <input type="number" class="form-control" name="stock" required>

                <label>Proveedor:</label>
                <select class="form-select" name="proveedor_id" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($proveedores as $prov): ?>
                        <option value="<?= $prov['id']; ?>"><?= $prov['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Imagen:</label>
                <input type="file" class="form-control" name="imagen">

                <button class="btn btn-success mt-3">Guardar</button>
                <a href="readproducto.php" class="btn btn-secondary mt-3">Volver</a>

            </form>

        </div>
    </div>
</div>
</body>
</html>
