
<?php
header("Content-Type: application/json; charset=utf-8");

require_once "conexion.php";

$datos = json_decode(file_get_contents("php://input"), true);

if (!$datos || empty($datos["productos"]) || !isset($datos["total"])) {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Datos incompletos"
    ]);
    exit;
}

$nombre = trim($datos["nombre"] ?? "");
$telefono = trim($datos["telefono"] ?? "");
$total = (float)$datos["total"];
$productos = $datos["productos"];

if ($nombre === "" || $total <= 0) {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Nombre o total inválido"
    ]);
    exit;
}

try {
    $conexion->begin_transaction();

    $sql = "INSERT INTO pedidos (nombre_cliente, telefono, total)
            VALUES (?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssd", $nombre, $telefono, $total);
    $stmt->execute();

    $id_pedido = $conexion->insert_id;

    $sqlDetalle = "INSERT INTO detalle_pedido
        (id_pedido, id_producto, nombre_producto,
         precio_unitario, cantidad, subtotal)
        VALUES (?, ?, ?, ?, ?, ?)";

    $detalle = $conexion->prepare($sqlDetalle);

    foreach ($productos as $producto) {
        $id_producto = isset($producto["id_producto"])
            ? (int)$producto["id_producto"] : null;

        $nombre_producto = trim($producto["nombre"] ?? "");
        $precio = (float)($producto["precio"] ?? 0);
        $cantidad = (int)($producto["cantidad"] ?? 0);
        $subtotal = $precio * $cantidad;

        if ($nombre_producto === "" || $precio <= 0 || $cantidad <= 0) {
            throw new Exception("Producto inválido");
        }

        $detalle->bind_param(
            "iisdid",
            $id_pedido,
            $id_producto,
            $nombre_producto,
            $precio,
            $cantidad,
            $subtotal
        );

        $detalle->execute();
    }

    $conexion->commit();

    echo json_encode([
        "exito" => true,
        "mensaje" => "Pedido guardado correctamente",
        "id_pedido" => $id_pedido
    ]);

} catch (Throwable $e) {
    $conexion->rollback();

    http_response_code(400);

    echo json_encode([
        "exito" => false,
        "mensaje" => $e->getMessage()
    ]);
}
?>
