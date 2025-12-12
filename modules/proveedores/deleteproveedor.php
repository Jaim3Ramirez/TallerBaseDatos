<?php
require_once __DIR__ . "/../../config/config.php";


$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header("Location: readproveedor.php"); exit; }

// obtener imagen actual
$stmt = $conn->prepare("SELECT imagen FROM proveedores WHERE id = :id");
$stmt->execute([':id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// eliminar registro
$del = $conn->prepare("DELETE FROM proveedores WHERE id = :id");
$del->execute([':id' => $id]);

// eliminar archivo de imagen si existe
if ($row && !empty($row['imagen']) && file_exists(__DIR__ . "/../../imagen/".$row['imagen'])) {
    @unlink(__DIR__ . "/../../imagen/".$row['imagen']);
}

header("Location: readproveedor.php");
exit();
?>
