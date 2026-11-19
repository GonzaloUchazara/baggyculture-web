<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = (int)$_SESSION['id_usuario'];
$id_pedido  = isset($_GET['id_pedido']) ? (int)$_GET['id_pedido'] : 0;

if ($id_pedido <= 0) {
    header('Location: catalogo.php');
    exit;
}

// 1. Obtener la orden principal
$stmtOrder = $pdo->prepare("
    SELECT * 
    FROM pedidos 
    WHERE id_pedido = :id_pedido AND id_usuario = :id_usuario
    LIMIT 1
");
$stmtOrder->execute([
    ':id_pedido'  => $id_pedido,
    ':id_usuario' => $id_usuario
]);
$pedido = $stmtOrder->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    die("No se encontró el pedido o no tienes permisos para visualizarlo.");
}

// 2. Obtener los artículos comprados desde detalle_pedidos
$stmtItems = $pdo->prepare("
    SELECT 
        dp.cantidad,
        dp.precio_unitario,
        p.nombre,
        p.foto_pro,
        vp.talla,
        vp.color,
        vp.foto1_pro
    FROM detalle_pedidos dp
    INNER JOIN variantes_producto vp ON dp.id_variante = vp.id_variante
    INNER JOIN productos p ON vp.id_producto = p.id_producto
    WHERE dp.id_pedido = :id_pedido
");
$stmtItems->execute([':id_pedido' => $id_pedido]);
$detalles = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado #<?= $pedido['id_pedido'] ?></title>
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/pedido_completado.css">
</head>
<body>

<?php include __DIR__ . '/../includes/navbar.php'; ?>

<main class="success-container">
    <div class="success-card">
        <div class="check-icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>

        <h1>¡Gracias por tu compra!</h1>
        <p class="order-code">Número de Pedido: <strong>#<?= str_pad($pedido['id_pedido'], 5, '0', STR_PAD_LEFT) ?></strong></p>
        <span class="badge-status">Estado: <?= strtoupper(htmlspecialchars($pedido['estado'])) ?></span>

        <!-- Sección de Pago QR si aplica -->
        <?php if ($pedido['metodo_pago'] === 'QR / Transferencia'): ?>
            <div class="qr-payment-box">
                <h3>Paga con QR Simple</h3>
                <p>Escanea este código desde la app de tu banco para completar tu pago de <strong>Bs <?= number_format($pedido['total'], 2) ?></strong></p>
                <div class="qr-placeholder">
                    <!-- Puedes colocar aquí tu imagen QR fija o generada -->
                    <img src="../assets/img/qr_pago.png" alt="Código QR de Pago" onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=Pedido_<?= $pedido['id_pedido'] ?>_Monto_<?= $pedido['total'] ?>'">
                </div>
                <small>Indica tu número de orden (#<?= $pedido['id_pedido'] ?>) en la glosa de la transferencia.</small>
            </div>
        <?php endif; ?>

        <!-- Datos del pedido -->
        <div class="order-info-grid">
            <div class="info-block">
                <h4>Dirección de Entrega</h4>
                <p><?= nl2br(htmlspecialchars($pedido['direccion_envio'])) ?></p>
            </div>
            <div class="info-block">
                <h4>Método de Pago</h4>
                <p><?= htmlspecialchars($pedido['metodo_pago']) ?></p>
            </div>
        </div>

        <!-- Desglose de prendas compradas -->
        <div class="order-breakdown">
            <h4>Resumen de Artículos</h4>
            <div class="breakdown-list">
                <?php foreach ($detalles as $det): ?>
                    <?php 
                        $foto = !empty($det['foto1_pro']) ? $det['foto1_pro'] : $det['foto_pro'];
                        $ruta_img = !empty($foto) ? '../public/uploads/productos/' . $foto : '../public/uploads/default.jpg';
                    ?>
                    <div class="breakdown-item">
                        <img src="<?= htmlspecialchars($ruta_img) ?>" alt="<?= htmlspecialchars($det['nombre']) ?>">
                        <div class="breakdown-details">
                            <strong><?= htmlspecialchars($det['nombre']) ?></strong>
                            <span>Talla: <?= htmlspecialchars($det['talla']) ?> | Color: <?= htmlspecialchars($det['color']) ?></span>
                            <span>Cantidad: <?= (int)$det['cantidad'] ?> x Bs <?= number_format($det['precio_unitario'], 2) ?></span>
                        </div>
                        <div class="breakdown-total">
                            Bs <?= number_format($det['precio_unitario'] * $det['cantidad'], 2) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="grand-total-row">
                <span>Total Abonado / Por Cobrar:</span>
                <strong>Bs <?= number_format($pedido['total'], 2) ?></strong>
            </div>
        </div>

        <div class="action-buttons">
            <a href="catalogo.php" class="btn-back-home">Volver a la Tienda</a>
        </div>
    </div>
</main>

</body>
</html>