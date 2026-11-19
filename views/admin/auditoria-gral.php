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
    <title>Auditoría del Sistema - BaggyCulture Admin</title>
    <!-- Estilos de la barra de navegación y del módulo de auditoría -->
    <link rel="stylesheet" href="../../assets/css/navbar-admin-principal.css">
    <link rel="stylesheet" href="../../assets/css/auditoria-gral.css">
    <!-- Iconos FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">

    <?php include __DIR__ . '/../../includes/navbar-admin-principal.php'; ?>

    <main class="admin-container">
        <!-- Encabezado del módulo -->
        <header class="module-header">
            <div class="header-titles">
                <h1>Registro de Auditoría</h1>
                <p>Historial y trazabilidad de eventos disparados por triggers en la base de datos.</p>
            </div>
            <button id="btnRefresh" class="btn btn-secondary">
                <i class="fa-solid fa-arrows-rotate"></i> Actualizar Logs
            </button>
        </header>

        <!-- Segmentación por Pestañas (Usuarios, Pedidos, Productos, etc.) -->
        <nav class="audit-tabs" aria-label="Segmentación de auditoría">
            <button type="button" class="tab-btn active" data-grupo="todas">
                <i class="fa-solid fa-list-check"></i> Todas las Auditorías
            </button>
            <button type="button" class="tab-btn" data-grupo="usuarios">
                <i class="fa-solid fa-users"></i> Auditoría de Usuarios
            </button>
            <button type="button" class="tab-btn" data-grupo="pedidos">
                <i class="fa-solid fa-cart-shopping"></i> Auditoría de Pedidos y Ventas
            </button>
            <button type="button" class="tab-btn" data-grupo="productos">
                <i class="fa-solid fa-shirt"></i> Auditoría de Productos
            </button>
            <button type="button" class="tab-btn" data-grupo="subastas">
                <i class="fa-solid fa-gavel"></i> Auditoría de Subastas
            </button>
        </nav>

        <!-- Tarjetas KPI de Auditoría -->
        <section class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon icon-total"><i class="fa-solid fa-database"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">Eventos Totales</span>
                    <h3 id="kpiTotalLogs">0</h3>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon icon-insert"><i class="fa-solid fa-circle-plus"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">Creaciones (INSERT)</span>
                    <h3 id="kpiInserts">0</h3>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon icon-update"><i class="fa-solid fa-pen-nib"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">Modificaciones (UPDATE)</span>
                    <h3 id="kpiUpdates">0</h3>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon icon-delete"><i class="fa-solid fa-trash-can"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">Eliminaciones (DELETE)</span>
                    <h3 id="kpiDeletes">0</h3>
                </div>
            </div>
        </section>

        <!-- Filtros y Búsqueda -->
        <section class="toolbar-section">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Buscar por ID de registro, usuario BD o contenido...">
            </div>
            <div class="filter-group">
                <!-- Filtro por Tabla auditada -->
                <select id="filterTabla" class="form-select">
                    <option value="">Todas las tablas</option>
                    <option value="productos">productos</option>
                    <option value="variantes_producto">variantes_producto</option>
                    <option value="pedidos">pedidos</option>
                    <option value="detalle_pedidos">detalle_pedidos</option>
                    <option value="usuarios">usuarios</option>
                    <option value="subastas">subastas</option>
                    <option value="pujas">pujas</option>
                </select>

                <!-- Filtro por Operación Trigger -->
                <select id="filterOperacion" class="form-select">
                    <option value="">Todas las operaciones</option>
                    <option value="INSERT">INSERT</option>
                    <option value="UPDATE">UPDATE</option>
                    <option value="DELETE">DELETE</option>
                </select>
            </div>
        </section>

        <!-- Tabla Principal de Logs -->
        <section class="table-container">
            <table class="custom-table" id="tablaAuditoria">
                <thead>
                    <tr>
                        <th width="80">Log ID</th>
                        <th>Fecha y Hora</th>
                        <th>Tabla Afectada</th>
                        <th>Operación</th>
                        <th>ID Registro</th>
                        <th>Usuario BD</th>
                        <th width="110" class="text-center">Detalle</th>
                    </tr>
                </thead>
                <tbody id="auditoriaTableBody">
                    <tr>
                        <td colspan="7" class="text-center py-4">Cargando registros de auditoría...</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>

    <!-- MODAL: Inspección de Cambios (Diff Viewer) -->
    <div class="modal-overlay" id="diffModal">
        <div class="modal-card">
            <div class="modal-header">
                <div>
                    <h3 id="modalLogTitle">Auditoría de Evento #--</h3>
                    <small id="modalLogMeta" class="text-muted">Tabla: -- | Operación: -- | Registro: #--</small>
                </div>
                <button type="button" class="btn-close" id="btnCloseModal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="diff-container">
                    <!-- Valores Anteriores (OLD) -->
                    <div class="diff-column">
                        <div class="diff-header diff-header-old">
                            <i class="fa-solid fa-clock-rotate-left"></i> Datos Anteriores (OLD)
                        </div>
                        <div class="diff-content" id="datosAnterioresContainer">
                            <span class="text-muted">Sin datos previos</span>
                        </div>
                    </div>

                    <!-- Valores Nuevos (NEW) -->
                    <div class="diff-column">
                        <div class="diff-header diff-header-new">
                            <i class="fa-solid fa-wand-magic-sparkles"></i> Datos Nuevos (NEW)
                        </div>
                        <div class="diff-content" id="datosNuevosContainer">
                            <span class="text-muted">Sin datos posteriores</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" id="btnCancelModal">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- Script de interactividad JS -->
    <script src="../../assets/js/auditoria-gral.js"></script>
</body>
</html>