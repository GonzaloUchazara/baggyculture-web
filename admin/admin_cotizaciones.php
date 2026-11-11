<?php
session_start();

$_SESSION['usuario'] = 'AdminPrueba';
$_SESSION['rol'] = 'admin';
$_SESSION['usuario_id'] = 1;

$conexion = mysqli_connect("localhost", "root", "", "baggy_culture");
if (!$conexion) die("Error de conexion: " . mysqli_connect_error());
mysqli_set_charset($conexion, "utf8");

$mensaje = '';

if (isset($_GET['estado']) && isset($_GET['id'])) {
    $id     = intval($_GET['id']);
    $estado = mysqli_real_escape_string($conexion, $_GET['estado']);
    mysqli_query($conexion, "UPDATE cotizacion SET estado='$estado' WHERE id_cotizacion=$id");
    
    header("Location: admin_cotizaciones.php?msg=" . urlencode('Estado actualizado a: ' . ucfirst($estado)));
    exit;
}

if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    mysqli_query($conexion, "DELETE FROM cotizacion WHERE id_cotizacion=$id");
    
    header("Location: admin_cotizaciones.php?msg=" . urlencode('Cotizacion eliminada'));
    exit;
}

if (isset($_GET['msg'])) {
    $mensaje = 'ok|' . htmlspecialchars($_GET['msg']);
}

$sql = "SELECT c.*, 
               u.nombre AS nombre_usuario, 
               u.apellido AS apellido_usuario, 
               u.telefono AS tel_usuario, 
               u.direccion AS ciudad_usuario
        FROM cotizacion c
        LEFT JOIN usuarios u ON c.id_usuario = u.id_usuario
        ORDER BY c.id_cotizacion DESC";

$cotizaciones = mysqli_query($conexion, $sql);

if (!$cotizaciones) {
    // Consulta directa de respaldo si falla el JOIN
    $cotizaciones = mysqli_query($conexion, "SELECT * FROM cotizacion ORDER BY id_cotizacion DESC");
}

if (!$cotizaciones) die("Error en consulta: " . mysqli_error($conexion));
$total = mysqli_num_rows($cotizaciones);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Cotizaciones</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="sidebar">
    <div class="logo">BaggyCulture</div>
    <div class="seccion">General</div>
    <a href="admin_dashboard.php">Dashboard</a>
    <div class="seccion">Gestion</div>
    <a href="admin_usuarios.php">Usuarios</a>
    <a href="admin_productos.php">Productos</a>
    <a href="admin_cotizaciones.php" class="active">Cotizaciones</a>
    <a href="admin_pedidos.php">Pedidos</a>
    <a href="../index.php" target="_blank">Ver Tienda</a>
    <a href="../pages/logout.php">Cerrar Sesion</a>
</div>

<div class="contenido">
    <div class="page-titulo">Gestion de Cotizaciones</div>

    <?php if ($mensaje):
        [$tipo, $texto] = explode('|', $mensaje); ?>
        <div class="mensaje mensaje-<?= $tipo === 'ok' ? 'ok' : 'error' ?>">
            <?= $texto ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h3>Todas las cotizaciones (<?= $total ?>)</h3>
            <input type="text" class="buscador" placeholder="Buscar..."
                   onkeyup="buscar(this.value)">
        </div>
        <table id="tabla-cot">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Telefono</th>
                    <th>Ciudad</th>
                    <th>Descripcion / Producto</th>
                    <th>Total (Bs.)</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($c = mysqli_fetch_assoc($cotizaciones)): ?>
                <?php 
                    $cot_id = $c['id_cotizacion']; 
                    $nombre_completo = trim(($c['nombre_usuario'] ?? '') . ' ' . ($c['apellido_usuario'] ?? ''));
                    if (empty(trim($nombre_completo))) {
                        $nombre_completo = 'Usuario #' . ($c['id_usuario'] ?? 'N/A');
                    }
                ?>
                <tr>
                    <td><code>#<?= $cot_id ?></code></td>
                    <td><b><?= htmlspecialchars($nombre_completo) ?></b></td>
                    <td><?= htmlspecialchars($c['tel_usuario'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($c['ciudad_usuario'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($c['descripcion'] ?? 'N/A') ?></td>
                    <td><b>Bs. <?= number_format($c['total'] ?? 0, 2) ?></b></td>
                    <td><?= isset($c['fecha']) ? date('d/m/Y H:i', strtotime($c['fecha'])) : 'N/A' ?></td>
                    <td>
                        <?php $est = strtolower($c['estado'] ?? 'pendiente'); ?>
                        <span class="badge badge-<?= $est ?>">
                            <?= ucfirst($est) ?>
                        </span>
                    </td>
                    <td style="display:flex;gap:4px;flex-wrap:wrap">
                        <a href="?id=<?= $cot_id ?>&estado=Aprobada" class="btn btn-verde">Aprobar</a>
                        <a href="?id=<?= $cot_id ?>&estado=Pendiente" class="btn btn-azul">Pendiente</a>
                        <?php $tel = $c['tel_usuario'] ?? ''; ?>
                        <?php if (!empty($tel)): ?>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $tel) ?>"
                           target="_blank" class="btn btn-azul">WhatsApp</a>
                        <?php endif; ?>
                        <a href="?eliminar=<?= $cot_id ?>" class="btn btn-rojo"
                           onclick="return confirm('¿Eliminar esta cotizacion?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function buscar(texto) {
    document.querySelectorAll('#tabla-cot tbody tr').forEach(function(f) {
        f.style.display = f.textContent.toLowerCase().includes(texto.toLowerCase()) ? '' : 'none';
    });
}
</script>

</body>
</html>