<?php
$host = "localhost";
$usuario = "root";
$pass = "";
$bd = "proyecto_bodri";

$conexion = new mysqli($host, $usuario, $pass, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

session_start();
?>
