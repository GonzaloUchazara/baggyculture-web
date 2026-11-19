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
$sql = "
    SELECT 
        ic.id_item,
        ic.cantidad,
        p.id_producto,
        p.nombre,
        p.precio,
        p.stock AS stock_maximo,
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
    ORDER BY ic.id_item DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_usuario' => $id_usuario]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$subtotal_general = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/carrito.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    <main class="cart-container">
    <div class="cart-header">
        <h1>Tu Carrito</h1>
        <span class="cart-total-badge"><?= count($items) ?> artículos</span>
    </div>

    <?php if (empty($items)): ?>
        <div class="cart-empty">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <h2>Tu carrito está vacío</h2>
            <p>Descubre nuestras prendas exclusivas y añade tus favoritas.</p>
            <a href="catalogo.php" class="btn-primary">Explorar Catálogo</a>
        </div>
    <?php else: ?>
        <div class="cart-layout">
            
            <section class="cart-items-list">
                <?php foreach ($items as $item): ?>
                    <?php 
                        $foto = !empty($item['foto1_pro']) ? $item['foto1_pro'] : $item['foto_pro'];
                        $ruta_img = !empty($foto) ? '../public/uploads/productos/' . $foto : '../public/uploads/default.jpg';
                        $subtotal_item = $item['precio'] * $item['cantidad'];
                        $subtotal_general += $subtotal_item;
                    ?>
                    <article class="cart-item" data-id-item="<?= $item['id_item'] ?>">
                        <div class="cart-item-image">
                            <img src="<?= htmlspecialchars($ruta_img) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>">
                        </div>

                        <div class="cart-item-details">
                            <h3><a href="detalles.php?id_producto=<?= $item['id_producto'] ?>"><?= htmlspecialchars($item['nombre']) ?></a></h3>
                            
                            <div class="item-variants">
                                <?php if (!empty($item['talla'])): ?>
                                    <span class="variant-tag">Talla: <strong><?= htmlspecialchars($item['talla']) ?></strong></span>
                                <?php endif; ?>
                                <?php if (!empty($item['color'])): ?>
                                    <span class="variant-tag">Color: <strong><?= htmlspecialchars($item['color']) ?></strong></span>
                                <?php endif; ?>
                            </div>

                            <div class="item-unit-price">
                                Precio unitario: Bs <?= number_format($item['precio'], 2) ?>
                            </div>
                        </div>

                        <div class="cart-item-actions">
                            <div class="qty-control">
                                <button type="button" class="btn-qty" data-action="decrease">-</button>
                                <span class="qty-number"><?= (int)$item['cantidad'] ?></span>
                                <button type="button" class="btn-qty" data-action="increase" data-max="<?= (int)$item['stock_maximo'] ?>">+</button>
                            </div>

                            <button type="button" class="btn-remove" title="Eliminar del carrito">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="cart-item-subtotal">
                            <span>Subtotal:</span>
                            <strong>Bs <?= number_format($subtotal_item, 2) ?></strong>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

            
            <aside class="cart-summary">
                <h2>Resumen del Pedido</h2>
                <div class="summary-line">
                    <span>Subtotal</span>
                    <span id="summary-subtotal">Bs <?= number_format($subtotal_general, 2) ?></span>
                </div>
                <div class="summary-line">
                    <span>Envío</span>
                    <span class="free-shipping">A coordinar</span>
                </div>
                <hr>
                <div class="summary-line summary-total">
                    <span>Total</span>
                    <strong id="summary-total">Bs <?= number_format($subtotal_general, 2) ?></strong>
                </div>

                <a href="checkout.php" class="btn-checkout">Continuar Compra</a>
                <a href="catalogo.php" class="btn-continue-shopping">Seguir comprando</a>
            </aside>
        </div>
    <?php endif; ?>
</main>

<script src="../assets/js/carrito_view.js"></script>
</body>
</html>