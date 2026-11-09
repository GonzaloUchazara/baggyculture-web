<?php
require_once __DIR__ . '/../config/conexion.php';

$busqueda = isset($_GET['q']) ? trim($_GET['q']) : '';
$sql = "SELECT id_producto, nombre, precio, categoria, calificacion, estado, foto_pro FROM productos WHERE estado = 'activo'";
$params = [];

if ($busqueda !== '') {
    $sql .= " AND (nombre LIKE :busqueda_nom OR categoria LIKE :busqueda_cat)";
    $params[':busqueda_nom'] = '%' . $busqueda . '%';
    $params[':busqueda_cat'] = '%' . $busqueda . '%';
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo</title>
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="../assets/css/catalogo.css">
</head>
<body>

<?php include __DIR__ . '/../includes/navbar.php'; ?>

<section class="category-hero">
  <div class="category-hero-content">
    <span class="category-hero-tag">COLECCIÓN EXCLUSIVA</span>
    <h1 class="category-hero-title">Ropa de Hombre</h1>
    <p class="category-hero-desc">
      Cortes limpios, materiales premium y estilo contemporáneo diseñados para el uso diario.
    </p>
  </div>
</section>



<div class="catalog-toolbar">
  <div class="catalog-toolbar-container">
    <p class="products-count">Mostrando </p>
    

    <div class="toolbar-search">
        <form method="GET" action="">
            <input type="text" name="q" placeholder="Buscar en catalogo..." value="<?php echo htmlspecialchars($busqueda); ?>"/>
        </form>
    </div>


    <div class="sort-box">
      <label for="sort-select">Ordenar por:</label>
      <select id="sort-select" class="sort-dropdown">
        <option value="destacados">Destacados</option>
        <option value="precio-menor">Precio: Menor a Mayor</option>
        <option value="precio-mayor">Precio: Mayor a Menor</option>
      </select>
    </div>
  </div>
</div>


    <div class="catalogo-grid"> 
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $producto): ?>
                <?php 
                $ruta_imagen = !empty($producto['foto_pro']) ? '../public/uploads/productos/' . $producto['foto_pro'] : '../public/uploads/default.jpg';
                ?>

                


                <div class="card">
                    <div class="card-media">

                        <a href="detalles.php?id_producto=<?php echo $producto['id_producto']; ?>"><img src="<?php echo htmlspecialchars($ruta_imagen); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="card-img"> </a>



                        <?php if ($producto['calificacion'] >= 4.5): ?>
                            <span class="badge-bestseller">POPULAR</span>
                        <?php endif; ?>

                        <button class="btn-wishlist" data-id="<?php echo $producto['id_producto']; ?>" aria-label="Añadir a favoritos" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="card-content">
                        <div class="card-meta">
                            <span class="category"><?php echo htmlspecialchars($producto['categoria']); ?></span>
                            <div class="rating">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                                <span><?php echo number_format($producto['calificacion'], 1); ?></span>
                            </div>
                        </div>

                        <h3 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <div class="card-price"><?php echo number_format($producto['precio'], 2); ?> Bs</div>
                        

                        

                        <button class="btn-add" data-id="<?php echo $producto['id_producto']; ?>" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h2v2c0 .55.45 1 1 1s1-.45 1-1V8h6v2c0 .55.45 1 1 1s1-.45 1-1V8h2v12z"/>
                            </svg>
                            <span>Añadir al Carrito</span>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No se encontraron productos disponibles</p>
        <?php endif; ?>
    </div>

    
</body>
</html>
