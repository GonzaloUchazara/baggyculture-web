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
$carpeta_uploads = '../uploads/';
if (!file_exists($carpeta_uploads)) {
    mkdir($carpeta_uploads, 0777, true);
}

if (isset($_POST['agregar_producto'])) {
    $id_prod      = intval($_POST['id_producto']);
    $nombre       = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $id_categoria = intval($_POST['id_categoria']);
    $precio       = floatval($_POST['precio']);
    $stock        = intval($_POST['stock']);
    $descripcion  = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $nombre_lower = strtolower($nombre);
    $ruta_imagen  = '';

    if (isset($_FILES['imagen_archivo']) && $_FILES['imagen_archivo']['error'] === UPLOAD_ERR_OK) {
        $nombre_tmp  = $_FILES['imagen_archivo']['tmp_name'];
        $extension   = pathinfo($_FILES['imagen_archivo']['name'], PATHINFO_EXTENSION);
        $nombre_foto = time() . '_' . uniqid() . '.' . $extension;
        $destino     = $carpeta_uploads . $nombre_foto;

        if (move_uploaded_file($nombre_tmp, $destino)) {
            $ruta_imagen = '../uploads/' . $nombre_foto;
        }
    }

    $sql = "INSERT INTO productos (id_producto, nombre, id_categoria, precio, stock, descripcion, imagen, nombre_lower)
            VALUES ($id_prod, '$nombre', $id_categoria, $precio, $stock, '$descripcion', '$ruta_imagen', '$nombre_lower')";
            
    if (mysqli_query($conexion, $sql)) {
        header("Location: admin_productos.php?msg=" . urlencode("Producto #$id_prod agregado exitosamente."));
        exit;
    } else {
        $mensaje = 'error|Error al insertar: ' . mysqli_error($conexion);
    }
}

if (isset($_GET['msg'])) {
    $mensaje = 'ok|' . htmlspecialchars($_GET['msg']);
}

if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    mysqli_query($conexion, "DELETE FROM productos WHERE id_producto=$id");
    header("Location: admin_productos.php?msg=" . urlencode("Producto #$id eliminado."));
    exit;
}

if (isset($_POST['editar_precio'])) {
    $id     = intval($_POST['producto_id']);
    $precio = floatval($_POST['nuevo_precio']);
    mysqli_query($conexion, "UPDATE productos SET precio=$precio WHERE id_producto=$id");
    header("Location: admin_productos.php?msg=" . urlencode("Precio actualizado."));
    exit;
}

if (isset($_POST['editar_stock'])) {
    $id    = intval($_POST['producto_id']);
    $stock = intval($_POST['nuevo_stock']);
    mysqli_query($conexion, "UPDATE productos SET stock=$stock WHERE id_producto=$id");
    header("Location: admin_productos.php?msg=" . urlencode("Stock actualizado."));
    exit;
}

$categorias_list = mysqli_query($conexion, "SELECT * FROM categorias");

$sql_prod = "SELECT p.*, c.nombre AS nombre_categoria 
             FROM productos p 
             LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
             ORDER BY p.id_producto DESC";
$productos = mysqli_query($conexion, $sql_prod);
if (!$productos) {
    $productos = mysqli_query($conexion, "SELECT * FROM productos");
}
$total = $productos ? mysqli_num_rows($productos) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Productos</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="sidebar">
    <div class="logo">BaggyCulture</div>
    <div class="seccion">General</div>
    <a href="admin_dashboard.php">Dashboard</a>
    <div class="seccion">Gestion</div>
    <a href="admin_usuarios.php">Usuarios</a>
    <a href="admin_productos.php" class="active">Productos</a>
    <a href="admin_cotizaciones.php">Cotizaciones</a>
    <a href="admin_pedidos.php">Pedidos</a>
    <a href="../index.php" target="_blank">Ver Tienda</a>
    <a href="../pages/logout.php">Cerrar Sesion</a>
</div>

