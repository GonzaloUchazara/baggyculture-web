<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../models/subasta.php';

$subastas = [];
if (isset($pdo)) {
    try {
        $subastaModel = new subasta($pdo);
        $subastas = $subastaModel->obtenerTodas();
    } catch (Exception $e) {
        $subastas = [];
    }
}

$stmtProd = $pdo->query("SELECT id_producto, nombre, precio FROM productos ORDER BY nombre ASC");
$listaProductos = $stmtProd->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - Subastas | BaggyCulture</title>
    <link rel="stylesheet" href="../../assets/css/subastas-admin.css">
    <link rel="stylesheet" href="../../assets/css/subastador.css">
</head>
<body class="admin-body">

    <?php include __DIR__ . '/../../includes/navbar-admin.php'; ?>

    <main class="admin-container">
        <header class="section-header">
            <div>
                <h1>Gestión de Subastas</h1>
                <p class="subtitle">Supervisa, edita precios y controla el estado de las subastas activas.</p>
            </div>
            <button type="button" class="btn btn-primary" id="btnAbrirCrearSubasta">+ Nueva Subasta</button>
        </header>

        <section class="table-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Precio Base</th>
                        <th>Precio Actual</th>
                        <th>Inc. Mínimo</th>
                        <th>Inicio / Fin</th>
                        <th>Estado</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($subastas)): ?>
                        <tr>
                            <td colspan="8" class="text-center">No hay subastas registradas actualmente.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($subastas as $subasta): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($subasta['id_subasta']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($subasta['producto_nombre'] ?? 'Producto #' . $subasta['id_producto']) ?></strong>
                                </td>
                                <td>Bs. <?= number_format($subasta['precio_base'], 2) ?></td>
                                <td>
                                    <span class="text-highlight">Bs. <?= number_format($subasta['precio_actual'], 2) ?></span>
                                </td>
                                <td>+Bs. <?= number_format($subasta['incremento_minimo'], 2) ?></td>
                                <td class="date-cell">
                                    <span><?= date('d/m/Y H:i', strtotime($subasta['fecha_inicio'])) ?></span>
                                    <small><?= date('d/m/Y H:i', strtotime($subasta['fecha_fin'])) ?></small>
                                </td>
                                <td>
                                    <span class="badge-status <?= $subasta['estado'] === 'activa' ? 'status-activa' : 'status-inactiva' ?>">
                                        <?= ucfirst(htmlspecialchars($subasta['estado'])) ?>
                                    </span>
                                </td>
                                <td class="actions-cell">
                                    <a href="../subastadetalle.php?id=<?= $subasta['id_subasta'] ?>" 
                                    target="_blank" 
                                    class="btn-action btn-view" 
                                    title="Ver subasta pública">
                                    Ver
                                    </a>
                                    <button type="button" 
                                    class="btn-action btn-extend" 
                                    data-id="<?= $subasta['id_subasta'] ?>"
                                    data-fin="<?= $subasta['fecha_fin'] ?>"
                                    title="Extender duración">
                                    + Tiempo
                                    </button>
                                    <button 
                                        type="button" 
                                        class="btn-action btn-edit" 
                                        data-id="<?= $subasta['id_subasta'] ?>"
                                        data-producto="<?= htmlspecialchars($subasta['producto_nombre'] ?? '') ?>"
                                        data-precio-base="<?= $subasta['precio_base'] ?>"
                                        data-inicio="<?= date('Y-m-d\TH:i', strtotime($subasta['fecha_inicio'])) ?>"
                                        data-fin="<?= date('Y-m-d\TH:i', strtotime($subasta['fecha_fin'])) ?>"
                                        data-estado="<?= $subasta['estado'] ?>"
                                    >
                                        Editar
                                    </button>

                                    <!-- Desactivación lógica en lugar de borrado físico -->
                                    <form action="../../controllers/subastacontroler.php" method="POST" class="inline-form">
                                        <input type="hidden" name="accion" value="toggle_estado">
                                        <input type="hidden" name="id_subasta" value="<?= $subasta['id_subasta'] ?>">
                                        <input type="hidden" name="nuevo_estado" value="<?= $subasta['estado'] === 'activa' ? 'finalizada' : 'activa' ?>">
                                        <button type="submit" class="btn-action <?= $subasta['estado'] === 'activa' ? 'btn-danger' : 'btn-success' ?>">
                                            <?= $subasta['estado'] === 'activa' ? 'Desactivar' : 'Activar' ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <!-- Modal de Edición que activaremos con JS en el siguiente paso -->
    <div class="modal-backdrop" id="modalEditarSubasta">
        <div class="modal-box">
            <header class="modal-header">
                <h3>Editar Subasta</h3>
                <button type="button" class="btn-close" id="btnCerrarModal">&times;</button>
            </header>
            <form action="../../controllers/subastacontroler.php" method="POST" class="modal-form">
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="id_subasta" id="modal_id_subasta">
                
                <div class="form-group">
                    <label for="modal_producto_nombre">Producto</label>
                    <input type="text" name="producto_nombre" id="modal_producto_nombre" readonly>
                </div>

                <div class="form-group">
                    <label for="modal_precio_base">Precio Base (Bs.)</label>
                    <input type="number" step="0.50" min="1" name="precio_base" id="modal_precio_base" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="modal_fecha_inicio">Fecha Inicio</label>
                        <input type="datetime-local" name="fecha_inicio" id="modal_fecha_inicio" required>
                    </div>
                    <div class="form-group">
                        <label for="modal_fecha_fin">Fecha Fin</label>
                        <input type="datetime-local" name="fecha_fin" id="modal_fecha_fin" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="modal_estado">Estado</label>
                    <select name="estado" id="modal_estado" required>
                        <option value="activa">Activa</option>
                        <option value="finalizada">Finalizada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnCancelarModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-backdrop" id="modalExtenderSubasta">
        <div class="modal-box modal-box-sm">
            <header class="modal-header">
                <h3>Extender Tiempo de Subasta</h3>
                <button type="button" class="btn-close" id="btnCerrarExtender">&times;</button>
            </header>
            <form action="../../controllers/subastacontroler.php" method="POST" class="modal-form">
                <input type="hidden" name="accion" value="extender">
                <input type="hidden" name="id_subasta" id="extender_id_subasta">
                
                <p class="modal-info">
                    Fecha fin actual: <strong id="extender_fecha_actual">--</strong>
                </p>

                <div class="form-group">
                    <label for="minutos_extra">Tiempo a sumar</label>
                    <select name="minutos_extra" id="minutos_extra" required>
                        <option value="15">+15 Minutos</option>
                        <option value="30">+30 Minutos</option>
                        <option value="60">+1 Hora</option>
                        <option value="1440">+24 Horas (1 Día)</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnCancelarExtender">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Aplicar Extensión</button>
                </div>
            </form>
        </div>
    </div>







    <!--modal de creacion pe -->
    <div class="modal-backdrop" id="modalCrearSubasta">
    <div class="modal-box">
        <header class="modal-header">
            <h3>Crear Nueva Subasta</h3>
            <button type="button" class="btn-close" id="btnCerrarCrear">&times;</button>
        </header>
        <form action="../../controllers/subastas-crud.php" method="POST" class="modal-form">
            <input type="hidden" name="accion" value="crear">

            <div class="form-group">
                <label for="crear_id_producto">Producto a Subastar</label>
                <select name="id_producto" id="crear_id_producto" required>
                    <option value="">-- Selecciona un producto --</option>
                    <?php foreach ($listaProductos as $prod): ?>
                        <option value="<?= $prod['id_producto'] ?>">
                            <?= htmlspecialchars($prod['nombre']) ?> (Precio ref: Bs. <?= number_format($prod['precio'], 2) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="crear_precio_base">Precio Base (Bs.)</label>
                    <input type="number" step="0.50" min="1" name="precio_base" id="crear_precio_base" placeholder="Ej: 50.00" required>
                </div>
                <div class="form-group">
                    <label for="crear_incremento_minimo">Inc. Mínimo (Bs.)</label>
                    <input type="number" step="0.50" min="1" name="incremento_minimo" id="crear_incremento_minimo" value="5.00" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="crear_fecha_inicio">Fecha y Hora Inicio</label>
                    <input type="datetime-local" name="fecha_inicio" id="crear_fecha_inicio" required>
                </div>
                <div class="form-group">
                    <label for="crear_fecha_fin">Fecha y Hora Fin</label>
                    <input type="datetime-local" name="fecha_fin" id="crear_fecha_fin" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnCancelarCrear">Cancelar</button>
                <button type="submit" class="btn btn-primary">Publicar Subasta</button>
            </div>
        </form>
    </div>
</div>

    <script src="../../assets/js/subastador.js"></script>
</body>
</html>