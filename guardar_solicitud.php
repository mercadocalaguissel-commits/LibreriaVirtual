<?php
header("Content-Type: application/json; charset=utf-8");

require_once "conexion.php";

$datos = json_decode(file_get_contents("php://input"), true);

if (!$datos) {
    http_response_code(400);
    echo json_encode([
        "exito" => false,
        "mensaje" => "No se recibieron los datos"
    ]);
    exit;
}

$nombre = trim($datos["nombre"] ?? "");
$correo = trim($datos["correo"] ?? "");
$ubicacion = trim($datos["ubicacion"] ?? "");
$metodo_pago = trim($datos["metodo_pago"] ?? "");
$producto = trim($datos["producto"] ?? "");
$mensaje = trim($datos["mensaje"] ?? "");

if (
    $nombre === "" ||
    $correo === "" ||
    $ubicacion === "" ||
    $metodo_pago === "" ||
    $producto === "" ||
    $mensaje === "" ||
    !filter_var($correo, FILTER_VALIDATE_EMAIL)
) {
    http_response_code(400);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Completa todos los campos correctamente"
    ]);
    exit;
}

try {
    $sql = "INSERT INTO solicitudes
        (nombre, correo, ubicacion, metodo_pago, producto, mensaje)
        VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "ssssss",
        $nombre,
        $correo,
        $ubicacion,
        $metodo_pago,
        $producto,
        $mensaje
    );

    $stmt->execute();

    echo json_encode([
        "exito" => true,
        "mensaje" => "Solicitud guardada correctamente",
        "id_solicitud" => $conexion->insert_id
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Error al guardar la solicitud"
    ]);
}
?>