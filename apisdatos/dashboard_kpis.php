<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/conexion.php';

try {
    $db = isset($pdo) ? $pdo : null;
    if (!$db && function_exists('getDB')) {
        $db = getDB();
    }
    if (!$db) {
        throw new Exception("No hay conexión a la base de datos disponible.");
    }

    $stmtVentas = $db->query("
        SELECT COALESCE(SUM(total), 0) AS total_ventas 
        FROM pedidos 
        WHERE estado IN ('pagado', 'enviado', 'entregado')
    ");
    $totalVentas = (float)$stmtVentas->fetch(PDO::FETCH_ASSOC)['total_ventas'];

    
    $stmtPendientes = $db->query("
        SELECT COUNT(*) AS total_pendientes 
        FROM pedidos 
        WHERE estado = 'pendiente'
    ");
    $pedidosPendientes = (int)$stmtPendientes->fetch(PDO::FETCH_ASSOC)['total_pendientes'];

    
    $stmtSubastas = $db->query("
        SELECT COUNT(*) AS total_activas 
        FROM subastas 
        WHERE estado = 'activa'
    ");
    $subastasActivas = (int)$stmtSubastas->fetch(PDO::FETCH_ASSOC)['total_activas'];

    
    $stmtProductos = $db->query("
        SELECT COUNT(*) AS total_productos 
        FROM productos
    ");
    $totalProductos = (int)$stmtProductos->fetch(PDO::FETCH_ASSOC)['total_productos'];

    echo json_encode([
        'status' => 'success',
        'data' => [
            'total_ventas'       => number_format($totalVentas, 2, '.', ','),
            'pedidos_pendientes' => $pedidosPendientes,
            'subastas_activas'   => $subastasActivas,
            'total_productos'    => $totalProductos
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Error al consultar métricas: ' . $e->getMessage()
    ]);
}