<?php
/**
 * views/admin/subastador/modal_extender.php
 * Modal para extender la duración de una subasta activa
 */
?>
<div id="modalExtenderTiempo" class="modal-backdrop">
    <div class="modal-dialog" style="max-width: 480px;">
        <div class="modal-header">
            <h3>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 14 14"></polyline>
                </svg>
                Extender Tiempo de Subasta
            </h3>
            <button type="button" class="btn-close-modal" data-close-modal aria-label="Cerrar">&times;</button>
        </div>

        <form action="subastador.php" method="POST" id="formExtenderTiempo">
            <input type="hidden" name="accion" value="extender_tiempo">
            <input type="hidden" name="id_subasta" id="extenderIdSubasta" value="">

            <div class="modal-body">
                <p style="color: var(--text-secondary); font-size: 0.88rem; margin-bottom: 16px;">
                    Ajusta la nueva fecha y hora límite para dar más tiempo a los postores en esta subasta.
                </p>

                <div class="form-group">
                    <label for="inputExtenderFecha">Nueva Fecha y Hora de Cierre *</label>
                    <input type="datetime-local" name="nueva_fecha_fin" id="inputExtenderFecha" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-close-modal>Cancelar</button>
                <button type="submit" class="btn-primary-action" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff;">
                    Guardar Extensión
                </button>
            </div>
        </form>
    </div>
</div>
