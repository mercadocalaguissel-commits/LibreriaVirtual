<?php
session_start();
require_once "conexion.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = trim($_POST["usuario"] ?? "");
    $password = $_POST["password"] ?? "";

    $sql = "SELECT id_admin, usuario, password
            FROM administradores
            WHERE usuario = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($admin = $resultado->fetch_assoc()) {

        if (password_verify($password, $admin["password"])) {

            session_regenerate_id(true);

            $_SESSION["id_admin"] = $admin["id_admin"];
            $_SESSION["usuario"] = $admin["usuario"];

            header("Location: admin.php");
            exit;

        } else {
            $error = "Usuario o contraseña incorrectos.";
        }

    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrador - Librería JP</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login {
            background: white;
            padding: 35px;
            border-radius: 15px;
            width: 320px;
            box-shadow: 0 5px 20px #0002;
        }

        h2 {
            text-align: center;
            color: #243b64;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #243b64;
            color: white;
            border: none;
            cursor: pointer;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="login">

    <h2>📚 Librería JP</h2>

    <h3>Panel de administración</h3>

    <?php if ($error !== ""): ?>
        <p class="error">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <input
            type="text"
            name="usuario"
            placeholder="Usuario"
            required>

        <input
            type="password"
            name="password"
            placeholder="Contraseña"
            required>

        <button type="submit">
            Iniciar sesión
        </button>

    </form>

</div>

</body>
</html>