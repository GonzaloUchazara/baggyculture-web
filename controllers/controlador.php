<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/usuario.php';
require_once __DIR__ . '/../models/producto.php';

class Controlador {
    private $usuarioModel;
    private $productoModel;

    public function __construct($pdo) {
        $this->usuarioModel = new Usuario($pdo);
        $this->productoModel = new Producto($pdo);
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

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../public/index.php?error=campos_vacios');
            exit;
        }
        $email = trim($_POST['correo'] ?? $_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            header('Location: ../public/index.php?error=campos_vacios');
            exit;
        }

        $usuario = $this->usuarioModel->obtenerPorEmail($email);

        if (!$usuario) {
            $_SESSION['login_error'] = "El usuario o correo electrónico ingresado no existe.";
            header('Location: ../public/index.php');
            exit();
        }
        $passwordHash = $usuario['password_hash'] ?? $usuario['password'] ?? '';

        if (!password_verify($password, $passwordHash)) {
            $_SESSION['login_error'] = "La contraseña ingresada es incorrecta.";
            header('Location: ../public/index.php');
            exit();
        }

        $_SESSION['id_usuario'] = $usuario['id_usuario'] ?? $usuario['id'] ?? null;
        $_SESSION['nombre']     = $usuario['nombre'];
        $_SESSION['rol']        = $usuario['rol'];

        $_SESSION['login_exito'] = "¡Bienvenido de nuevo, " . htmlspecialchars($usuario['nombre']) . "! Has iniciado sesión correctamente.";

        switch ($_SESSION['rol']) {
            case 'admin':
                header('Location: ../views/admin/admin.php');
                exit();
            case 'asesor':
                header('Location: ../views/admin/asesor.php');
                exit();
            case 'subastador':
                header('Location: ../views/admin/subastador.php');
                exit();
            case 'cliente':
            default:
                header('Location: ../views/catalogo.php');
                exit();
        }
    }

    public function agregarProducto() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/admin/admin.php');
            exit();
        }

        $nombre       = trim($_POST['nombre'] ?? '');
        $descripcion  = trim($_POST['descripcion'] ?? '');
        $precio       = intval($_POST['precio'] ?? 0);
        $stock        = intval($_POST['stock'] ?? 0);
        $categoria    = trim($_POST['categoria'] ?? '');
        $calificacion = floatval($_POST['calificacion'] ?? 5.00);
        $estado       = $_POST['estado'] ?? 'activo';

        if (empty($nombre) || $precio <= 0 || empty($categoria)) {
            $_SESSION['admin_msg'] = ['tipo' => 'error', 'texto' => 'Completa los campos obligatorios (Nombre, Precio mayor a 0 y Categoría).'];
            header('Location: ../views/admin/admin.php');
            exit();
        }

        // Manejo de imagen en public/uploads/productos/
        $nombreFoto = 'default.jpg';
        if (isset($_FILES['foto_pro']) && $_FILES['foto_pro']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['foto_pro']['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($ext, $permitidas)) {
                $nombreFoto = 'prod_' . time() . '_' . rand(100, 999) . '.' . $ext;
                $destino = __DIR__ . '/../public/uploads/productos/' . $nombreFoto;
                move_uploaded_file($_FILES['foto_pro']['tmp_name'], $destino);
            } else {
                $_SESSION['admin_msg'] = ['tipo' => 'error', 'texto' => 'Formato de imagen no válido. Usa JPG, PNG o WEBP.'];
                header('Location: ../views/admin/admin.php');
                exit();
            }
        }

        if ($this->productoModel->agregar($nombre, $descripcion, $precio, $stock, $categoria, $calificacion, $estado, $nombreFoto)) {
            $_SESSION['admin_msg'] = ['tipo' => 'exito', 'texto' => '¡Producto agregado al catálogo correctamente!'];
        } else {
            $_SESSION['admin_msg'] = ['tipo' => 'error', 'texto' => 'Error al guardar el producto en la base de datos.'];
        }

        header('Location: ../views/admin/admin.php');
        exit();
    }

    public function eliminarProducto() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = intval($_GET['id'] ?? 0);
        if ($id > 0 && $this->productoModel->eliminar($id)) {
            $_SESSION['admin_msg'] = ['tipo' => 'exito', 'texto' => 'Producto eliminado correctamente.'];
        } else {
            $_SESSION['admin_msg'] = ['tipo' => 'error', 'texto' => 'No se pudo eliminar el producto.'];
        }

        header('Location: ../views/admin/admin.php');
        exit();
    }
}

$accion = $_POST['accion'] ?? $_GET['accion'] ?? 'login';
$controlador = new Controlador($pdo);

if ($accion === 'registrar') {
    $controlador->registrar();
} elseif ($accion === 'agregar_producto') {
    $controlador->agregarProducto();
} elseif ($accion === 'eliminar_producto') {
    $controlador->eliminarProducto();
} else {
    $controlador->login();
}