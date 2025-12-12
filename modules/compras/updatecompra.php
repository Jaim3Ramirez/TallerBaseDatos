<?php
require_once __DIR__ . "/../../config/config.php";


// Validar ID recibido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de compra no válido.");
}

$id = $_GET['id'];

// Obtener compra actual
try {
    $stmt = $conn->prepare("SELECT * FROM compras WHERE id = ?");
    $stmt->execute([$id]);
    $compra = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$compra) {
        die("La compra no existe.");
    }
    
    // Guardar el producto_id anterior para revertir stock
    $old_producto_id = $compra['producto_id'];
} catch (PDOException $e) {
    die("Error al obtener compra: " . $e->getMessage());
}

// Obtener lista de productos
try {
    $productos = $conn->query("SELECT id, nombre, precio FROM productos");
} catch (PDOException $e) {
    die("Error al obtener productos: " . $e->getMessage());
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    // Obtener precio actual del producto seleccionado
    $stmt = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
    $stmt->execute([$producto_id]);
    $precio = $stmt->fetchColumn();

    try {
        // Primero, devolver el stock de la compra anterior
        $stmt = $conn->prepare("SELECT cantidad FROM compras WHERE id = ?");
        $stmt->execute([$id]);
        $compra_antigua = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $devolver = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
        $devolver->execute([$compra_antigua['cantidad'], $old_producto_id]);

        $sql = "UPDATE compras 
                SET producto_id = ?, cantidad = ?, precio = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$producto_id, $cantidad, $precio, $id]);

        // Ahora agregar el stock de la nueva compra
        $sumar = $conn->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
        $sumar->execute([$cantidad, $producto_id]);

        header("Location: readcompras.php");
        exit;

    } catch (PDOException $e) {
        echo "Error al actualizar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Compra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h2 class="text-center mb-4">Editar Compra</h2>

    <div class="card shadow">
        <div class="card-body">

            <form method="POST">

                <!-- PRODUCTO -->
                <div class="mb-3">
                    <label class="form-label">Producto</label>
                    <select name="producto_id" class="form-select" required>
                        <?php while ($row = $productos->fetch(PDO::FETCH_ASSOC)) { ?>
                            <option value="<?= $row['id'] ?>" 
                                <?= $row['id'] == $compra['producto_id'] ? "selected" : "" ?>>
                                <?= $row['nombre'] ?> — $<?= number_format($row['precio'], 2) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- CANTIDAD -->
                <div class="mb-3">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" class="form-control" value="<?= $compra['cantidad'] ?>" min="1" required>
                </div>

                <!-- BOTONES -->
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="readcompras.php" class="btn btn-secondary">🔙 Volver</a>

            </form>

        </div>
    </div>

</div>

</body>
</html>
