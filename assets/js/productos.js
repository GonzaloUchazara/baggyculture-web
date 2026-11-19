document.addEventListener('DOMContentLoaded', () => {
    // Referencias al DOM
    const productosTableBody = document.getElementById('productosTableBody');
    const searchInput = document.getElementById('searchInput');
    const filterEstado = document.getElementById('filterEstado');
    const filterCategoria = document.getElementById('filterCategoria');
    
    // Modal y Formulario
    const productModal = document.getElementById('productModal');
    const btnOpenModal = document.getElementById('btnOpenModal');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const btnCancelModal = document.getElementById('btnCancelModal');
    const productForm = document.getElementById('productForm');
    const modalTitle = document.getElementById('modalTitle');
    const btnAddVariant = document.getElementById('btnAddVariant');
    const variantsContainer = document.getElementById('variantsContainer');

    let productosData = [];

    
    const API_URL = '../../controllers/productos-controler.php';

    function getRutaImagen(foto) {
        if (!foto) return '../../public/uploads/default.jpg';
        if (foto.startsWith('http://') || foto.startsWith('https://') || foto.startsWith('/') || foto.startsWith('../')) {
            return foto;
        }
        return `../../public/uploads/productos/${foto}`;
    }

    
    async function cargarProductos() {
        try {
            const response = await fetch(`${API_URL}?action=listar`);
            const result = await response.json();

            if (result.status === 'success') {
                productosData = result.data;
                renderizarTabla(productosData);
            } else {
                productosTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">${result.message}</td></tr>`;
            }
        } catch (error) {
            console.error('Error al cargar productos:', error);
            productosTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">Error al conectar con el servidor</td></tr>`;
        }
    }

    
    function renderizarTabla(lista) {
        if (!lista || lista.length === 0) {
            productosTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4">No se encontraron productos registrados</td></tr>`;
            return;
        }

        productosTableBody.innerHTML = '';

        lista.forEach(prod => {
            const tr = document.createElement('tr');
            tr.dataset.id = prod.id_producto;

            const fotoSrc = getRutaImagen(prod.foto_pro);
            const badgeClass = `badge badge-${prod.estado}`;
            const toggleIconClass = prod.estado === 'activo' ? 'fa-toggle-on toggle-active' : 'fa-toggle-off toggle-inactive';
            const toggleTooltip = prod.estado === 'activo' ? 'Desactivar producto' : 'Activar producto';

            tr.innerHTML = `
                <td>
                    <img src="${fotoSrc}" alt="${prod.nombre}" class="product-img-thumb" onerror="this.src='../../public/uploads/default.jpg'">
                </td>
                <td>
                    <strong>${prod.nombre}</strong><br>
                    <small class="help-text">${prod.total_variantes} variante(s)</small>
                </td>
                <td>${prod.categoria}</td>
                <td>Bs. ${parseFloat(prod.precio).toFixed(2)}</td>
                <td>${prod.stock} uds.</td>
                <td><span class="${badgeClass}">${prod.estado}</span></td>
                <td class="text-center">
                    <button type="button" class="btn-action btn-expand" title="Ver Variantes" data-id="${prod.id_producto}">
                        <i class="fa-solid fa-layer-group"></i>
                    </button>
                    <button type="button" class="btn-action btn-edit" title="Editar" data-id="${prod.id_producto}">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button type="button" class="btn-action btn-toggle" title="${toggleTooltip}" data-id="${prod.id_producto}" data-estado="${prod.estado}">
                        <i class="fa-solid ${toggleIconClass}"></i>
                    </button>
                </td>
            `;

            productosTableBody.appendChild(tr);

            
            const subTr = document.createElement('tr');
            subTr.id = `subrow-${prod.id_producto}`;
            subTr.className = 'variants-subrow';
            subTr.style.display = 'none';
            subTr.innerHTML = `
                <td colspan="7">
                    <div id="variants-content-${prod.id_producto}" class="variant-chips-container">
                        <small>Cargando detalles de variantes...</small>
                    </div>
                </td>
            `;
            productosTableBody.appendChild(subTr);
        });

        asignarEventosAcciones();
    }

    
    function asignarEventosAcciones() {
        // Expandir / Ocultar variantes
        document.querySelectorAll('.btn-expand').forEach(btn => {
            btn.onclick = async () => {
                const id = btn.dataset.id;
                const subRow = document.getElementById(`subrow-${id}`);
                const container = document.getElementById(`variants-content-${id}`);

                if (subRow.style.display === 'none') {
                    subRow.style.display = 'table-row';
                    try {
                        const res = await fetch(`${API_URL}?action=obtener&id=${id}`);
                        const resData = await res.json();
                        if (resData.status === 'success') {
                            const variantes = resData.data.variantes || [];
                            if (variantes.length === 0) {
                                container.innerHTML = `<span class="help-text">Este producto no cuenta con variantes registradas.</span>`;
                            } else {
                                container.innerHTML = variantes.map(v => `
                                    <div class="variant-card-chip">
                                        <img src="${getRutaImagen(v.foto1_pro)}" alt="${v.talla}" onerror="this.src='../../public/uploads/default.jpg'">
                                        <div>
                                            <strong>Talla:</strong> ${v.talla} | 
                                            <strong>Color:</strong> ${v.color || 'N/A'}
                                        </div>
                                    </div>
                                `).join('');
                            }
                        }
                    } catch (e) {
                        container.innerHTML = `<span class="text-danger">Error al cargar variantes.</span>`;
                    }
                } else {
                    subRow.style.display = 'none';
                }
            };
        });

      
        document.querySelectorAll('.btn-toggle').forEach(btn => {
            btn.onclick = async () => {
                const id = btn.dataset.id;
                const estadoActual = btn.dataset.estado;
                const nuevoEstado = estadoActual === 'activo' ? 'desactivado' : 'activo';

                const formData = new FormData();
                formData.append('action', 'cambiar_estado');
                formData.append('id', id);
                formData.append('estado', nuevoEstado);

                try {
                    const res = await fetch(API_URL, {
                        method: 'POST',
                        body: formData
                    });
                    const resData = await res.json();
                    if (resData.status === 'success') {
                        cargarProductos();
                    } else {
                        alert(resData.message);
                    }
                } catch (e) {
                    alert('Error de conexión al actualizar el estado.');
                }
            };
        });

        // Editar Producto
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.onclick = async () => {
                const id = btn.dataset.id;
                try {
                    const res = await fetch(`${API_URL}?action=obtener&id=${id}`);
                    const result = await res.json();
                    if (result.status === 'success') {
                        abrirModalEdicion(result.data);
                    }
                } catch (e) {
                    alert('Error al obtener datos del producto.');
                }
            };
        });
    }


    function abrirModalCreacion() {
        productForm.reset();
        document.getElementById('id_producto').value = '';
        modalTitle.textContent = 'Nuevo Producto';
        variantsContainer.innerHTML = '';
        agregarFilaVariante(); 
        productModal.classList.add('open');
    }

    function abrirModalEdicion(data) {
        productForm.reset();
        document.getElementById('id_producto').value = data.id_producto;
        document.getElementById('nombre').value = data.nombre;
        document.getElementById('descripcion').value = data.descripcion;
        document.getElementById('precio').value = data.precio;
        document.getElementById('stock').value = data.stock;
        document.getElementById('categoria').value = data.categoria;
        document.getElementById('estado').value = data.estado;
        
        modalTitle.textContent = `Editar Producto #${data.id_producto}`;
        variantsContainer.innerHTML = '';

        if (data.variantes && data.variantes.length > 0) {
            data.variantes.forEach(v => agregarFilaVariante(v));
        } else {
            agregarFilaVariante();
        }

        sincronizarFotosPorColor();
        productModal.classList.add('open');
    }

    function cerrarModal() {
        productModal.classList.remove('open');
    }

    function sincronizarFotosPorColor() {
        const filas = variantsContainer.querySelectorAll('.variant-row-item');
        const coloresVistos = new Set();

        filas.forEach(fila => {
            const inputColor = fila.querySelector('.var-color');
            const colorOriginal = inputColor ? inputColor.value.trim() : '';
            const colorKey = colorOriginal.toLowerCase();

            const colFoto1 = fila.querySelector('.var-col-foto1');
            const colFoto2 = fila.querySelector('.var-col-foto2');
            const inputFoto1 = colFoto1 ? colFoto1.querySelector('.var-file1') : null;
            const inputFoto2 = colFoto2 ? colFoto2.querySelector('.var-file2') : null;
            const notice1 = colFoto1 ? colFoto1.querySelector('.var-shared-notice') : null;
            const notice2 = colFoto2 ? colFoto2.querySelector('.var-shared-notice') : null;

            if (!colorKey) {
                // Si aún no se escribe el color, se mantienen los campos de archivo visibles
                if (inputFoto1) inputFoto1.style.display = 'block';
                if (inputFoto2) inputFoto2.style.display = 'block';
                if (notice1) notice1.style.display = 'none';
                if (notice2) notice2.style.display = 'none';
                return;
            }

            if (coloresVistos.has(colorKey)) {
                // Si el color ya apareció antes en otra fila, se comparten las fotos
                if (inputFoto1) {
                    inputFoto1.style.display = 'none';
                    inputFoto1.value = '';
                }
                if (inputFoto2) {
                    inputFoto2.style.display = 'none';
                    inputFoto2.value = '';
                }
                if (notice1) {
                    notice1.style.display = 'block';
                    notice1.querySelector('.badge-shared').innerHTML = `<i class="fa-solid fa-share-nodes"></i> Comparte foto adelante ("${colorOriginal}")`;
                }
                if (notice2) {
                    notice2.style.display = 'block';
                    notice2.querySelector('.badge-shared').innerHTML = `<i class="fa-solid fa-share-nodes"></i> Comparte foto atrás ("${colorOriginal}")`;
                }
            } else {
                // Es el primer registro de este color: permite subir adelante y atrás
                coloresVistos.add(colorKey);
                if (inputFoto1) inputFoto1.style.display = 'block';
                if (inputFoto2) inputFoto2.style.display = 'block';
                if (notice1) notice1.style.display = 'none';
                if (notice2) notice2.style.display = 'none';
            }
        });
    }

    function agregarFilaVariante(variante = null) {
        const div = document.createElement('div');
        div.className = 'variant-row-item';

        const tallaVal = variante ? variante.talla : '';
        const colorVal = variante ? (variante.color || '') : '';

        div.innerHTML = `
            <div class="variant-col">
                <label class="field-label-sm">Talla *</label>
                <input type="text" name="tallas[]" class="form-control var-talla" placeholder="Ej. S, M, L..." value="${tallaVal}" required>
            </div>
            <div class="variant-col">
                <label class="field-label-sm">Color *</label>
                <input type="text" name="colores[]" class="form-control var-color" placeholder="Ej. Negro, Azul..." value="${colorVal}" required>
            </div>
            <div class="variant-col var-col-foto1">
                <label class="field-label-sm">Adelante (foto_pro1)</label>
                <input type="file" name="variantes_foto1[]" class="form-control var-file var-file1" accept="image/*" title="Foto Adelante">
                <div class="var-shared-notice" style="display: none;">
                    <span class="badge-shared"></span>
                </div>
            </div>
            <div class="variant-col var-col-foto2">
                <label class="field-label-sm">Atrás (foto_pro2)</label>
                <input type="file" name="variantes_foto2[]" class="form-control var-file var-file2" accept="image/*" title="Foto Atrás">
                <div class="var-shared-notice" style="display: none;">
                    <span class="badge-shared"></span>
                </div>
            </div>
            <div class="variant-col" style="align-self: center; padding-top: 1.1rem;">
                <button type="button" class="btn-remove-variant" title="Eliminar Variante">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        `;

        const inputColor = div.querySelector('.var-color');
        if (inputColor) {
            inputColor.addEventListener('input', () => sincronizarFotosPorColor());
        }

        div.querySelector('.btn-remove-variant').onclick = () => {
            div.remove();
            sincronizarFotosPorColor();
        };

        variantsContainer.appendChild(div);
        sincronizarFotosPorColor();
    }

   
    productForm.onsubmit = async (e) => {
        e.preventDefault();

        const formData = new FormData(productForm);
        formData.append('action', 'guardar');

        try {
            const res = await fetch(API_URL, {
                method: 'POST',
                body: formData
            });
            const result = await res.json();

            if (result.status === 'success') {
                cerrarModal();
                cargarProductos();
            } else {
                alert(result.message || 'Error al guardar el producto');
            }
        } catch (error) {
            console.error('Error al guardar:', error);
            alert('Error de conexión con el servidor al procesar el formulario.');
        }
    };

   
    function aplicarFiltros() {
        const query = searchInput.value.toLowerCase().trim();
        const est = filterEstado.value;
        const cat = filterCategoria.value.toLowerCase();

        const filtrados = productosData.filter(p => {
            const matchNombre = p.nombre.toLowerCase().includes(query) || p.categoria.toLowerCase().includes(query);
            const matchEstado = est === '' || p.estado === est;
            const matchCat = cat === '' || p.categoria.toLowerCase() === cat;
            return matchNombre && matchEstado && matchCat;
        });

        renderizarTabla(filtrados);
    }

   
    btnOpenModal.onclick = abrirModalCreacion;
    btnCloseModal.onclick = cerrarModal;
    btnCancelModal.onclick = cerrarModal;
    btnAddVariant.onclick = () => agregarFilaVariante();

    searchInput.addEventListener('input', aplicarFiltros);
    filterEstado.addEventListener('change', aplicarFiltros);
    filterCategoria.addEventListener('change', aplicarFiltros);

    
    cargarProductos();
});