<div class="row">
    <!-- Clientes Registrados -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 p-3">
            <div class="card-body text-center">
                <h6 class="text-primary mb-2">Clientes registrados</h6>
                <h2 class="text-primary mb-3" id="total-clientes-kpi">142</h2>
                <div id="total-clientes-chart" style="width: 100%; "></div>
            </div>
        </div>
    </div>

    <!-- Cotizaciones Emitidas -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 p-3">
            <div class="card-body text-center">
                <h6 class="text-info mb-2">Cotizaciones emitidas</h6>
                <h2 class="text-info mb-3" id="total-cotizaciones-kpi">128</h2>
                <div id="total-cotizaciones-chart" style="width: 100%; "></div>
            </div>
        </div>
    </div>

    <!-- Tasa de Éxito -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 p-3">
            <div class="card-body text-center">
                <h6 class="text-success mb-2">Tasa de Éxito (%)</h6>
                <h2 class="text-success mb-3" id="tasa-exito-kpi">61%</h2>
                <div id="tasa-exito-chart" style="width: 100%; "></div>
            </div>
        </div>
    </div>

    <!-- Cotizaciones Vencidas -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 p-3">
            <div class="card-body text-center">
                <h6 class="text-danger mb-2">Cotizaciones vencidas</h6>
                <h2 class="text-danger mb-3" id="cotizaciones-vencidas-kpi">14</h2>
                <div id="cotizaciones-vencidas-chart" style="width: 100%; "></div>
            </div>
        </div>
    </div>
</div>


<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-2 text-primary">
            <i class="fas fa-bullseye me-2"></i> Tasa de Seguimiento Activo
        </h5>

        <div class="d-flex align-items-center mb-3">
            <h2 class="mb-0 text-success me-2" id="seguimiento-activo-kpi">45%</h2>
            <span class="text-muted">Clientes con al menos una interacción</span>
        </div>

        <div id="seguimiento-activo-chart" style="height: 250px;"></div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-4">Canales de Contacto Más Usados</h5>

        <div class="row text-center">
            <!-- Llamada -->
            <div class="col-12 col-sm-4 mb-4 mb-sm-0">
                <div class="d-flex flex-column align-items-center">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mb-2"
                        style="width: 48px; height: 48px;">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <strong>Llamadas</strong>
                    <span class="text-muted small">58% de interacciones</span>
                </div>
            </div>

            <!-- WhatsApp -->
            <div class="col-12 col-sm-4 mb-4 mb-sm-0">
                <div class="d-flex flex-column align-items-center">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mb-2"
                        style="width: 48px; height: 48px;">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <strong>WhatsApp</strong>
                    <span class="text-muted small">30% de interacciones</span>
                </div>
            </div>

            <!-- Correo -->
            <div class="col-12 col-sm-4">
                <div class="d-flex flex-column align-items-center">
                    <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center mb-2"
                        style="width: 48px; height: 48px;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <strong>Correo Electrónico</strong>
                    <span class="text-muted small">12% de interacciones</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-2 text-primary">
            <i class="fas fa-chart-bar me-2"></i> Comparación Semana Actual vs Anterior
        </h5>

        <div class="d-flex align-items-center mb-3">
            <span class="text-muted">Mide si estás captando más o menos clientes respecto a la semana pasada</span>
        </div>

        <div id="comparacion-semanal-chart" style="height: 300px;"></div>
    </div>
</div>

<!-- Estado de Cotizaciones -->
<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-3 text-primary">
            <i class="fas fa-balance-scale me-2"></i> Estado de Cotizaciones
        </h5>
        <div id="estado-cotizaciones-chart" style="height: 320px;"></div>
    </div>
</div>
