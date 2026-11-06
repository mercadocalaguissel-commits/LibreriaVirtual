<?php
require_once "conexion.php";

$mensaje = "";

$resultado = $conexion->query(
    "SELECT COUNT(*) AS total FROM administradores"
);

$total = $resultado->fetch_assoc()["total"];

if ($_SERVER["REQUEST_METHOD"] === "POST" && $total == 0) {

    $usuario = trim($_POST["usuario"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($usuario === "" || strlen($password) < 12) {

        $mensaje = "Escribe un usuario y una contraseña de al menos 12 caracteres.";

    } else {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conexion->prepare(
            "INSERT INTO administradores (usuario, password) VALUES (?, ?)"
        );

        $stmt->bind_param("ss", $usuario, $hash);
        $stmt->execute();

        $mensaje = "Administrador creado correctamente.";

        $total = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear administrador - Librería JP</title>
</head>
<body>

<h2>Crear administrador - Librería JP</h2>

<p><?= htmlspecialchars($mensaje) ?></p>

<?php if ($total == 0): ?>

<form method="POST">

    <input
        type="text"
        name="usuario"
        placeholder="Nombre de usuario"
        required>

    <br><br>

    <input
        type="password"
        name="password"
        placeholder="Contraseña (mínimo 12 caracteres)"
        minlength="12"
        required>

    <br><br>

    <button type="submit">Crear administrador</button>

</form>

<?php else: ?>

<p>Ya existe un administrador. No se puede crear otro desde esta página.</p>

<?php endif; ?>

</body>
</html>