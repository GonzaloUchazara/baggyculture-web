<?php
session_start();

$_SESSION['usuario'] = 'AdminPrueba';
$_SESSION['rol'] = 'admin';
$_SESSION['usuario_id'] = 1;

$conexion = mysqli_connect("localhost", "root", "", "baggy_culture");
if (!$conexion) {
    die("Error de conexion: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

$mensaje = '';

// Inspeccionar dinamicamente la estructura de la tabla usuarios
$columnas = array();
$res_cols = mysqli_query($conexion, "SHOW COLUMNS FROM usuarios");
if ($res_cols) {
    while ($col = mysqli_fetch_assoc($res_cols)) {
        $columnas[] = $col['Field'];
    }
}

$campo_id     = in_array('id_usuario', $columnas) ? 'id_usuario' : (in_array('id', $columnas) ? 'id' : '');
$campo_correo = in_array('email', $columnas) ? 'email' : (in_array('correo', $columnas) ? 'correo' : (in_array('usuario', $columnas) ? 'usuario' : ''));
$campo_pass   = in_array('password', $columnas) ? 'password' : (in_array('contrasena', $columnas) ? 'contrasena' : (in_array('clave', $columnas) ? 'clave' : ''));
$campo_rol    = in_array('rol', $columnas) ? 'rol' : (in_array('tipo', $columnas) ? 'tipo' : (in_array('tipo_usuario', $columnas) ? 'tipo_usuario' : ''));

// ── AGREGAR USUARIO ──
if (isset($_POST['agregar_usuario'])) {
    $nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $email    = mysqli_real_escape_string($conexion, $_POST['email']);
    $password = mysqli_real_escape_string($conexion, $_POST['password']);
    $rol      = mysqli_real_escape_string($conexion, $_POST['rol']);
    
    $id_user = !empty($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;
    
    // Si no se ingreso ID manual, calcular el MAX + 1 para evitar duplicados
    if ($id_user === 0 && !empty($campo_id)) {
        $res_max = mysqli_query($conexion, "SELECT MAX(CAST($campo_id AS UNSIGNED)) AS max_id FROM usuarios");
        if ($res_max) {
            $row_max = mysqli_fetch_assoc($res_max);
            $id_user = intval($row_max['max_id'] ?? 0) + 1;
        } else {
            $id_user = 1;
        }
    }

    $cols_insert = array('nombre');
    $vals_insert = array("'$nombre'");

    if (!empty($campo_id) && $id_user > 0) {
        $cols_insert[] = $campo_id;
        $vals_insert[] = $id_user;
    }
    if (!empty($campo_correo)) {
        $cols_insert[] = $campo_correo;
        $vals_insert[] = "'$email'";
    }
    if (!empty($campo_pass)) {
        $cols_insert[] = $campo_pass;
        $vals_insert[] = "'$password'";
    }
    if (!empty($campo_rol)) {
        $cols_insert[] = $campo_rol;
        $vals_insert[] = "'$rol'";
    }

    $sql = "INSERT INTO usuarios (" . implode(', ', $cols_insert) . ") VALUES (" . implode(', ', $vals_insert) . ")";
            
    if (mysqli_query($conexion, $sql)) {
        header("Location: admin_usuarios.php?msg=" . urlencode("Usuario '$nombre' agregado exitosamente con ID #$id_user."));
        exit;
    } else {
        $mensaje = 'error|Error al insertar usuario: ' . mysqli_error($conexion);
    }
}

if (isset($_GET['msg'])) {
    $mensaje = 'ok|' . htmlspecialchars($_GET['msg']);
}

// ── ELIMINAR USUARIO ──
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $email_del = mysqli_real_escape_string($conexion, $_GET['email'] ?? '');

    if (!empty($campo_id) && $id > 0) {
        mysqli_query($conexion, "DELETE FROM usuarios WHERE $campo_id=$id");
    } else if (!empty($campo_correo) && !empty($email_del)) {
        mysqli_query($conexion, "DELETE FROM usuarios WHERE $campo_correo='$email_del'");
    }

    header("Location: admin_usuarios.php?msg=" . urlencode("Usuario eliminado correctamente."));
    exit;
}

// ── CAMBIAR ROL ──
if (isset($_POST['cambiar_rol'])) {
    $id        = intval($_POST['usuario_id']);
    $nuevo_rol = mysqli_real_escape_string($conexion, $_POST['nuevo_rol']);
    
    if (!empty($campo_rol) && !empty($campo_id) && $id > 0) {
        mysqli_query($conexion, "UPDATE usuarios SET $campo_rol='$nuevo_rol' WHERE $campo_id=$id");
    }
    header("Location: admin_usuarios.php?msg=" . urlencode("Rol de usuario actualizado."));
    exit;
}

// ── OBTENER USUARIOS ──
$order_clause = !empty($campo_id) ? "ORDER BY CAST($campo_id AS UNSIGNED) DESC" : "";
$usuarios = mysqli_query($conexion, "SELECT * FROM usuarios $order_clause");
$total = $usuarios ? mysqli_num_rows($usuarios) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Usuarios</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="sidebar">
    <div class="logo">BaggyCulture</div>
    <div class="seccion">General</div>
    <a href="admin_dashboard.php">Dashboard</a>
    <div class="seccion">Gestion</div>
    <a href="admin_usuarios.php" class="active">Usuarios</a>
    <a href="admin_productos.php">Productos</a>
    <a href="admin_cotizaciones.php">Cotizaciones</a>
    <a href="admin_pedidos.php">Pedidos</a>
    <a href="../index.php" target="_blank">Ver Tienda</a>
    <a href="../pages/logout.php">Cerrar Sesion</a>
</div>

<div class="contenido">
    <div class="page-titulo">Gestion de Usuarios</div>

    <?php 
    if ($mensaje) {
        $parts = explode('|', $mensaje);
        $tipo = $parts[0];
        $texto = $parts[1];
        echo '<div class="mensaje mensaje-' . ($tipo === 'ok' ? 'ok' : 'error') . '">' . $texto . '</div>';
    }
    ?>

    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h3>Agregar nuevo usuario</h3></div>
        <form action="admin_usuarios.php" method="POST">
            <div class="form-grid">
                <div class="form-grupo">
                    <label>ID del Usuario (Manual o dejar vacio)</label>
                    <input type="number" name="id_usuario" placeholder="Ej. 16 (Vacio para ID automatico)">
                </div>
                <div class="form-grupo">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" placeholder="Ej. Juan Flores" required>
                </div>
                <div class="form-grupo">
                    <label>Email / Correo</label>
                    <input type="email" name="email" placeholder="correo@ejemplo.com" required>
                </div>
                <div class="form-grupo">
                    <label>Contraseña</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <div class="form-grupo">
                    <label>Rol</label>
                    <select name="rol" required>
                        <option value="Cliente">Cliente</option>
                        <option value="Asesor">Asesor</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
            </div>
            <div class="form-footer">
                <button type="submit" name="agregar_usuario" class="btn btn-oscuro">Agregar usuario</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Lista de usuarios (<?php echo $total; ?>)</h3>
            <input type="text" class="buscador" placeholder="Buscar usuario..." onkeyup="buscar(this.value)">
        </div>
        <table id="tabla-usuarios">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email / Correo</th>
                    <th>Rol Actual</th>
                    <th>Registrado</th>
                    <th>Cambiar Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($total > 0) {
                    while ($u = mysqli_fetch_assoc($usuarios)) {
                        $u_id = (!empty($campo_id) && isset($u[$campo_id])) ? $u[$campo_id] : 0;
                        $u_nom = isset($u['nombre']) ? $u['nombre'] : '';
                        $u_email = (!empty($campo_correo) && isset($u[$campo_correo])) ? $u[$campo_correo] : 'N/A';
                        $u_rol = (!empty($campo_rol) && isset($u[$campo_rol])) ? $u[$campo_rol] : 'Cliente';
                        $u_fecha = isset($u['fecha_registro']) ? $u['fecha_registro'] : (isset($u['fecha']) ? $u['fecha'] : 'N/A');

                        echo '<tr>';
                        echo '<td><code>#' . $u_id . '</code></td>';
                        echo '<td><b>' . htmlspecialchars($u_nom) . '</b></td>';
                        echo '<td>' . htmlspecialchars($u_email) . '</td>';
                        echo '<td><span class="badge badge-' . strtolower($u_rol) . '">' . htmlspecialchars($u_rol) . '</span></td>';
                        echo '<td>' . htmlspecialchars($u_fecha) . '</td>';
                        echo '<td>';
                        echo '<form action="admin_usuarios.php" method="POST" style="display:flex;gap:4px;align-items:center">';
                        echo '<input type="hidden" name="usuario_id" value="' . $u_id . '">';
                        echo '<select name="nuevo_rol" style="padding:4px;border-radius:6px;border:1px solid #ddd;font-size:12px">';
                        echo '<option value="Cliente"' . ($u_rol === 'Cliente' ? ' selected' : '') . '>Cliente</option>';
                        echo '<option value="Asesor"' . ($u_rol === 'Asesor' ? ' selected' : '') . '>Asesor</option>';
                        echo '<option value="admin"' . ($u_rol === 'admin' ? ' selected' : '') . '>Administrador</option>';
                        echo '</select>';
                        echo '<button type="submit" name="cambiar_rol" class="btn btn-azul">Cambiar</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '<td>';
                        echo '<a href="admin_usuarios.php?eliminar=' . $u_id . '&email=' . urlencode($u_email) . '" class="btn btn-rojo" onclick="return confirm(\'¿Eliminar usuario ' . htmlspecialchars($u_nom) . '?\')">Eliminar</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="7" style="text-align:center;color:#aaa">No hay usuarios registrados.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function buscar(texto) {
    document.querySelectorAll('#tabla-usuarios tbody tr').forEach(function(f) {
        f.style.display = f.textContent.toLowerCase().includes(texto.toLowerCase()) ? '' : 'none';
    });
}
</script>
</body>
</html>