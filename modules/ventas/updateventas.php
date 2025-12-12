<?php
require_once __DIR__ . "/../../config/config.php";

$id = $_GET['id'];

// Obtener venta original
$stmt = $conn->prepare("SELECT * FROM ventas WHERE id = ?");
$stmt->execute([$id]);
$venta = $stmt->fetch();

$old_cantidad = $venta['cantidad'];
$old_producto = $venta['producto_id'];

// Obtener listas
$productos = $conn->query("SELECT id, nombre, precio, stock FROM productos");
$clientes = $conn->query("SELECT id, nombre FROM clientes");

// Si se envía formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $cliente_id = $_POST['cliente_id'];
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $estado = $_POST['estado'];

    // Calcular total en servidor
    $stmtPrice = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
    $stmtPrice->execute([$producto_id]);
    $precio = $stmtPrice->fetchColumn();
    $total = $precio * $cantidad;

    // Primero, devolver el stock de la venta anterior
    $devolver = $conn->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
    $devolver->execute([$old_cantidad, $old_producto]);

    // Verificar stock disponible del nuevo producto
    $check = $conn->prepare("SELECT stock FROM productos WHERE id = ?");
    $check->execute([$producto_id]);
    $stock = $check->fetchColumn();

    if ($stock < $cantidad) {
        die("Error: No hay suficiente stock. Stock disponible: $stock");
    }

    // Actualizar venta
    $sql = "UPDATE ventas SET cliente_id=?, producto_id=?, cantidad=?, total=?, estado=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cliente_id, $producto_id, $cantidad, $total, $estado, $id]);

    // Ahora sí, descontar la nueva cantidad
    $restar = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
    $restar->execute([$cantidad, $producto_id]);

    header("Location: readventas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Venta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="text-center mb-4">Editar Venta</h2>

    <div class="card shadow mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Cliente:</label>
                    <select name="cliente_id" class="form-select" required>
                        <?php foreach ($clientes as $cli) { ?>
                            <option value="<?= $cli['id'] ?>" <?= $cli['id']==$venta['cliente_id'] ? 'selected' : '' ?>>
                                <?= $cli['nombre'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Producto:</label>
                    <select name="producto_id" class="form-select" required>
                        <?php foreach ($productos as $p) { ?>
                            <option value="<?= $p['id'] ?>" data-price="<?= $p['precio'] ?>" <?= $p['id']==$venta['producto_id'] ? 'selected' : '' ?>>
                                <?= $p['nombre'] ?> (Stock: <?= $p['stock'] ?>)
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cantidad:</label>
                    <input type="number" name="cantidad" class="form-control" value="<?= $venta['cantidad'] ?>" min="1" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Total:</label>
                    <input type="number" step="0.01" class="form-control" id="total" name="total" value="<?= $venta['total'] ?>" readonly required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado:</label>
                    <select name="estado" class="form-select">
                        <option <?= $venta['estado']=="Pendiente"?"selected":"" ?>>Pendiente</option>
                        <option <?= $venta['estado']=="Completada"?"selected":"" ?>>Completada</option>
                        <option <?= $venta['estado']=="Cancelada"?"selected":"" ?>>Cancelada</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Actualizar</button>
                <a href="readventas.php" class="btn btn-secondary w-100 mt-2">🔙 Volver</a>
            </form>
        </div>
    </div>
</div>

<script>
    (function(){
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

        // Calcular inicial
        calcularTotal();
    })();
</script>

</body>
</html>
