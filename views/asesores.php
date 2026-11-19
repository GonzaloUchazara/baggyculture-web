<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/conexion.php';

$sql = "SELECT id_usuario, nombre, apellido, email, telefono, foto_perfil 
        FROM usuarios 
        WHERE rol = 'asesor' 
        ORDER BY id_usuario ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$asesores = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asesores | BaggyCulture</title>
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="../assets/css/catalogo.css">
    <style>
        .asesores-container {
            max-width: 1180px;
            margin: 50px auto 80px;
            padding: 0 24px;
        }
        .asesores-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 28px;
        }
        .asesor-card {
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 16px;
            padding: 24px;
            display: flex;
            gap: 20px;
            align-items: flex-start;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .asesor-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }
        .asesor-avatar {
            width: 92px;
            height: 92px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f3f4f6;
            flex-shrink: 0;
            background-color: #f9fafb;
        }
        .asesor-info {
            flex: 1;
            min-width: 0;
        }
        .asesor-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #9a6b32;
            background: #fdf6ec;
            padding: 4px 10px;
            border-radius: 999px;
            margin-bottom: 8px;
        }
        .asesor-nombre {
            font-size: 1.2rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 6px;
            text-transform: capitalize;
        }
        .asesor-desc {
            font-size: 0.88rem;
            color: #6b7280;
            line-height: 1.5;
            margin: 0 0 14px;
        }
        .asesor-contacto {
            display: flex;
            flex-direction: column;
            gap: 8px;
            border-top: 1px solid #f3f4f6;
            padding-top: 12px;
        }
        .asesor-dato {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.88rem;
            color: #374151;
            text-decoration: none;
            word-break: break-all;
        }
        .asesor-dato:hover {
            color: #111827;
        }
        .btn-whatsapp {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 6px;
            padding: 10px 16px;
            background-color: #25D366;
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            transition: background-color 0.2s ease, transform 0.15s ease;
        }
        .btn-whatsapp:hover {
            background-color: #1ebe5d;
            transform: scale(1.02);
        }
        .empty-asesores {
            text-align: center;
            padding: 60px 20px;
            background: #f9fafb;
            border-radius: 16px;
            color: #6b7280;
        }
        @media (max-width: 480px) {
            .asesores-grid { grid-template-columns: 1fr; }
            .asesor-card { flex-direction: column; align-items: center; text-align: center; }
            .asesor-dato { justify-content: center; }
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/../includes/navbar.php'; ?>

<section class="category-hero">
    <div class="category-hero-content">
        <span class="category-hero-tag">ATENCIÓN PERSONALIZADA</span>
        <h1 class="category-hero-title">Nuestros Asesores</h1>
        <p class="category-hero-desc">
            Contáctate directamente con nuestro equipo para cotizaciones al por mayor, disponibilidad de tallas y asesoría de estilo.
        </p>
    </div>
</section>

<div class="asesores-container">
    <?php if (!empty($asesores)): ?>
        <div class="asesores-grid">
            <?php foreach ($asesores as $asesor): ?>
                <?php
                $foto = !empty($asesor['foto_perfil']) ? basename($asesor['foto_perfil']) : 'default_user.jpg';
                $ruta_foto = '../public/uploads/perfil/' . $foto;
                $nombre_completo = trim($asesor['nombre'] . ' ' . $asesor['apellido']);
                $telefono_raw = trim($asesor['telefono'] ?? '');
                $telefono_limpio = preg_replace('/[^0-9]/', '', $telefono_raw);
                if (strlen($telefono_limpio) === 8) {
                    $telefono_wa = '591' . $telefono_limpio;
                } else {
                    $telefono_wa = $telefono_limpio;
                }
                $mensaje_wa = urlencode("Hola " . $nombre_completo . ", vengo de BaggyCulture y quisiera solicitar una cotización / asesoría.");
                ?>

                <div class="asesor-card">
                    <img src="<?php echo htmlspecialchars($ruta_foto); ?>"
                         onerror="this.src='../public/uploads/perfil/default_user.jpg'"
                         alt="<?php echo htmlspecialchars($nombre_completo); ?>"
                         class="asesor-avatar">

                    <div class="asesor-info">
                        <span class="asesor-badge">Asesor de Moda & Cotizaciones</span>
                        <h3 class="asesor-nombre"><?php echo htmlspecialchars($nombre_completo); ?></h3>
                        <p class="asesor-desc">
                            Especialista en colecciones BaggyCulture, pedidos personalizados y cotizaciones rápidas para clientes.
                        </p>

                        <div class="asesor-contacto">
                            <a href="mailto:<?php echo htmlspecialchars($asesor['email']); ?>" class="asesor-dato">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                <span><?php echo htmlspecialchars($asesor['email']); ?></span>
                            </a>

                            <?php if (!empty($telefono_raw)): ?>
                                <div class="asesor-dato">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                    <span><?php echo htmlspecialchars($telefono_raw); ?></span>
                                </div>

                                <a href="https://wa.me/<?php echo htmlspecialchars($telefono_wa); ?>?text=<?php echo $mensaje_wa; ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="btn-whatsapp">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824z"/>
                                    </svg>
                                    <span>Contactar por WhatsApp</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-asesores">
            <h3>Aún no hay asesores registrados</h3>
            <p>Pronto nuestro equipo de asesores estará disponible para ayudarte con tus cotizaciones.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>