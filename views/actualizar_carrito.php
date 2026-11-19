<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/conexion.php';

header('Content-Type: application/json');
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([
        'status' => 'auth_required',
        'message' => 'Sesión expirada'
    ]);
    exit;
}
$id_usuario = (int)$_SESSION['id_usuario'];
$data = json_decode(file_get_contents('php://input'), true);

$id_item = isset($data['id_item']) ? (int)$data['id_item'] : 0;
$action  = isset($data['action']) ? trim($data['action']) : '';

if ($id_item <= 0 || !in_array($action, ['increase', 'decrease', 'remove'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Parámetros inválidos'
    ]);
    exit;
}
try {
   
    $sqlCheck = "
        SELECT 
            ic.id_item,
            ic.id_carrito,
            ic.cantidad,
            p.precio,
            p.stock AS stock_maximo
        FROM items_carrito ic
        INNER JOIN carritos c ON ic.id_carrito = c.id_carrito
        INNER JOIN variantes_producto vp ON ic.id_variante = vp.id_variante
        INNER JOIN productos p ON vp.id_producto = p.id_producto
        WHERE ic.id_item = :id_item AND c.id_usuario = :id_usuario
        LIMIT 1
    ";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([
        ':id_item'    => $id_item,
        ':id_usuario' => $id_usuario
    ]);
    $itemActual = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$itemActual) {
        echo json_encode([
            'status' => 'error',
            'message' => 'El artículo no fue encontrado en tu carrito'
        ]);
        exit;
    }

    $id_carrito = (int)$itemActual['id_carrito'];
    $cantidad   = (int)$itemActual['cantidad'];
    $precio     = (float)$itemActual['precio'];
    $stockMax   = (int)$itemActual['stock_maximo'];

   
    if ($action === 'increase') {
        if ($cantidad >= $stockMax) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Has alcanzado el límite de stock disponible para este producto'
            ]);
            exit;
        }
        $nuevaCantidad = $cantidad + 1;
        $stmtUpd = $pdo->prepare("UPDATE items_carrito SET cantidad = :cant WHERE id_item = :id_item");
        $stmtUpd->execute([':cant' => $nuevaCantidad, ':id_item' => $id_item]);

    } elseif ($action === 'decrease') {
        $nuevaCantidad = $cantidad - 1;
        if ($nuevaCantidad <= 0) {
            
            $stmtDel = $pdo->prepare("DELETE FROM items_carrito WHERE id_item = :id_item");
            $stmtDel->execute([':id_item' => $id_item]);
            $action = 'remove';
        } else {
            $stmtUpd = $pdo->prepare("UPDATE items_carrito SET cantidad = :cant WHERE id_item = :id_item");
            $stmtUpd->execute([':cant' => $nuevaCantidad, ':id_item' => $id_item]);
        }

    } elseif ($action === 'remove') {
        $stmtDel = $pdo->prepare("DELETE FROM items_carrito WHERE id_item = :id_item");
        $stmtDel->execute([':id_item' => $id_item]);
        $nuevaCantidad = 0;
    }

   
    $sqlTotales = "
        SELECT 
            COALESCE(SUM(ic.cantidad * p.precio), 0) AS total_general,
            COALESCE(SUM(ic.cantidad), 0) AS total_prendas,
            COUNT(ic.id_item) AS total_filas
        FROM items_carrito ic
        INNER JOIN variantes_producto vp ON ic.id_variante = vp.id_variante
        INNER JOIN productos p ON vp.id_producto = p.id_producto
        WHERE ic.id_carrito = :id_carrito
    ";
    $stmtTotales = $pdo->prepare($sqlTotales);
    $stmtTotales->execute([':id_carrito' => $id_carrito]);
    $totales = $stmtTotales->fetch(PDO::FETCH_ASSOC);

    $subtotalItem = ($action !== 'remove') ? ($nuevaCantidad * $precio) : 0;

    echo json_encode([
        'status'         => 'success',
        'action_done'    => $action,
        'item_cantidad'  => $nuevaCantidad,
        'item_subtotal'  => number_format($subtotalItem, 2, '.', ''),
        'total_general'  => number_format((float)$totales['total_general'], 2, '.', ''),
        'total_prendas'  => (int)$totales['total_prendas'],
        'total_filas'    => (int)$totales['total_filas']
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al actualizar el carrito: ' . $e->getMessage()
    ]);
}