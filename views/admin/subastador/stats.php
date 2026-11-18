<?php
/**
 * views/admin/subastador/stats.php
 * Tarjetas de métricas y resumen clave para el subastador
 */
?>
<section class="stats-grid">
    <!-- Subastas Activas -->
    <div class="stat-card card-active">
        <div class="stat-header">
            <span class="stat-title">Subastas en Vivo</span>
            <div class="stat-icon" style="color: var(--accent-emerald);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
        </div>
        <div class="stat-value"><?= number_format($kpis['activas'] ?? 0) ?></div>
        <div class="stat-footer">
            <span class="pulse-dot"></span>
            <span>Recibiendo ofertas activas</span>
        </div>
    </div>

    <!-- Total de Pujas -->
    <div class="stat-card card-bids">
        <div class="stat-header">
            <span class="stat-title">Total de Pujas</span>
            <div class="stat-icon" style="color: var(--accent-gold);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                </svg>
            </div>
        </div>
        <div class="stat-value"><?= number_format($kpis['total_pujas'] ?? 0) ?></div>
        <div class="stat-footer">
            <span>Interacción global acumulada</span>
        </div>
    </div>

    <!-- Puja Más Alta Activa -->
    <div class="stat-card card-highest">
        <div class="stat-header">
            <span class="stat-title">Puja Más Alta Activa</span>
            <div class="stat-icon" style="color: #a78bfa;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
        </div>
        <div class="stat-value">Bs. <?= number_format($kpis['mayor_puja'] ?? 0, 2) ?></div>
        <div class="stat-footer">
            <span>Pike más alto registrado en vivo</span>
        </div>
    </div>

    <!-- Finalizadas -->
    <div class="stat-card card-finished">
        <div class="stat-header">
            <span class="stat-title">Subastas Concluidas</span>
            <div class="stat-icon" style="color: #94a3b8;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
        </div>
        <div class="stat-value"><?= number_format($kpis['finalizadas'] ?? 0) ?></div>
        <div class="stat-footer">
            <span><?= number_format($kpis['canceladas'] ?? 0) ?> subastas canceladas</span>
        </div>
    </div>
</section>
