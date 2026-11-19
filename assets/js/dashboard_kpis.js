document.addEventListener('DOMContentLoaded', () => {
  cargarMetricasKPI();
});

async function cargarMetricasKPI() {
  try {
    const response = await fetch('../../apisdatos/dashboard_kpis.php');
    if (!response.ok) throw new Error('Error en la solicitud HTTP');

    const result = await response.json();
    if (result.status === 'success') {
      const data = result.data;
      const elVentas = document.getElementById('kpi-ventas');
      const elPedidos = document.getElementById('kpi-pedidos');
      const elSubastas = document.getElementById('kpi-subastas');
      const elProductos = document.getElementById('kpi-productos');

      if (elVentas) elVentas.textContent = `Bs. ${data.total_ventas}`;
      if (elPedidos) elPedidos.textContent = data.pedidos_pendientes;
      if (elSubastas) elSubastas.textContent = data.subastas_activas;
      if (elProductos) elProductos.textContent = data.total_productos;
    } else {
      console.error(result.message);
    }
  } catch (error) {
    console.error('Fallo al cargar métricas del dashboard:', error);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  cargarGraficos();
});

async function cargarGraficos() {
  try {
    const res = await fetch('../../apisdatos/dashboard_graficos.php');
    if (!res.ok) throw new Error('Error en la petición de gráficos');

    const json = await res.json();
    if (json.status !== 'success') throw new Error(json.message);

    renderChartPedidos(json.pedidos);
    renderChartCategorias(json.categorias);
  } catch (error) {
    console.error('Fallo al renderizar gráficos:', error);
  }
}

function renderChartPedidos(data) {
  const ctx = document.getElementById('chartPedidos').getContext('2d');
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: data.labels.length > 0 ? data.labels : ['Sin pedidos'],
      datasets: [{
        data: data.values.length > 0 ? data.values : [1],
        backgroundColor: [
          '#f59e0b', // Pendiente - Ámbar
          '#10b981', // Pagado - Esmeralda
          '#3b82f6', // Enviado - Azul
          '#6366f1', // Entregado - Índigo
          '#ef4444'  // Cancelado u otros
        ],
        borderColor: '#161618',
        borderWidth: 3,
        hoverOffset: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: '#9ca3af',
            padding: 16,
            font: { size: 12 }
          }
        }
      },
      cutout: '70%'
    }
  });
}

function renderChartCategorias(data) {
  const ctx = document.getElementById('chartCategorias').getContext('2d');
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: data.labels.length > 0 ? data.labels : ['Sin datos'],
      datasets: [{
        data: data.values.length > 0 ? data.values : [1],
        backgroundColor: [
          '#eab308', // Dorado BaggyAdmin
          '#8b5cf6', // Morado
          '#06b6d4', // Cyan
          '#ec4899', // Rosa
          '#84cc16'  // Lima
        ],
        borderColor: '#161618',
        borderWidth: 3,
        hoverOffset: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: '#9ca3af',
            padding: 16,
            font: { size: 12 }
          }
        }
      },
      cutout: '70%'
    }
  });
}