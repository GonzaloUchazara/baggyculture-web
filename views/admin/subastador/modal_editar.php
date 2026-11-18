<?php
/**
 * views/admin/subastador/modal_editar.php
 * Modal para editar parámetros de una subasta existente
 */
?>
<div id="modalEditarSubasta" class="modal-backdrop">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Editar Subasta <span id="labelEditId" style="color: var(--accent-gold);"></span>
            </h3>
            <button type="button" class="btn-close-modal" data-close-modal aria-label="Cerrar">&times;</button>
        </div>

        <form action="subastador.php" method="POST" id="formEditarSubasta">
            <input type="hidden" name="accion" value="editar_subasta">
            <input type="hidden" name="id_subasta" id="editIdSubasta" value="">

            <div class="modal-body">
                <p id="editProductoNombre" style="color: #fff; font-weight: 700; margin-bottom: 16px; font-size: 0.95rem;"></p>

                <!-- Precios -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="editPrecioBase">Precio Base (Bs) *</label>
                        <input type="number" step="0.50" min="1" name="precio_base" id="editPrecioBase" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="editIncremento">Incremento Mínimo (Bs) *</label>
                        <input type="number" step="1.00" min="1" name="incremento_minimo" id="editIncremento" class="form-control" required>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="editFechaInicio">Fecha y Hora de Inicio *</label>
                        <input type="datetime-local" name="fecha_inicio" id="editFechaInicio" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="editFechaFin">Fecha y Hora de Cierre *</label>
                        <input type="datetime-local" name="fecha_fin" id="editFechaFin" class="form-control" required>
                    </div>
                </div>

                <!-- Estado -->
                <div class="form-group">
                    <label for="editEstado">Estado de la Subasta *</label>
                    <select name="estado" id="editEstado" class="form-control" required>
                        <option value="activa">Activa (En Vivo)</option>
                        <option value="finalizada">Finalizada (Cerrada)</option>
                        <option value="cancelada">Cancelada (Anulada)</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-close-modal>Cancelar</button>
                <button type="submit" class="btn-primary-action" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff;">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
