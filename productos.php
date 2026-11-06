<?php

require_once "conexion.php";

// Evitar que se muestre el mensaje de conexión
// y devolver los productos en formato JSON.

$consulta = "SELECT 
    p.id_producto,
    p.nombre,
    p.descripcion,
    p.precio,
    p.stock,
    p.imagen,
    c.nombre AS categoria
FROM productos p
INNER JOIN categorias c
ON p.id_categoria = c.id_categoria
ORDER BY p.id_producto ASC";

$resultado = $conexion->query($consulta);

$productos = [];

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = $fila;
    }
}

header("Content-Type: application/json; charset=utf-8");

echo json_encode($productos, JSON_UNESCAPED_UNICODE);

?>