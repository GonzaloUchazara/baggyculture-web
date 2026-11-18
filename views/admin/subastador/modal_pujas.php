<?php
/**
 * views/admin/subastador/modal_pujas.php
 * Modal para visualizar el historial y postores de una subasta específica
 */
?>
<div id="modalHistorialPujas" class="modal-backdrop">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 id="modalPujasTitle">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                Historial de Ofertas
            </h3>
            <button type="button" class="btn-close-modal" data-close-modal aria-label="Cerrar">&times;</button>
        </div>

        <div class="modal-body">
            <div id="bidsLoading" style="text-align: center; padding: 25px; color: var(--text-muted);">
                <div class="pulse-dot" style="margin: 0 auto 10px;"></div>
                <span>Cargando ofertas registradas...</span>
            </div>

            <div id="bidsListContainer"></div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-secondary" data-close-modal>Cerrar</button>
        </div>
    </div>
</div>
