<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    require_once __DIR__ . '/../../includes/auth-guard.php';
}

require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../models/producto.php';

$productoModel = new Producto($pdo);
$productos = $productoModel->obtenerTodos();
$mensaje = $_SESSION['admin_msg'] ?? null;
unset($_SESSION['admin_msg']);

$totalVentas = 0.0;
$pedidosPendientes = 0;
$subastasActivas = 0;
$totalProductos = 0;


try {
    $stmtVentas = $pdo->query("
        SELECT COALESCE(SUM(total), 0) AS total_ventas 
        FROM pedidos 
        WHERE estado IN ('pagado', 'enviado', 'entregado')
    ");
    $totalVentas = (float)$stmtVentas->fetchColumn();

    $stmtPendientes = $pdo->query("
        SELECT COUNT(*) AS total_pendientes 
        FROM pedidos 
        WHERE estado = 'pendiente'
    ");
    $pedidosPendientes = (int)$stmtPendientes->fetchColumn();

    $stmtSubastas = $pdo->query("
        SELECT COUNT(*) AS total_activas 
        FROM subastas 
        WHERE estado = 'activa'
    ");
    $subastasActivas = (int)$stmtSubastas->fetchColumn();

    $stmtProductos = $pdo->query("
        SELECT COUNT(*) AS total_productos 
        FROM productos
    ");
    $totalProductos = (int)$stmtProductos->fetchColumn();

    
    $stmtChartPedidos = $pdo->query("
        SELECT estado, COUNT(*) AS cantidad 
        FROM pedidos 
        GROUP BY estado
    ");
    $dataPedidosRaw = $stmtChartPedidos->fetchAll(PDO::FETCH_ASSOC);

    $stmtChartCat = $pdo->query("
        SELECT COALESCE(categoria, 'General') AS categoria, COUNT(*) AS total 
        FROM productos 
        GROUP BY categoria
    ");
    $dataCatRaw = $stmtChartCat->fetchAll(PDO::FETCH_ASSOC);

    $chartPedidosLabels = array_column($dataPedidosRaw, 'estado');
    $chartPedidosValues = array_map('intval', array_column($dataPedidosRaw, 'cantidad'));

    $chartCatLabels = array_column($dataCatRaw, 'categoria');
    $chartCatValues = array_map('intval', array_column($dataCatRaw, 'total'));


//bloques de abajo de subastas
    $stmtUltimosPedidos = $pdo->query("
        SELECT p.id_pedido, p.total, p.estado, p.fecha_pedido,
               CONCAT(u.nombre, ' ', COALESCE(u.apellido, '')) AS cliente
        FROM pedidos p
        INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
        ORDER BY p.fecha_pedido DESC
        LIMIT 5
    ");
    $ultimosPedidos = $stmtUltimosPedidos->fetchAll(PDO::FETCH_ASSOC);

    // 2. Subastas activas ordenadas por la más próxima a cerrar
    $stmtSubastasCierre = $pdo->query("
        SELECT s.id_subasta, s.precio_actual, s.fecha_fin, s.estado,
               pr.nombre AS producto
        FROM subastas s
        INNER JOIN productos pr ON s.id_producto = pr.id_producto
        WHERE s.estado = 'activa'
        ORDER BY s.fecha_fin ASC
        LIMIT 5
    ");
    $subastasCierre = $stmtSubastasCierre->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error cargando KPIs en admin.php: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador Principal | BaggyCulture</title>
    <link rel="stylesheet" href="../../assets/css/navbar-admin-principal.css">
    <link rel="stylesheet" href="../../assets/css/subastas-admin.css">
    <link rel="stylesheet" href="../../assets/css/subastador.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_kpis.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-body">

    <?php include __DIR__ . '/../../includes/navbar-admin-principal.php'; ?>

    <main class="admin-container">
        <header class="section-header">
            <div>
                <h1>Principal Dashboard</h1>
                <p class="subtitle">Panel de control, catálogo de inventario y administración general de BaggyCulture</p>
            </div>
        </header>

        <section class="kpi-container">
            <div class="kpi-grid">
                <!-- Tarjeta 1: Total Ventas -->
                <article class="kpi-card">
                    <div class="kpi-info">
                        <p class="kpi-label">Total Ventas</p>
                        <h3 class="kpi-value" id="kpi-ventas">Bs. <?= number_format($totalVentas, 2) ?></h3>
                        <span class="kpi-subtext highlight-green">Ingresos confirmados</span>
                    </div>
                    <div class="kpi-icon-wrapper icon-ventas">
                        <span>💰</span>
                    </div>
                </article>

                <!-- Tarjeta 2: Pedidos Pendientes -->
                <article class="kpi-card">
                    <div class="kpi-info">
                        <p class="kpi-label">Pedidos Pendientes</p>
                        <h3 class="kpi-value" id="kpi-pedidos"><?= $pedidosPendientes ?></h3>
                        <span class="kpi-subtext highlight-amber">Requieren atención</span>
                    </div>
                    <div class="kpi-icon-wrapper icon-pedidos">
                        <span>📦</span>
                    </div>
                </article>

                <!-- Tarjeta 3: Subastas Activas -->
                <article class="kpi-card">
                    <div class="kpi-info">
                        <p class="kpi-label">Subastas Activas</p>
                        <h3 class="kpi-value" id="kpi-subastas"><?= $subastasActivas ?></h3>
                        <span class="kpi-subtext highlight-blue">En curso ahora</span>
                    </div>
                    <div class="kpi-icon-wrapper icon-subastas">
                        <span>🔨</span>
                    </div>
                </article>

                <!-- Tarjeta 4: Productos Catálogo -->
                <article class="kpi-card">
                    <div class="kpi-info">
                        <p class="kpi-label">Productos Catálogo</p>
                        <h3 class="kpi-value" id="kpi-productos"><?= $totalProductos ?></h3>
                        <span class="kpi-subtext highlight-gold">Total en plataforma</span>
                    </div>
                    <div class="kpi-icon-wrapper icon-productos">
                        <span>🏷️</span>
                    </div>
                </article>
            </div>
        </section>


        <section class="charts-container">
  <div class="charts-grid">
    <!-- Gráfico 1: Pedidos -->
    <article class="chart-card">
      <div class="chart-header">
        <h3 class="chart-title">Distribución de Pedidos</h3>
        <p class="chart-subtitle">Estado de órdenes en el sistema</p>
      </div>
      <div class="chart-body">
        <canvas id="chartPedidos"></canvas>
      </div>
    </article>

    <!-- Gráfico 2: Categorías de Ropa -->
    <article class="chart-card">
      <div class="chart-header">
        <h3 class="chart-title">Inventario por Categoría</h3>
        <p class="chart-subtitle">Volumen de productos en catálogo</p>
      </div>
      <div class="chart-body">
        <canvas id="chartCategorias"></canvas>
      </div>
    </article>
  </div>
</section>



<!-- Sección Tablas Rápidas -->
        <section class="tables-container">
            <div class="tables-grid">
                <!-- Tabla 1: Órdenes Recientes -->
                <article class="table-card">
                    <div class="table-card-header">
                        <h3 class="table-title">Órdenes Recientes</h3>
                        
                    </div>
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($ultimosPedidos)): ?>
                                    <?php foreach ($ultimosPedidos as $p): ?>
                                        <tr>
                                            <td>#<?= htmlspecialchars($p['id_pedido']) ?></td>
                                            <td><?= htmlspecialchars($p['cliente']) ?></td>
                                            <td>Bs. <?= number_format((float)$p['total'], 2) ?></td>
                                            <td>
                                                <span class="badge-status badge-<?= strtolower($p['estado']) ?>">
                                                    <?= htmlspecialchars($p['estado']) ?>
                                                </span>
                                            </td>
                                            <td><?= date('d/m H:i', strtotime($p['fecha_pedido'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="empty-cell">No hay pedidos registrados</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </article>

                <!-- Tabla 2: Subastas en Curso -->
                <article class="table-card">
                    <div class="table-card-header">
                        <h3 class="table-title">Subastas Próximas a Cerrar</h3>
                        <a href="../subastas.php" class="table-link">Gestionar &rarr;</a>
                    </div>
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Prenda</th>
                                    <th>Puja Actual</th>
                                    <th>Cierre</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($subastasCierre)): ?>
                                    <?php foreach ($subastasCierre as $sub): ?>
                                        <tr>
                                            <td>#<?= htmlspecialchars($sub['id_subasta']) ?></td>
                                            <td><?= htmlspecialchars($sub['producto']) ?></td>
                                            <td>Bs. <?= number_format((float)$sub['precio_actual'], 2) ?></td>
                                            <td><?= date('d/m H:i', strtotime($sub['fecha_fin'])) ?></td>
                                            <td>
                                                <span class="badge-status badge-activa">Activa</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="empty-cell">No hay subastas activas actualmente</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </article>
            </div>
        </section>


        <!-- Sección Acciones Rápidas -->
        <section class="actions-container">
            <div class="actions-header">
                <h3 class="actions-title">Acciones Rápidas</h3>
                <p class="actions-subtitle">Accesos directos a las operaciones frecuentes de BaggyCulture</p>
            </div>
            
            <div class="actions-grid">
                <!-- Acción 1: Nuevo Producto -->
                <a href="gestion-productos.php" class="action-card">
                    <div class="action-icon">➕</div>
                    <div class="action-info">
                        <h4>Nuevo Producto</h4>
                        <p>Agregar prenda o variantes</p>
                    </div>
                </a>

                <!-- Acción 2: Crear Subasta -->
                <a href="pedidos-ventas.php" class="action-card">
                    <div class="action-icon">🔨</div>
                    <div class="action-info">
                        <h4>Pedidos y Ventas</h4>
                        <p>Controlar pedidos y ventas</p>
                    </div>
                </a>

                <!-- Acción 3: Gestión de Pedidos -->
                <a href="gestion-usuarios.php" class="action-card">
                    <div class="action-icon">📦</div>
                    <div class="action-info">
                        <h4>Usuarios</h4>
                        <p>Actualizar usuarioss</p>
                    </div>
                </a>

                <!-- Acción 4: Registro de Auditoría -->
                <a href="auditoria-gral.php" class="action-card">
                    <div class="action-icon">🛡️</div>
                    <div class="action-info">
                        <h4>Auditoría</h4>
                        <p>Historial y triggers del sistema</p>
                    </div>
                </a>
            </div>
        </section>
    </main>

    <script src="../../assets/js/dashboard_kpis.js"></script>
</body>
</html>
