<?php
// Redirect to dashboard using the application folder name to build an absolute URL
$app = basename(__DIR__);
$host = $_SERVER['HTTP_HOST'];
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
header("Location: {$scheme}://{$host}/{$app}/views/dashboard.php");
exit();
?>