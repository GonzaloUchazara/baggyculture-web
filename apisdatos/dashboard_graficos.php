<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/conexion.php'; 

try {
    if (isset($conexion)) {
        $db = $conexion;
    } elseif (isset($pdo)) {
        $db = $pdo;
    } else {
        $db = getDB();
    }

  
    $stmtPedidos = $db->query("
        SELECT estado, COUNT(*) AS cantidad 
        FROM pedidos 
        GROUP BY estado
    ");
    $estadosData = $stmtPedidos->fetchAll(PDO::FETCH_ASSOC);

    
    $stmtCategorias = $db->query("
        SELECT COALESCE(categoria, 'Sin categoría') AS categoria, COUNT(*) AS total 
        FROM productos 
        GROUP BY categoria
    ");
    $categoriasData = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'pedidos' => [
            'labels' => array_column($estadosData, 'estado'),
            'values' => array_map('intval', array_column($estadosData, 'cantidad'))
        ],
        'categorias' => [
            'labels' => array_column($categoriasData, 'categoria'),
            'values' => array_map('intval', array_column($categoriasData, 'total'))
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Error al obtener datos de gráficos: ' . $e->getMessage()
    ]);
}