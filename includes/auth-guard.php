<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Verificar si hay sesión activa
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['rol'])) {
    $isInsideAdmin = (strpos($_SERVER['PHP_SELF'] ?? '', '/admin/') !== false);
    $redirect = $isInsideAdmin ? '../../public/index.php?error=sesion_requerida' : '../public/index.php?error=sesion_requerida';
    header('Location: ' . $redirect);
    echo "<script>window.location.href='" . $redirect . "';</script>";
    exit;
}

// 2. Si el rol en sesión es inactivo, destruir sesión y bloquear acceso
if ($_SESSION['rol'] === 'inactivo') {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    
    // Iniciar nueva sesión solo para pasar el mensaje de error
    session_start();
    $_SESSION['login_error'] = "Tu cuenta ha sido desactivada. No tienes acceso a ningún panel del sistema.";

    $isInsideAdmin = (strpos($_SERVER['PHP_SELF'] ?? '', '/admin/') !== false);
    $redirect = $isInsideAdmin ? '../../public/index.php?error=cuenta_inactiva' : '../public/index.php?error=cuenta_inactiva';
    header('Location: ' . $redirect);
    echo "<script>window.location.href='" . $redirect . "';</script>";
    exit;
}

// 3. Verificación en tiempo real contra la base de datos (por si el administrador desactivó la cuenta recientemente)
try {
    if (!isset($pdo)) {
        $configPath = __DIR__ . '/../config/conexion.php';
        if (file_exists($configPath)) {
            require_once $configPath;
        }
    }

    if (isset($pdo)) {
        $stmtGuard = $pdo->prepare("SELECT rol FROM usuarios WHERE id_usuario = ? LIMIT 1");
        $stmtGuard->execute([$_SESSION['id_usuario']]);
        $rolActual = $stmtGuard->fetchColumn();

        // Si el usuario ya no existe o su rol pasó a 'inactivo'
        if (!$rolActual || $rolActual === 'inactivo') {
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();

            session_start();
            $_SESSION['login_error'] = "Tu cuenta ha sido desactivada. No tienes acceso a ningún panel del sistema.";

            $isInsideAdmin = (strpos($_SERVER['PHP_SELF'] ?? '', '/admin/') !== false);
            $redirect = $isInsideAdmin ? '../../public/index.php?error=cuenta_inactiva' : '../public/index.php?error=cuenta_inactiva';
            header('Location: ' . $redirect);
            echo "<script>window.location.href='" . $redirect . "';</script>";
            exit;
        }

        // Mantener sincronizado el rol
        $_SESSION['rol'] = $rolActual;
    }
} catch (Exception $e) {
    // Si falla la verificación en BD, continúa con la verificación de rol en sesión
}

// 4. Si es cliente común, no permitir entrar a paneles de administración
$isInsideAdmin = (strpos($_SERVER['PHP_SELF'] ?? '', '/admin/') !== false);
if ($isInsideAdmin && $_SESSION['rol'] === 'cliente') {
    header('Location: ../catalogo.php');
    echo "<script>window.location.href='../catalogo.php';</script>";
    exit;
}
