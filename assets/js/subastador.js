/**
 * BAGGY CULTURE - PANEL SUBASTADOR JS
 * Lógica interactiva en cliente: temporizadores en vivo, filtros, modales y acciones AJAX
 */

document.addEventListener('DOMContentLoaded', () => {
    initCountdowns();
    initFilterAndSearch();
    initModals();
    initProductPicker();
    initDurationPresets();
    initActionHandlers();
});

// ============================================================================
// TEMPORIZADORES EN TIEMPO REAL
// ============================================================================
function initCountdowns() {
    const timerElements = document.querySelectorAll('[data-countdown]');
    if (!timerElements.length) return;

    function updateTimers() {
        const now = new Date().getTime();

        timerElements.forEach(el => {
            const endTimeStr = el.getAttribute('data-countdown');
            if (!endTimeStr) return;

            // Compatibilidad de formato fecha para JS
            const end = new Date(endTimeStr.replace(' ', 'T')).getTime();
            const diff = end - now;

            const timerDisplay = el.querySelector('.countdown-timer');
            if (!timerDisplay) return;

            if (diff <= 0) {
                timerDisplay.textContent = 'Finalizada';
                timerDisplay.classList.remove('urgent');
                timerDisplay.style.color = '#9ca3af';

                // Si aún decía activa en el badge, actualizarlo visualmente
                const row = el.closest('tr');
                if (row && row.getAttribute('data-estado') === 'activa') {
                    const badge = row.querySelector('.status-badge');
                    if (badge && !badge.classList.contains('status-finalizada')) {
                        badge.className = 'status-badge status-finalizada';
                        badge.textContent = 'Finalizada';
                    }
                }
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            // Alerta urgente si queda menos de 30 minutos
            if (days === 0 && hours === 0 && minutes < 30) {
                timerDisplay.classList.add('urgent');
            } else {
                timerDisplay.classList.remove('urgent');
            }

            let formatted = '';
            if (days > 0) formatted += `${days}d `;
            formatted += `${String(hours).padStart(2, '0')}h ${String(minutes).padStart(2, '0')}m ${String(seconds).padStart(2, '0')}s`;

            timerDisplay.textContent = formatted;
        });
    }

    updateTimers();
    setInterval(updateTimers, 1000);
}

// ============================================================================
// BÚSQUEDA Y FILTRADO INSTANTÁNEO
// ============================================================================
function initFilterAndSearch() {
    const searchInput = document.getElementById('searchSubastas');
    const tabButtons = document.querySelectorAll('.filter-tabs .tab-btn');
    const tableRows = document.querySelectorAll('.auction-table tbody tr');
    const emptyState = document.getElementById('emptySearchState');

    let currentFilter = 'todas';
    let searchQuery = '';

    function applyFilters() {
        let visibleCount = 0;

        tableRows.forEach(row => {
            const estado = row.getAttribute('data-estado') || '';
            const searchData = (row.getAttribute('data-search') || '').toLowerCase();

            const matchesStatus = (currentFilter === 'todas') || (estado === currentFilter);
            const matchesSearch = !searchQuery || searchData.includes(searchQuery);

            if (matchesStatus && matchesSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            searchQuery = e.target.value.trim().toLowerCase();
            applyFilters();
        });
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            tabButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentFilter = btn.getAttribute('data-filter') || 'todas';
            applyFilters();
        });
    });
}

// ============================================================================
// GESTIÓN DE MODALES
// ============================================================================
function initModals() {
    // Abrir modales vía data-open-modal
    document.querySelectorAll('[data-open-modal]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const modalId = trigger.getAttribute('data-open-modal');
            openModal(modalId);
        });
    });

    // Cerrar modales vía data-close-modal
    document.querySelectorAll('[data-close-modal]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const modal = trigger.closest('.modal-backdrop');
            if (modal) closeModal(modal.id);
        });
    });

    // Cerrar al dar click fuera (backdrop)
    document.querySelectorAll('.modal-backdrop').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal(modal.id);
            }
        });
    });

    // Cerrar con Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-backdrop.active').forEach(m => closeModal(m.id));
        }
    });
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.classList.remove('active');
    if (!document.querySelector('.modal-backdrop.active')) {
        document.body.style.overflow = '';
    }
}

