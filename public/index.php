<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <!--importante -->
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <!--fin -->
    <link rel="stylesheet" href="../assets/css/inicio.css">
</head>
<body>

    




<?php include __DIR__ . '/../includes/navbar.php'; ?>
<main>
  <section class="hero">
    <div class="hero-content">
      <span class="badge">NUEVA TEMPORADA 2026</span>

    <h1 class="hero-title">
        Colección<br>
        <em>Otoño &amp; Invierno</em>
      </h1>

    <p class="hero-description">
        Descubre elegancia atemporal y minimalismo refinado. Diseños sostenibles creados para destacar en cualquier ocasión.
      </p>

    <div class="hero-buttons">
        <a href="#" class="btn btn-primary">Explorar Colección &rarr;</a>
        <a href="#" class="btn btn-outline">Ver Ofertas</a>
      </div>
    </div>
  </section>
</main>


<section class="features-bar">
  <div class="features-container">
    <div class="feature-item">
      <div class="feature-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="1" y="3" width="15" height="13"></rect>
          <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
          <circle cx="5.5" cy="18.5" r="2.5"></circle>
          <circle cx="18.5" cy="18.5" r="2.5"></circle>
        </svg>
      </div>
      <div class="feature-text">
        <h4>Envío Gratuito</h4>
        <p>En compras mayores a bs 75</p>
      </div>
    </div>



    <div class="feature-item">
      <div class="feature-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        </svg>
      </div>
      <div class="feature-text">
        <h4>Pago 100% Seguro</h4>
        <p>Múltiples métodos de pago</p>
      </div>
    </div>


    <div class="feature-item">
      <div class="feature-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
          <path d="M3 3v5h5"></path>
        </svg>
      </div>


      <div class="feature-text">
        <h4>Devoluciones Fáciles</h4>
        <p>Hasta 30 días garantizados</p>
      </div>
    </div>


    <div class="feature-item">
      <div class="feature-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
          <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
        </svg>
      </div>
      <div class="feature-text">
        <h4>Soporte 24/7</h4>
        <p>Atención rápida y personalizada</p>
      </div>
    </div>
  </div>
</section>

<!-- Categorías Destacadas -->

<section class="categories-section">
  <div class="categories-header">
    <div class="categories-title-box">
      <h2>Categorías Destacadas</h2>
      <p>Explora nuestras selecciones diseñadas para cada estilo.</p>
    </div>
    <a href="#" class="view-all-link">Ver todo el catálogo &rarr;</a>
  </div>

  <div class="categories-grid">


    <!-- Tarjeta 1: Mujer -->
    <a href="#" class="category-card">
      <img src="../assets/img/mujer.jpg" alt="Colección Mujer">
      <div class="category-info">
        <span class="category-tag">COLECCIÓN</span>
        <h3 class="category-name">Mujer</h3>
        <span class="category-cta">COMPRAR AHORA &gt;</span>
      </div>
    </a>

    <!-- Tarjeta 2: Hombre -->
    <a href="#" class="category-card">
      <img src="../assets/img/hombre.jpg" alt="Colección Hombre">
      <div class="category-info">
        <span class="category-tag">COLECCIÓN</span>
        <h3 class="category-name">Hombre</h3>
        <span class="category-cta">COMPRAR AHORA &gt;</span>
      </div>
    </a>


    <!--tarjeta 3 accesorios -->
    <a href="#" class="category-card">
      <img src="../assets/img/accesorios.jpg" alt="Accesorios">
      <div class="category-info">
        <span class="category-tag">ESENCIALES</span>
        <h3 class="category-name">Accesorios</h3>
        <span class="category-cta">COMPRAR AHORA &gt;</span>
      </div>
    </a>


    <!--accesorios -->
    <a href="#" class="category-card">
      <img src="../assets/img/zapatos.jpg" alt="Urban & Luxury">
      <div class="category-info">
        <span class="category-tag">CALZADO</span>
        <h3 class="category-name">Urban &amp; Luxury</h3>
        <span class="category-cta">COMPRAR AHORA &gt;</span>
      </div>
    </a>
  </div>
</section>



<!-- Encabezado y Filtros de Productos Destacados -->
<section class="featured-products-header">
  <div class="featured-text">
    <h2>Productos Destacados</h2>
    <p>Descubre las prendas más codiciadas de esta temporada.</p>
  </div>

  <div class="filter-buttons">
    <button type="button" class="filter-btn active">Todos</button>
    <button type="button" class="filter-btn">Mujer</button>
    <button type="button" class="filter-btn">Hombre</button>
    <button type="button" class="filter-btn">Novedades</button>
  </div>
