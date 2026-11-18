<?php

?>
<div id="modalCrearSubasta" class="modal-backdrop">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                Lanzar Nueva Subasta
            </h3>
            <button type="button" class="btn-close-modal" data-close-modal aria-label="Cerrar">&times;</button>
        </div>

        <form action="subastador.php" method="POST" id="formCrearSubasta">
            <input type="hidden" name="accion" value="crear_subasta">

            <div class="modal-body">
                <!-- Selector de Producto -->
                <div class="form-group">
                    <label for="selectProducto">Seleccionar Producto del Catálogo *</label>
                    <select name="id_producto" id="selectProducto" class="form-control" required>
                        <option value="">-- Elige una prenda o accesorio --</option>
                        <?php foreach ($productosDisponibles as $p): ?>
                            <option value="<?= $p['id_producto'] ?>" 
                                    data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                                    data-precio="<?= $p['precio'] ?>"
                                    data-foto="<?= htmlspecialchars($p['foto_pro'] ?? 'default.jpg') ?>">
                                <?= htmlspecialchars($p['nombre']) ?> (Catálogo: Bs. <?= number_format($p['precio'], 2) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Preview dinámico al seleccionar -->
                    <div id="productPreviewBox" class="product-picker-preview" style="display: none;">
                        <img id="previewProductImg" src="../../public/uploads/default.jpg" alt="Preview" class="product-picker-thumb">
                        <div>
                            <strong id="previewProductName" style="color: #fff; font-size: 0.9rem;">Nombre Producto</strong>
                            <p id="previewProductPrice" style="color: var(--accent-gold); font-size: 0.8rem; margin-top: 2px;">Precio</p>
                        </div>
                    </div>
                </div>

                <!-- Precios -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="inputPrecioBase">Precio Base de Inicio (Bs) *</label>
                        <input type="number" step="0.50" min="1" name="precio_base" id="inputPrecioBase" class="form-control" placeholder="Ej: 50.00" required>
                    </div>

                    <div class="form-group">
                        <label for="inputIncremento">Incremento Mínimo (Bs) *</label>
                        <input type="number" step="1.00" min="1" name="incremento_minimo" id="inputIncremento" class="form-control" value="5.00" required>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="inputFechaInicio">Fecha y Hora de Inicio *</label>
                        <input type="datetime-local" name="fecha_inicio" id="inputFechaInicio" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="inputFechaFin">Fecha y Hora de Cierre *</label>
                        <input type="datetime-local" name="fecha_fin" id="inputFechaFin" class="form-control" required>
                    </div>
                </div>

                <!-- Presets rápidos de duración -->
                <div class="form-group">
                    <label style="margin-bottom: 4px;">Duración Rápida de Subasta</label>
                    <div class="quick-presets">
                        <button type="button" class="preset-chip" data-hours="1">+1 Hora</button>
                        <button type="button" class="preset-chip" data-hours="6">+6 Horas</button>
                        <button type="button" class="preset-chip" data-hours="12">+12 Horas</button>
                        <button type="button" class="preset-chip" data-hours="24">+24 Horas</button>
                        <button type="button" class="preset-chip" data-hours="72">+3 Días</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-close-modal>Cancelar</button>
                <button type="submit" class="btn-primary-action">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 14 14"></polyline>
                    </svg>
                    Publicar Subasta
                </button>
            </div>
        </form>
    </div>
</div>
