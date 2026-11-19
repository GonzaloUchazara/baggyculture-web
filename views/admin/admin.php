<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    <title>Panel Administrador Principal | BaggyCulture</title>
    <link rel="stylesheet" href="../../assets/css/navbar-admin-principal.css">
    <link rel="stylesheet" href="../../assets/css/subastas-admin.css">
    <link rel="stylesheet" href="../../assets/css/subastador.css">
    <style>
        .admin-main-grid {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 24px;
            align-items: start;
            margin-top: 1.5rem;
        }
        @media (max-width: 992px) {
            .admin-main-grid {
                grid-template-columns: 1fr;
            }
        }
        .admin-form-card {
            background-color: #18181b;
            border: 1px solid #27272a;
            border-radius: 10px;
            padding: 20px;
        }
        .admin-form-card h2 {
            font-size: 1.15rem;
            color: #f3f4f6;
            margin-bottom: 1.25rem;
            border-left: 4px solid #f59e0b;
            padding-left: 10px;
        }
        .alerta {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .alerta.exito {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid #10b981;
            color: #6ee7b7;
        }
        .alerta.error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid #ef4444;
            color: #fca5a5;
        }
        .thumb-img {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: 6px;
            background-color: #27272a;
        }
        .badge-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-status.activo {
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
        }
        .badge-status.desactivado {
            background: rgba(156, 163, 175, 0.2);
            color: #9ca3af;
        }
        .badge-status.agotado {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
        }
        .btn-delete {
            color: #f87171;
            text-decoration: none;
            font-size: 0.85rem;
            padding: 4px 10px;
            border: 1px solid rgba(248, 113, 113, 0.3);
            border-radius: 5px;
            transition: all 0.2s ease;
        }
        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.2);
        }
    </style>
</head>
<body class="admin-body">

    <?php include __DIR__ . '/../../includes/navbar-admin-principal.php'; ?>

    <main class="admin-container">
        <header class="section-header">
            <div>
                <h1>Principal Dashboard</h1>
                <p class="subtitle">Panel de control, catálogo de inventario y administración general de BaggyCulture</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="../catalogo.php" class="btn btn-secondary">Ver Tienda</a>
                <a href="subastador.php" class="btn btn-primary">Panel Subastas</a>
            </div>
        </header>

        <?php if ($mensaje): ?>
            <div class="alerta <?= htmlspecialchars($mensaje['tipo']) ?>">
                <?= htmlspecialchars($mensaje['texto']) ?>
            </div>
        <?php endif; ?>

        <div class="admin-main-grid">
            <!-- Formulario para agregar producto -->
            <div class="admin-form-card">
                <h2>Añadir Nuevo Producto</h2>
                <form action="../../controllers/controlador.php" method="POST" enctype="multipart/form-data" class="modal-form">
                    <input type="hidden" name="accion" value="agregar_producto">

                    <div class="form-group">
                        <label>Nombre de la Prenda *</label>
                        <input type="text" name="nombre" placeholder="Ej. Hoodie Oversize Black" required>
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" rows="2" placeholder="Detalles de tela, corte, estilo..."></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Precio (Bs.) *</label>
                            <input type="number" name="precio" min="1" placeholder="180" required>
                        </div>
                        <div class="form-group">
                            <label>Stock disponible *</label>
                            <input type="number" name="stock" min="0" value="10" required>
                        </div>
                    </div>

                    <div class="form-row">
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

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">+ Guardar Producto</button>
                </form>
            </div>

            <!-- Tabla de inventario registrado -->
            <section class="table-card">
                <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #27272a;">
                    <h2 style="font-size: 1.15rem; margin: 0; color: #f3f4f6;">Inventario Registrado (<?= count($productos) ?>)</h2>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th class="text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos)): ?>
                            <tr><td colspan="7" class="text-center">No hay productos registrados todavía.</td></tr>
                        <?php else: ?>
                            <?php foreach ($productos as $p): ?>
                                <tr>
                                    <td>
                                        <img src="../../public/uploads/productos/<?= htmlspecialchars($p['foto_pro']) ?>" 
                                             onerror="this.src='../../public/uploads/default.jpg'" 
                                             class="thumb-img" alt="Foto">
                                    </td>
                                    <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                                    <td><?= htmlspecialchars($p['categoria']) ?></td>
                                    <td>Bs. <?= number_format($p['precio'], 2) ?></td>
                                    <td><?= htmlspecialchars($p['stock']) ?> unid.</td>
                                    <td><span class="badge-status <?= htmlspecialchars($p['estado']) ?>"><?= htmlspecialchars($p['estado']) ?></span></td>
                                    <td class="text-right">
                                        <a href="../../controllers/controlador.php?accion=eliminar_producto&id=<?= $p['id_producto'] ?>" 
                                           class="btn-delete" 
                                           onclick="return confirm('¿Seguro que deseas eliminar este producto?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </main>

</body>
</html>
