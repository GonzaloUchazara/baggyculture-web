document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('chartCategoriasSubastas');
    const mensajeVacio = document.getElementById('sinDatosMensaje');

    if (!canvas) return;

    const urlApi = '../../controllers/subastacontroler.php?accion=datos_categorias';

    fetch(urlApi)
        .then(response => response.json())
        .then(data => {
            if (!data.categorias || data.categorias.length === 0) {
                canvas.style.display = 'none';
                if (mensajeVacio) mensajeVacio.style.display = 'block';
                return;
            }

            const ctx = canvas.getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.categorias,
                    datasets: [{
                        data: data.totales,
                        backgroundColor: [
                            '#6366f1', 
                            '#ec4899', 
                            '#10b981', 
                            '#f59e0b', 
                            '#3b82f6', 
                            '#8b5cf6'  
                        ],
                        borderColor: '#1a1e29',
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
                                color: '#e2e8f0',
                                boxWidth: 14,
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const totalDinero = Number(context.raw).toLocaleString('es-BO', { minimumFractionDigits: 2 });
                                    const prendas = data.cantidades[index];
                                    return ` ${context.label}: Bs. ${totalDinero} (${prendas} ${prendas === 1 ? 'venta' : 'ventas'})`;
                                }
                            }
                        }
                    },
                    cutout: '70%' 
                }
            });
        })
        .catch(error => {
            console.error('Error al cargar métricas de categorías:', error);
        });
});

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('chartTopPostores');
    const sinPujasMsg = document.getElementById('sinPujasMsg');

    if (!canvas) return;

    // Ruta al controlador
    const urlApi = '../../controllers/subastacontroler.php?accion=top_postores';

    fetch(urlApi)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data.usuarios || data.usuarios.length === 0) {
                canvas.style.display = 'none';
                if (sinPujasMsg) sinPujasMsg.style.display = 'block';
                return;
            }

            const ctx = canvas.getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.usuarios,
                    datasets: [{
                        data: data.ofertas,
                        backgroundColor: [
                            '#3b82f6', // Azul principal
                            '#10b981', // Verde esmeralda
                            '#f59e0b', // Ámbar
                            '#ec4899', // Rosa
                            '#8b5cf6'  // Púrpura
                        ],
                        borderColor: '#1e293b',
                        borderWidth: 3,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#e2e8f0',
                                boxWidth: 12,
                                padding: 14,
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const totalOfertas = context.raw;
                                    const montoTotal = Number(data.volumen[index]).toLocaleString('es-BO', { minimumFractionDigits: 2 });
                                    return ` ${context.label}: ${totalOfertas} pujas (Monto acumulado: Bs. ${montoTotal})`;
                                }
                            }
                        }
                    },
                    cutout: '68%'
                }
            });
        })
        .catch(error => {
            console.error('Error cargando top postores:', error);
        });
});



document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('chartRitmoPujas');
    const sinRitmoMsg = document.getElementById('sinRitmoMsg');

    if (!canvas) return;

    const urlApi = '../../controllers/subastacontroler.php?accion=ritmo_pujas';

    fetch(urlApi)
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (!data.tiempos || data.tiempos.length === 0) {
                canvas.style.display = 'none';
                if (sinRitmoMsg) sinRitmoMsg.style.display = 'block';
                return;
            }

            const ctx = canvas.getContext('2d');

            // Crear un degradado suave bajo la línea
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.tiempos,
                    datasets: [{
                        label: 'Monto de Oferta (Bs.)',
                        data: data.montos,
                        borderColor: '#38bdf8',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#0284c7',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.35, // Curvatura suave de la línea
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)'
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 11 }
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.08)'
                            },
                            ticks: {
                                color: '#94a3b8',
                                callback: function(value) {
                                    return 'Bs. ' + value;
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Oculto porque la tarjeta ya lo titula arriba
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const valor = Number(context.raw).toLocaleString('es-BO', { minimumFractionDigits: 2 });
                                    return ` Oferta registrada: Bs. ${valor}`;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(err => {
            console.error('Error al cargar la línea de ritmo de pujas:', err);
        });
});