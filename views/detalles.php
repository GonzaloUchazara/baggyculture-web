<?php 
require_once '../config/conexion.php';
$id_producto = isset($_GET['id_producto']) ? (int)$_GET['id_producto'] : 0;

$stmt = $pdo->prepare("SELECT * From productos WHERE id_producto = :id AND estado = 'activo'");

$stmt->execute(['id' => $id_producto]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$producto){
    die("no se encontro tal producto");
}

$stmtVar = $pdo->prepare("SELECT * FROM variantes_producto WHERE id_producto = :id");
$stmtVar->execute(['id' => $id_producto]);
$variantes = $stmtVar->fetchAll(PDO::FETCH_ASSOC);


$tallas = array_filter(array_unique(array_column($variantes,'talla')));

$ruta_disco = dirname(__DIR__) . '/public/uploads/productos/';
$fotos_galeria = [];
$colores = [];

if (!empty($producto['foto_pro']) && file_exists($ruta_disco . $producto['foto_pro'])) {
    $fotos_galeria[] = $producto['foto_pro'];
}

foreach ($variantes as $v) {
    $color_nombre = trim($v['color']);

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

if (empty($fotos_galeria) && !empty($producto['foto_pro'])) {
    $fotos_galeria[] = $producto['foto_pro'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($producto['nombre']) ?></title>
    <link rel="stylesheet" href="../assets/css/detalle.css">
</head>
<body>
    <main class="product-detail-container">

        <section class="gallery-col">
            <div class="main-image-wrap">
                <img id="mainImage" src="../public/uploads/productos/<?= htmlspecialchars($producto['foto_pro']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">

            </div>

            <div class="thumbnails-wrap">
                <?php foreach ($fotos_galeria as $index => $foto): ?>

                    <img 
                    src="../public/uploads/productos/<?= htmlspecialchars($foto) ?>" 
                    class="thumb <?= $index === 0 ? 'active' : '' ?>" 
                    onclick="cambiarFoto(this.src)"
                    alt="Vista <?= $index + 1 ?>">
                            
                <?php endforeach; ?>
            </div>
        </section>

        <section class="info-col">
            <span class="category-tag"><?= strtoupper(htmlspecialchars($producto['categoria'])) ?></span>
            <h1 class="product-title"><?= htmlspecialchars($producto['nombre']) ?></h1>
            <p class="product-desc"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
            
            <div class="product-price">
                Bs. <?= number_format($producto['precio'], 2) ?>
            </div>

            <form action="agregar_carrito.php" method="POST" class="purchase-form">
                <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">

                <!--colores -->
    <?php if (!empty($colores)): ?>
        <div class="selector-group">
          <label>Color:</label>
          <div class="options-pills">
            <?php foreach ($colores as $index => $color): ?>
              <label class="pill-label">
                <input type="radio" name="color" value="<?= htmlspecialchars($color) ?>" <?= $index === 0 ? 'checked' : '' ?>>
                <span class="pill-btn"><?= htmlspecialchars($color) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <!--talla -->

      <?php if (!empty($tallas)): ?>
        <div class="selector-group">
          <label>Talla:</label>
          <div class="options-pills">
            <?php foreach ($tallas as $index => $talla): ?>
              <label class="pill-label">
                <input type="radio" name="talla" value="<?= htmlspecialchars($talla) ?>" <?= $index === 0 ? 'checked' : '' ?>>
                <span class="pill-btn pill-square"><?= htmlspecialchars($talla) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

    <button type="submit" class="btn-add-cart">Añadir al carrito</button>
        </form>
    </section>

      
    </main>

    <script>
function cambiarFoto(nuevaRuta) {
  document.getElementById('mainImage').src = nuevaRuta;
  document.querySelectorAll('.thumb').forEach(el => el.classList.remove('active'));
  if (window.event && window.event.target) {
    window.event.target.classList.add('active');
  }
}
</script>
</body>
</html>
