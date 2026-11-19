<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/usuario.php';

class Controlador {
    private $usuarioModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->usuarioModel = new Usuario($pdo);
    }

    public function registrar() {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
            exit;
        }

        $nombre           = trim($_POST['nombre'] ?? '');
        $apellido         = trim($_POST['apellido'] ?? '');
        $email            = trim($_POST['correo'] ?? '');
        $password         = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $telefono         = trim($_POST['telefono'] ?? '');
        $telefono         = !empty($telefono) ? $telefono : null;

        if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Por favor completa todos los campos obligatorios.']);
            exit;
        }

        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'El formato del correo electrónico no es válido.']);
            exit;
        }

        
        if ($password !== $password_confirm) {
            echo json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden.']);
            exit;
        }

      
        if (strlen($password) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'La contraseña debe tener al menos 6 caracteres.']);
            exit;
        }

        
        if ($this->usuarioModel->emailExiste($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Este correo ya se encuentra registrado.']);
            exit;
        }

        
        $registrado = $this->usuarioModel->registrar($nombre, $apellido, $email, $password, $telefono, 'cliente');

        if ($registrado) {
            echo json_encode(['status' => 'success', 'message' => '¡Cuenta creada con éxito! Ya puedes iniciar sesión.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Hubo un error al registrar la cuenta. Intenta de nuevo.']);
        }
        exit;
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
            || (isset($_POST['ajax']) && $_POST['ajax'] == '1');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
                exit;
            }
            header('Location: ../public/index.php?error=campos_vacios');
            exit;
        }

        $email    = trim($_POST['correo'] ?? $_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['status' => 'error', 'message' => 'Por favor completa todos los campos.']);
                exit;
            }
            $_SESSION['login_error'] = "Por favor completa todos los campos.";
            header('Location: ../public/index.php');
            exit;
        }

        $usuario = $this->usuarioModel->obtenerPorEmail($email);

        if (!$usuario) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['status' => 'error', 'message' => 'El usuario o correo electrónico ingresado no existe.']);
                exit;
            }
            $_SESSION['login_error'] = "El usuario o correo electrónico ingresado no existe.";
            header('Location: ../public/index.php');
            exit();
        }

        $passwordHash = $usuario['password_hash'] ?? $usuario['password'] ?? '';

        $esValida = password_verify($password, $passwordHash);

        
        if (!$esValida && $password === $passwordHash) {
            $esValida = true;
            $nuevoHash = password_hash($password, PASSWORD_BCRYPT);
            $this->usuarioModel->actualizarPassword($usuario['id_usuario'], $nuevoHash);
        }

        if (!$esValida) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['status' => 'error', 'message' => 'La contraseña ingresada es incorrecta.']);
                exit;
            }
            $_SESSION['login_error'] = "La contraseña ingresada es incorrecta.";
            header('Location: ../public/index.php');
            exit();
        }

        $_SESSION['id_usuario']  = $usuario['id_usuario'] ?? $usuario['id'] ?? null;
        $_SESSION['nombre']      = $usuario['nombre'];
        $_SESSION['apellido']    = $usuario['apellido'] ?? '';
        $_SESSION['correo']      = $usuario['correo'] ?? $usuario['email'] ?? '';
        $_SESSION['email']       = $usuario['email'] ?? '';
        $_SESSION['telefono']    = $usuario['telefono'] ?? '';
        $_SESSION['rol']         = $usuario['rol'];
        $_SESSION['foto_perfil'] = !empty($usuario['foto_perfil']) ? $usuario['foto_perfil'] : 'default_user.jpg';

        $_SESSION['login_exito'] = "¡Bienvenido de nuevo, " . htmlspecialchars($usuario['nombre']) . "! Has iniciado sesión correctamente.";

        switch ($_SESSION['rol']) {
            case 'admin':
                $redirect = '../views/admin/admin.php';
                break;
            case 'asesor':
                $redirect = '../views/admin/asesor.php';
                break;
            case 'subastador':
                $redirect = '../views/admin/subastador.php';
                break;
            case 'cliente':
            default:
                $redirect = '../views/catalogo.php';
                break;
        }

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status'   => 'success',
                'message'  => '¡Bienvenido de nuevo, ' . htmlspecialchars($usuario['nombre']) . '!',
                'redirect' => $redirect
            ]);
            exit;
        }

        header('Location: ' . $redirect);
        exit();
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
        header('Location: ../public/index.php');
        exit();
    }

    
    public function cambiarFoto() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ../public/index.php');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
            $archivo = $_FILES['foto_perfil'];

            if ($archivo['error'] === UPLOAD_ERR_OK) {
                $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
                $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

                if (!in_array($extension, $permitidas)) {
                    $_SESSION['login_error'] = "Formato no válido (solo JPG, PNG o WEBP).";
                    header('Location: ../views/perfil.php');
                    exit;
                }
                if ($archivo['size'] > 2 * 1024 * 1024) {
                    $_SESSION['login_error'] = "La imagen supera el límite permitido de 2MB.";
                    header('Location: ../views/perfil.php');
                    exit;
                }

                $carpetaDestino = __DIR__ . '/../public/uploads/perfil/';
                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }

                $nuevoNombre = 'user_' . $_SESSION['id_usuario'] . '_' . time() . '.' . $extension;
                $rutaDestino = $carpetaDestino . $nuevoNombre;

                if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                   
                    $fotoPrevia = $_SESSION['foto_perfil'] ?? '';
                    if ($fotoPrevia && $fotoPrevia !== 'default_user.jpg' && file_exists($carpetaDestino . $fotoPrevia)) {
                        @unlink($carpetaDestino . $fotoPrevia);
                    }

                    
                    $actualizado = $this->usuarioModel->actualizarFotoPerfil($_SESSION['id_usuario'], $nuevoNombre);

                    if ($actualizado) {
                        $_SESSION['foto_perfil'] = $nuevoNombre;
                        $_SESSION['login_exito'] = "Foto de perfil actualizada correctamente.";
                    } else {
                        $_SESSION['login_error'] = "No se pudo actualizar en la base de datos.";
                    }
                } else {
                    $_SESSION['login_error'] = "Error al mover el archivo al servidor.";
                }
            } else {
                $_SESSION['login_error'] = "Error al subir la imagen. Código: " . $archivo['error'];
            }
        }

        header('Location: ../views/perfil.php');
        exit;
    }

    public function actualizarPerfil() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ../public/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/perfil.php');
            exit;
        }

        $id_usuario = $_SESSION['id_usuario'];
        $usuario = $this->usuarioModel->obtenerPorId($id_usuario);
        if (!$usuario) {
            $_SESSION['login_error'] = "Usuario no encontrado.";
            header('Location: ../views/perfil.php');
            exit;
        }

        $nombre           = isset($_POST['nombre']) ? trim($_POST['nombre']) : null;
        $apellido         = isset($_POST['apellido']) ? trim($_POST['apellido']) : null;
        $telefono         = isset($_POST['telefono']) ? trim($_POST['telefono']) : null;
        $password_actual  = $_POST['password_actual'] ?? '';
        $password_nueva   = $_POST['password_nueva'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        $cambios = [];

        
        if ($nombre !== null && $nombre !== '' && $nombre !== $usuario['nombre']) {
            $cambios['nombre'] = $nombre;
        }

        
        $apellidoOrig = $usuario['apellido'] ?? '';
        if ($apellido !== null && $apellido !== '' && $apellido !== $apellidoOrig) {
            $cambios['apellido'] = $apellido;
        }

       
        $telOrig = $usuario['telefono'] ?? '';
        if ($telefono !== null && $telefono !== $telOrig) {
            $cambios['telefono'] = !empty($telefono) ? $telefono : null;
        }

       
        if (!empty($password_nueva)) {
            if (empty($password_actual)) {
                $_SESSION['login_error'] = "Para cambiar tu contraseña debes ingresar tu contraseña actual.";
                header('Location: ../views/perfil.php');
                exit;
            }

            $passwordHash = $usuario['password_hash'] ?? $usuario['password'] ?? '';
            $esValida = password_verify($password_actual, $passwordHash) || ($password_actual === $passwordHash);

            if (!$esValida) {
                $_SESSION['login_error'] = "La contraseña actual no es correcta. No se aplicaron cambios.";
                header('Location: ../views/perfil.php');
                exit;
            }

            if (strlen($password_nueva) < 6) {
                $_SESSION['login_error'] = "La nueva contraseña debe tener al menos 6 caracteres.";
                header('Location: ../views/perfil.php');
                exit;
            }

            if ($password_nueva !== $password_confirm) {
                $_SESSION['login_error'] = "La confirmación de la nueva contraseña no coincide.";
                header('Location: ../views/perfil.php');
                exit;
            }

            $cambios['password_hash'] = password_hash($password_nueva, PASSWORD_BCRYPT);
        }

        // Si no hubo ningún cambio real
        if (empty($cambios)) {
            $_SESSION['login_exito'] = "No se detectaron cambios en tus datos.";
            header('Location: ../views/perfil.php');
            exit;
        }

        $actualizado = $this->usuarioModel->actualizarDatos($id_usuario, $cambios);

        if ($actualizado) {
            if (isset($cambios['nombre'])) $_SESSION['nombre'] = $cambios['nombre'];
            if (isset($cambios['apellido'])) $_SESSION['apellido'] = $cambios['apellido'];
            if (isset($cambios['telefono'])) $_SESSION['telefono'] = $cambios['telefono'];
            $_SESSION['login_exito'] = "Datos actualizados correctamente.";
        } else {
            $_SESSION['login_error'] = "Ocurrió un error al intentar guardar los datos.";
        }

        header('Location: ../views/perfil.php');
        exit;
    }
}

$accion = $_POST['accion'] ?? $_GET['accion'] ?? 'login';
$controlador = new Controlador($pdo);

if ($accion === 'registrar') {
    $controlador->registrar();
} elseif ($accion === 'cambiar_foto') {
    $controlador->cambiarFoto();
} elseif ($accion === 'actualizar_perfil' || $accion === 'actualizar_datos') {
    $controlador->actualizarPerfil();
} elseif ($accion === 'logout') {
    $controlador->logout();
} else {
    $controlador->login();
}
