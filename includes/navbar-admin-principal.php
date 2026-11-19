<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_path = (strpos($_SERVER['PHP_SELF'] ?? '', '/admin/') !== false) ? '../../' : '../';
$current_page = basename($_SERVER['PHP_SELF'] ?? '');
?>
<link rel="stylesheet" href="<?= $base_path ?>assets/css/navbar-admin-principal.css">

<header class="admin-header">
    <nav class="admin-navbar">
        <div class="admin-nav-brand">
            <a href="admin.php" class="brand-logo">
                <span class="logo-accent">Baggy</span>Admin
            </a>
            <span class="badge-role">Principal</span>
        </div>

        <button class="nav-toggle" id="adminPrincipalNavToggle" aria-label="Abrir menú">
            <span class="hamburger"></span>
        </button>

        <div class="admin-nav-menu" id="adminPrincipalNavMenu">
            <ul class="nav-links">
                <li>
                    <a href="admin.php" class="nav-link <?= ($current_page === 'admin.php' || $current_page === 'dashboard.php') ? 'active' : '' ?>">Principal Dashboard</a>
                </li>
                <li>
                    <a href="#" class="nav-link <?= ($current_page === 'productos.php') ? 'active' : '' ?>">Gestión de Productos</a>
                </li>
                <li>
                    <a href="#" class="nav-link <?= ($current_page === 'pedidos.php' || $current_page === 'ventas.php') ? 'active' : '' ?>">Pedidos y Ventas</a>
                </li>
                <li>
                    <a href="#" class="nav-link <?= ($current_page === 'usuarios.php') ? 'active' : '' ?>">Usuarios</a>
                </li>
                <li>
                    <a href="#" class="nav-link <?= ($current_page === 'auditoria-subastas.php' || $current_page === 'auditoria.php') ? 'active' : '' ?>">Auditoría</a>
                </li>
            </ul>

            <div class="admin-nav-user">
                <div class="user-info">
                    <span class="user-name">
                        <?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['nombre_usuario'] ?? 'Administrador') ?>
                    </span>
                    <span class="user-status">En línea</span>
                </div>
                <a href="<?= $base_path ?>controllers/controlador.php?accion=logout" class="btn-logout" title="Cerrar sesión">
                    Salir
                </a>
            </div>
        </div>
    </nav>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const adminNavToggle = document.getElementById('adminPrincipalNavToggle');
    const adminNavMenu = document.getElementById('adminPrincipalNavMenu');
    if (adminNavToggle && adminNavMenu) {
        adminNavToggle.addEventListener('click', function() {
            adminNavMenu.classList.toggle('is-active');
        });
    }
});
</script>
