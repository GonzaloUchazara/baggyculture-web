<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    require_once __DIR__ . '/../../includes/auth-guard.php';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - BaggyCulture Admin</title>
    <!-- Vinculación a los estilos del módulo -->
    <link rel="stylesheet" href="../../assets/css/navbar-admin-principal.css">
    <link rel="stylesheet" href="../../assets/css/gestion-usuarios.css">
    <!-- Iconos FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">

    <?php include __DIR__ . '/../../includes/navbar-admin-principal.php'; ?>

    <main class="admin-container">
        <!-- Encabezado del módulo -->
        <header class="module-header">
            <div class="header-titles">
                <h1>Gestión de Usuarios</h1>
                <p>Administra cuentas de clientes, permisos de roles y accesos al sistema.</p>
            </div>
            <button id="btnOpenModal" class="btn btn-primary">
                <i class="fa-solid fa-user-plus"></i> Nuevo Usuario
            </button>
        </header>

        <!-- Tarjetas KPI Superiores -->
        <section class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon icon-users"><i class="fa-solid fa-users"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">Total Usuarios</span>
                    <h3 id="kpiTotalUsuarios">0</h3>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon icon-clients"><i class="fa-solid fa-bag-shopping"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">Clientes</span>
                    <h3 id="kpiClientes">0</h3>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon icon-staff"><i class="fa-solid fa-user-shield"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">Staff / Internos</span>
                    <h3 id="kpiStaff">0</h3>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon icon-advisors"><i class="fa-solid fa-user-tie"></i></div>
                <div class="kpi-data">
                    <span class="kpi-label">Asesores / Subastadores</span>
                    <h3 id="kpiAsesores">0</h3>
                </div>
            </div>
        </section>

        <!-- Barra de Búsqueda y Filtros -->
        <section class="toolbar-section">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Buscar por nombre, apellido, correo o teléfono...">
            </div>
            <div class="filter-group">
                <select id="filterRol" class="form-select">
                    <option value="">Todos los roles</option>
                    <option value="cliente">Cliente</option>
                    <option value="admin">Administrador</option>
                    <option value="asesor">Asesor</option>
                    <option value="subastador">Subastador</option>
                </select>
                <button id="btnRefresh" class="btn btn-secondary">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </button>
            </div>
        </section>

        <!-- Tabla Principal de Usuarios -->
        <section class="table-container">
            <table class="custom-table" id="tablaUsuarios">
                <thead>
                    <tr>
                        <th width="70">Avatar</th>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                        <th>Teléfono</th>
                        <th>Rol Asignado</th>
                        <th>Fecha de Registro</th>
                        <th width="120" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="usuariosTableBody">
                    <tr>
                        <td colspan="7" class="text-center py-4">Cargando directorio de usuarios...</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>

    <!-- MODAL: Crear / Editar Usuario -->
    <div class="modal-overlay" id="userModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3 id="modalTitle">Nuevo Usuario</h3>
                <button type="button" class="btn-close" id="btnCloseModal">&times;</button>
            </div>

            <form id="userForm" enctype="multipart/form-data">
                <input type="hidden" id="id_usuario" name="id_usuario">

                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej. Andrés" required maxlength="100">
                        </div>

                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Ej. Flores" required maxlength="100">
                        </div>

                        <div class="form-group col-span-2">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="usuario@baggyculture.com" required maxlength="150">
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono / WhatsApp</label>
                            <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="Ej. 76543210" maxlength="20">
                        </div>

                        <div class="form-group">
                            <label for="rol">Rol en la Plataforma</label>
                            <select id="rol" name="rol" class="form-select" required>
                                <option value="cliente">Cliente</option>
                                <option value="admin">Administrador</option>
                                <option value="asesor">Asesor</option>
                                <option value="subastador">Subastador</option>
                            </select>
                        </div>

                        <div class="form-group col-span-2">
                            <label for="password">Contraseña</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres (dejar vacío al editar si no se cambia)">
                            <small class="help-text" id="passwordHelp">Obligatorio al crear; opcional al editar.</small>
                        </div>

                        <div class="form-group col-span-2">
                            <label for="foto_perfil">Foto de Perfil</label>
                            <input type="file" id="foto_perfil" name="foto_perfil" class="form-control" accept="image/*">
                            <small class="help-text">Formatos válidos: JPG, PNG o WEBP.</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" id="btnCancelModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveUser">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script interactivo (Paso 4) -->
    <script src="../../assets/js/gestion-usuarios.js"></script>
</body>
</html>