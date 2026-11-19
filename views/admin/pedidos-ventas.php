<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos y Ventas - BaggyCulture Admin</title>
    <link rel="stylesheet" href="../../assets/css/navbar-admin-principal.css">
    <link rel="stylesheet" href="../../assets/css/pedidos-ventas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">
    <?php include __DIR__ . '/../../includes/navbar-admin-principal.php'; ?>
    <main class="admin-container">
        <!-- Encabezado del módulo -->
        <header class="module-header">
            <div class="header-titles">
                <h1>Gestión de Pedidos y Ventas</h1>
                <p>Monitorea transacciones, despachos y estados de pedidos en tiempo real.</p>
            </div>
            <button id="btnRefresh" class="btn btn-secondary">
                <i class="fa-solid fa-arrows-rotate"></i> Actualizar
            </button>
        </header>

        <!-- Métricas KPI Superiores -->
        <section class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon icon-revenue"><i class="fa-solid fa-wallet"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">Ingresos Totales</span>
                    <h3 id="kpiTotalVentas">Bs. 0.00</h3>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon icon-pending"><i class="fa-solid fa-clock"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">Por Despachar</span>
                    <h3 id="kpiPendientes">0</h3>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon icon-completed"><i class="fa-solid fa-circle-check"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">Entregados</span>
                    <h3 id="kpiEntregados">0</h3>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon icon-cancelled"><i class="fa-solid fa-ban"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">Cancelados</span>
                    <h3 id="kpiCancelados">0</h3>
                </div>
            </div>
        </section>

        <!-- Barra de Búsqueda y Filtros -->
        <section class="toolbar-section">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Buscar por cliente, ID de pedido o dirección...">
            </div>
            <div class="filter-group">
                <select id="filterEstado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="en_camino">En camino</option>
                    <option value="entregado">Entregado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
                <select id="filterMetodo" class="form-select">
                    <option value="">Todos los métodos de pago</option>
                    <option value="Efectivo contra entrega">Efectivo contra entrega</option>
                    <option value="QR / Transferencia">QR / Transferencia</option>
                </select>
            </div>
        </section>

        <!-- Tabla Principal de Pedidos -->
        <section class="table-container">
            <table class="custom-table" id="tablaPedidos">
                <thead>
                    <tr>
                        <th width="90">Nº Pedido</th>
                        <th>Fecha y Hora</th>
                        <th>Cliente</th>
                        <th>Método Pago</th>
                        <th>Total</th>
                        <th>Estado Actual</th>
                        <th width="120" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="pedidosTableBody">
                    <tr>
                        <td colspan="7" class="text-center py-4">Cargando pedidos...</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>

    <!-- MODAL: Detalle Completo del Pedido -->
    <div class="modal-overlay" id="orderModal">
        <div class="modal-card">
            <div class="modal-header">
                <div>
                    <h3 id="modalOrderNumber">Detalle del Pedido #--</h3>
                    <small id="modalOrderDate" class="text-muted">Fecha: --/--/----</small>
                </div>
                <button type="button" class="btn-close" id="btnCloseModal">&times;</button>
            </div>

            <div class="modal-body">
                <!-- Información de Cliente y Entrega -->
                <div class="order-info-grid">
                    <div class="info-block">
                        <span class="info-label"><i class="fa-solid fa-user"></i> Datos del Cliente</span>
                        <strong id="modalCustomerName">--</strong>
                        <p id="modalCustomerContact" class="text-muted">--</p>
                    </div>
                    <div class="info-block">
                        <span class="info-label"><i class="fa-solid fa-location-dot"></i> Dirección de Envío</span>
                        <p id="modalShippingAddress">--</p>
                    </div>
                    <div class="info-block">
                        <span class="info-label"><i class="fa-solid fa-credit-card"></i> Pago y Estado</span>
                        <p id="modalPaymentMethod">--</p>
                        <span id="modalOrderStatusBadge" class="badge">--</span>
                    </div>
                </div>

                <!-- Desglose de prendas -->
                <h4 class="form-section-title">Prendas de la Orden</h4>
                <div class="table-container mini-table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th width="60">Foto</th>
                                <th>Prenda</th>
                                <th>Talla / Color</th>
                                <th>Cant.</th>
                                <th>Precio Unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalOrderItemsBody">
                            <!-- Items cargados dinámicamente -->
                        </tbody>
                    </table>
                </div>

                <!-- Total general -->
                <div class="order-total-bar">
                    <span>Total de la Orden:</span>
                    <strong id="modalOrderTotal">Bs. 0.00</strong>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" id="btnCancelModal">Cerrar</button>
            </div>
        </div>
    </div>

    
    <script src="../../assets/js/pedidos.js"></script>
</body>
</html>