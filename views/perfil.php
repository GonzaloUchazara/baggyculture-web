<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../public/index.php');
    exit;
}

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/usuario.php';

$usuarioModel = new Usuario($pdo);
$usuario = $usuarioModel->obtenerPorId($_SESSION['id_usuario']);

if (!$usuario) {
    session_destroy();
    header('Location: ../public/index.php');
    exit;
}

$nombre_usuario   = $usuario['nombre'] ?? '';
$apellido_usuario = $usuario['apellido'] ?? '';
$email_usuario    = $usuario['email'] ?? '';
$telefono_usuario = $usuario['telefono'] ?? '';
$rol_usuario      = $usuario['rol'] ?? 'cliente';
$nombre_foto      = !empty($usuario['foto_perfil']) ? $usuario['foto_perfil'] : 'default_user.jpg';
$ruta_avatar      = "../public/uploads/perfil/" . htmlspecialchars($nombre_foto);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | Baggy Culture</title>
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/perfil.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <main class="perfil-layout">
        <aside class="perfil-sidebar">
            <div class="avatar-card">
                <form action="../controllers/controlador.php?accion=cambiar_foto" method="POST" enctype="multipart/form-data" class="avatar-section">
                    <div class="avatar-wrapper" id="avatarTrigger" title="Haz clic para cambiar tu foto">
                        <img src="<?php echo $ruta_avatar; ?>" alt="Foto de perfil" class="avatar-img-circle" id="imgPerfil">
                        <div class="avatar-overlay">
                            <svg viewBox="0 0 24 24" class="camera-icon">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                <circle cx="12" cy="13" r="4"></circle>
                            </svg>
                            <span class="overlay-text">Cambiar foto</span>
                        </div>
                        <input type="file" name="foto_perfil" id="inputFileFoto" accept="image/png, image/jpeg, image/webp" class="input-hidden" required>
                    </div>

                    <!-- botones para foto -->
                    <div class="save-photo-box" id="savePhotoBox" style="display: none;">
                        <p id="photoNameText" class="photo-info-text"></p>
                        <div class="photo-btn-group">
                            <button type="submit" class="btn-confirm-photo">Guardar</button>
                            <button type="button" class="btn-cancel-photo" id="btnCancelPhoto">Cancelar</button>
                        </div>
                    </div>
                </form>

                <h3 class="user-name"><?php echo htmlspecialchars($nombre_usuario); ?></h3>
                <span class="user-badge"><?php echo htmlspecialchars(ucfirst($rol_usuario)); ?></span>
            </div>

            <nav class="perfil-nav">
                <a href="#datos" class="nav-item active">Datos de la Cuenta</a>
                <a href="../views/subastas.php" class="nav-item">Subastas</a>
                <a href="../controllers/controlador.php?accion=logout" class="nav-item logout">Cerrar Sesión</a>
            </nav>
        </aside>

        <section class="perfil-content" id="datos">
            <h2>Configuración del Perfil</h2>
            <p class="subtitle">Gestiona tu imagen y datos personales.</p>

            <div id="contenedorDatosPersonales">
                <div class="perfil-card-form">
                    <h3 class="form-title">Información Personal</h3>

                    <form action="../controllers/controlador.php?accion=actualizar_datos" method="POST" class="form-grid" id="formPerfil">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="input-control" value="<?php echo htmlspecialchars($nombre_usuario); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" name="apellido" id="apellido" class="input-control" value="<?php echo htmlspecialchars($apellido_usuario); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="correo">Correo Electrónico (No modificable)</label>
                            <input type="email" id="correo" class="input-control readonly" value="<?php echo htmlspecialchars($email_usuario); ?>" readonly tabindex="-1">
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono / WhatsApp</label>
                            <input type="tel" name="telefono" id="telefono" class="input-control" value="<?php echo htmlspecialchars($telefono_usuario); ?>" placeholder="Opcional" disabled>
                        </div>

                        <!-- Sección de cambio de contraseña (oculta hasta que se pulse Editar) -->
                        <div id="seccionPassword" class="form-group full-width" style="display: none; margin-top: 10px;">
                            <div style="border-top: 1px dashed #e5e7eb; padding-top: 15px; margin-bottom: 12px;">
                                <span style="font-size: 0.85rem; color: #6b7280; font-weight: 600;">¿DESEAS CAMBIAR TU CONTRASEÑA? (OPCIONAL)</span>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div class="form-group">
                                    <label for="password_nueva">Nueva Contraseña</label>
                                    <input type="password" name="password_nueva" id="password_nueva" class="input-control" placeholder="Dejar en blanco para no cambiarla" minlength="6" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirm">Confirmar Nueva Contraseña</label>
                                    <input type="password" name="password_confirm" id="password_confirm" class="input-control" placeholder="Repite la nueva contraseña" minlength="6" disabled>
                                </div>
                            </div>

                            <div style="background: #f8fafc; padding: 14px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 12px;">
                                <label for="password_actual" style="color: #0f172a; font-weight: 600; font-size: 0.85rem;">
                                    Ingresa tu contraseña actual (solo requerida si vas a cambiar tu contraseña):
                                </label>
                                <input type="password" name="password_actual" id="password_actual" class="input-control" placeholder="Tu contraseña actual" style="margin-top: 6px; width: 100%;" disabled>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-primary" id="btnEditar"> Editar Datos</button>
                            <button type="button" class="btn-cancel" id="btnCancelar" style="display: none;">Cancelar</button>
                            <button type="submit" class="btn-primary" id="btnGuardar" style="display: none;"> Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <script src="../assets/js/foto_perfil.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnEditar   = document.getElementById('btnEditar');
        const btnCancelar = document.getElementById('btnCancelar');
        const btnGuardar  = document.getElementById('btnGuardar');
        const formPerfil  = document.getElementById('formPerfil');
        const secPassword = document.getElementById('seccionPassword');

        const editableInputs = [
            document.getElementById('nombre'),
            document.getElementById('apellido'),
            document.getElementById('telefono'),
            document.getElementById('password_nueva'),
            document.getElementById('password_confirm'),
            document.getElementById('password_actual')
        ].filter(Boolean);

        // Guardar valores iniciales para restaurar al cancelar
        const initialValues = {};
        editableInputs.forEach(input => {
            initialValues[input.id] = input.value;
        });

        // Al hacer clic en Editar
        btnEditar.addEventListener('click', function () {
            editableInputs.forEach(input => {
                input.disabled = false;
            });
            if (secPassword) {
                secPassword.style.display = 'block';
            }
            btnEditar.style.display   = 'none';
            btnCancelar.style.display = 'inline-block';
            btnGuardar.style.display  = 'inline-block';

            const nombreInput = document.getElementById('nombre');
            if (nombreInput) {
                nombreInput.focus();
            }
        });

        // Al hacer clic en Cancelar
        btnCancelar.addEventListener('click', function () {
            editableInputs.forEach(input => {
                input.value = initialValues[input.id] || '';
                input.disabled = true;
            });
            if (secPassword) {
                secPassword.style.display = 'none';
            }
            btnGuardar.style.display  = 'none';
            btnCancelar.style.display = 'none';
            btnEditar.style.display   = 'inline-block';
        });
    });
    </script>
</body>
</html>
