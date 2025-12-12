<?php
require_once __DIR__ . "/../../config/config.php";

// Cuando el formulario se envía
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $cliente_id = $_POST['cliente_id'];
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $estado = $_POST['estado'];

    // Calcular total en servidor para evitar manipulación en cliente
    $stmtPrice = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
    $stmtPrice->execute([$producto_id]);
    $precio = $stmtPrice->fetchColumn();
    $total = $precio * $cantidad;

    // Verificar stock disponible
    $check = $conn->prepare("SELECT stock FROM productos WHERE id = ?");
    $check->execute([$producto_id]);
    $stock = $check->fetchColumn();

    if ($stock < $cantidad) {
        die("Error: No hay suficiente stock disponible. Stock actual: $stock");
    }

    // Insert venta
    $sql = "INSERT INTO ventas (cliente_id, producto_id, cantidad, total, estado)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$cliente_id, $producto_id, $cantidad, $total, $estado]);

    // Restar stock
    $updateStock = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
    $updateStock->execute([$cantidad, $producto_id]);

    header("Location: readventas.php");
    exit();
}

// Obtener productos
$productos = $conn->query("SELECT id, nombre, precio FROM productos");

// Obtener clientes
$clientes = $conn->query("SELECT id, nombre FROM clientes");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Venta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="text-center mb-4">Registrar Venta</h2>

    <div class="card shadow mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Cliente:</label>
                    <select name="cliente_id" class="form-select" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($clientes as $cli) { ?>
                            <option value="<?= $cli['id'] ?>"><?= $cli['nombre'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Producto:</label>
                    <select name="producto_id" class="form-select" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($productos as $prod) { ?>
                            <option value="<?= $prod['id'] ?>" data-price="<?= $prod['precio'] ?>"><?= $prod['nombre'] ?> ($<?= number_format($prod['precio'], 2) ?>)</option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cantidad:</label>
                    <input type="number" name="cantidad" class="form-control" min="1" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Total ($):</label>
                    <input type="number" step="0.01" name="total" id="total" class="form-control" readonly required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado:</label>
                    <select name="estado" class="form-select">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Completada">Completada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100">Guardar</button>
                <a href="readventas.php" class="btn btn-secondary w-100 mt-2">🔙 Volver</a>

            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        const productoSelect = document.querySelector('select[name="producto_id"]');
        const cantidadInput = document.querySelector('input[name="cantidad"]');
        const totalInput = document.getElementById('total');

        function calcularTotal() {
            const opt = productoSelect.options[productoSelect.selectedIndex];
            const precio = parseFloat(opt ? opt.dataset.price || 0 : 0);
            const cantidad = parseFloat(cantidadInput.value || 0);
            const total = precio * cantidad;
            totalInput.value = total.toFixed(2);
        }

        productoSelect.addEventListener('change', calcularTotal);
        cantidadInput.addEventListener('input', calcularTotal);

        // Calcular inicial si ya hay valores
        calcularTotal();
    })();
</script>

</body>
</html>
