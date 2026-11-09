<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/subastas.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>

<?php include __DIR__ . '/../includes/navbar.php'; ?>
    <header class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-content">

    <div class="hero-badge">
        <span class="badge-dot"></span>
        <span>BAGGY CULTURE • PIEZAS ÚNICAS & PROTOTIPOS</span>
      </div>

      <h1 class="hero-title">Subastas en Tiempo Real</h1>

      <p class="hero-description">
        Consigue prendas exclusivas de edición limitada, muestras de pasarela y colaboraciones únicas. Puja directo en bolivianos (Bs).
      </p>

      <div class="hero-features">
        <div class="feature-item">
          <i class="fa-solid fa-gavel"></i>
          <span>Pujas Transparentes</span>
        </div>
        <div class="feature-item">
          <i class="fa-solid fa-shield-halved"></i>
          <span>Autenticidad Garantizada</span>
        </div>
        <div class="feature-item">
          <i class="fa-solid fa-box"></i>
          <span>Envío Asegurado</span>
        </div>
      </div>

    </div>
  </header>

  <section class="filter-bar-section">
    <div class="filter-bar-container">
        
    <div class="filter-tabs">
        <button class="tab-btn active">Activas Ahora (4)</button>
        <button class="tab-btn">Próximas</button>
        <button class="tab-btn">Finalizadas</button>
      </div>

      <div class="filter-info">
        Incremento mínimo de puja: <strong>20 Bs</strong>
      </div>

    </div>
  </section>




  <section class="how-it-works-section">
    <div class="how-it-works-container">
      
      <h2 class="section-title">¿Cómo funcionan las subastas en BaggyCulture?</h2>

      <div class="steps-grid">
        
        <!-- Paso 1 -->
        <article class="step-card">
          <div class="step-badge">1</div>
          <h3 class="step-title">Haz tu Oferta en Bolivianos</h3>
          <p class="step-description">
            Ingresa tu puja superando la oferta actual según el incremento mínimo establecido.
          </p>
        </article>

        <!-- Paso 2 -->
        <article class="step-card">
          <div class="step-badge">2</div>
          <h3 class="step-title">Monitorea en Tiempo Real</h3>
          <p class="step-description">
            Recibirás alertas instantáneas si otro participante supera tu postura antes de que cierre el reloj.
          </p>
        </article>

        <!-- Paso 3 -->
        <article class="step-card">
          <div class="step-badge">3</div>
          <h3 class="step-title">Gana & Recibe en Casa</h3>
          <p class="step-description">
            Si eres el mejor postor al finalizar el tiempo, la prenda es tuya con envío exprés incluido.
          </p>
        </article>

      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-container">
      
      
      <div class="footer-left">
        <div class="footer-logo">
          BAGGY<span class="logo-dot">.</span>
        </div>
        <p class="footer-copy">
          © 2026 Baggy Studio Inc. Todos los derechos reservados.
        </p>
      </div>

      <!-- Lado derecho: Redes sociales -->
      <div class="footer-socials">
        <a href="#" aria-label="Instagram" class="social-link">
          <i class="fa-brands fa-instagram"></i>
        </a>
        <a href="#" aria-label="Facebook" class="social-link">
          <i class="fa-brands fa-facebook-f"></i>
        </a>
      </div>

    </div>
  </footer>

  
</body>
</html>