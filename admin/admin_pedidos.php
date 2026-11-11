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
    
    // Actualización de estado usando la clave id_pedido
    mysqli_query($conexion, "UPDATE pedidos SET estado='$estado' WHERE id_pedido=$id");

    header("Location: admin_pedidos.php?msg=" . urlencode('Estado actualizado a: ' . ucfirst($estado)));
    exit;
}

if (isset($_GET['msg'])) {
    $mensaje = 'ok|' . htmlspecialchars($_GET['msg']);
}

$sql = "SELECT p.*, c.nombre AS cliente, c.telefono AS tel_cliente
        FROM pedidos p
        LEFT JOIN clientes c ON p.cliente_id = c.id
        ORDER BY p.id_pedido DESC";

$pedidos = mysqli_query($conexion, $sql);

if (!$pedidos) {
    // Si la relación LEFT JOIN falla, hace una consulta directa ordenada por id_pedido
    $pedidos = mysqli_query($conexion, "SELECT * FROM pedidos ORDER BY id_pedido DESC");
}

if (!$pedidos) die("Error en consulta: " . mysqli_error($conexion));
$total = mysqli_num_rows($pedidos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Pedidos</title>
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
    <a href="admin_cotizaciones.php">Cotizaciones</a>
    <a href="admin_pedidos.php" class="active">Pedidos</a>
    <a href="../index.php" target="_blank">Ver Tienda</a>
    <a href="../pages/logout.php">Cerrar Sesion</a>
</div>

<div class="contenido">
    <div class="page-titulo">Gestion de Pedidos</div>

    <?php if ($mensaje):
        [$tipo, $texto] = explode('|', $mensaje); ?>
        <div class="mensaje mensaje-<?= $tipo === 'ok' ? 'ok' : 'error' ?>">
            <?= $texto ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h3>Todos los pedidos (<?= $total ?>)</h3>
            <input type="text" class="buscador" placeholder="Buscar..."
                   onkeyup="buscar(this.value)">
        </div>
        <table id="tabla-pedidos">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Ciudad</th>
                    <th>Pago</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Cambiar estado</th>
                    <th>Contacto</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($p = mysqli_fetch_assoc($pedidos)): ?>
                <?php $ped_id = $p['id_pedido'] ?? $p['id'] ?? ''; ?>
                <tr>
                    <td><code>#<?= $ped_id ?></code></td>
                    <td><b><?= htmlspecialchars($p['cliente'] ?? $p['nombre_cliente'] ?? 'Cliente General') ?></b></td>
                    <td><b>Bs. <?= number_format($p['total'] ?? 0, 2) ?></b></td>
                    <td><?= htmlspecialchars($p['ciudad_entrega'] ?? $p['ciudad'] ?? 'N/A') ?></td>
                    <td><?= ucfirst(htmlspecialchars($p['metodo_pago'] ?? 'N/A')) ?></td>
                    <td>
                        <?php $est = $p['estado'] ?? 'pendiente'; ?>
                        <span class="badge badge-<?= strtolower($est) ?>">
                            <?= ucfirst($est) ?>
                        </span>
                    </td>
                    <td><?= isset($p['creado']) ? date('d/m/Y H:i', strtotime($p['creado'])) : (isset($p['fecha']) ? date('d/m/Y H:i', strtotime($p['fecha'])) : 'N/A') ?></td>
                    <td style="display:flex;gap:4px;flex-wrap:wrap">
                        <a href="?id=<?= $ped_id ?>&estado=confirmado" class="btn btn-azul">Confirmar</a>
                        <a href="?id=<?= $ped_id ?>&estado=enviado"    class="btn btn-verde">Enviado</a>
                        <a href="?id=<?= $ped_id ?>&estado=completado" class="btn btn-oscuro">Entregado</a>
                        <a href="?id=<?= $ped_id ?>&estado=cancelado"  class="btn btn-rojo">Cancelar</a>
                    </td>
                    <td>
                        <?php $tel = $p['tel_cliente'] ?? $p['telefono'] ?? ''; ?>
                        <?php if (!empty($tel)): ?>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $tel) ?>"
                           target="_blank" class="btn btn-verde">WhatsApp</a>
                        <?php else: ?>
                        <span style="color:#aaa;font-size:12px">N/A</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function buscar(texto) {
    document.querySelectorAll('#tabla-pedidos tbody tr').forEach(function(f) {
        f.style.display = f.textContent.toLowerCase().includes(texto.toLowerCase()) ? '' : 'none';
    });
}
</script>
</body>
</html>