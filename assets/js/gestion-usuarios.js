document.addEventListener('DOMContentLoaded', () => {
    // Referencias al DOM - Tabla y Filtros
    const usuariosTableBody = document.getElementById('usuariosTableBody');
    const searchInput = document.getElementById('searchInput');
    const filterRol = document.getElementById('filterRol');
    const btnRefresh = document.getElementById('btnRefresh');

    // Referencias al DOM - KPIs
    const kpiTotalUsuarios = document.getElementById('kpiTotalUsuarios');
    const kpiClientes = document.getElementById('kpiClientes');
    const kpiStaff = document.getElementById('kpiStaff');
    const kpiAsesores = document.getElementById('kpiAsesores');

    // Referencias al DOM - Modal y Formulario
    const userModal = document.getElementById('userModal');
    const btnOpenModal = document.getElementById('btnOpenModal');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const btnCancelModal = document.getElementById('btnCancelModal');
    const userForm = document.getElementById('userForm');
    const modalTitle = document.getElementById('modalTitle');
    const passwordHelp = document.getElementById('passwordHelp');
    const passwordInput = document.getElementById('password');

    let usuariosData = [];

    // Endpoint Backend
    const API_URL = '../../controllers/gestion-usuarios.php';

    function getRutaAvatar(foto) {
        if (!foto || foto === 'default_user.jpg') {
            return '../../public/uploads/perfil/default_user.jpg';
        }
        if (foto.startsWith('http://') || foto.startsWith('https://') || foto.startsWith('/') || foto.startsWith('../')) {
            return foto;
        }
        return `../../public/uploads/perfil/${foto}`;
    }

    // -------------------------------------------------------------------------
    // 1. CARGA INICIAL Y KPIS
    // -------------------------------------------------------------------------
    async function cargarUsuarios() {
        try {
            usuariosTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4">Cargando directorio de usuarios...</td></tr>`;
            const response = await fetch(`${API_URL}?action=listar`);
            const result = await response.json();

            if (result.status === 'success') {
                usuariosData = result.data;
                actualizarKPIs(result.kpis);
                renderizarTabla(usuariosData);
            } else {
                usuariosTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">${result.message}</td></tr>`;
            }
        } catch (error) {
            console.error('Error al cargar usuarios:', error);
            usuariosTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">Error de conexión al cargar usuarios</td></tr>`;
        }
    }

    // -------------------------------------------------------------------------
    // 2. ACTUALIZAR KPIS
    // -------------------------------------------------------------------------
    function actualizarKPIs(kpis) {
        if (!kpis) return;
        kpiTotalUsuarios.textContent = kpis.total_usuarios;
        kpiClientes.textContent = kpis.clientes;
        kpiStaff.textContent = kpis.staff;
        kpiAsesores.textContent = kpis.asesores;
    }

    // -------------------------------------------------------------------------
    // 3. RENDERIZAR TABLA DE USUARIOS
    // -------------------------------------------------------------------------
    function renderizarTabla(lista) {
        if (!lista || lista.length === 0) {
            usuariosTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4">No se encontraron usuarios registrados</td></tr>`;
            return;
        }

        usuariosTableBody.innerHTML = '';

        lista.forEach(u => {
            const tr = document.createElement('tr');
            tr.dataset.id = u.id_usuario;

            const avatarSrc = getRutaAvatar(u.foto_perfil);

            const fechaObj = new Date(u.creado_en);
            const fechaFormateada = isNaN(fechaObj.getTime()) 
                ? u.creado_en 
                : fechaObj.toLocaleDateString('es-BO', { year: 'numeric', month: 'short', day: 'numeric' });

            tr.innerHTML = `
                <td>
                    <img src="${avatarSrc}" alt="${u.nombre}" class="user-avatar-thumb" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(u.nombre + '+' + u.apellido)}&background=181b21&color=fff'">
                </td>
                <td>
                    <strong>${u.nombre} ${u.apellido}</strong><br>
                    <small class="help-text">ID: #${u.id_usuario}</small>
                </td>
                <td>${u.email}</td>
                <td>${u.telefono || '<span class="text-muted">No registrado</span>'}</td>
                <td>
                    <select class="role-select role-${u.rol}" data-id="${u.id_usuario}">
                        <option value="cliente" ${u.rol === 'cliente' ? 'selected' : ''}>Cliente</option>
                        <option value="admin" ${u.rol === 'admin' ? 'selected' : ''}>Admin</option>
                        <option value="asesor" ${u.rol === 'asesor' ? 'selected' : ''}>Asesor</option>
                        <option value="subastador" ${u.rol === 'subastador' ? 'selected' : ''}>Subastador</option>
                    </select>
                </td>
                <td><small class="text-muted">${fechaFormateada}</small></td>
                <td class="text-center">
                    <button type="button" class="btn-action btn-edit" title="Editar Usuario" data-id="${u.id_usuario}">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </td>
            `;

            usuariosTableBody.appendChild(tr);
        });

        asignarEventosFila();
    }

    // -------------------------------------------------------------------------
    // 4. ACCIONES: CAMBIO RÁPIDO DE ROL Y EDICIÓN
    // -------------------------------------------------------------------------
    function asignarEventosFila() {
        // Selector de Rol directo
        document.querySelectorAll('.role-select').forEach(select => {
            select.onchange = async () => {
                const id_usuario = select.dataset.id;
                const nuevo_rol = select.value;

                const formData = new FormData();
                formData.append('action', 'cambiar_rol');
                formData.append('id_usuario', id_usuario);
                formData.append('rol', nuevo_rol);

                try {
                    const response = await fetch(API_URL, {
                        method: 'POST',
                        body: formData
                    });
                    const res = await response.json();

                    if (res.status === 'success') {
                        select.className = `role-select role-${nuevo_rol}`;
                        cargarUsuarios(); // Recalcula KPIs
                    } else {
                        alert(res.message || 'Error al actualizar el rol');
                        cargarUsuarios();
                    }
                } catch (error) {
                    console.error('Error al cambiar rol:', error);
                    alert('Error de conexión al guardar el rol.');
                    cargarUsuarios();
                }
            };
        });

        // Botón Editar
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.onclick = async () => {
                const id_usuario = btn.dataset.id;
                try {
                    const res = await fetch(`${API_URL}?action=obtener&id=${id_usuario}`);
                    const result = await res.json();
                    if (result.status === 'success') {
                        abrirModalEdicion(result.data);
                    } else {
                        alert(result.message);
                    }
                } catch (e) {
                    alert('Error al obtener datos del usuario.');
                }
            };
        });
    }

    // -------------------------------------------------------------------------
    // 5. CONTROL DEL MODAL
    // -------------------------------------------------------------------------
    function abrirModalCreacion() {
        userForm.reset();
        document.getElementById('id_usuario').value = '';
        modalTitle.textContent = 'Nuevo Usuario';
        passwordInput.required = true;
        passwordHelp.textContent = 'Obligatorio al crear (mínimo 6 caracteres).';
        userModal.classList.add('open');
    }

    function abrirModalEdicion(data) {
        userForm.reset();
        document.getElementById('id_usuario').value = data.id_usuario;
        document.getElementById('nombre').value = data.nombre;
        document.getElementById('apellido').value = data.apellido;
        document.getElementById('email').value = data.email;
        document.getElementById('telefono').value = data.telefono || '';
        document.getElementById('rol').value = data.rol;

        modalTitle.textContent = `Editar Usuario #${data.id_usuario}`;
        passwordInput.required = false;
        passwordHelp.textContent = 'Dejar en blanco si no deseas cambiar la contraseña actual.';
        userModal.classList.add('open');
    }

    function cerrarModal() {
        userModal.classList.remove('open');
    }

    // Guardar (Submit)
    userForm.onsubmit = async (e) => {
        e.preventDefault();

        const formData = new FormData(userForm);
        formData.append('action', 'guardar');

        try {
            const res = await fetch(API_URL, {
                method: 'POST',
                body: formData
            });
            const result = await res.json();

            if (result.status === 'success') {
                cerrarModal();
                cargarUsuarios();
            } else {
                alert(result.message || 'Error al procesar el usuario');
            }
        } catch (error) {
            console.error('Error al guardar:', error);
            alert('Error de conexión al guardar los datos.');
        }
    };

    // -------------------------------------------------------------------------
    // 6. FILTRADO Y BÚSQUEDA REACTIVA
    // -------------------------------------------------------------------------
    function aplicarFiltros() {
        const query = searchInput.value.toLowerCase().trim();
        const rolSeleccionado = filterRol.value;

        const filtrados = usuariosData.filter(u => {
            const nombreCompleto = `${u.nombre} ${u.apellido}`.toLowerCase();
            const email = (u.email || '').toLowerCase();
            const tel = (u.telefono || '').toLowerCase();
            const idMatch = u.id_usuario.toString().includes(query);

            const matchTexto = idMatch || nombreCompleto.includes(query) || email.includes(query) || tel.includes(query);
            const matchRol = rolSeleccionado === '' || u.rol === rolSeleccionado;

            return matchTexto && matchRol;
        });

        renderizarTabla(filtrados);
    }

    // Listeners
    btnOpenModal.onclick = abrirModalCreacion;
    btnCloseModal.onclick = cerrarModal;
    btnCancelModal.onclick = cerrarModal;
    btnRefresh.onclick = cargarUsuarios;

    searchInput.addEventListener('input', aplicarFiltros);
    filterRol.addEventListener('change', aplicarFiltros);

    // Carga inicial
    cargarUsuarios();
});