<?php
require_once __DIR__ . '/../config/config.php';

// Update proveedor imagen fields that are empty, 'default' or missing extension
try {
    $sql = "UPDATE proveedores SET imagen = 'default.jpg' WHERE imagen = '' OR imagen IS NULL OR imagen = 'default' OR imagen NOT LIKE '%.%'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    echo "Updated providers to use default.jpg where needed. Rows affected: " . $stmt->rowCount();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>