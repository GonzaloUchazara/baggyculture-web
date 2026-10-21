<?php
session_start();
require 'conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    if (!empty($correo) && !empty($contrasena)) {
        // Consultar el usuario por correo
        $stmt = $pdo->prepare("SELECT id_usuario, nombre, apellido, contrasena FROM usuarios WHERE correo = :correo");
        $stmt->execute(['correo' => $correo]);
        $usuario = $stmt->fetch();

        // Verificar si existe el usuario
        if ($usuario) {
            // Caso A: Si usaste password_hash() al registrar (Recomendado por seguridad)
            if (password_verify($contrasena, $usuario['contrasena'])) {
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['nombre'] = $usuario['nombre'];
                header("Location: panel.php");
                exit;
            } 
            // Caso B: Si guardas la contraseña en texto plano (Solo para pruebas)
            elseif ($contrasena === $usuario['contrasena']) {
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['nombre'] = $usuario['nombre'];
                header("Location: panel.php");
                exit;
            } else {
                $mensaje = "Contraseña incorrecta.";
            }
        } else {
            $mensaje = "El correo ingresado no está registrado.";
        }
    } else {
        $mensaje = "Por favor completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>

    <h2>Iniciar Sesión</h2>

    <?php if (!empty($mensaje)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Correo Electrónico:</label><br>
        <input type="email" name="correo" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="contrasena" required><br><br>

        <button type="submit">Ingresar</button>
    </form>

</body>
</html>