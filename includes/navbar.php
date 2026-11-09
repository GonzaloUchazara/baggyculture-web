<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<link rel="stylesheet" href="../assets/css/navbar.css">
<link rel="stylesheet" href="../assets/css/login.css">


<header class="navbar-container">
    <nav class="navbar">

        <div class="nav-logo">
            <a href="../public/index.php">BAGGYCULTURE <span>.</span></a>
        </div>

        <ul class="nav-links">
            <li><a href="../public/index.php" class="<?php echo ($current_page === 'index.php') ? 'active' : ''; ?>">Inicio</a></li>
            <li><a href="../views/catalogo.php" class="<?php echo ($current_page === 'catalogo.php') ? 'active' : ''; ?>">Catálogo</a></li>
            <li><a href="../views/subastas.php">Subastas</a></li>
            <li><a href="#">Asesores</a></li>
            <li><a href="#">Probador</a></li>
        </ul>

        <div class="nav-actions">
            <div class="search-box">
                <span class="search-icon">&#128269;</span>
                <input type="text" placeholder="Buscar productos..." />
            </div>

            <!-- perfil -->
            <button type="button" class="icon-btn" id="btnOpenLogin" aria-label="Iniciar Sesión" title="Iniciar Sesión">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </button>

            <!-- carrito -->
            <button type="button" class="icon-btn" aria-label="Carrito">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
            </button>
        </div>
    </nav>



    <!-- login oculto que aparece -->
     <!-- Modal Flotante (oculto por defecto) -->
    <div id="modalLogin" class="modal-overlay hidden">
        <div class="modal-wrapper">
            <button type="button" class="modal-close" id="btnCloseModal" aria-label="Cerrar">&times;</button>
            
            <div class="card-flip-container" id="cardFlip">

                <!-- CARA 1: INICIAR SESIÓN (Frente) -->
                <div class="card-face card-front" id="cardFront">
                    <div class="card_lo">
                        <div class="card2">
                            <form action="../controllers/controlador.php?accion=login" method="POST"class="form" id="formLogin" >
                                <p class="form-heading">Iniciar Sesión</p>

                                <div class="field">
                                    <svg viewBox="0 0 16 16" fill="currentColor" height="16" width="16" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                        <path d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z"></path>
                                    </svg>
                                    <input type="text" name="usuario" class="input-field" placeholder="Username o Email" required autocomplete="username" id="correo" />
                                </div>

                                <div class="field">
                                    <svg viewBox="0 0 16 16" fill="currentColor" height="16" width="16" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
                                    </svg>
                                    <input type="password" name="password" id="password" class="input-field" placeholder="Password" required autocomplete="current-password" />
                                </div>

                                <div id="loginFeedback" style="display: none; font-size: 0.85em; text-align: center; margin-top: 5px;"></div>

                                <div class="btn">
                                    <button type="submit" class="button1">Entrar</button>
                                    <button type="button" class="button2" id="btnToRegister">Registrarse</button>
                                </div>
                                <button type="button" class="button3">¿Olvidaste tu contraseña?</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- CARA 2: REGISTRO (Atrás) -->
                <div class="card-face card-back" id="cardBack">
                    <div class="card_lo">
                        <div class="card2">
                            <form class="form" id="formRegister">
                                <p class="form-heading">Crear Cuenta</p>

                                <!-- Nombre -->
                                <div class="field">
                                    <svg viewBox="0 0 16 16" fill="currentColor" height="16" width="16" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 8 10c-.289 0-1.516.68-2.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                    </svg>
                                    <input type="text" name="nombre" class="input-field" placeholder="Nombre" required autocomplete="given-name" />
                                </div>

                                <!-- Apellido -->
                                <div class="field">
                                    <svg viewBox="0 0 16 16" fill="currentColor" height="16" width="16" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 8 10c-.289 0-1.516.68-2.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                    </svg>
                                    <input type="text" name="apellido" class="input-field" placeholder="Apellido" required autocomplete="family-name" />
                                </div>

                                <!-- Correo -->
                                <div class="field">
                                    <svg viewBox="0 0 16 16" fill="currentColor" height="16" width="16" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                    </svg>
                                    <input type="email" name="correo" class="input-field" placeholder="Correo electrónico" required autocomplete="email" />
                                </div>

                                <!-- Contraseña -->
                                <div class="field">
                                    <svg viewBox="0 0 16 16" fill="currentColor" height="16" width="16" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
                                    </svg>
                                    <input type="password" name="password" class="input-field" placeholder="Contraseña" required autocomplete="new-password" />
                                </div>

                                <!-- Comprobar Contraseña -->
                                <div class="field">
                                    <svg viewBox="0 0 16 16" fill="currentColor" height="16" width="16" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
                                    </svg>
                                    <input type="password" name="password_confirm" class="input-field" placeholder="Comprobar contraseña" required autocomplete="new-password" />
                                </div>

                                <!-- Teléfono (Opcional) -->
                                <div class="field">
                                    <svg viewBox="0 0 16 16" fill="currentColor" height="16" width="16" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                        <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                    </svg>
                                    <input type="tel" name="telefono" class="input-field" placeholder="Teléfono (Opcional)" autocomplete="tel" />
                                </div>

                                <div id="registerFeedback" style="display: none; font-size: 0.85em; text-align: center; margin-top: 8px;"></div>

                                <div class="btn">
                                    <button type="submit" class="button1">Registrarme</button>
                                    <button type="button" class="button2" id="btnToLogin">Volver al Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script src="../assets/js/login.js"></script>
</header>


