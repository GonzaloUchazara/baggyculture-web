<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Subasta.php';

$id_subasta = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_GET['id_subasta']) ? (int)$_GET['id_subasta'] : 0);

$modeloSubasta = new subasta($pdo);
$subasta = $modeloSubasta->obtenerporid($id_subasta);

if (!$subasta) {
    die("No se encontró la subasta solicitada.");
}

$id_producto = (int)$subasta['id_producto'];

$stmtVar = $pdo->prepare("SELECT * FROM variantes_producto WHERE id_producto = :id");
$stmtVar->execute(['id' => $id_producto]);
$variantes = $stmtVar->fetchAll(PDO::FETCH_ASSOC);

$ruta_disco = dirname(__DIR__) . '/public/uploads/productos/';
$fotos_galeria = [];
$colores = [];

if (!empty($subasta['foto_pro']) && file_exists($ruta_disco . $subasta['foto_pro'])) {
    $fotos_galeria[] = $subasta['foto_pro'];
}

foreach ($variantes as $v) {
    $color_nombre = isset($v['color']) ? trim($v['color']) : '';

    if (!empty($color_nombre) && !in_array($color_nombre, $colores)) {
        $colores[] = $color_nombre;

        if (!empty($v['foto1_pro']) && file_exists($ruta_disco . $v['foto1_pro']) && !in_array($v['foto1_pro'], $fotos_galeria)) {
            $fotos_galeria[] = $v['foto1_pro'];
        }

        if (!empty($v['foto2_pro']) && file_exists($ruta_disco . $v['foto2_pro']) && !in_array($v['foto2_pro'], $fotos_galeria)) {
            $fotos_galeria[] = $v['foto2_pro'];
        }
    }
}

if (empty($fotos_galeria) && !empty($subasta['foto_pro'])) {
    $fotos_galeria[] = $subasta['foto_pro'];
}

$foto_portada = (!empty($subasta['foto_pro']) && file_exists($ruta_disco . $subasta['foto_pro']))
    ? '../public/uploads/productos/' . $subasta['foto_pro']
    : '../public/uploads/default.jpg';

$tiempo_fin_timestamp = strtotime($subasta['fecha_fin']);
$es_finalizada = ($subasta['estado'] === 'finalizada' || $tiempo_fin_timestamp <= time());
$minimo_siguiente = (float)$subasta['precio_actual'] + (float)$subasta['incremento_minimo'];
$usuario_autenticado = isset($_SESSION['id_usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($subasta['nombre']) ?> | Subasta BaggyCulture</title>
    <link rel="stylesheet" href="../assets/css/subastadetalle.css">
</head>
<body>
    <div style="max-width: 1100px; width: 100%; margin: 0 auto;">
        <a href="subastas.php" class="volver-link">
            ← Volver a Subastas
        </a>

        <main class="product-detail-container">
            <section class="gallery-col">
                <div class="main-image-wrap">
                    <img id="mainImage" src="<?= htmlspecialchars($foto_portada) ?>" alt="<?= htmlspecialchars($subasta['nombre']) ?>">
                </div>

                <?php if (count($fotos_galeria) > 1): ?>
                <div class="thumbnails-wrap">
                    <?php foreach ($fotos_galeria as $index => $foto): ?>
                        <img 
                            src="../public/uploads/productos/<?= htmlspecialchars($foto) ?>" 
                            class="thumb <?= $index === 0 ? 'active' : '' ?>" 
                            onclick="cambiarFoto(this.src)"
                            alt="Vista <?= $index + 1 ?>">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

            <section class="info-col">
                <div class="auction-badge <?= $es_finalizada ? 'finalizada' : '' ?>" 
                     id="reloj-subasta" 
                     data-fin="<?= $subasta['fecha_fin'] ?>"
                     data-fin-ms="<?= $tiempo_fin_timestamp * 1000 ?>">
                    <?= $es_finalizada ? 'SUBASTA FINALIZADA' : 'TIEMPO RESTANTE: Calculando...' ?>
                </div>

                <span class="category-tag">LOTE #<?= str_pad($subasta['id_subasta'], 3, '0', STR_PAD_LEFT) ?> • <?= $es_finalizada ? 'SUBASTA FINALIZADA' : 'SUBASTA EN VIVO' ?></span>
                <h1 class="product-title"><?= htmlspecialchars($subasta['nombre']) ?></h1>
                <p class="product-desc"><?= nl2br(htmlspecialchars($subasta['descripcion'] ?? 'Prenda de edición exclusiva de BaggyCulture.')) ?></p>
                
                <!-- Precio Actual -->
                <div class="auction-price-box">
                    <span class="label-puja"><?= $es_finalizada ? 'Precio Final de Venta' : 'Puja actual más alta' ?></span>
                    <div class="monto-actual">Bs. <span id="span-precio-actual"><?= number_format($subasta['precio_actual'], 2) ?></span></div>
                    <div class="ganador-lead"><?= $es_finalizada ? 'Ganador de la prenda: ' : 'Líder: ' ?><span id="span-ganador"><?= htmlspecialchars($subasta['nombre_ganador'] ?? 'Aún sin ofertas') ?></span></div>
                </div>

                <?php if ($es_finalizada): ?>
                    <!-- Subasta Finalizada -->
                    <div class="subasta-concluida-box">
                        <h3>Esta subasta ha concluido</h3>
                        <p>No se admiten más ofertas para este lote.</p>
                    </div>
                <?php elseif (!$usuario_autenticado): ?>
                    <!-- Usuario no autenticado -->
                    <div class="login-requerido-box">
                        <p>🔒 Debes iniciar sesión con tu cuenta para poder realizar una oferta en esta subasta.</p>
                        <a href="login.php" class="btn-ir-login">Iniciar Sesión para Pujar</a>
                    </div>
                <?php else: ?>
                    <!-- Formulario de Puja Habilitado -->
                    <form id="form-puja-subasta" class="purchase-form" onsubmit="enviarPuja(event)">
                        <input type="hidden" name="id_subasta" value="<?= $subasta['id_subasta'] ?>">

                        <!-- Botones de incremento rápido -->
                        <label style="font-weight: 600; font-size: 0.85rem; color: #555;">Incremento rápido sugerido:</label>
                        <div class="quick-bids-wrap">
                            <button type="button" class="btn-quick-bid" onclick="sumarIncremento(20)">+20 Bs</button>
                            <button type="button" class="btn-quick-bid" onclick="sumarIncremento(50)">+50 Bs</button>
                            <button type="button" class="btn-quick-bid" onclick="sumarIncremento(100)">+100 Bs</button>
                        </div>

                        <label for="input-monto" style="font-weight: 600; font-size: 0.85rem; color: #333;">
                            Tu oferta en Bs (Mínimo requerido: Bs. <span id="label-minimo"><?= number_format($minimo_siguiente, 2) ?></span>):
                        </label>
                        <input type="number" 
                               name="monto" 
                               id="input-monto" 
                               class="input-monto-field"
                               step="1.00" 
                               min="<?= $minimo_siguiente ?>" 
                               value="<?= $minimo_siguiente ?>" 
                               required>

                        <!-- Botón de Confirmación -->
                        <button type="submit" id="btn-enviar-puja" class="btn-submit-auction">
                            Confirmar y enviar puja
                        </button>

                        <div id="feedback-msg" class="feedback-alerta"></div>
                    </form>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <script src="../assets/js/subastadetalle.js"></script>
</body>
</html>
