<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_path = (strpos($_SERVER['PHP_SELF'] ?? '', '/admin/') !== false) ? '../../' : '../';
$current_page = basename($_SERVER['PHP_SELF'] ?? '');
?>
<link rel="stylesheet" href="<?= $base_path ?>assets/css/subastas-admin.css">

<header class="admin-header">
    <nav class="admin-navbar">
        <div class="admin-nav-brand">
            <a href="subastador.php" class="brand-logo">
                <span class="logo-accent">Baggy</span>Subastador
            </a>
            <span class="badge-role">Subastas</span>
        </div>

        <button class="nav-toggle" id="adminNavToggle" aria-label="Abrir menú">
            <span class="hamburger"></span>
        </button>
        <div class="admin-nav-menu" id="adminNavMenu">
            <ul class="nav-links">
                <li>
                    <a href="subastador.php" class="nav-link <?= $current_page === 'subastador.php' ? 'active' : '' ?>">Gestión Subastas</a>
                </li>
                <li>
                    <a href="reportes-categoria.php" class="nav-link <?= $current_page === 'reportes-categoria.php' ? 'active' : '' ?>">Dashboard (Datos)</a>
                </li>
                <li>
                    <a href="auditoria-subastas.php" class="nav-link <?= $current_page === 'auditoria-subastas.php' ? 'active' : '' ?>">Auditoría</a>
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
    const adminNavToggle = document.getElementById('adminNavToggle');
    const adminNavMenu = document.getElementById('adminNavMenu');
    if (adminNavToggle && adminNavMenu) {
        adminNavToggle.addEventListener('click', function() {
            adminNavMenu.classList.toggle('is-active');
        });
    }
});
</script>
