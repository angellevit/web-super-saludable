<?php
require 'conexion.php';

$nombre = "adri";
$email = "adri@admin.com";
$password_plano = "adri123";

$password_hash = password_hash($password_plano, PASSWORD_DEFAULT);
$rol = "admin";

$stmt = $conexion->prepare(
    "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?,?,?,?)"
);
$stmt->bind_param("ssss", $nombre, $email, $password_hash, $rol);

if ($stmt->execute()) {
    echo "Admin creado. Ahora puedes entrar con: admin@admin.com / 123456";
} else {
    echo "Error al crear admin: " . $conexion->error;
}