// ============================================================================
// SELECTOR DE PRODUCTOS (MODAL CREAR SUBASTA)
// ============================================================================
function initProductPicker() {
    const select = document.getElementById('selectProducto');
    const previewBox = document.getElementById('productPreviewBox');
    const previewImg = document.getElementById('previewProductImg');
    const previewName = document.getElementById('previewProductName');
    const previewPrice = document.getElementById('previewProductPrice');
    const inputPrecioBase = document.getElementById('inputPrecioBase');

    if (!select) return;

    select.addEventListener('change', () => {
        const option = select.selectedOptions[0];
        if (!option || !option.value) {
            if (previewBox) previewBox.style.display = 'none';
            return;
        }

        const nombre = option.getAttribute('data-nombre') || option.text;
        const foto = option.getAttribute('data-foto') || 'default.jpg';
        const precio = option.getAttribute('data-precio') || '0';

        if (previewImg) previewImg.src = `../../public/uploads/productos/${foto}`;
        if (previewName) previewName.textContent = nombre;
        if (previewPrice) previewPrice.textContent = `Precio catálogo original: Bs. ${parseFloat(precio).toFixed(2)}`;
        if (previewBox) previewBox.style.display = 'flex';

        // Sugerir precio base si el campo está vacío
        if (inputPrecioBase && !inputPrecioBase.value) {
            inputPrecioBase.value = parseFloat(precio).toFixed(2);
        }
    });
}

// ============================================================================
// PRESETS DE DURACIÓN DE SUBASTA
// ============================================================================
function initDurationPresets() {
    const presetButtons = document.querySelectorAll('.preset-chip[data-hours]');
    const inputInicio = document.getElementById('inputFechaInicio');
    const inputFin = document.getElementById('inputFechaFin');

    if (!inputFin) return;

    // Inicializar inicio con la fecha/hora actual si está vacío
    if (inputInicio && !inputInicio.value) {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        inputInicio.value = now.toISOString().slice(0, 16);
    }

    presetButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const hours = parseFloat(btn.getAttribute('data-hours') || '24');
            const baseDate = (inputInicio && inputInicio.value) ? new Date(inputInicio.value) : new Date();

            const targetDate = new Date(baseDate.getTime() + (hours * 60 * 60 * 1000));
            targetDate.setMinutes(targetDate.getMinutes() - targetDate.getTimezoneOffset());
            inputFin.value = targetDate.toISOString().slice(0, 16);

            presetButtons.forEach(b => b.style.borderColor = 'var(--border-color)');
            btn.style.borderColor = 'var(--accent-gold)';
        });
    });
}

// ============================================================================
// ACCIONES DE SUBASTAS (FINALIZAR, CANCELAR, EXTENDER, HISTORIAL)
// ============================================================================
function initActionHandlers() {
    // Editar Subasta (Abrir modal con datos)
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const id = btn.getAttribute('data-id');
            const nombre = btn.getAttribute('data-nombre');
            const base = btn.getAttribute('data-base');
            const inc = btn.getAttribute('data-inc');
            const inicio = btn.getAttribute('data-inicio');
            const fin = btn.getAttribute('data-fin');
            const estado = btn.getAttribute('data-estado');

            document.getElementById('editIdSubasta').value = id;
            document.getElementById('labelEditId').textContent = `#${id}`;
            document.getElementById('editProductoNombre').textContent = `Producto: ${nombre}`;
            document.getElementById('editPrecioBase').value = parseFloat(base).toFixed(2);
            document.getElementById('editIncremento').value = parseFloat(inc).toFixed(2);
            document.getElementById('editFechaInicio').value = inicio;
            document.getElementById('editFechaFin').value = fin;
            document.getElementById('editEstado').value = estado;

            openModal('modalEditarSubasta');
        });
    });

    // Eliminar Subasta Físicamente de la BD
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const id = btn.getAttribute('data-id');
            const nombre = btn.getAttribute('data-producto');

            if (confirm(`¿Estás SEGURO de ELIMINAR definitivamente la subasta #${id} (${nombre}) de la Base de Datos? Esta acción borrará la subasta y sus ofertas asociadas.`)) {
                await ejecutarEliminarSubasta(id);
            }
        });
    });

    // Ver Historial de Pujas
    document.querySelectorAll('.btn-ver-pujas').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const idSubasta = btn.getAttribute('data-id');
            const nombreProd = btn.getAttribute('data-producto') || `Subasta #${idSubasta}`;
            await cargarHistorialPujas(idSubasta, nombreProd);
        });
    });

    // Extender Tiempo
    document.querySelectorAll('.btn-extender').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const idSubasta = btn.getAttribute('data-id');
            const fechaFin = btn.getAttribute('data-fin');
            abrirModalExtender(idSubasta, fechaFin);
        });
    });

    // Finalizar Subasta Anticipadamente
    document.querySelectorAll('.btn-finalizar').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const idSubasta = btn.getAttribute('data-id');
            if (confirm(`¿Estás seguro de FINALIZAR la subasta #${idSubasta} ahora mismo? El postor con mayor puja será declarado ganador definitivo.`)) {
                ejecutarCambioEstado(idSubasta, 'finalizada');
            }
        });
    });

    // Cancelar Subasta
    document.querySelectorAll('.btn-cancelar').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const idSubasta = btn.getAttribute('data-id');
            if (confirm(`¿Deseas CANCELAR la subasta #${idSubasta}? No se declarará ningún ganador.`)) {
                ejecutarCambioEstado(idSubasta, 'cancelada');
            }
        });
    });
}

