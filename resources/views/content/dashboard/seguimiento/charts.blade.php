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
            <div class="col-md-6">
                <div class="p-3 border rounded shadow-sm bg-light">
                    <h6 class="text-success mb-1"><i class="fas fa-user-check me-1"></i> Activos</h6>
                    <h2 class="fw-bold text-success m-0" id="clientes-activos">152</h2>
                    <small class="text-muted">Clientes en seguimiento</small>
                </div>
            </div>

            <div class="col-md-6 mt-3 mt-md-0">
                <div class="p-3 border rounded shadow-sm bg-light">
                    <h6 class="text-danger mb-1"><i class="fas fa-user-times me-1"></i> Inactivos</h6>
                    <h2 class="fw-bold text-danger m-0" id="clientes-inactivos">48</h2>
                    <small class="text-muted">Sin interacción reciente</small>
                </div>
            </div>
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

