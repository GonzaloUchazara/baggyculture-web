<?php
session_start();

$_SESSION['usuario'] = 'AdminPrueba';
$_SESSION['rol'] = 'admin';
$_SESSION['usuario_id'] = 1;

$conexion = mysqli_connect("localhost", "root", "", "baggy_culture");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

$res_usuarios = mysqli_query($conexion, "SELECT COUNT(*) as t FROM usuarios");
$total_usuarios = $res_usuarios ? mysqli_fetch_assoc($res_usuarios)['t'] : 0;

$res_productos = mysqli_query($conexion, "SELECT COUNT(*) as t FROM productos");
$total_productos = $res_productos ? mysqli_fetch_assoc($res_productos)['t'] : 0;

$res_cotizaciones = mysqli_query($conexion, "SELECT COUNT(*) as t FROM cotizaciones");
$total_cotizaciones = $res_cotizaciones ? mysqli_fetch_assoc($res_cotizaciones)['t'] : 0;

$res_ventas = mysqli_query($conexion, "SELECT SUM(total) as t FROM pedidos WHERE estado != 'cancelado'");
$total_ventas = $res_ventas ? (mysqli_fetch_assoc($res_ventas)['t'] ?? 0) : 0;

$ultimas_cot = mysqli_query($conexion, "SELECT * FROM cotizaciones ORDER BY fecha DESC LIMIT 5");
$ultimos_ped = mysqli_query($conexion, "
    SELECT p.*, c.nombre as cliente 
    FROM pedidos p 
    LEFT JOIN clientes c ON p.cliente_id = c.id 
    ORDER BY p.creado DESC LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Dashboard</title>
    <!-- RUTA RELATIVA PARA EL CSS -->
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="sidebar">
    <div class="logo">BaggyCulture</div>
    <div class="seccion">General</div>
    <a href="admin_dashboard.php" class="active"> Dashboard</a>
    <div class="seccion">Gestión</div>
    <a href="admin_usuarios.php"> Usuarios</a>
    <a href="admin_productos.php"> Productos</a>
    <a href="admin_cotizaciones.php"> Cotizaciones</a>
    <a href="admin_pedidos.php"> Pedidos</a>
    <a href="../index.php" target="_blank"> Ver Tienda</a>
    <a href="../pages/logout.php"> Cerrar Sesión</a>
</div>

<div class="contenido">
    <div class="page-titulo"> Dashboard — Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?></div>

    <div class="stats">
        <div class="stat-card">
            <div class="numero"><?= $total_usuarios ?></div>
            <div class="etiqueta">Usuarios</div>
        </div>
        <div class="stat-card">
            <div class="numero"><?= $total_productos ?></div>
            <div class="etiqueta">Productos</div>
        </div>
        <div class="stat-card">
            <div class="numero"><?= $total_cotizaciones ?></div>
            <div class="etiqueta">Cotizaciones</div>
        </div>
        <div class="stat-card">
            <div class="numero">Bs. <?= number_format($total_ventas, 0) ?></div>
            <div class="etiqueta">Total Ventas</div>
        </div>
    </div>

    <!-- ÚLTIMAS COTIZACIONES -->
    <div class="card" style="margin-bottom:20px">
        <div class="card-header">
            <h3> Últimas cotizaciones</h3>
            <a href="admin_cotizaciones.php" class="btn btn-oscuro">Ver todas</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Producto</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($ultimas_cot && mysqli_num_rows($ultimas_cot) > 0): ?>
                    <?php while ($c = mysqli_fetch_assoc($ultimas_cot)): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['nombre'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['telefono'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['producto'] ?? '') ?></td>
                        <td><?= isset($c['fecha']) ? date('d/m/Y H:i', strtotime($c['fecha'])) : 'N/A' ?></td>
                        <td><span class="badge badge-<?= $c['estado'] ?? 'pendiente' ?>"><?= ucfirst($c['estado'] ?? 'pendiente') ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;color:#aaa">No hay cotizaciones aún</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ÚLTIMOS PEDIDOS -->
    <div class="card">
        <div class="card-header">
            <h3> Últimos pedidos</h3>
            <a href="admin_pedidos.php" class="btn btn-oscuro">Ver todos</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Ciudad</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($ultimos_ped && mysqli_num_rows($ultimos_ped) > 0): ?>
                    <?php while ($p = mysqli_fetch_assoc($ultimos_ped)): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['cliente'] ?? 'Cliente General') ?></td>
                        <td><b>Bs. <?= number_format($p['total'] ?? 0, 2) ?></b></td>
                        <td><?= htmlspecialchars($p['ciudad_entrega'] ?? 'N/A') ?></td>
                        <td><?= isset($p['creado']) ? date('d/m/Y H:i', strtotime($p['creado'])) : 'N/A' ?></td>
                        <td><span class="badge badge-<?= $p['estado'] ?? 'pendiente' ?>"><?= ucfirst($p['estado'] ?? 'pendiente') ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;color:#aaa">No hay pedidos aún</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>