// Cargar Pujas vía AJAX
async function cargarHistorialPujas(idSubasta, nombreProducto) {
    const modalTitle = document.getElementById('modalPujasTitle');
    const container = document.getElementById('bidsListContainer');
    const loading = document.getElementById('bidsLoading');

    if (modalTitle) modalTitle.textContent = `Pujas: ${nombreProducto} (#${idSubasta})`;
    if (loading) loading.style.display = 'block';
    if (container) container.innerHTML = '';

    openModal('modalHistorialPujas');

    try {
        const response = await fetch(`subastador.php?ajax=obtener_pujas&id_subasta=${idSubasta}`);
        const data = await response.json();

        if (loading) loading.style.display = 'none';

        if (!data.pujas || data.pujas.length === 0) {
            container.innerHTML = `
                <div class="empty-table-state" style="padding: 30px 10px;">
                    <p style="color: var(--text-muted);">Esta subasta aún no ha recibido ofertas.</p>
                </div>
            `;
            return;
        }

        let html = '<div class="bids-history-list">';
        data.pujas.forEach((p, idx) => {
            const esLider = idx === 0;
            html += `
                <div class="bid-item ${esLider ? 'bid-leader' : ''}">
                    <div class="bid-user">
                        ${esLider ? '<span class="bid-badge-lead">Líder</span>' : `<span style="color: var(--text-muted); font-size: 0.8rem;">#${idx + 1}</span>`}
                        <div>
                            <strong style="color: #fff; font-size: 0.88rem;">${p.usuario || 'Usuario'}</strong>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">${p.fecha_puja}</div>
                        </div>
                    </div>
                    <div class="bid-amount">Bs. ${parseFloat(p.monto).toFixed(2)}</div>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;

    } catch (err) {
        if (loading) loading.style.display = 'none';
        container.innerHTML = `<p style="color: var(--accent-rose); text-align: center;">Error al cargar las pujas.</p>`;
    }
}

// Abrir Modal Extender Tiempo
function abrirModalExtender(idSubasta, fechaFinActual) {
    const inputId = document.getElementById('extenderIdSubasta');
    const inputFecha = document.getElementById('inputExtenderFecha');
    if (inputId) inputId.value = idSubasta;

    if (inputFecha) {
        // Por defecto agregar 1 hora a la fecha actual
        const base = fechaFinActual ? new Date(fechaFinActual.replace(' ', 'T')) : new Date();
        const extendida = new Date(base.getTime() + (60 * 60 * 1000));
        extendida.setMinutes(extendida.getMinutes() - extendida.getTimezoneOffset());
        inputFecha.value = extendida.toISOString().slice(0, 16);
    }

    openModal('modalExtenderTiempo');
}

// Ejecutar Eliminación Física vía POST
async function ejecutarEliminarSubasta(idSubasta) {
    const formData = new FormData();
    formData.append('accion', 'eliminar_subasta');
    formData.append('id_subasta', idSubasta);
    formData.append('ajax', '1');

    try {
        const res = await fetch('subastador.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.status === 'success') {
            showToast(data.message || 'Subasta eliminada correctamente de la BD.', 'success');
            setTimeout(() => window.location.reload(), 900);
        } else {
            showToast(data.message || 'Error al eliminar', 'error');
        }
    } catch (err) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'subastador.php';
        form.innerHTML = `
            <input type="hidden" name="accion" value="eliminar_subasta">
            <input type="hidden" name="id_subasta" value="${idSubasta}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Ejecutar Cambio de Estado vía POST
async function ejecutarCambioEstado(idSubasta, nuevoEstado) {
    const formData = new FormData();
    formData.append('accion', 'cambiar_estado');
    formData.append('id_subasta', idSubasta);
    formData.append('estado', nuevoEstado);
    formData.append('ajax', '1');

    try {
        const res = await fetch('subastador.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.status === 'success') {
            showToast(data.message || `Subasta ${nuevoEstado} con éxito.`, 'success');
            setTimeout(() => window.location.reload(), 900);
        } else {
            showToast(data.message || 'Error al cambiar estado', 'error');
        }
    } catch (err) {
        // Fallback tradicional
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'subastador.php';
        form.innerHTML = `
            <input type="hidden" name="accion" value="cambiar_estado">
            <input type="hidden" name="id_subasta" value="${idSubasta}">
            <input type="hidden" name="estado" value="${nuevoEstado}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// ============================================================================
// TOAST FEEDBACK NOTIFICATION
// ============================================================================
function showToast(message, type = 'success') {
    let toast = document.getElementById('adminToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'adminToast';
        toast.className = 'toast-notification';
        document.body.appendChild(toast);
    }

    toast.className = `toast-notification ${type} active`;
    toast.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="${type === 'success' ? '#10b981' : '#ef4444'}" stroke-width="2">
            ${type === 'success' ? '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>' : '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>'}
        </svg>
        <span>${message}</span>
    `;

    setTimeout(() => {
        toast.classList.remove('active');
    }, 3800);
}
