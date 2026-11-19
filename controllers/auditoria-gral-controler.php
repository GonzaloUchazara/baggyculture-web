<?php
header('Content-Type: application/json; charset=utf-8');

// Conexión centralizada a la base de datos
require_once __DIR__ . '/../config/conexion.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {

        // -------------------------------------------------------------
        // 1. LISTAR EVENTOS DE AUDITORÍA Y CALCULAR KPIS
        // -------------------------------------------------------------
        case 'listar':
            $grupo           = trim($_GET['grupo'] ?? 'todas');
            $tablaFiltro     = trim($_GET['tabla'] ?? '');
            $operacionFiltro = trim($_GET['operacion'] ?? '');
            $busqueda        = trim($_GET['q'] ?? '');

            // Grupos para segmentar auditorías
            $gruposTablas = [
                'productos' => ['productos', 'variantes_producto'],
                'pedidos'   => ['pedidos', 'detalle_pedidos'],
                'usuarios'  => ['usuarios'],
                'subastas'  => ['subastas', 'pujas']
            ];

            $where = " WHERE 1 = 1";
            $params = [];

            if (!empty($tablaFiltro)) {
                $where .= " AND nombre_tabla = ?";
                $params[] = $tablaFiltro;
            } elseif (isset($gruposTablas[$grupo])) {
                $inTablas = implode("','", $gruposTablas[$grupo]);
                $where .= " AND nombre_tabla IN ('$inTablas')";
            }

            if (!empty($operacionFiltro)) {
                $where .= " AND operacion = ?";
                $params[] = $operacionFiltro;
            }

            if (!empty($busqueda)) {
                $where .= " AND (clave_registro LIKE ? OR usuario_bd LIKE ? OR nombre_tabla LIKE ?)";
                $paramLike = "%{$busqueda}%";
                $params[] = $paramLike;
                $params[] = $paramLike;
                $params[] = $paramLike;
            }

            $sql = "SELECT id_auditoria, nombre_tabla, operacion, fecha_operacion, 
                           usuario_bd, clave_registro, momento
                    FROM auditoria
                    $where
                    ORDER BY id_auditoria DESC LIMIT 200";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $logs = $stmt->fetchAll();

            // Cálculo de métricas KPI según el grupo activo
            $sqlKpis = "SELECT 
                            COUNT(*) AS total_logs,
                            SUM(CASE WHEN operacion = 'INSERT' THEN 1 ELSE 0 END) AS inserts,
                            SUM(CASE WHEN operacion = 'UPDATE' THEN 1 ELSE 0 END) AS updates,
                            SUM(CASE WHEN operacion = 'DELETE' THEN 1 ELSE 0 END) AS deletes
                        FROM auditoria";

            if (isset($gruposTablas[$grupo]) && empty($tablaFiltro)) {
                $inTablas = implode("','", $gruposTablas[$grupo]);
                $sqlKpis .= " WHERE nombre_tabla IN ('$inTablas')";
            } elseif (!empty($tablaFiltro)) {
                $sqlKpis .= " WHERE nombre_tabla = " . $pdo->quote($tablaFiltro);
            }

            $stmtKpi = $pdo->query($sqlKpis);
            $kpis = $stmtKpi->fetch();

            echo json_encode([
                'status' => 'success',
                'data'   => $logs,
                'kpis'   => [
                    'total_logs' => (int)($kpis['total_logs'] ?? 0),
                    'inserts'    => (int)($kpis['inserts'] ?? 0),
                    'updates'    => (int)($kpis['updates'] ?? 0),
                    'deletes'    => (int)($kpis['deletes'] ?? 0)
                ]
            ]);
            break;

        // -------------------------------------------------------------
        // 2. OBTENER DETALLE COMPLETO Y PARSEAR JSON (DIFF)
        // -------------------------------------------------------------
        case 'obtener_detalle':
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'ID de log inválido']);
                exit;
            }

            $stmt = $pdo->prepare("SELECT * FROM auditoria WHERE id_auditoria = ?");
            $stmt->execute([$id]);
            $log = $stmt->fetch();

            if (!$log) {
                echo json_encode(['status' => 'error', 'message' => 'Registro de auditoría no encontrado']);
                exit;
            }

            // Decodificar campos JSON para entregar objetos estructurados
            $log['datos_anteriores'] = !empty($log['datos_anteriores']) ? json_decode($log['datos_anteriores'], true) : null;
            $log['datos_nuevos']     = !empty($log['datos_nuevos'])     ? json_decode($log['datos_nuevos'], true) : null;

            echo json_encode(['status' => 'success', 'data' => $log]);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no permitida']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}