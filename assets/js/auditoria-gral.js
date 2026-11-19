document.addEventListener('DOMContentLoaded', () => {
    // Referencias al DOM - Tabla y Filtros
    const auditoriaTableBody = document.getElementById('auditoriaTableBody');
    const searchInput = document.getElementById('searchInput');
    const filterTabla = document.getElementById('filterTabla');
    const filterOperacion = document.getElementById('filterOperacion');
    const btnRefresh = document.getElementById('btnRefresh');

    // Referencias al DOM - KPIs
    const kpiTotalLogs = document.getElementById('kpiTotalLogs');
    const kpiInserts = document.getElementById('kpiInserts');
    const kpiUpdates = document.getElementById('kpiUpdates');
    const kpiDeletes = document.getElementById('kpiDeletes');

    // Referencias al DOM - Modal Diff Viewer
    const diffModal = document.getElementById('diffModal');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const btnCancelModal = document.getElementById('btnCancelModal');
    const modalLogTitle = document.getElementById('modalLogTitle');
    const modalLogMeta = document.getElementById('modalLogMeta');
    const datosAnterioresContainer = document.getElementById('datosAnterioresContainer');
    const datosNuevosContainer = document.getElementById('datosNuevosContainer');

    // Endpoint Backend
    const API_URL = '../../controllers/auditoria-gral-controler.php';

    // Grupo de auditoría activo (pestañas)
    let grupoActivo = 'todas';

    const tabConfig = {
        todas: [
            { value: '', label: 'Todas las tablas' },
            { value: 'usuarios', label: 'usuarios' },
            { value: 'pedidos', label: 'pedidos' },
            { value: 'detalle_pedidos', label: 'detalle_pedidos' },
            { value: 'productos', label: 'productos' },
            { value: 'variantes_producto', label: 'variantes_producto' },
            { value: 'subastas', label: 'subastas' },
            { value: 'pujas', label: 'pujas' }
        ],
        usuarios: [
            { value: '', label: 'Todas las tablas de usuarios' },
            { value: 'usuarios', label: 'usuarios' }
        ],
        pedidos: [
            { value: '', label: 'Todas las tablas de pedidos y ventas' },
            { value: 'pedidos', label: 'pedidos (cabecera)' },
            { value: 'detalle_pedidos', label: 'detalle_pedidos (ítems)' }
        ],
        productos: [
            { value: '', label: 'Todas las tablas de productos' },
            { value: 'productos', label: 'productos (catálogo)' },
            { value: 'variantes_producto', label: 'variantes_producto (tallas/colores)' }
        ],
        subastas: [
            { value: '', label: 'Todas las tablas de subastas' },
            { value: 'subastas', label: 'subastas' },
            { value: 'pujas', label: 'pujas' }
        ]
    };

    function actualizarOpcionesFiltroTabla(grupo) {
        const opciones = tabConfig[grupo] || tabConfig.todas;
        filterTabla.innerHTML = '';
        opciones.forEach(opt => {
            const el = document.createElement('option');
            el.value = opt.value;
            el.textContent = opt.label;
            filterTabla.appendChild(el);
        });
        filterTabla.value = '';
    }

    // -------------------------------------------------------------------------
    // 1. CARGA DE LOGS Y KPIS
    // -------------------------------------------------------------------------
    async function cargarAuditoria() {
        try {
            auditoriaTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4">Cargando eventos de auditoría...</td></tr>`;

            const params = new URLSearchParams({
                action: 'listar',
                grupo: grupoActivo,
                tabla: filterTabla.value,
                operacion: filterOperacion.value,
                q: searchInput.value.trim()
            });

            const response = await fetch(`${API_URL}?${params.toString()}`);
            const result = await response.json();

            if (result.status === 'success') {
                actualizarKPIs(result.kpis);
                renderizarTabla(result.data);
            } else {
                auditoriaTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">${result.message}</td></tr>`;
            }
        } catch (error) {
            console.error('Error al cargar logs:', error);
            auditoriaTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">Error al conectar con el servidor</td></tr>`;
        }
    }

    // -------------------------------------------------------------------------
    // 2. ACTUALIZAR KPIS
    // -------------------------------------------------------------------------
    function actualizarKPIs(kpis) {
        if (!kpis) return;
        kpiTotalLogs.textContent = kpis.total_logs;
        kpiInserts.textContent = kpis.inserts;
        kpiUpdates.textContent = kpis.updates;
        kpiDeletes.textContent = kpis.deletes;
    }

    // -------------------------------------------------------------------------
    // 3. RENDERIZAR TABLA DE LOGS
    // -------------------------------------------------------------------------
    function renderizarTabla(lista) {
        if (!lista || lista.length === 0) {
            auditoriaTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4">No hay eventos de auditoría registrados para este filtro</td></tr>`;
            return;
        }

        auditoriaTableBody.innerHTML = '';

        lista.forEach(log => {
            const tr = document.createElement('tr');

            // Formatear fecha
            const fechaObj = new Date(log.fecha_operacion);
            const fechaFormateada = isNaN(fechaObj.getTime()) 
                ? log.fecha_operacion 
                : fechaObj.toLocaleString('es-BO', { dateStyle: 'short', timeStyle: 'medium' });

            const badgeOpClass = `badge-op badge-op-${log.operacion.toLowerCase()}`;

            tr.innerHTML = `
                <td><strong>#${log.id_auditoria}</strong></td>
                <td><small class="text-muted">${fechaFormateada}</small></td>
                <td><span class="badge-table">${log.nombre_tabla}</span></td>
                <td><span class="${badgeOpClass}">${log.operacion}</span></td>
                <td><strong>#${log.clave_registro}</strong></td>
                <td><small class="text-muted">${log.usuario_bd}</small></td>
                <td class="text-center">
                    <button type="button" class="btn-action btn-inspect" title="Inspeccionar Cambios" data-id="${log.id_auditoria}">
                        <i class="fa-solid fa-code-compare"></i>
                    </button>
                </td>
            `;

            auditoriaTableBody.appendChild(tr);
        });

        // Asignar listeners a los botones de inspección
        document.querySelectorAll('.btn-inspect').forEach(btn => {
            btn.onclick = () => abrirModalDiff(btn.dataset.id);
        });
    }

    // -------------------------------------------------------------------------
    // 4. MODAL DIFF VIEWER (COMPARADOR DE ESTADOS)
    // -------------------------------------------------------------------------
    async function abrirModalDiff(id_auditoria) {
        datosAnterioresContainer.innerHTML = `<span class="text-muted">Cargando datos previos...</span>`;
        datosNuevosContainer.innerHTML = `<span class="text-muted">Cargando datos posteriores...</span>`;
        diffModal.classList.add('open');

        try {
            const res = await fetch(`${API_URL}?action=obtener_detalle&id=${id_auditoria}`);
            const result = await res.json();

            if (result.status === 'success') {
                const log = result.data;

                modalLogTitle.textContent = `Auditoría de Evento #${log.id_auditoria}`;
                modalLogMeta.textContent = `Tabla: ${log.nombre_tabla} | Operación: ${log.operacion} | ID Registro: #${log.clave_registro} | Usuario: ${log.usuario_bd}`;

                const oldData = log.datos_anteriores || {};
                const newData = log.datos_nuevos || {};

                // Obtener conjunto de claves de ambos objetos
                const allKeys = Array.from(new Set([...Object.keys(oldData), ...Object.keys(newData)]));

                if (allKeys.length === 0) {
                    datosAnterioresContainer.innerHTML = `<span class="text-muted">Sin datos disponibles</span>`;
                    datosNuevosContainer.innerHTML = `<span class="text-muted">Sin datos disponibles</span>`;
                    return;
                }

                let htmlOld = '';
                let htmlNew = '';

                allKeys.forEach(key => {
                    const valOld = oldData[key] !== undefined ? oldData[key] : '—';
                    const valNew = newData[key] !== undefined ? newData[key] : '—';

                    // Si ambos valores existen y son distintos, marcar como cambio
                    const isChanged = (valOld !== '—' && valNew !== '—' && String(valOld) !== String(valNew));
                    const changedClass = isChanged ? 'changed' : '';

                    htmlOld += `
                        <div class="diff-entry ${changedClass}">
                            <span class="diff-key">${key}</span>
                            <span class="diff-val">${valOld !== null ? valOld : 'NULL'}</span>
                        </div>
                    `;

                    htmlNew += `
                        <div class="diff-entry ${changedClass}">
                            <span class="diff-key">${key}</span>
                            <span class="diff-val">${valNew !== null ? valNew : 'NULL'}</span>
                        </div>
                    `;
                });

                datosAnterioresContainer.innerHTML = log.operacion === 'INSERT' 
                    ? `<span class="text-muted"><i class="fa-solid fa-ban"></i> Registro nuevo (sin datos previos)</span>` 
                    : htmlOld;

                datosNuevosContainer.innerHTML = log.operacion === 'DELETE' 
                    ? `<span class="text-muted"><i class="fa-solid fa-trash"></i> Registro eliminado (sin datos posteriores)</span>` 
                    : htmlNew;

            } else {
                alert(result.message || 'Error al obtener el detalle del log');
                cerrarModal();
            }
        } catch (error) {
            console.error('Error al cargar detalle diff:', error);
            alert('Error de comunicación con el servidor.');
            cerrarModal();
        }
    }

    function cerrarModal() {
        diffModal.classList.remove('open');
    }

    // -------------------------------------------------------------------------
    // 5. EVENT LISTENERS
    // -------------------------------------------------------------------------
    const tabBtns = document.querySelectorAll('.tab-btn');
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            tabBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            grupoActivo = btn.dataset.grupo || 'todas';
            actualizarOpcionesFiltroTabla(grupoActivo);
            cargarAuditoria();
        });
    });

    let debounceTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(cargarAuditoria, 350);
    });

    filterTabla.addEventListener('change', cargarAuditoria);
    filterOperacion.addEventListener('change', cargarAuditoria);
    btnRefresh.onclick = cargarAuditoria;

    btnCloseModal.onclick = cerrarModal;
    btnCancelModal.onclick = cerrarModal;

    // Carga inicial
    cargarAuditoria();
});