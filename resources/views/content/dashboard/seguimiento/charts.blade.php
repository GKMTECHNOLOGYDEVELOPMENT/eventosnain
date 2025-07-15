<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-3 text-primary">
            <i class="fas fa-bullseye me-2"></i> Contactabilidad y Efectividad por Canal
        </h5>
        <div id="radarContactoEfectividad" style="height: 400px;"></div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-3 text-primary">
            <i class="fas fa-users me-2"></i> Estado de Clientes
        </h5>

        <div class="row text-center">
            <!-- Pendientes -->
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="p-3 border rounded shadow-sm bg-light-warning">
                    <h6 class="text-warning mb-1">
                        <i class="fas fa-clock me-1"></i> Pendientes
                    </h6>
                    <h2 class="fw-bold text-warning m-0" id="clientes-pendientes">0</h2>
                    <small class="text-muted">Clientes por atender</small>
                </div>
            </div>

            <!-- En Proceso -->
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="p-3 border rounded shadow-sm bg-light-primary">
                    <h6 class="text-primary mb-1">
                        <i class="fas fa-spinner me-1"></i> En Proceso
                    </h6>
                    <h2 class="fw-bold text-primary m-0" id="clientes-en-proceso">0</h2>
                    <small class="text-muted">Clientes en seguimiento</small>
                </div>
            </div>

            <!-- Completados -->
            <div class="col-md-4">
                <div class="p-3 border rounded shadow-sm bg-light-success">
                    <h6 class="text-success mb-1">
                        <i class="fas fa-check-circle me-1"></i> Completados
                    </h6>
                    <h2 class="fw-bold text-success m-0" id="clientes-completados">0</h2>
                    <small class="text-muted">Procesos finalizados</small>
                </div>
            </div>
        </div>

        <!-- Total (opcional) -->
        <div class="text-center mt-3">
            <small class="text-muted">Total de clientes: <span id="total-clientes-estados" class="fw-bold">0</span></small>
        </div>
    </div>
</div>


<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-3 text-primary">
            <i class="fas fa-user-clock me-2"></i> Clientes sin Seguimiento
        </h5>

        <div class="d-flex align-items-center justify-content-between p-3 border rounded bg-warning bg-opacity-10">
            <div class="d-flex align-items-center">
                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                    style="width: 48px; height: 48px;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h2 class="fw-bold text-warning mb-0" id="clientes-sin-seguimiento">23</h2>
                    <small class="text-muted">Clientes sin contacto en los últimos 7 días</small>
                </div>
            </div>
            <a href="#modalSeguimientoClientes" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal">
                Ver detalles
            </a>
        </div>
    </div>
</div>


<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-4 text-primary">
            <i class="fas fa-chart-bar me-2"></i> Distribución de Tipos de Cliente
        </h5>
        <div id="barTiposCliente" style="height: 300px;"></div>
    </div>
</div>


<div class="bg-white rounded-xl shadow-sm p-4 mt-4 w-full max-w-md">
    <div class="flex items-start gap-3">
        <div class="text-blue-500 mt-1">
            <i class="fas fa-info-circle" data-tooltip-target="tooltipPromedio" class="cursor-pointer"></i>
        </div>
        <div>
            <p class="text-sm text-gray-700">
                <strong>Promedio general:</strong>
                <span class="text-gray-600">2 días y 7 horas</span> desde el registro hasta el primer contacto.
            </p>
        </div>
    </div>
</div>

