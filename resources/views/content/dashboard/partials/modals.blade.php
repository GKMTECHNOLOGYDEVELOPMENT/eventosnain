<div class="modal fade" id="modalClientesInteraccion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clientes con Interacción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="tabla-clientes-interaccion">
                    <p class="text-center text-muted">Cargando clientes...</p>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal: Clientes en Riesgo -->
<div class="modal fade" id="modalClientesRiesgo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clientes en Riesgo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Listado de clientes sin ningún contacto registrado.</p>
                <div id="tabla-clientes-riesgo">Cargando clientes...</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Promedio desde Registro -->
<div class="modal fade" id="modalPromedioRegistro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Promedio desde Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Días promedio desde el registro de cada cliente.</p>
                <div id="tabla-promedio-registro">Cargando información...</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Proceso Estancado -->
<div class="modal fade" id="modalProcesoEstancado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clientes con Proceso Estancado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Clientes sin interacción por más de 15 días desde el registro.
                </p>
                <div id="tabla-proceso-estancado">Cargando clientes...</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Interacción Completa -->
<div class="modal fade" id="modalInteraccionCompleta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clientes con Interacción Completa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Clientes que han pasado por llamada, WhatsApp, reunión y
                    contrato.</p>
                <div id="tabla-interaccion-completa">Cargando clientes...</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: cotizacion -->
<div class="modal fade" id="modalCotizaciones" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clientes por Estado de Cotización</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="tabla-clientes-cotizaciones">
                <!-- Se rellena por JS -->
            </div>
        </div>
    </div>
</div>
