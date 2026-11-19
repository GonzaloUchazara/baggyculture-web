<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../models/subasta.php';

$modelo = new subasta($pdo);
$tipo = $_GET['tipo'] ?? 'cambios';

if ($tipo === 'pujas') {
    $auditorias = $modelo->obtenerAuditoriaSubastas();
} else {
    $auditorias = $modelo->obtenerAuditoriaGeneral();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Auditoría de Subastas | BaggyCulture</title>
    <link rel="stylesheet" href="../../assets/css/subastas-admin.css">
    <link rel="stylesheet" href="../../assets/css/subastador.css">
    <link rel="stylesheet" href="../../assets/css/admin-auditoria.css">
</head>
<body class="admin-body">
    <?php include __DIR__ . '/../../includes/navbar-admin.php'; ?>
    <main class="admin-container">
        <header class="section-header">
            <div>
                <h1>Registro de Auditoría de Subastas</h1>
                <p class="subtitle">
                    <?= $tipo === 'pujas' ? 'Historial de variaciones de precio e incrementos de pujas' : 'Historial de creaciones, modificaciones y eliminaciones (Triggers)' ?>
                </p>
            </div>
            <a href="subastador.php" class="btn btn-secondary">ir a Subastas</a>
        </header>

        <div class="auditoria-tabs">
            <a href="?tipo=cambios" class="tab-link <?= $tipo === 'cambios' ? 'active' : '' ?>">
                Cambios en Subastas (INSERT / UPDATE / DELETE)
            </a>
            <a href="?tipo=pujas" class="tab-link <?= $tipo === 'pujas' ? 'active' : '' ?>">
                Historial de Pujas (Incrementos)
            </a>
        </div>

        <section class="table-card">
            <?php if ($tipo === 'pujas'): ?>
                <!-- TABLA HISTORIAL DE PUJAS (auditoria_subastas) -->
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID Reg.</th>
                            <th>Subasta / Producto</th>
                            <th>Usuario</th>
                            <th>Precio Anterior</th>
                            <th>Precio Nuevo</th>
                            <th>Diferencia</th>
                            <th>Acción</th>
                            <th>Fecha de Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($auditorias)): ?>
                            <tr>
                                <td colspan="8" class="text-center">No hay registros de pujas disponibles.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($auditorias as $reg): ?>
                                <?php $diff = $reg['precio_nuevo'] - $reg['precio_anterior']; ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($reg['id_auditoria']) ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($reg['producto_nombre'] ?? 'Producto') ?></strong>
                                        <small class="text-muted d-block">Subasta #<?= htmlspecialchars($reg['id_subasta']) ?></small>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($reg['usuario_nombre'] ?? 'Usuario #' . $reg['id_usuario']) ?>
                                    </td>
                                    <td>Bs. <?= number_format($reg['precio_anterior'], 2) ?></td>
                                    <td>
                                        <span class="text-highlight">Bs. <?= number_format($reg['precio_nuevo'], 2) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge-diff <?= $diff >= 0 ? 'diff-up' : 'diff-down' ?>">
                                            <?= ($diff >= 0 ? '+' : '') . number_format($diff, 2) ?> Bs.
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-action">
                                            <?= htmlspecialchars($reg['accion']) ?>
                                        </span>
                                    </td>
                                    <td class="text-muted">
                                        <?= date('d/m/Y H:i:s', strtotime($reg['fecha_registro'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <!-- TABLA CAMBIOS EN SUBASTAS (auditoria general) -->
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID Reg.</th>
                            <th>Operación</th>
                            <th>Subasta / Producto</th>
                            <th>Usuario BD</th>
                            <th>Detalle de Cambios / Datos</th>
                            <th>Fecha Operación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($auditorias)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay registros de cambios en subastas todavía. Realiza un cambio en el panel para observarlo aquí.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($auditorias as $reg): ?>
                                <?php
                                    $op = strtoupper($reg['operacion'] ?? '');
                                    $ant = !empty($reg['datos_anteriores']) ? json_decode($reg['datos_anteriores'], true) : [];
                                    $new = !empty($reg['datos_nuevos']) ? json_decode($reg['datos_nuevos'], true) : [];
                                    $badgeClass = 'badge-action';
                                    if ($op === 'INSERT') $badgeClass = 'badge-insert';
                                    elseif ($op === 'UPDATE') $badgeClass = 'badge-update';
                                    elseif ($op === 'DELETE') $badgeClass = 'badge-delete';
                                ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($reg['id_auditoria']) ?></td>
                                    <td>
                                        <span class="<?= $badgeClass ?>">
                                            <?= htmlspecialchars($op) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($reg['producto_nombre'] ?? 'Subasta #' . $reg['clave_registro']) ?></strong>
                                        <small class="text-muted d-block">ID Subasta: #<?= htmlspecialchars($reg['clave_registro']) ?></small>
                                    </td>
                                    <td>
                                        <span class="text-muted"><?= htmlspecialchars($reg['usuario_bd']) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($op === 'INSERT'): ?>
                                            <span class="change-item"><span class="change-key">Precio Base:</span> <span class="change-new">Bs. <?= number_format($new['precio_base'] ?? 0, 2) ?></span></span>
                                            <span class="change-item"><span class="change-key">Inc. Mín:</span> <span class="change-new">+Bs. <?= number_format($new['incremento_minimo'] ?? 0, 2) ?></span></span>
                                            <span class="change-item"><span class="change-key">Estado:</span> <span class="change-new"><?= htmlspecialchars($new['estado'] ?? 'activa') ?></span></span>
                                            <span class="change-item"><span class="change-key">Fin:</span> <?= htmlspecialchars($new['fecha_fin'] ?? '') ?></span>
                                        <?php elseif ($op === 'DELETE'): ?>
                                            <span class="change-item"><span class="change-key">Subasta eliminada</span> (Último precio: Bs. <?= number_format($ant['precio_actual'] ?? $ant['precio_base'] ?? 0, 2) ?> &bull; Estado: <?= htmlspecialchars($ant['estado'] ?? '') ?>)</span>
                                        <?php elseif ($op === 'UPDATE'): ?>
                                            <?php
                                                $cambios = [];
                                                $keys = array_unique(array_merge(array_keys($ant ?: []), array_keys($new ?: [])));
                                                foreach ($keys as $k) {
                                                    if ($k === 'id_subasta' || $k === 'id_producto') continue;
                                                    $valAnt = $ant[$k] ?? null;
                                                    $valNew = $new[$k] ?? null;
                                                    if ($valAnt != $valNew) {
                                                        $cambios[$k] = ['ant' => $valAnt, 'new' => $valNew];
                                                    }
                                                }
                                            ?>
                                            <?php if (empty($cambios)): ?>
                                                <span class="text-muted">Actualización sin variación de datos clave</span>
                                            <?php else: ?>
                                                <?php foreach ($cambios as $k => $c): ?>
                                                    <span class="change-item">
                                                        <span class="change-key"><?= ucfirst(str_replace('_', ' ', $k)) ?>:</span>
                                                        <span class="change-old"><?= htmlspecialchars((string)($c['ant'] ?? 'null')) ?></span>
                                                        &rarr;
                                                        <span class="change-new"><?= htmlspecialchars((string)($c['new'] ?? 'null')) ?></span>
                                                    </span>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted"><?= htmlspecialchars($reg['operacion']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted">
                                        <?= date('d/m/Y H:i:s', strtotime($reg['fecha_operacion'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>