<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/conexion.php';
header('Content-Type: application/json');
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([
        'status' => 'auth_required',
        'message' => 'Debes iniciar sesión para añadir productos al carrito'
    ]);
    exit;
}
$inputJSON = json_decode(file_get_contents('php://input'), true);

$id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : (int)($inputJSON['id_producto'] ?? 0);
$color       = isset($_POST['color']) ? trim($_POST['color']) : trim($inputJSON['color'] ?? '');
$talla       = isset($_POST['talla']) ? trim($_POST['talla']) : trim($inputJSON['talla'] ?? '');
$cantidad    = 1;

if ($id_producto <= 0 || empty($color) || empty($talla)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Faltan datos obligatorios (talla o color)'
    ]);
    exit;
}

$id_usuario = (int)$_SESSION['id_usuario'];

try {
    // Buscar la variante y el stock disponible (el stock reside en la tabla productos)
    $stmtVar = $pdo->prepare("
        SELECT vp.id_variante, p.stock 
        FROM variantes_producto vp
        INNER JOIN productos p ON vp.id_producto = p.id_producto
        WHERE vp.id_producto = :id_pro 
          AND TRIM(vp.talla) = :talla 
          AND TRIM(vp.color) = :color 
        LIMIT 1
    ");
    $stmtVar->execute([
        ':id_pro' => $id_producto,
        ':talla'  => $talla,
        ':color'  => $color
    ]);
    $variante = $stmtVar->fetch(PDO::FETCH_ASSOC);

    if (!$variante) {
        // Búsqueda flexible por si no coinciden mayúsculas/minúsculas
        $stmtVarFlex = $pdo->prepare("
            SELECT vp.id_variante, p.stock 
            FROM variantes_producto vp
            INNER JOIN productos p ON vp.id_producto = p.id_producto
            WHERE vp.id_producto = :id_pro 
              AND LOWER(TRIM(vp.talla)) = LOWER(:talla) 
              AND LOWER(TRIM(vp.color)) = LOWER(:color) 
            LIMIT 1
        ");
        $stmtVarFlex->execute([
            ':id_pro' => $id_producto,
            ':talla'  => $talla,
            ':color'  => $color
        ]);
        $variante = $stmtVarFlex->fetch(PDO::FETCH_ASSOC);
    }

    if (!$variante) {
        echo json_encode([
            'status' => 'error',
            'message' => 'La combinación de talla y color seleccionada no está disponible'
        ]);
        exit;
    }

    $stockDisponible = (int)$variante['stock'];
    if ($stockDisponible < $cantidad) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No hay stock disponible para este producto'
        ]);
        exit;
    }

    $id_variante = (int)$variante['id_variante'];

    
    $stmtCart = $pdo->prepare("SELECT id_carrito FROM carritos WHERE id_usuario = :id_usuario LIMIT 1");
    $stmtCart->execute([':id_usuario' => $id_usuario]);
    $carrito = $stmtCart->fetch(PDO::FETCH_ASSOC);

    if ($carrito) {
        $id_carrito = (int)$carrito['id_carrito'];
    } else {
        $stmtNewCart = $pdo->prepare("INSERT INTO carritos (id_usuario) VALUES (:id_usuario)");
        $stmtNewCart->execute([':id_usuario' => $id_usuario]);
        $id_carrito = (int)$pdo->lastInsertId();
    }

    
    $stmtItem = $pdo->prepare("
        INSERT INTO items_carrito (id_carrito, id_variante, cantidad)
        VALUES (:id_carrito, :id_variante, :cantidad)
        ON DUPLICATE KEY UPDATE cantidad = cantidad + :cantidad_upd
    ");
    $stmtItem->execute([
        ':id_carrito'     => $id_carrito,
        ':id_variante'    => $id_variante,
        ':cantidad'       => $cantidad,
        ':cantidad_upd'   => $cantidad
    ]);

    
    $stmtCount = $pdo->prepare("SELECT SUM(cantidad) AS total FROM items_carrito WHERE id_carrito = :id_carrito");
    $stmtCount->execute([':id_carrito' => $id_carrito]);
    $totalItems = (int)($stmtCount->fetchColumn() ?: 0);

    echo json_encode([
        'status' => 'success',
        'message' => 'Producto añadido al carrito',
        'total_items' => $totalItems
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}