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

// Obtener los productos actuales del carrito
$sql = "
    SELECT 
        ic.id_item,
        ic.cantidad,
        p.id_producto,
        p.nombre,
        p.precio,
        p.foto_pro,
        vp.id_variante,
        vp.talla,
        vp.color,
        vp.foto1_pro
    FROM carritos c
    INNER JOIN items_carrito ic ON c.id_carrito = ic.id_carrito
    INNER JOIN variantes_producto vp ON ic.id_variante = vp.id_variante
    INNER JOIN productos p ON vp.id_producto = p.id_producto
    WHERE c.id_usuario = :id_usuario
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id_usuario' => $id_usuario]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($items)) {
    header('Location: carrito.php');
    exit;
}

$total_orden = 0;
foreach ($items as $item) {
    $total_orden += $item['precio'] * $item['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - BaggyCulture</title>
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/checkout.css">
</head>
<body>

<?php include __DIR__ . '/../includes/navbar.php'; ?>

<main class="checkout-container">
    <h1 class="checkout-title">Finalizar Pedido</h1>

    <div class="checkout-layout">
        
        <section class="checkout-form-section">
            <form action="procesar_pedido.php" method="POST" id="checkout-form">
                <h2>1. Datos de Entrega</h2>
                <div class="form-group">
                    <label for="direccion_envio">Dirección completa de entrega:</label>
                    <textarea 
                        name="direccion_envio" 
                        id="direccion_envio" 
                        rows="3" 
                        required 
                        placeholder="Zona, Avenida/Calle, Número de puerta, Referencias o indicaciones adicionales"
                    ></textarea>
                </div>

                <h2>2. Método de Pago</h2>
                <div class="payment-methods">
                    <label class="payment-option">
                        <input type="radio" name="metodo_pago" value="QR / Transferencia" checked>
                        <div class="option-content">
                            <strong>QR Simple / Transferencia Bancaria</strong>
                            <span>Te mostraremos el código QR tras confirmar tu pedido</span>
                        </div>
                    </label>

                    <label class="payment-option">
                        <input type="radio" name="metodo_pago" value="Efectivo contra entrega">
                        <div class="option-content">
                            <strong>Pago en efectivo contra entrega</strong>
                            <span>Pagas al momento de recibir tus prendas</span>
                        </div>
                    </label>
                </div>

                <button type="submit" class="btn-confirm-order" id="btn-submit-order">
                    Confirmar Pedido (Bs <?= number_format($total_orden, 2) ?>)
                </button>
            </form>
        </section>

        
        <aside class="order-summary-sidebar">
            <h2>Tu Pedido (<?= count($items) ?> artículos)</h2>
            <div class="summary-items-list">
                <?php foreach ($items as $item): ?>
                    <?php 
                        $foto = !empty($item['foto1_pro']) ? $item['foto1_pro'] : $item['foto_pro'];
                        $ruta_img = !empty($foto) ? '../public/uploads/productos/' . $foto : '../public/uploads/default.jpg';
                        $subtotal_item = $item['precio'] * $item['cantidad'];
                    ?>
                    <div class="summary-item">
                        <img src="<?= htmlspecialchars($ruta_img) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>">
                        <div class="summary-item-info">
                            <h4><?= htmlspecialchars($item['nombre']) ?></h4>
                            <p class="meta"><?= htmlspecialchars($item['color']) ?> | Talla <?= htmlspecialchars($item['talla']) ?></p>
                            <span class="qty">Cant: <?= (int)$item['cantidad'] ?></span>
                        </div>
                        <div class="summary-item-price">
                            Bs <?= number_format($subtotal_item, 2) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="summary-totals-box">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>Bs <?= number_format($total_orden, 2) ?></span>
                </div>
                <div class="total-row">
                    <span>Envío</span>
                    <span class="free-text">Por coordinar</span>
                </div>
                <div class="total-row total-highlight">
                    <span>Total a Pagar</span>
                    <strong>Bs <?= number_format($total_orden, 2) ?></strong>
                </div>
            </div>
        </aside>
    </div>
</main>

</body>
</html>