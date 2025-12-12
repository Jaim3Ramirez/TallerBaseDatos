<?php
require_once __DIR__ . "/../../config/config.php";


$id = $_GET["id"];

$sql = "SELECT * FROM clientes WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $email  = $_POST["email"];
    $telefono = $_POST["telefono"];
    $direccion = $_POST["direccion"];

    // Imagen NUEVA o mantener la misma
    if (!empty($_FILES["imagen"]["name"])) {
        $imagen = $_FILES["imagen"]["name"];
        $rutaDestino = __DIR__ . "/../../imagen/" . $imagen;
        if (!is_dir(__DIR__ . "/../../imagen/")) {
            mkdir(__DIR__ . "/../../imagen/", 0755, true);
        }
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino);
    } else {
        $imagen = $cliente["imagen"];
    }

    $sql = "UPDATE clientes SET nombre=:nombre, email=:email, telefono=:telefono, direccion=:direccion, imagen=:imagen WHERE id=:id";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":telefono", $telefono);
    $stmt->bindParam(":direccion", $direccion);
    $stmt->bindParam(":imagen", $imagen);
    $stmt->bindParam(":id", $id);

    if ($stmt->execute()) {
        header("Location: readcliente.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">✏ Editar Cliente</h4>
                </div>

                <div class="card-body">

                    <form method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?= $cliente['nombre'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $cliente['email'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" name="telefono" class="form-control" value="<?= $cliente['telefono'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control" value="<?= $cliente['direccion'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Imagen Actual</label><br>
                            <img src="../../imagen/<?= $cliente['imagen'] ?>" width="150" class="rounded shadow-sm">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cambiar Imagen (opcional)</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-warning w-100">💾 Guardar Cambios</button>

                    </form>

                    <a href="readcliente.php" class="btn btn-secondary w-100 mt-2">🔙 Volver</a>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
