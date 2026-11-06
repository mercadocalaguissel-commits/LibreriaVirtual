<?php
session_start();

if (!isset($_SESSION["id_admin"])) {
    header("Location: login.php");
    exit;
}

require_once "conexion.php";

function escapar($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}

$pedidos = $conexion->query(
    "SELECT * FROM pedidos ORDER BY fecha DESC LIMIT 10"
);

$solicitudes = $conexion->query(
    "SELECT * FROM solicitudes ORDER BY fecha DESC LIMIT 10"
);

$productos = $conexion->query(
    "SELECT * FROM productos ORDER BY nombre ASC"
);

$totalPedidos = $conexion->query(
    "SELECT COUNT(*) AS total FROM pedidos"
)->fetch_assoc()["total"];

$totalSolicitudes = $conexion->query(
    "SELECT COUNT(*) AS total FROM solicitudes"
)->fetch_assoc()["total"];

$totalProductos = $conexion->query(
    "SELECT COUNT(*) AS total FROM productos"
)->fetch_assoc()["total"];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administración - Librería JP</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f8;
            margin: 0;
            color: #26344d;
        }

        header {
            background: #243b64;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        header a {
            color: white;
            text-decoration: none;
            background: #405b8b;
            padding: 10px 15px;
            border-radius: 6px;
        }

        main {
            padding: 25px;
        }

        .resumen {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .tarjeta {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 3px 10px #0001;
        }

        .tarjeta h3 {
            margin-top: 0;
        }

        .numero {
            font-size: 30px;
            font-weight: bold;
            color: #243b64;
        }

        .tabla-contenedor {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #e9edf5;
        }

        h2 {
            color: #243b64;
        }
    </style>
</head>

<body>

<header>
    <div>
        <h2 style="color:white;">📚 Librería Estudiantil JP</h2>
        Bienvenido, <?= escapar($_SESSION["usuario"]) ?>
    </div>

    <a href="logout.php">Cerrar sesión</a>
</header>

<main>

    <h1>Panel de administración</h1>

    <div class="resumen">

        <div class="tarjeta">
            <h3>Pedidos</h3>
            <div class="numero"><?= $totalPedidos ?></div>
        </div>

        <div class="tarjeta">
            <h3>Solicitudes</h3>
            <div class="numero"><?= $totalSolicitudes ?></div>
        </div>

        <div class="tarjeta">
            <h3>Productos</h3>
            <div class="numero"><?= $totalProductos ?></div>
        </div>

    </div>

    <div class="tabla-contenedor">

        <h2>Últimos pedidos</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Teléfono</th>
                <th>Total</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>

            <?php while ($fila = $pedidos->fetch_assoc()): ?>
            <tr>
                <td><?= escapar($fila["id_pedido"]) ?></td>
                <td><?= escapar($fila["nombre_cliente"]) ?></td>
                <td><?= escapar($fila["telefono"]) ?></td>
                <td>Bs. <?= escapar($fila["total"]) ?></td>
                <td><?= escapar($fila["fecha"]) ?></td>
                <td><?= escapar($fila["estado"]) ?></td>
            </tr>
            <?php endwhile; ?>

        </table>

    </div>

    <div class="tabla-contenedor">

        <h2>Últimas solicitudes</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Producto</th>
                <th>Método de pago</th>
                <th>Fecha</th>
            </tr>

            <?php while ($fila = $solicitudes->fetch_assoc()): ?>
            <tr>
                <td><?= escapar($fila["id_solicitud"]) ?></td>
                <td><?= escapar($fila["nombre"]) ?></td>
                <td><?= escapar($fila["correo"]) ?></td>
                <td><?= escapar($fila["producto"]) ?></td>
                <td><?= escapar($fila["metodo_pago"]) ?></td>
                <td><?= escapar($fila["fecha"]) ?></td>
            </tr>
            <?php endwhile; ?>

        </table>

    </div>

    <div class="tabla-contenedor">

        <h2>Productos registrados</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Stock</th>
            </tr>

            <?php while ($fila = $productos->fetch_assoc()): ?>
            <tr>
                <td><?= escapar($fila["id_producto"]) ?></td>
                <td><?= escapar($fila["nombre"]) ?></td>
                <td>Bs. <?= escapar($fila["precio"]) ?></td>
                <td><?= escapar($fila["stock"]) ?></td>
            </tr>
            <?php endwhile; ?>

        </table>

    </div>

</main>

</body>
</html>