</section>



<!-- Banner Promocional de Descuento -->
<section class="promo-banner-section">
  <div class="promo-card">


    <div class="promo-content">
      <span class="promo-badge">DESCUENTO DE BIENVENIDA</span>

      <h2 class="promo-title">
        Obtén un <span>20% OFF</span> en tu primera compra!
      </h2>

      <p class="promo-desc">
        Aprovecha nuestra promoción por tiempo limitado en prendas seleccionadas. Utiliza el código al finalizar tu pago.
      </p>



      <div class="coupon-box">
        <span class="coupon-label">CÓDIGO:</span>
        <strong class="coupon-code">BIENVENIDO20</strong>
        <button type="button" class="copy-btn">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
          </svg>
          Copiar
        </button>
      </div>
    </div>

   
    
    <div class="promo-action">
      <a href="#" class="btn-promo">Aprovechar Descuento</a>
    </div>
  </div>
</section>





<!-- footer -->
<!-- Sección Newsletter: Únete al Club BaggyCulture -->
<section class="newsletter-section">
  <div class="newsletter-container">
    <div class="newsletter-icon">
      <!-- Icono de sobre -->
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#d89f70" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
        <polyline points="22,6 12,13 2,6"></polyline>
      </svg>
    </div>

    <h2 class="newsletter-title">Únete al Club BaggyCulture</h2>
    <p class="newsletter-subtitle">
      Suscríbete a nuestra newsletter para recibir lanzamientos exclusivos, ofertas secretas y un 10% adicional de descuento.
    </p>

    <form class="newsletter-form" onsubmit="event.preventDefault();">
      <input type="email" placeholder="Ingresa tu correo electrónico" class="newsletter-input" />
      <button type="submit" class="newsletter-btn">Suscribirse</button>
    </form>

    <span class="newsletter-disclaimer">Sin spam. Puedes desuscribirte en cualquier momento.</span>
  </div>
</section>


<footer class="footer">
  <div class="footer-container">
    
    <div class="footer-brand">
      <h3 class="footer-logo">BAGGYCULTURE<span>.</span></h3>
      <p class="footer-description">
        Redefiniendo el guardarropa moderno con piezas minimalistas, duraderas y de producción responsable. Elegancia para el día a día.
      </p>
      <div class="footer-socials">
        <!-- Instagram -->
        <a href="#" class="social-btn" aria-label="Instagram">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
          </svg>
        </a>
        <!-- Facebook -->
        <a href="#" class="social-btn" aria-label="Facebook">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
          </svg>
        </a>
        <!-- Pinterest -->
        <a href="#" class="social-btn" aria-label="Pinterest">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="8" x2="12" y2="16"></line>
            <circle cx="12" cy="12" r="10"></circle>
          </svg>
        </a>
        <!-- TikTok -->
        <a href="#" class="social-btn" aria-label="TikTok">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path>
          </svg>
        </a>
      </div>
    </div>

    <!-- Columna 2: Tienda -->
    <div class="footer-links-group">
      <h4>TIENDA</h4>
      <ul>
        <li><a href="#">Mujer</a></li>
        <li><a href="#">Hombre</a></li>
        <li><a href="#">Accesorios</a></li>
        <li><a href="#">Novedades</a></li>
        <li><a href="#">Rebajas</a></li>
      </ul>
    </div>

    <!-- Columna 3: Soporte -->
    <div class="footer-links-group">
      <h4>SOPORTE</h4>
      <ul>
        <li><a href="#">Estado del pedido</a></li>
        <li><a href="#">Guía de Tallas</a></li>
        <li><a href="#">Política de Envíos</a></li>
        <li><a href="#">Devoluciones</a></li>
        <li><a href="#">Contacto</a></li>
      </ul>
    </div>

    <!-- Columna 4: Nosotros -->
    <div class="footer-links-group">
      <h4>NOSOTROS</h4>
      <ul>
        <li><a href="#">Sobre Baggyculture</a></li>
        <li><a href="#">Sostenibilidad</a></li>
        <li><a href="#">Prensa</a></li>
        <li><a href="#">Carreras</a></li>
        <li><a href="#">Términos y Privacidad</a></li>
      </ul>
    </div>
  </div>
</footer>
</body>
</html>
