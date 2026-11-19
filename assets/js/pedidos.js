document.addEventListener('DOMContentLoaded', () => {
    // Referencias al DOM - Tabla y Filtros
    const pedidosTableBody = document.getElementById('pedidosTableBody');
    const searchInput = document.getElementById('searchInput');
    const filterEstado = document.getElementById('filterEstado');
    const filterMetodo = document.getElementById('filterMetodo');
    const btnRefresh = document.getElementById('btnRefresh');

    // Referencias al DOM - KPIs
    const kpiTotalVentas = document.getElementById('kpiTotalVentas');
    const kpiPendientes = document.getElementById('kpiPendientes');
    const kpiEntregados = document.getElementById('kpiEntregados');
    const kpiCancelados = document.getElementById('kpiCancelados');

    // Referencias al DOM - Modal Detalle
    const orderModal = document.getElementById('orderModal');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const btnCancelModal = document.getElementById('btnCancelModal');
    const modalOrderNumber = document.getElementById('modalOrderNumber');
    const modalOrderDate = document.getElementById('modalOrderDate');
    const modalCustomerName = document.getElementById('modalCustomerName');
    const modalCustomerContact = document.getElementById('modalCustomerContact');
    const modalShippingAddress = document.getElementById('modalShippingAddress');
    const modalPaymentMethod = document.getElementById('modalPaymentMethod');
    const modalOrderStatusBadge = document.getElementById('modalOrderStatusBadge');
    const modalOrderItemsBody = document.getElementById('modalOrderItemsBody');
    const modalOrderTotal = document.getElementById('modalOrderTotal');

    let pedidosData = [];

    // Endpoint Backend
    const API_URL = '../../controllers/pedidos-controler.php';

    function getRutaImagen(foto) {
        if (!foto) return '../../public/uploads/default.jpg';
        if (foto.startsWith('http://') || foto.startsWith('https://') || foto.startsWith('/') || foto.startsWith('../')) {
            return foto;
        }
        return `../../public/uploads/productos/${foto}`;
    }

    // -------------------------------------------------------------------------
    // 1. CARGAR PEDIDOS Y KPIS DESDE EL SERVIDOR
    // -------------------------------------------------------------------------
    async function cargarPedidos() {
        try {
            pedidosTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4">Cargando pedidos...</td></tr>`;
            const response = await fetch(`${API_URL}?action=listar`);
            const result = await response.json();

            if (result.status === 'success') {
                pedidosData = result.data;
                actualizarKPIs(result.kpis);
                renderizarTabla(pedidosData);
            } else {
                pedidosTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">${result.message}</td></tr>`;
            }
        } catch (error) {
            console.error('Error al cargar pedidos:', error);
            pedidosTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">Error de conexión al cargar pedidos</td></tr>`;
        }
    }

    // -------------------------------------------------------------------------
    // 2. ACTUALIZAR TARJETAS KPI
    // -------------------------------------------------------------------------
    function actualizarKPIs(kpis) {
        if (!kpis) return;
        kpiTotalVentas.textContent = `Bs. ${parseFloat(kpis.total_ventas).toLocaleString('es-BO', { minimumFractionDigits: 2 })}`;
        kpiPendientes.textContent = kpis.pendientes;
        kpiEntregados.textContent = kpis.entregados;
        kpiCancelados.textContent = kpis.cancelados;
    }

    // -------------------------------------------------------------------------
    // 3. RENDERIZAR TABLA DE PEDIDOS
    // -------------------------------------------------------------------------
    function renderizarTabla(lista) {
        if (!lista || lista.length === 0) {
            pedidosTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4">No se encontraron pedidos registrados</td></tr>`;
            return;
        }

        pedidosTableBody.innerHTML = '';

        lista.forEach(pedido => {
            const tr = document.createElement('tr');
            tr.dataset.id = pedido.id_pedido;

            // Formatear fecha
            const fechaObj = new Date(pedido.fecha_pedido);
            const fechaFormateada = isNaN(fechaObj.getTime()) 
                ? pedido.fecha_pedido 
                : fechaObj.toLocaleString('es-BO', { dateStyle: 'short', timeStyle: 'short' });

            tr.innerHTML = `
                <td><strong>#${pedido.id_pedido}</strong></td>
                <td><small class="text-muted">${fechaFormateada}</small></td>
                <td>
                    <strong>${pedido.cliente_nombre || 'Cliente General'}</strong><br>
                    <small class="text-muted">${pedido.telefono || pedido.email || 'Sin contacto'}</small>
                </td>
                <td>${pedido.metodo_pago}</td>
                <td><strong>Bs. ${parseFloat(pedido.total).toFixed(2)}</strong></td>
                <td>
                    <select class="status-select status-${pedido.estado}" data-id="${pedido.id_pedido}">
                        <option value="pendiente" ${pedido.estado === 'pendiente' ? 'selected' : ''}>Pendiente</option>
                        <option value="en_camino" ${pedido.estado === 'en_camino' ? 'selected' : ''}>En camino</option>
                        <option value="entregado" ${pedido.estado === 'entregado' ? 'selected' : ''}>Entregado</option>
                        <option value="cancelado" ${pedido.estado === 'cancelado' ? 'selected' : ''}>Cancelado</option>
                    </select>
                </td>
                <td class="text-center">
                    <button type="button" class="btn-action btn-view-order" title="Ver Detalle" data-id="${pedido.id_pedido}">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </td>
            `;

            pedidosTableBody.appendChild(tr);
        });

        asignarEventosFila();
    }

    // -------------------------------------------------------------------------
    // 4. EVENTOS DE ACCIÓN (CAMBIAR ESTADO Y VER DETALLE)
    // -------------------------------------------------------------------------
    function asignarEventosFila() {
        // Selector de cambio de estado en la fila
        document.querySelectorAll('.status-select').forEach(select => {
            select.onchange = async () => {
                const id_pedido = select.dataset.id;
                const nuevo_estado = select.value;

                const formData = new FormData();
                formData.append('action', 'cambiar_estado');
                formData.append('id_pedido', id_pedido);
                formData.append('estado', nuevo_estado);

                try {
                    const response = await fetch(API_URL, {
                        method: 'POST',
                        body: formData
                    });
                    const res = await response.json();

                    if (res.status === 'success') {
                        // Cambiar la clase de color del select inmediatamente
                        select.className = `status-select status-${nuevo_estado}`;
                        // Recargar datos y recalcular KPIs de inmediato
                        cargarPedidos();
                    } else {
                        alert(res.message || 'No se pudo actualizar el estado.');
                        cargarPedidos(); // Restaurar valor visual original
                    }
                } catch (error) {
                    console.error('Error al cambiar estado:', error);
                    alert('Error de conexión al guardar el cambio de estado.');
                    cargarPedidos();
                }
            };
        });

        // Botón Ver Detalle (abre el modal)
        document.querySelectorAll('.btn-view-order').forEach(btn => {
            btn.onclick = async () => {
                const id_pedido = btn.dataset.id;
                abrirModalDetalle(id_pedido);
            };
        });
    }

    // -------------------------------------------------------------------------
    // 5. MODAL DE DETALLE DEL PEDIDO
    // -------------------------------------------------------------------------
    async function abrirModalDetalle(id_pedido) {
        modalOrderItemsBody.innerHTML = `<tr><td colspan="6" class="text-center py-4">Cargando desglose...</td></tr>`;
        orderModal.classList.add('open');

        try {
            const response = await fetch(`${API_URL}?action=obtener_detalle&id=${id_pedido}`);
            const res = await response.json();

            if (res.status === 'success') {
                const pedido = res.data;

                // Datos de cabecera
                modalOrderNumber.textContent = `Detalle del Pedido #${pedido.id_pedido}`;
                modalOrderDate.textContent = `Fecha: ${pedido.fecha_pedido}`;
                modalCustomerName.textContent = pedido.cliente_nombre || 'Cliente General';
                modalCustomerContact.textContent = `Tel: ${pedido.telefono || 'N/A'} | Email: ${pedido.email || 'N/A'}`;
                modalShippingAddress.textContent = pedido.direccion_envio || 'Retiro en tienda / Sin dirección';
                modalPaymentMethod.textContent = `Método: ${pedido.metodo_pago}`;

                // Badge de estado en el modal
                modalOrderStatusBadge.className = `badge badge-${pedido.estado}`;
                modalOrderStatusBadge.textContent = pedido.estado.replace('_', ' ');

                // Desglose de prendas
                modalOrderTotal.textContent = `Bs. ${parseFloat(pedido.total).toFixed(2)}`;

                if (!pedido.items || pedido.items.length === 0) {
                    modalOrderItemsBody.innerHTML = `<tr><td colspan="6" class="text-center py-3">No hay productos en este detalle</td></tr>`;
                } else {
                    modalOrderItemsBody.innerHTML = pedido.items.map(item => `
                        <tr>
                            <td>
                                <img src="${getRutaImagen(item.foto)}" alt="${item.producto_nombre}" class="item-thumb-img" onerror="this.src='../../public/uploads/default.jpg'">
                            </td>
                            <td><strong>${item.producto_nombre}</strong></td>
                            <td>${item.talla || 'Única'} / ${item.color || 'Estándar'}</td>
                            <td>${item.cantidad}</td>
                            <td>Bs. ${parseFloat(item.precio_unitario).toFixed(2)}</td>
                            <td><strong>Bs. ${parseFloat(item.subtotal).toFixed(2)}</strong></td>
                        </tr>
                    `).join('');
                }
            } else {
                alert(res.message || 'Error al obtener detalle del pedido.');
                cerrarModal();
            }
        } catch (error) {
            console.error('Error al abrir modal detalle:', error);
            alert('Error de conexión con el servidor.');
            cerrarModal();
        }
    }

    function cerrarModal() {
        orderModal.classList.remove('open');
    }

    // -------------------------------------------------------------------------
    // 6. FILTRADO Y BÚSQUEDA REACTIVA
    // -------------------------------------------------------------------------
    function aplicarFiltros() {
        const query = searchInput.value.toLowerCase().trim();
        const est = filterEstado.value;
        const met = filterMetodo.value;

        const filtrados = pedidosData.filter(p => {
            const cliente = (p.cliente_nombre || '').toLowerCase();
            const direccion = (p.direccion_envio || '').toLowerCase();
            const idMatch = p.id_pedido.toString().includes(query);
            const matchTexto = idMatch || cliente.includes(query) || direccion.includes(query);

            const matchEstado = est === '' || p.estado === est;
            const matchMetodo = met === '' || p.metodo_pago === met;

            return matchTexto && matchEstado && matchMetodo;
        });

        renderizarTabla(filtrados);
    }

    // Event Listeners
    btnRefresh.onclick = cargarPedidos;
    btnCloseModal.onclick = cerrarModal;
    btnCancelModal.onclick = cerrarModal;

    searchInput.addEventListener('input', aplicarFiltros);
    filterEstado.addEventListener('change', aplicarFiltros);
    filterMetodo.addEventListener('change', aplicarFiltros);

    // Carga inicial
    cargarPedidos();
});