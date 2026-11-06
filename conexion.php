<?php

$conexion = new mysqli(
    "localhost",
    "libreria_app",
    "JP_Libreria2026!",
    "libreria_estudiantil_jp",
    3307
);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");

?>