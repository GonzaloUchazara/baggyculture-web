<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/usuario.php';

class Controlador {
    private $usuarioModel;

    public function __construct($pdo) {
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

        /* validar correo */
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'El formato del correo electrónico no es válido.']);
            exit;
        }

        /* contrasenas iguales */
        if ($password !== $password_confirm) {
            echo json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden.']);
            exit;
        }

        /* longitud */
        if (strlen($password) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'La contraseña debe tener al menos 6 caracteres.']);
            exit;
        }

        /* correo exista no dups */
        if ($this->usuarioModel->emailExiste($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Este correo ya se encuentra registrado.']);
            exit;
        }

        /* registrarlo */
        $registrado = $this->usuarioModel->registrar($nombre, $apellido, $email, $password, $telefono, 'cliente');

        if ($registrado) {
            echo json_encode(['status' => 'success', 'message' => '¡Cuenta creada con éxito! Ya puedes iniciar sesión.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Hubo un error al registrar la cuenta. Intenta de nuevo.']);
        }
        exit;
    }


    public function login(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('Location: ../public/index.php?error=campos_vacios');
            exit;
        }
        $email = trim($_POST['correo'] ?? $_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';

        if(empty($email) || empty($password)){
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

        switch ($_SESSION['rol']){
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



}

$accion = $_POST['accion'] ?? $_GET['accion'] ?? 'login';
$controlador = new Controlador($pdo);

if($accion ==='registrar'){
    $controlador->registrar();
}else{
    $controlador->login();
}


