document.addEventListener('DOMContentLoaded', () => {
    // --- 1. MODAL EDITAR SUBASTA ---
    const modalEditar = document.getElementById('modalEditarSubasta');
    const btnCerrarModal = document.getElementById('btnCerrarModal');
    const btnCancelarModal = document.getElementById('btnCancelarModal');

    // Campos del formulario de edición
    const inputId = document.getElementById('modal_id_subasta');
    const inputProducto = document.getElementById('modal_producto_nombre');
    const inputPrecioBase = document.getElementById('modal_precio_base');
    const inputInicio = document.getElementById('modal_fecha_inicio');
    const inputFin = document.getElementById('modal_fecha_fin');
    const selectEstado = document.getElementById('modal_estado');

    // Abrir modal editar con datos de la fila
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            inputId.value = btn.dataset.id;
            inputProducto.value = btn.dataset.producto;
            inputPrecioBase.value = btn.dataset.precioBase;
            inputInicio.value = btn.dataset.inicio;
            inputFin.value = btn.dataset.fin;
            selectEstado.value = btn.dataset.estado;

            modalEditar.classList.add('is-open');
        });
    });

    const cerrarModalEdicion = () => modalEditar.classList.remove('is-open');
    if (btnCerrarModal) btnCerrarModal.addEventListener('click', cerrarModalEdicion);
    if (btnCancelarModal) btnCancelarModal.addEventListener('click', cerrarModalEdicion);


    // --- 2. MODAL EXTENDER TIEMPO ---
    const modalExtender = document.getElementById('modalExtenderSubasta');
    const btnCerrarExtender = document.getElementById('btnCerrarExtender');
    const btnCancelarExtender = document.getElementById('btnCancelarExtender');

    const inputExtenderId = document.getElementById('extender_id_subasta');
    const txtFechaActual = document.getElementById('extender_fecha_actual');

    // Abrir modal extender
    document.querySelectorAll('.btn-extend').forEach(btn => {
        btn.addEventListener('click', () => {
            inputExtenderId.value = btn.dataset.id;
            txtFechaActual.textContent = btn.dataset.fin;
            modalExtender.classList.add('is-open');
        });
    });

    const cerrarModalExtension = () => modalExtender.classList.remove('is-open');
    if (btnCerrarExtender) btnCerrarExtender.addEventListener('click', cerrarModalExtension);
    if (btnCancelarExtender) btnCancelarExtender.addEventListener('click', cerrarModalExtension);


    // Cerrar al hacer clic en el fondo oscuro
    window.addEventListener('click', (e) => {
        if (e.target === modalEditar) cerrarModalEdicion();
        if (e.target === modalExtender) cerrarModalExtension();
    });

    




    const modalCrear = document.getElementById('modalCrearSubasta');
    const btnAbrirCrear = document.getElementById('btnAbrirCrearSubasta');
    const btnCerrarCrear = document.getElementById('btnCerrarCrear');
    const btnCancelarCrear = document.getElementById('btnCancelarCrear');

    if (btnAbrirCrear && modalCrear) {
        btnAbrirCrear.addEventListener('click', () => {
            modalCrear.classList.add('is-open');
        });

        const cerrarModalCrear = () => modalCrear.classList.remove('is-open');
        if (btnCerrarCrear) btnCerrarCrear.addEventListener('click', cerrarModalCrear);
        if (btnCancelarCrear) btnCancelarCrear.addEventListener('click', cerrarModalCrear);

        window.addEventListener('click', (e) => {
            if (e.target === modalCrear) cerrarModalCrear();
        });
    }
});
