<?php
/**
 * ============================================================================
 * BAGGY CULTURE - PANEL DE CONTROL PARA SUBASTADOR
 * views/admin/subastador.php
 * ============================================================================
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../models/Subasta.php';

// Verificación de sesión y datos del subastador
$nombre_subastador = $_SESSION['nombre'] ?? 'Subastador Oficial';
$rol_usuario       = $_SESSION['rol'] ?? 'subastador';
$foto_perfil       = !empty($_SESSION['foto_perfil']) ? basename($_SESSION['foto_perfil']) : 'default_user.jpg';
$ruta_avatar       = "../../public/uploads/perfil/" . htmlspecialchars($foto_perfil);

// ============================================================================
// MANEJO DE ACCIONES VÍA AJAX O FORMULARIO POST
// ============================================================================
$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_POST['ajax']) && $_POST['ajax'] == '1')
    || (isset($_GET['ajax']));

// 1. Obtener historial de pujas por AJAX
if (isset($_GET['ajax']) && $_GET['ajax'] === 'obtener_pujas') {
    header('Content-Type: application/json; charset=utf-8');
    $id_subasta = isset($_GET['id_subasta']) ? (int)$_GET['id_subasta'] : 0;
    
    $stmtPujas = $pdo->prepare("
        SELECT pu.id_puja, pu.monto, pu.fecha_puja, u.nombre AS usuario
        FROM pujas pu
        INNER JOIN usuarios u ON pu.id_usuario = u.id_usuario
        WHERE pu.id_subasta = :id
        ORDER BY pu.monto DESC, pu.fecha_puja DESC
    ");
    $stmtPujas->execute(['id' => $id_subasta]);
    $pujas = $stmtPujas->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'pujas' => $pujas]);
    exit;
}

// 2. Procesar acciones POST (crear, cambiar estado, extender)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    // CREAR NUEVA SUBASTA
    if ($accion === 'crear_subasta') {
        $id_producto       = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
        $precio_base       = isset($_POST['precio_base']) ? (float)$_POST['precio_base'] : 0;
        $incremento_minimo = isset($_POST['incremento_minimo']) ? (float)$_POST['incremento_minimo'] : 5.00;
        $fecha_inicio      = $_POST['fecha_inicio'] ?? '';
        $fecha_fin         = $_POST['fecha_fin'] ?? '';

        if ($id_producto > 0 && $precio_base > 0 && !empty($fecha_inicio) && !empty($fecha_fin)) {
            try {
                $sqlInsert = "INSERT INTO subastas 
                    (id_producto, precio_base, precio_actual, incremento_minimo, fecha_inicio, fecha_fin, estado) 
                    VALUES (:id_producto, :precio_base, :precio_actual, :incremento, :inicio, :fin, 'activa')";
                
                $stmt = $pdo->prepare($sqlInsert);
                $stmt->execute([
                    ':id_producto'    => $id_producto,
                    ':precio_base'    => $precio_base,
                    ':precio_actual'  => $precio_base,
                    ':incremento'     => $incremento_minimo,
                    ':inicio'         => $fecha_inicio,
                    ':fin'            => $fecha_fin
                ]);

                $_SESSION['admin_msg'] = "¡Subasta lanzada con éxito para el producto seleccionado!";
            } catch (Exception $e) {
                $_SESSION['admin_err'] = "Error al crear la subasta: " . $e->getMessage();
            }
        } else {
            $_SESSION['admin_err'] = "Por favor completa todos los campos requeridos con valores válidos.";
        }

        header('Location: subastador.php');
        exit;
    }

    // CAMBIAR ESTADO (FINALIZAR O CANCELAR)
    if ($accion === 'cambiar_estado') {
        $id_subasta  = isset($_POST['id_subasta']) ? (int)$_POST['id_subasta'] : 0;
        $nuevoEstado = $_POST['estado'] ?? '';

        if ($id_subasta > 0 && in_array($nuevoEstado, ['activa', 'finalizada', 'cancelada'])) {
            try {
                $stmt = $pdo->prepare("UPDATE subastas SET estado = :estado WHERE id_subasta = :id");
                $stmt->execute([':estado' => $nuevoEstado, ':id' => $id_subasta]);

                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['status' => 'success', 'message' => "Subasta #$id_subasta marcada como '$nuevoEstado'."]);
                    exit;
                }

                $_SESSION['admin_msg'] = "Subasta #$id_subasta actualizada a estado '$nuevoEstado'.";
            } catch (Exception $e) {
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                    exit;
                }
                $_SESSION['admin_err'] = "Error al actualizar estado: " . $e->getMessage();
            }
        }

        header('Location: subastador.php');
        exit;
    }

    // EDITAR SUBASTA
    if ($accion === 'editar_subasta') {
        $id_subasta       = isset($_POST['id_subasta']) ? (int)$_POST['id_subasta'] : 0;
        $precio_base       = isset($_POST['precio_base']) ? (float)$_POST['precio_base'] : 0;
        $incremento_minimo = isset($_POST['incremento_minimo']) ? (float)$_POST['incremento_minimo'] : 5.00;
        $fecha_inicio      = $_POST['fecha_inicio'] ?? '';
        $fecha_fin         = $_POST['fecha_fin'] ?? '';
        $estado            = $_POST['estado'] ?? 'activa';

        if ($id_subasta > 0 && $precio_base > 0 && !empty($fecha_inicio) && !empty($fecha_fin)) {
            try {
                $stmt = $pdo->prepare("
                    UPDATE subastas 
                    SET precio_base = :base,
                        incremento_minimo = :inc,
                        fecha_inicio = :ini,
                        fecha_fin = :fin,
                        estado = :est
                    WHERE id_subasta = :id
                ");
                $stmt->execute([
                    ':base' => $precio_base,
                    ':inc'  => $incremento_minimo,
                    ':ini'  => $fecha_inicio,
                    ':fin'  => $fecha_fin,
                    ':est'  => $estado,
                    ':id'   => $id_subasta
                ]);

                $_SESSION['admin_msg'] = "Subasta #$id_subasta editada correctamente.";
            } catch (Exception $e) {
                $_SESSION['admin_err'] = "Error al editar subasta: " . $e->getMessage();
            }
        }
        header('Location: subastador.php');
        exit;
    }

    // ELIMINAR SUBASTA FÍSICAMENTE
    if ($accion === 'eliminar_subasta') {
        $id_subasta = isset($_POST['id_subasta']) ? (int)$_POST['id_subasta'] : 0;
        if ($id_subasta > 0) {
            try {
                // Eliminar registros de auditoria si existen para evitar restricciones
                $pdo->prepare("DELETE FROM auditoria_subastas WHERE id_subasta = :id")->execute([':id' => $id_subasta]);
                // Eliminar subasta (las pujas se eliminan en cascada por foreign key)
                $stmt = $pdo->prepare("DELETE FROM subastas WHERE id_subasta = :id");
                $stmt->execute([':id' => $id_subasta]);

                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['status' => 'success', 'message' => "Subasta #$id_subasta eliminada definitivamente de la base de datos."]);
                    exit;
                }
                $_SESSION['admin_msg'] = "Subasta #$id_subasta eliminada con éxito de la base de datos.";
            } catch (Exception $e) {
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                    exit;
                }
                $_SESSION['admin_err'] = "Error al eliminar subasta: " . $e->getMessage();
            }
        }
        header('Location: subastador.php');
        exit;
    }

    // EXTENDER TIEMPO
    if ($accion === 'extender_tiempo') {
        $id_subasta       = isset($_POST['id_subasta']) ? (int)$_POST['id_subasta'] : 0;
        $nueva_fecha_fin  = $_POST['nueva_fecha_fin'] ?? '';

        if ($id_subasta > 0 && !empty($nueva_fecha_fin)) {
            try {
                $stmt = $pdo->prepare("UPDATE subastas SET fecha_fin = :fin, estado = 'activa' WHERE id_subasta = :id");
                $stmt->execute([':fin' => $nueva_fecha_fin, ':id' => $id_subasta]);

                $_SESSION['admin_msg'] = "Tiempo extendido correctamente para la subasta #$id_subasta.";
            } catch (Exception $e) {
                $_SESSION['admin_err'] = "Error al extender el tiempo: " . $e->getMessage();
            }
        }

        header('Location: subastador.php');
        exit;
    }
}

// ============================================================================
// CONSULTA DE DATOS PARA EL PANEL
// ============================================================================

// 1. Obtener todas las subastas con información de producto y postor líder
$sqlSubastas = "
    SELECT s.*, 
           p.nombre, p.descripcion, p.categoria, p.foto_pro,
           u.nombre AS nombre_ganador,
           (SELECT COUNT(*) FROM pujas pu WHERE pu.id_subasta = s.id_subasta) AS total_pujas
    FROM subastas s
    INNER JOIN productos p ON s.id_producto = p.id_producto
    LEFT JOIN usuarios u ON s.id_ganador = u.id_usuario
    ORDER BY s.id_subasta DESC
";
$subastas = $pdo->query($sqlSubastas)->fetchAll(PDO::FETCH_ASSOC);

// 2. Obtener lista de productos activos para el selector de creación
$productosDisponibles = $pdo->query("SELECT id_producto, nombre, precio, foto_pro FROM productos WHERE estado = 'activo' ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

// 3. Calcular métricas clave (KPIs)
$kpis = [
    'activas'     => 0,
    'finalizadas' => 0,
    'canceladas'  => 0,
    'total_pujas' => 0,
    'mayor_puja'  => 0.00
];

foreach ($subastas as $s) {
    $est = strtolower($s['estado']);
    if ($est === 'activa') {
        $kpis['activas']++;
        if ($s['precio_actual'] > $kpis['mayor_puja']) {
            $kpis['mayor_puja'] = (float)$s['precio_actual'];
        }
    } elseif ($est === 'finalizada') {
        $kpis['finalizadas']++;
    } elseif ($est === 'cancelada') {
        $kpis['canceladas']++;
    }

    $kpis['total_pujas'] += (int)($s['total_pujas'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control Subastas | Baggy Culture</title>
    <!-- Favicon & Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Estilos del Subastador -->
    <link rel="stylesheet" href="../../assets/css/subastador.css">
</head>
<body>

    <!-- TOPBAR / NAVBAR SUBASTADOR -->
    <header class="admin-navbar">
        <a href="subastador.php" class="brand-wrapper">
            <span class="brand-logo-badge">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                BAGGY
            </span>
            <div class="brand-info">
                <h1>BAGGY CULTURE</h1>
                <p>Módulo Subastador</p>
            </div>
        </a>

        <div class="topbar-actions">
            <!-- Estado en vivo -->
            <div class="system-status">
                <span class="pulse-dot"></span>
                <span>Servidor en Tiempo Real</span>
            </div>

            <!-- Accesos rápidos a la tienda -->
            <a href="../subastas.php" target="_blank" class="btn-nav-link" title="Ver subastas públicas en tienda">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                    <polyline points="15 3 21 3 21 9"></polyline>
                    <line x1="10" y1="14" x2="21" y2="3"></line>
                </svg>
                Ver Subastas Públicas
            </a>

            <a href="../catalogo.php" target="_blank" class="btn-nav-link" title="Ver catálogo general">
                Catálogo
            </a>

            <!-- Perfil del usuario autenticado -->
            <div class="user-profile-menu">
                <img src="<?= $ruta_avatar ?>" alt="Avatar" class="user-profile-avatar" onerror="this.src='../../public/uploads/perfil/default_user.jpg'">
                <div class="user-profile-meta">
                    <span class="name"><?= htmlspecialchars($nombre_subastador) ?></span>
                    <span class="role"><?= ucfirst(htmlspecialchars($rol_usuario)) ?></span>
                </div>
                <a href="../../controllers/controlador.php?accion=logout" class="btn-logout" title="Cerrar Sesión">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- CONTENEDOR PRINCIPAL -->
    <main class="admin-container">
        <!-- TÍTULO Y BOTÓN DE ACCIÓN -->
        <div class="section-header">
            <div class="title-area">
                <h2>
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    Gestión de Subastas en Vivo
                </h2>
                <p>Supervisa pujas, administra estados, crea subastas y controla el tiempo de finalización.</p>
            </div>

            <button type="button" class="btn-primary-action" data-open-modal="modalCrearSubasta">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Nueva Subasta
            </button>
        </div>

        <!-- MENSAJES FLASH DE NOTIFICACIÓN -->
        <?php if (!empty($_SESSION['admin_msg'])): ?>
            <div style="background-color: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #34d399; padding: 12px 18px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span><?= htmlspecialchars($_SESSION['admin_msg']) ?></span>
            </div>
            <?php unset($_SESSION['admin_msg']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['admin_err'])): ?>
            <div style="background-color: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #f87171; padding: 12px 18px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span><?= htmlspecialchars($_SESSION['admin_err']) ?></span>
            </div>
            <?php unset($_SESSION['admin_err']); ?>
        <?php endif; ?>

        <!-- KPI METRICS / RESUMEN GENERAL -->
        <?php include __DIR__ . '/subastador/stats.php'; ?>

        <!-- TABLA Y HERRAMIENTAS DE GESTIÓN -->
        <?php include __DIR__ . '/subastador/tabla.php'; ?>
    </main>

    <!-- MODAL: CREAR NUEVA SUBASTA -->
    <?php include __DIR__ . '/subastador/modal_crear.php'; ?>

    <!-- MODAL: EDITAR SUBASTA -->
    <?php include __DIR__ . '/subastador/modal_editar.php'; ?>

    <!-- MODAL: HISTORIAL DE PUJAS EN VIVO -->
    <?php include __DIR__ . '/subastador/modal_pujas.php'; ?>

    <!-- MODAL: EXTENDER TIEMPO -->
    <?php include __DIR__ . '/subastador/modal_extender.php'; ?>

    <!-- SCRIPTS INTERACTIVOS -->
    <script src="../../assets/js/subastador.js"></script>
</body>
</html>
