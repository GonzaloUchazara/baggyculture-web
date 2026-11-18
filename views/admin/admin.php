<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Protección opcional de ruta (descomenta si quieres exigir login de admin)
/*
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../../public/index.php');
    exit();
}
*/

require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../models/producto.php';

$productoModel = new Producto($pdo);
$productos = $productoModel->obtenerTodos();
$mensaje = $_SESSION['admin_msg'] ?? null;
unset($_SESSION['admin_msg']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador | BaggyCulture</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #0f0f0f; color: #f5f5f5; padding: 30px 20px; }
        .container { max-width: 1150px; margin: 0 auto; }
        header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #2a2a2a; padding-bottom: 18px; margin-bottom: 30px; }
        header h1 { font-size: 1.6rem; letter-spacing: 1px; text-transform: uppercase; }
        header h1 span { color: #ff4d00; }
        .btn-nav { background: #222; color: #fff; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-size: 0.9rem; border: 1px solid #333; transition: 0.2s; }
        .btn-nav:hover { background: #ff4d00; border-color: #ff4d00; }

        .alerta { padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-weight: 500; }
        .alerta.exito { background: rgba(34, 197, 94, 0.15); border: 1px solid #22c55e; color: #86efac; }
        .alerta.error { background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #fca5a5; }

        .grid { display: grid; grid-template-columns: 380px 1fr; gap: 30px; align-items: start; }
        @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }

        .card { background: #181818; border: 1px solid #2a2a2a; border-radius: 12px; padding: 24px; }
        .card h2 { font-size: 1.15rem; margin-bottom: 20px; color: #fff; border-left: 4px solid #ff4d00; padding-left: 10px; }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 0.85rem; color: #bbb; margin-bottom: 6px; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 10px 12px; background: #101010; border: 1px solid #333;
            border-radius: 6px; color: #fff; font-size: 0.95rem;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #ff4d00; }
        .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        .btn-submit {
            width: 100%; padding: 12px; background: #ff4d00; color: #fff; border: none;
            border-radius: 6px; font-size: 1rem; font-weight: bold; cursor: pointer; margin-top: 8px; transition: 0.2s;
        }
        .btn-submit:hover { background: #e04400; }

        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th, td { padding: 12px 10px; text-align: left; border-bottom: 1px solid #262626; }
        th { color: #999; font-weight: 600; text-transform: uppercase; font-size: 0.78rem; }
        .thumb { width: 48px; height: 48px; object-fit: cover; border-radius: 6px; background: #222; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .badge.activo { background: rgba(34, 197, 94, 0.2); color: #4ade80; }
        .badge.desactivado { background: rgba(156, 163, 175, 0.2); color: #9ca3af; }
        .badge.agotado { background: rgba(239, 68, 68, 0.2); color: #f87171; }
        .btn-del { color: #f87171; text-decoration: none; font-size: 0.85rem; padding: 5px 10px; border: 1px solid rgba(248, 113, 113, 0.3); border-radius: 5px; }
        .btn-del:hover { background: rgba(239, 68, 68, 0.2); }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Baggy<span>Culture</span> · Panel Admin</h1>
        <div style="display: flex; gap: 10px;">
            <a href="../catalogo.php" class="btn-nav">Ver Catálogo</a>
            <a href="../../public/index.php" class="btn-nav">Inicio</a>
        </div>
    </header>

    <?php if ($mensaje): ?>
        <div class="alerta <?= htmlspecialchars($mensaje['tipo']) ?>">
            <?= htmlspecialchars($mensaje['texto']) ?>
        </div>
    <?php endif; ?>

    <div class="grid">
        <!-- Formulario para añadir producto -->
        <div class="card">
            <h2>Añadir Nuevo Producto</h2>
            <form action="../../controllers/controlador.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="agregar_producto">

                <div class="form-group">
                    <label>Nombre de la Prenda *</label>
                    <input type="text" name="nombre" placeholder="Ej. Hoodie Oversize Black" required>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="2" placeholder="Detalles de tela, corte, estilo..."></textarea>
                </div>

                <div class="row-2">
                    <div class="form-group">
                        <label>Precio (Bs.) *</label>
                        <input type="number" name="precio" min="1" placeholder="180" required>
                    </div>
                    <div class="form-group">
                        <label>Stock disponible *</label>
                        <input type="number" name="stock" min="0" value="10" required>
                    </div>
                </div>

                <div class="row-2">
                    <div class="form-group">
                        <label>Categoría *</label>
                        <select name="categoria" required>
                            <option value="baggy">Baggy</option>
                            <option value="poleras">Poleras</option>
                            <option value="hoodies">Hoodies</option>
                            <option value="pantalones">Pantalones</option>
                            <option value="accesorios">Accesorios</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado">
                            <option value="activo">Activo</option>
                            <option value="agotado">Agotado</option>
                            <option value="desactivado">Desactivado</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Calificación inicial (1.0 - 5.0)</label>
                    <input type="number" name="calificacion" step="0.1" min="1" max="5" value="5.0">
                </div>

                <div class="form-group">
                    <label>Imagen Principal del Producto</label>
                    <input type="file" name="foto_pro" accept="image/jpeg,image/png,image/webp">
                </div>

                <button type="submit" class="btn-submit">+ Guardar Producto</button>
            </form>
        </div>

        <!-- Tabla de productos registrados -->
        <div class="card">
            <h2>Inventario Registrado (<?= count($productos) ?>)</h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos)): ?>
                            <tr><td colspan="7" style="text-align:center; color:#777;">No hay productos registrados todavía.</td></tr>
                        <?php else: ?>
                            <?php foreach ($productos as $p): ?>
                                <tr>
                                    <td>
                                        <img src="../../public/uploads/productos/<?= htmlspecialchars($p['foto_pro']) ?>" 
                                             onerror="this.src='../../public/uploads/default.jpg'" 
                                             class="thumb" alt="Foto">
                                    </td>
                                    <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                                    <td><?= htmlspecialchars($p['categoria']) ?></td>
                                    <td>Bs. <?= htmlspecialchars($p['precio']) ?></td>
                                    <td><?= htmlspecialchars($p['stock']) ?> unid.</td>
                                    <td><span class="badge <?= htmlspecialchars($p['estado']) ?>"><?= htmlspecialchars($p['estado']) ?></span></td>
                                    <td>
                                        <a href="../../controllers/controlador.php?accion=eliminar_producto&id=<?= $p['id_producto'] ?>" 
                                           class="btn-del" 
                                           onclick="return confirm('¿Seguro que deseas eliminar este producto?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>