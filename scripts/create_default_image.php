<?php
// Script to create a visible PNG default image in imagen/ folder using GD
$path = __DIR__ . '/../imagen/default.jpg';
$dir = dirname($path);
if (!is_dir($dir)) mkdir($dir, 0755, true);

// Create a 300x200 image
$width = 300;
$height = 200;
$image = imagecreatetruecolor($width, $height);
$bg = imagecolorallocate($image, 240, 240, 240); // light gray
$txt = imagecolorallocate($image, 100, 100, 100); // dark gray

// Fill background
imagefilledrectangle($image, 0, 0, $width, $height, $bg);

// Add text
$fontSize = 5; // built-in font
$text = 'Sin imagen';
$bbox = ['w' => imagefontwidth($fontSize) * strlen($text), 'h' => imagefontheight($fontSize)];
$x = intval(($width - $bbox['w']) / 2);
$y = intval(($height - $bbox['h']) / 2);
imagestring($image, $fontSize, $x, $y, $text, $txt);

// Save as JPEG
imagejpeg($image, $path, 85);
imagedestroy($image);

echo "default.jpg created at: " . realpath($path);
?>