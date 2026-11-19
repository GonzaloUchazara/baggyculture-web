<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - BaggyCulture Admin</title>
    
    <link rel="stylesheet" href="../../assets/css/gestion-productos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php include __DIR__ . '/../../includes/navbar-admin-principal.php'; ?>
    <main class="admin-container">
        <!-- Encabezado del módulo -->
        <header class="module-header">
            <div class="header-titles">
                <h1>Gestión de Productos</h1>
                <p>Administra el catálogo, stock global, estados y variantes de prendas.</p>
            </div>
            <button id="btnOpenModal" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Nuevo Producto
            </button>
        </header>

        <!-- Barra de herramientas: Búsqueda y Filtros -->
        <section class="toolbar-section">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Buscar por nombre o categoría...">
            </div>
            <div class="filter-group">
                <select id="filterEstado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="activo">Activo</option>
                    <option value="desactivado">Desactivado</option>
                    <option value="agotado">Agotado</option>
                </select>
                <select id="filterCategoria" class="form-select">
                    <option value="">Todas las categorías</option>
                    <option value="Poleras">Poleras</option>
                    <option value="Pantalones">Pantalones</option>
                    <option value="Hoodies">Hoodies</option>
                    <option value="Accesorios">Accesorios</option>
                </select>
            </div>
        </section>

       
        <section class="table-container">
            <table class="custom-table" id="tablaProductos">
                <thead>
                    <tr>
                        <th width="70">Foto</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock Global</th>
                        <th>Estado</th>
                        <th width="160" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="productosTableBody">
                    
                    <tr>
                        <td colspan="7" class="text-center py-4">Cargando catálogo...</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>

   
    <div class="modal-overlay" id="productModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3 id="modalTitle">Nuevo Producto</h3>
                <button type="button" class="btn-close" id="btnCloseModal">&times;</button>
            </div>
            
            <form id="productForm" enctype="multipart/form-data">
                <input type="hidden" id="id_producto" name="id_producto">

                <div class="modal-body">
                
                    <h4 class="form-section-title">Información Principal</h4>
                    <div class="form-grid">
                        <div class="form-group col-span-2">
                            <label for="nombre">Nombre de la prenda</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej. Baggy Cargo Jeans V2" required maxlength="50">
                        </div>

                        <div class="form-group col-span-2">
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" class="form-control" rows="2" placeholder="Detalles de corte, tela o confección..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="precio">Precio (Bs.)</label>
                            <input type="number" id="precio" name="precio" class="form-control" min="1" step="1" placeholder="Ej. 180" required>
                        </div>

                        <div class="form-group">
                            <label for="stock">Stock Total Inicial</label>
                            <input type="number" id="stock" name="stock" class="form-control" min="0" placeholder="Ej. 20" required>
                        </div>

                        <div class="form-group">
                            <label for="categoria">Categoría</label>
                            <input type="text" id="categoria" name="categoria" class="form-control" placeholder="Ej. Hoodies" required>
                        </div>

                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select id="estado" name="estado" class="form-select" required>
                                <option value="activo">Activo</option>
                                <option value="desactivado">Desactivado</option>
                                <option value="agotado">Agotado</option>
                            </select>
                        </div>

                        <div class="form-group col-span-2">
                            <label for="foto_pro">Foto Principal de Portada</label>
                            <input type="file" id="foto_pro" name="foto_pro" class="form-control" accept="image/*">
                            <small class="help-text">Imagen principal para la card en catálogo.</small>
                        </div>
                    </div>

                   
                    <div class="variants-header">
                        <h4 class="form-section-title">Variantes de Producto</h4>
                        <button type="button" id="btnAddVariant" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-plus"></i> Añadir Variante
                        </button>
                    </div>

                    <div id="variantsContainer" class="variants-list">
                       
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" id="btnCancelModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveProduct">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <script src="../../assets/js/productos.js"></script>
</body>
</html>