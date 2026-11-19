<link rel="stylesheet" href="../assets/css/dashboard_kpis.css">

<section class="kpi-container">
  <div class="kpi-grid">
    <!-- Tarjeta 1: Ventas -->
    <article class="kpi-card">
      <div class="kpi-info">
        <p class="kpi-label">Total Ventas</p>
        <h3 class="kpi-value" id="kpi-ventas">Bs. 0.00</h3>
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
        <h3 class="kpi-value" id="kpi-pedidos">0</h3>
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
        <h3 class="kpi-value" id="kpi-subastas">0</h3>
        <span class="kpi-subtext highlight-blue">En curso ahora</span>
      </div>
      <div class="kpi-icon-wrapper icon-subastas">
        <span>🔨</span>
      </div>
    </article>

    <!-- Tarjeta 4: Productos -->
    <article class="kpi-card">
      <div class="kpi-info">
        <p class="kpi-label">Productos Catálogo</p>
        <h3 class="kpi-value" id="kpi-productos">0</h3>
        <span class="kpi-subtext highlight-gold">Total en plataforma</span>
      </div>
      <div class="kpi-icon-wrapper icon-productos">
        <span>🏷️</span>
      </div>
    </article>
  </div>
</section>

<script src="../assets/js/dashboard_kpis.js"></script>