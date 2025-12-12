<?php
require_once __DIR__ . "/../../config/config.php";


$id = $_GET["id"];

$stmt = $conn->prepare("DELETE FROM productos WHERE id = :id");
$stmt->execute([":id" => $id]);

header("Location: readproducto.php");
exit();
?>
