<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/conexion.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Directorio para almacenar avatares
$uploadDir = dirname(__DIR__) . '/public/uploads/perfil/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Función auxiliar para subir fotos de perfil
function guardarAvatar($file, $uploadDir) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return null;
    }

    $fileName = uniqid('user_', true) . '.' . $ext;
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $fileName;
    }
    return null;
}

try {
    switch ($action) {

        // -------------------------------------------------------------
        // 1. LISTAR USUARIOS Y KPIS
        // -------------------------------------------------------------
        case 'listar':
            $sql = "SELECT id_usuario, nombre, apellido, email, telefono, rol, foto_perfil, creado_en 
                    FROM usuarios 
                    ORDER BY id_usuario DESC";
            $stmt = $pdo->query($sql);
            $usuarios = $stmt->fetchAll();

            $kpiTotal = count($usuarios);
            $kpiClientes = 0;
            $kpiStaff = 0;
            $kpiAsesores = 0;

            foreach ($usuarios as $u) {
                if ($u['rol'] === 'cliente') {
                    $kpiClientes++;
                } elseif ($u['rol'] === 'admin') {
                    $kpiStaff++;
                } elseif (in_array($u['rol'], ['asesor', 'subastador'])) {
                    $kpiAsesores++;
                }
            }

            echo json_encode([
                'status' => 'success',
                'data' => $usuarios,
                'kpis' => [
                    'total_usuarios' => $kpiTotal,
                    'clientes'       => $kpiClientes,
                    'staff'          => $kpiStaff,
                    'asesores'       => $kpiAsesores
                ]
            ]);
            break;

        // -------------------------------------------------------------
        // 2. OBTENER DETALLE DE UN USUARIO
        // -------------------------------------------------------------
        case 'obtener':
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'ID de usuario inválido']);
                exit;
            }

            $stmt = $pdo->prepare("SELECT id_usuario, nombre, apellido, email, telefono, rol, foto_perfil FROM usuarios WHERE id_usuario = ?");
            $stmt->execute([$id]);
            $usuario = $stmt->fetch();

            if (!$usuario) {
                echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
                exit;
            }

            echo json_encode(['status' => 'success', 'data' => $usuario]);
            break;

        // -------------------------------------------------------------
        // 3. CREAR / EDITAR USUARIO
        // -------------------------------------------------------------
        case 'guardar':
            $id_usuario = !empty($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : null;
            $nombre     = trim($_POST['nombre'] ?? '');
            $apellido   = trim($_POST['apellido'] ?? '');
            $email      = trim($_POST['email'] ?? '');
            $telefono   = trim($_POST['telefono'] ?? '');
            $rol        = $_POST['rol'] ?? 'cliente';
            $password   = trim($_POST['password'] ?? '');

            $rolesValidos = ['cliente', 'admin', 'asesor', 'subastador'];
            if (empty($nombre) || empty($apellido) || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($rol, $rolesValidos)) {
                echo json_encode(['status' => 'error', 'message' => 'Datos obligatorios inválidos o incompletos']);
                exit;
            }

            // Validar que el correo no esté duplicado en otro usuario
            if ($id_usuario) {
                $stmtCheck = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?");
                $stmtCheck->execute([$email, $id_usuario]);
            } else {
                $stmtCheck = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
                $stmtCheck->execute([$email]);
            }

            if ($stmtCheck->fetch()) {
                echo json_encode(['status' => 'error', 'message' => 'El correo electrónico ya se encuentra registrado']);
                exit;
            }

            $foto_perfil = null;
            if (!empty($_FILES['foto_perfil']['name'])) {
                $foto_perfil = guardarAvatar($_FILES['foto_perfil'], $uploadDir);
            }

            if ($id_usuario) {
                // Modo Edición
                if (!empty($password)) {
                    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                    if ($foto_perfil) {
                        $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, email = ?, password_hash = ?, telefono = ?, rol = ?, foto_perfil = ? WHERE id_usuario = ?";
                        $params = [$nombre, $apellido, $email, $passwordHash, $telefono, $rol, $foto_perfil, $id_usuario];
                    } else {
                        $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, email = ?, password_hash = ?, telefono = ?, rol = ? WHERE id_usuario = ?";
                        $params = [$nombre, $apellido, $email, $passwordHash, $telefono, $rol, $id_usuario];
                    }
                } else {
                    if ($foto_perfil) {
                        $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, email = ?, telefono = ?, rol = ?, foto_perfil = ? WHERE id_usuario = ?";
                        $params = [$nombre, $apellido, $email, $telefono, $rol, $foto_perfil, $id_usuario];
                    } else {
                        $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, email = ?, telefono = ?, rol = ? WHERE id_usuario = ?";
                        $params = [$nombre, $apellido, $email, $telefono, $rol, $id_usuario];
                    }
                }

                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);

                echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado correctamente']);
            } else {
                // Modo Creación
                if (empty($password) || strlen($password) < 6) {
                    echo json_encode(['status' => 'error', 'message' => 'La contraseña debe tener al menos 6 caracteres']);
                    exit;
                }

                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                $fotoFinal = $foto_perfil ?? 'default_user.jpg';

                $sql = "INSERT INTO usuarios (nombre, apellido, email, password_hash, telefono, rol, foto_perfil) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nombre, $apellido, $email, $passwordHash, $telefono, $rol, $fotoFinal]);

                echo json_encode(['status' => 'success', 'message' => 'Usuario creado exitosamente']);
            }
            break;

        
        case 'cambiar_rol':
            $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);
            $nuevo_rol  = trim($_POST['rol'] ?? '');

            $rolesValidos = ['cliente', 'admin', 'asesor', 'subastador'];
            if (!$id_usuario || !in_array($nuevo_rol, $rolesValidos)) {
                echo json_encode(['status' => 'error', 'message' => 'Parámetros inválidos']);
                exit;
            }

            $stmt = $pdo->prepare("UPDATE usuarios SET rol = ? WHERE id_usuario = ?");
            $stmt->execute([$nuevo_rol, $id_usuario]);

            echo json_encode(['status' => 'success', 'message' => 'Rol actualizado a ' . $nuevo_rol]);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no permitida']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}