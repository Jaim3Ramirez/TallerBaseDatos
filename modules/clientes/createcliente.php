<?php
require_once __DIR__ . "/../../config/config.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $direccion = $_POST["direccion"];

    // Procesar imagen
    $imagen = $_FILES["imagen"]["name"];
    $rutaTemp = $_FILES["imagen"]["tmp_name"];
    $rutaDestino = __DIR__ . "/../../imagen/" . $imagen;
    
    if (!is_dir(__DIR__ . "/../../imagen/")) {
        mkdir(__DIR__ . "/../../imagen/", 0755, true);
    }

    if (!empty($rutaTemp) && move_uploaded_file($rutaTemp, $rutaDestino)) {
        // Imagen guardada correctamente
    } else {
        // Si por alguna razón no se puede mover la imagen, usar default o dejar vacío
        $imagen = '';
    }

    $sql = "INSERT INTO clientes (nombre, email, telefono, direccion, imagen)
            VALUES (:nombre, :email, :telefono, :direccion, :imagen)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":telefono", $telefono);
    $stmt->bindParam(":direccion", $direccion);
    $stmt->bindParam(":imagen", $imagen);

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
    <title>Nuevo Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow" style="max-width: 500px; margin:auto;">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Agregar Cliente</h4>
        </div>

        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">
                
                <label class="form-label">Nombre:</label>
                <input type="text" name="nombre" class="form-control" required>

                <label class="form-label mt-3">Email:</label>
                <input type="email" name="email" class="form-control" required>

                <label class="form-label mt-3">Teléfono:</label>
                <input type="text" name="telefono" class="form-control" required>

                <label class="form-label mt-3">Dirección:</label>
                <input type="text" name="direccion" class="form-control" required>

                <label class="form-label mt-3">Imagen:</label>
                <input type="file" name="imagen" class="form-control" required>

                <button class="btn btn-success w-100 mt-4">Guardar</button>
                <a href="readcliente.php" class="btn btn-secondary w-100 mt-2">🔙 Volver</a>

            </form>

        </div>
    </div>
</div>

</body>
</html>
