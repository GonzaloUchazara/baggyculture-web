<?php
/**
 * views/admin/subastador/tabla.php
 * Tabla y controles de gestión de subastas
 */
?>
<div class="panel-card">
    <!-- Barra de Herramientas y Filtros -->
    <div class="panel-toolbar">
        <div class="search-box">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="searchSubastas" class="search-input" placeholder="Buscar por producto, ID o categoría...">
        </div>

        <div class="filter-tabs">
            <button type="button" class="tab-btn active" data-filter="todas">Todas (<?= count($subastas) ?>)</button>
            <button type="button" class="tab-btn" data-filter="activa">En Vivo (<?= $kpis['activas'] ?>)</button>
            <button type="button" class="tab-btn" data-filter="finalizada">Finalizadas (<?= $kpis['finalizadas'] ?>)</button>
            <button type="button" class="tab-btn" data-filter="cancelada">Canceladas (<?= $kpis['canceladas'] ?>)</button>
        </div>
    </div>

    <!-- Tabla de Subastas -->
    <div class="table-responsive">
        <table class="auction-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precios (Bs)</th>
                    <th>Postor Líder</th>
                    <th>Pujas</th>
                    <th>Cierre / Tiempo</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($subastas)): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-table-state">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                <h3>No hay subastas registradas</h3>
                                <p>Crea tu primera subasta utilizando el botón superior "Nueva Subasta".</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($subastas as $s): 
                        $id = $s['id_subasta'];
                        $estado = strtolower($s['estado']);
                        $foto = !empty($s['foto_pro']) ? $s['foto_pro'] : 'default.jpg';
                        $rutaFoto = "../../public/uploads/productos/" . htmlspecialchars($foto);
                        $searchData = htmlspecialchars($s['nombre'] . ' ' . $id . ' ' . ($s['categoria'] ?? '') . ' ' . ($s['nombre_ganador'] ?? ''));
                    ?>
                    <tr data-estado="<?= $estado ?>" data-search="<?= $searchData ?>">
                        <!-- Producto -->
                        <td>
                            <div class="product-cell">
                                <img src="<?= $rutaFoto ?>" alt="<?= htmlspecialchars($s['nombre']) ?>" class="product-thumb" onerror="this.src='../../public/uploads/default.jpg'">
                                <div class="product-info">
                                    <a href="../detalle_subasta.php?id=<?= $id ?>" target="_blank" class="product-name" title="Ver subasta en tienda">
                                        <?= htmlspecialchars($s['nombre']) ?>
                                    </a>
                                    <span class="product-subtext">ID Subasta: #<?= $id ?> • <?= htmlspecialchars($s['categoria'] ?? 'Streetwear') ?></span>
                                </div>
                            </div>
                        </td>

                        <!-- Precios -->
                        <td>
                            <div class="price-display">
                                <span class="current-price">Bs. <?= number_format($s['precio_actual'], 2) ?></span>
                                <span class="base-price">Base: Bs. <?= number_format($s['precio_base'], 2) ?> (Min: +<?= number_format($s['incremento_minimo'], 2) ?>)</span>
                            </div>
                        </td>

                        <!-- Ganador / Postor Líder -->
                        <td>
                            <?php if (!empty($s['nombre_ganador'])): ?>
                                <span class="winner-pill">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                    </svg>
                                    <?= htmlspecialchars($s['nombre_ganador']) ?>
                                </span>
                            <?php else: ?>
                                <span class="winner-pill no-bids">Sin ofertas</span>
                            <?php endif; ?>
                        </td>

                        <!-- Total de Pujas -->
                        <td>
                            <span style="font-weight: 700; color: #fff; background-color: var(--bg-surface); padding: 4px 10px; border-radius: var(--radius-full); border: 1px solid var(--border-color); font-size: 0.8rem;">
                                <?= (int)$s['total_pujas'] ?> <?= (int)$s['total_pujas'] === 1 ? 'puja' : 'pujas' ?>
                            </span>
                        </td>

                        <!-- Fecha / Temporizador en vivo -->
                        <td>
                            <div class="countdown-box" <?= $estado === 'activa' ? 'data-countdown="' . htmlspecialchars($s['fecha_fin']) . '"' : '' ?>>
                                <span class="countdown-timer">
                                    <?= $estado === 'activa' ? 'Calculando...' : ucfirst($estado) ?>
                                </span>
                                <span class="countdown-date">
                                    Fin: <?= date('d/m/Y H:i', strtotime($s['fecha_fin'])) ?>
                                </span>
                            </div>
                        </td>

                        <!-- Estado -->
                        <td>
                            <span class="status-badge status-<?= $estado ?>">
                                <?php if ($estado === 'activa'): ?>
                                    <span class="pulse-dot" style="width: 6px; height: 6px;"></span>
                                <?php endif; ?>
                                <?= ucfirst($estado) ?>
                            </span>
                        </td>

                        <!-- Botones de Acción -->
                        <td>
                            <div class="action-buttons" style="justify-content: flex-end;">
                                <!-- Editar Subasta -->
                                <button type="button" class="btn-icon-action btn-editar" 
                                    data-id="<?= $id ?>" 
                                    data-nombre="<?= htmlspecialchars($s['nombre']) ?>"
                                    data-base="<?= $s['precio_base'] ?>"
                                    data-inc="<?= $s['incremento_minimo'] ?>"
                                    data-inicio="<?= date('Y-m-d\TH:i', strtotime($s['fecha_inicio'])) ?>"
                                    data-fin="<?= date('Y-m-d\TH:i', strtotime($s['fecha_fin'])) ?>"
                                    data-estado="<?= $estado ?>"
                                    title="Editar Subasta">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>

                                <!-- Ver Historial de Pujas -->
                                <button type="button" class="btn-icon-action btn-bids btn-ver-pujas" data-id="<?= $id ?>" data-producto="<?= htmlspecialchars($s['nombre']) ?>" title="Historial de Pujas">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </button>

                                <?php if ($estado === 'activa'): ?>
                                    <!-- Extender Tiempo -->
                                    <button type="button" class="btn-icon-action btn-extend btn-extender" data-id="<?= $id ?>" data-fin="<?= htmlspecialchars($s['fecha_fin']) ?>" title="Extender Tiempo">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 14 14"></polyline>
                                        </svg>
                                    </button>

                                    <!-- Finalizar Subasta -->
                                    <button type="button" class="btn-icon-action btn-finish btn-finalizar" data-id="<?= $id ?>" title="Declarar Ganador y Finalizar">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                    </button>

                                    <!-- Cancelar Subasta -->
                                    <button type="button" class="btn-icon-action btn-cancel btn-cancelar" data-id="<?= $id ?>" title="Cancelar Subasta">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="15" y1="9" x2="9" y2="15"></line>
                                            <line x1="9" y1="9" x2="15" y2="15"></line>
                                        </svg>
                                    </button>
                                <?php endif; ?>

                                <!-- Eliminar Subasta (Físico) -->
                                <button type="button" class="btn-icon-action btn-cancel btn-eliminar" data-id="<?= $id ?>" data-producto="<?= htmlspecialchars($s['nombre']) ?>" title="Eliminar definitivamente de la Base de Datos">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </button>

                                <!-- Enlace Tienda -->
                                <a href="../detalle_subasta.php?id=<?= $id ?>" target="_blank" class="btn-icon-action" title="Abrir en Tienda Pública">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Empty state oculto para cuando el buscador no coincida -->
    <div id="emptySearchState" class="empty-table-state" style="display: none;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <h3>No se encontraron resultados</h3>
        <p>Prueba con otros términos de búsqueda o cambia el filtro de estado.</p>
    </div>
</div>