<div class="contenido">
    <div class="page-titulo">Gestion de Productos</div>

    <?php if ($mensaje): ?>
        <?php $parts = explode('|', $mensaje); ?>
        <div class="mensaje mensaje-<?= $parts[0] === 'ok' ? 'ok' : 'error' ?>">
            <?= $parts[1] ?>
        </div>
    <?php endif; ?>

    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h3>Agregar producto</h3></div>
        <form action="admin_productos.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-grupo">
                    <label>ID del Producto</label>
                    <input type="number" name="id_producto" placeholder="Ej. 17" required>
                </div>
                <div class="form-grupo">
                    <label>Nombre del Producto</label>
                    <input type="text" name="nombre" placeholder="Ej. Polera Urban" required>
                </div>
                <div class="form-grupo">
                    <label>Categoria</label>
                    <select name="id_categoria" required>
                        <?php 
                        if ($categorias_list && mysqli_num_rows($categorias_list) > 0) {
                            while ($cat = mysqli_fetch_assoc($categorias_list)) {
                                $c_id = isset($cat['id_categoria']) ? $cat['id_categoria'] : (isset($cat['id']) ? $cat['id'] : 1);
                                echo '<option value="' . $c_id . '">' . htmlspecialchars($cat['nombre']) . '</option>';
                            }
                        } else {
                            echo '<option value="1">General</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-grupo">
                    <label>Precio (Bs.)</label>
                    <input type="number" step="0.01" name="precio" placeholder="140.00" required>
                </div>
                <div class="form-grupo">
                    <label>Stock</label>
                    <input type="number" name="stock" placeholder="20" value="20" required>
                </div>
                <div class="form-grupo full">
                    <label>Seleccionar Imagen desde mis archivos</label>
                    <input type="file" name="imagen_archivo" accept="image/*">
                </div>
                <div class="form-grupo full">
                    <label>Descripcion</label>
                    <input type="text" name="descripcion" placeholder="Descripcion corta">
                </div>
            </div>
            <div class="form-footer">
                <button type="submit" name="agregar_producto" class="btn btn-oscuro">Agregar producto</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Lista de productos (<?= $total ?>)</h3>
            <input type="text" class="buscador" placeholder="Buscar producto..." onkeyup="buscar(this.value)">
        </div>
        <table id="tabla-productos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Categoria</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($total > 0) {
                    while ($p = mysqli_fetch_assoc($productos)) {
                        $prod_id = isset($p['id_producto']) ? $p['id_producto'] : (isset($p['id']) ? $p['id'] : 0);
                        $nombre_p = isset($p['nombre']) ? $p['nombre'] : '';
                        $cat_p = isset($p['nombre_categoria']) ? $p['nombre_categoria'] : (isset($p['id_categoria']) ? $p['id_categoria'] : 'N/A');
                        $precio_p = isset($p['precio']) ? $p['precio'] : 0;
                        $stock_p = isset($p['stock']) ? $p['stock'] : 0;
                        $img_p = isset($p['imagen']) ? $p['imagen'] : '';

                        echo '<tr>';
                        echo '<td><code>#' . $prod_id . '</code></td>';
                        echo '<td>';
                        if (!empty($img_p)) {
                            echo '<img src="' . htmlspecialchars($img_p) . '" style="width:45px;height:45px;object-fit:cover;border-radius:6px" onerror="this.style.display=\'none\'">';
                        } else {
                            echo '<span style="color:#aaa;font-size:12px">Sin foto</span>';
                        }
                        echo '</td>';
                        echo '<td><b>' . htmlspecialchars($nombre_p) . '</b></td>';
                        echo '<td>' . htmlspecialchars($cat_p) . '</td>';
                        echo '<td>';
                        echo '<form action="admin_productos.php" method="POST" style="display:flex;gap:4px;align-items:center">';
                        echo '<input type="hidden" name="producto_id" value="' . $prod_id . '">';
                        echo '<input type="number" step="0.01" name="nuevo_precio" value="' . $precio_p . '" style="width:75px;padding:4px;border-radius:6px;border:1px solid #ddd;font-size:12px">';
                        echo '<button type="submit" name="editar_precio" class="btn btn-azul">OK</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '<td>';
                        echo '<form action="admin_productos.php" method="POST" style="display:flex;gap:4px;align-items:center">';
                        echo '<input type="hidden" name="producto_id" value="' . $prod_id . '">';
                        echo '<input type="number" name="nuevo_stock" value="' . $stock_p . '" style="width:60px;padding:4px;border-radius:6px;border:1px solid #ddd;font-size:12px">';
                        echo '<button type="submit" name="editar_stock" class="btn btn-verde">OK</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '<td>';
                        echo '<a href="admin_productos.php?eliminar=' . $prod_id . '" class="btn btn-rojo" onclick="return confirm(\'¿Eliminar ' . htmlspecialchars($nombre_p) . '?\')">Eliminar</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="7" style="text-align:center;color:#aaa">No hay productos cargados.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function buscar(texto) {
    document.querySelectorAll('#tabla-productos tbody tr').forEach(function(f) {
        f.style.display = f.textContent.toLowerCase().includes(texto.toLowerCase()) ? '' : 'none';
    });
}
</script>
</body>
</html>