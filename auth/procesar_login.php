<?php
include "../config/config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = :correo");
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['usuario'] = $usuario;
        header("Location: http://127.0.0.1/ProyectoWeb/views/dashboard.php");
    } else {
        echo  "<script>alert('Crendeciales incorrectas.'); window.location.href = 'login.php';</script>";
    }
}
?>