<?php
require_once __DIR__ . "/../../config/config.php";


// Obtener lista de productos
try {
    $productos = $conn->query("SELECT id, nombre, precio FROM productos");
} catch (PDOException $e) {
    die("Error al obtener productos: " . $e->getMessage());
}

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    // Obtener precio real del producto
    $stmt = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
    $stmt->execute([$producto_id]);
    $precio = $stmt->fetchColumn();

    try {
        $sql = "INSERT INTO compras (producto_id, cantidad, precio) 
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$producto_id, $cantidad, $precio]);

        // Aumentar stock del producto
        $updateStock = $conn->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
        $updateStock->execute([$cantidad, $producto_id]);

        header("Location: readcompras.php");
        exit;
    } catch (PDOException $e) {
        echo "Error al guardar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Compra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-4">
    <h2 class="text-center mb-4">Registrar Nueva Compra</h2>

    <div class="card shadow">
        <div class="card-body">

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Producto</label>
                    <select name="producto_id" class="form-select" required>
                        <option value="">Seleccione un producto</option>

                        <?php while ($row = $productos->fetch(PDO::FETCH_ASSOC)) { ?>
                            <option value="<?= $row['id'] ?>">
                                <?= $row['nombre'] ?> — $<?= number_format($row['precio'], 2) ?>
                            </option>
                        <?php } ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" class="form-control" min="1" required>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Compra</button>
                <a href="readcompras.php" class="btn btn-secondary">🔙 Volver</a>

            </form>

        </div>
    </div>
</div>

</body>
</html>
