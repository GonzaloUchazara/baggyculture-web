<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: carrito.php');
    exit;
}

$id_usuario       = (int)$_SESSION['id_usuario'];
$direccion_envio  = trim($_POST['direccion_envio'] ?? '');
$metodo_pago      = trim($_POST['metodo_pago'] ?? 'QR / Transferencia');

if (empty($direccion_envio)) {
    die("Error: La dirección de entrega es obligatoria.");
}

try {
    
    $pdo->beginTransaction();

   
    $sqlCart = "
        SELECT 
            ic.id_item,
            ic.id_variante,
            ic.cantidad,
            p.id_producto,
            p.nombre,
            p.precio,
            p.stock
        FROM carritos c
        INNER JOIN items_carrito ic ON c.id_carrito = ic.id_carrito
        INNER JOIN variantes_producto vp ON ic.id_variante = vp.id_variante
        INNER JOIN productos p ON vp.id_producto = p.id_producto
        WHERE c.id_usuario = :id_usuario
        FOR UPDATE
    ";
    $stmtCart = $pdo->prepare($sqlCart);
    $stmtCart->execute([':id_usuario' => $id_usuario]);
    $items = $stmtCart->fetchAll(PDO::FETCH_ASSOC);

    if (empty($items)) {
        $pdo->rollBack();
        header('Location: carrito.php');
        exit;
    }

   
    $totalPedido = 0;
    foreach ($items as $item) {
        if ($item['stock'] < $item['cantidad']) {
            $pdo->rollBack();
            die("Lo sentimos, no hay suficiente stock disponible de la prenda: " . htmlspecialchars($item['nombre']));
        }
        $totalPedido += $item['precio'] * $item['cantidad'];
    }

    
    $sqlPedido = "
        INSERT INTO pedidos (id_usuario, total, estado, metodo_pago, direccion_envio)
        VALUES (:id_usuario, :total, 'pendiente', :metodo_pago, :direccion_envio)
    ";
    $stmtPedido = $pdo->prepare($sqlPedido);
    $stmtPedido->execute([
        ':id_usuario'      => $id_usuario,
        ':total'           => $totalPedido,
        ':metodo_pago'     => $metodo_pago,
        ':direccion_envio' => $direccion_envio
    ]);
    $id_pedido = (int)$pdo->lastInsertId();

    
    $sqlDetalle = "
        INSERT INTO detalle_pedidos (id_pedido, id_variante, cantidad, precio_unitario)
        VALUES (:id_pedido, :id_variante, :cantidad, :precio_unitario)
    ";
    $stmtDetalle = $pdo->prepare($sqlDetalle);

    $sqlStock = "UPDATE productos SET stock = stock - :cant WHERE id_producto = :id_pro";
    $stmtStock = $pdo->prepare($sqlStock);

    foreach ($items as $item) {
        
        $stmtDetalle->execute([
            ':id_pedido'       => $id_pedido,
            ':id_variante'     => $item['id_variante'],
            ':cantidad'        => $item['cantidad'],
            ':precio_unitario' => $item['precio']
        ]);

        
        $stmtStock->execute([
            ':cant'   => $item['cantidad'],
            ':id_pro' => $item['id_producto']
        ]);
    }

    
    $sqlClear = "
        DELETE ic FROM items_carrito ic
        INNER JOIN carritos c ON ic.id_carrito = c.id_carrito
        WHERE c.id_usuario = :id_usuario
    ";
    $stmtClear = $pdo->prepare($sqlClear);
    $stmtClear->execute([':id_usuario' => $id_usuario]);

    
    $pdo->commit();

   
    header("Location: pedido_completado.php?id_pedido=" . $id_pedido);
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Ocurrió un error al procesar tu compra: " . $e->getMessage());
}