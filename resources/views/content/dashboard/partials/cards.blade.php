<style>
    .select2-container .select2-selection--single {
        height: 38px;
        padding: 6px 12px;
        border-radius: 0.375rem;
        border: 1px solid #d0d7de;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 24px;
        color: #495057;
        font-weight: 500;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>

<div class="row">
    <div class="col-lg-8 mb-4">
        <!-- ✅ TARJETA DE BIENVENIDA -->
        <div class="card mb-3"> <!-- usa mb-3 para separar las tarjetas -->
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Hola {{ Auth::user()->name }}! 🎉</h5>
                        <p class="mb-4">
                            El evento <span class="fw-medium" id="evento-seleccionado">No seleccionado</span>
                            tiene un total de <span class="fw-medium" id="total-clientes">0</span>
                            clientes registrados, con una meta de <span class="fw-medium" id="meta-registros">0</span>
                            registros.
                        </p>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="120"
                            alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png">
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ TARJETA COTIZACIONES VS MES ANTERIOR -->
        <div class="card shadow-sm mt-3">
            <div class="row g-0">
                <!-- 📊 Liquid Charts -->
                <div class="col-md-8 border-end">
                    <div class="card-body">
                        <h5 class="card-title text-primary mb-0">
                            <i class="fas fa-chart-line me-2"></i> Cotizaciones (Mes Actual vs Anterior)
                        </h5>
                        <div class="row text-center mt-2">
                            <!-- 💧 Mes Actual -->
                            <div class="col-md-6">
                                <h6 class="text-muted">Mes Actual</h6>
                                <div id="liquidMesActual" style="height: 200px;"></div>
                                <p class="fw-bold text-primary">S/ 12,200</p>
                            </div>
                            <!-- 💧 Mes Anterior -->
                            <div class="col-md-6">
                                <h6 class="text-muted">Mes Anterior</h6>
                                <div id="liquidMesAnterior" style="height: 200px;"></div>
                                <p class="fw-bold text-success">S/ 10,410</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 🔽 Selector de evento + resumen -->
                <div class="col-md-4 d-flex flex-column justify-content-between">
                    <div class="card-body">
                        <div class="mb-3 px-2">
                            <label for="selectEvento" class="form-label fw-bold text-primary">
                                <i class="fas fa-calendar-alt me-1"></i> Seleccione un evento
                            </label>
                            <select id="selectEvento" class="form-select select2 border-primary" style="width: 100%;">
                                <option value="">Seleccione un evento</option>
                                <option value="1">Evento A</option>
                                <option value="2">Evento B</option>
                                <option value="3">Evento C</option>
                            </select>
                        </div>



                        <!-- Espacio para mini chart u otro contenido -->
                        <div id="growthChart" style="height: 150px;"></div>
                    </div>

                    <!-- 📈 Comparación -->
                    <div class="bg-light text-center py-3 border-top">
                        <h6 class="mb-1 text-muted">Variación Mensual</h6>
                        <h3 class="fw-bold text-success">+17.2%</h3>
                        <p class="text-secondary small m-0">S/ 12,200 este mes vs S/ 10,410 anterior</p>
                    </div>
                </div>
            </div>
        </div>


        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title mb-4 text-primary">
                    <i class="fas fa-tachometer-alt me-2"></i> Indicadores de Seguimiento
                </h5>

                <div class="row text-center">
                    <!-- Clientes en Riesgo -->
                    <div class="col-12 col-md-3 mb-4 mb-md-0">
                        <div class="d-flex flex-column align-items-center">
                            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center mb-2"
                                style="width: 48px; height: 48px; cursor: pointer;" data-bs-toggle="modal"
                                data-bs-target="#modalClientesRiesgo">
                                <i class="fas fa-user-times"></i>
                            </div>
                            <strong>Clientes en Riesgo</strong>
                            <span class="text-muted small">Sin ningún contacto</span>
                        </div>
                    </div>

                    <!-- Promedio desde Registro -->
                    <div class="col-12 col-md-3 mb-4 mb-md-0">
                        <div class="d-flex flex-column align-items-center">
                            <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center mb-2"
                                style="width: 48px; height: 48px; cursor: pointer;" data-bs-toggle="modal"
                                data-bs-target="#modalPromedioRegistro">
                                <i class="fas fa-clock"></i>
                            </div>
                            <strong>Promedio desde Registro</strong>
                            <span class="text-muted small">Ej: 14 días</span>
                        </div>
                    </div>

                    <!-- Proceso Estancado -->
                    <div class="col-12 col-md-3 mb-4 mb-md-0">
                        <div class="d-flex flex-column align-items-center">
                            <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center mb-2"
                                style="width: 48px; height: 48px; cursor: pointer;" data-bs-toggle="modal"
                                data-bs-target="#modalProcesoEstancado">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <strong>Proceso Estancado</strong>
                            <span class="text-muted small">> 15 días sin interacción</span>
                        </div>
                    </div>

                    <!-- Interacción Completa -->
                    <div class="col-12 col-md-3">
                        <div class="d-flex flex-column align-items-center">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mb-2"
                                style="width: 48px; height: 48px; cursor: pointer;" data-bs-toggle="modal"
                                data-bs-target="#modalInteraccionCompleta">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <strong>Interacción Completa</strong>
                            <span class="text-muted small">Con todos los canales</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <!-- Top Clientes con Mayor Monto Cotizado -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-2 text-primary">
                            <i class="fas fa-user-tie me-2"></i> Top Clientes con Mayor Monto Cotizado
                        </h5>
                        <div id="line-race-clientes" style="height: 350px;"></div>
                    </div>
                </div>
            </div>

            <!-- Top Vendedores con Más Cotizaciones -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-2 text-primary">
                            <i class="fas fa-users me-2"></i> Top Vendedores con Más Cotizaciones
                        </h5>
                        <div id="line-race-vendedores" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Treemap: Monto Cotizado por Servicio -->
        <div class="card shadow-sm border-0 mt-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0 text-primary">
                        <i class="fas fa-project-diagram me-2"></i> Monto Cotizado por Servicio
                    </h5>
                    <span class="badge bg-light text-dark fw-normal">Actualizado al mes</span>
                </div>
                <div id="treemap-servicios" style="height: 370px;"></div>
            </div>
        </div>


        <!-- Line Chart: Promedio por Cotización -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0 text-primary">
                        <i class="fas fa-chart-line me-2"></i> Promedio por Cotización (Mensual)
                    </h5>

                    <select id="anio-promedio" class="form-select w-auto">
                        <option value="2025" selected>2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                    </select>
                </div>
                <div id="chart-promedio-cotizaciones" style="height: 350px;"></div>
            </div>
        </div>

        <div class="card mt-3 mb-3 shadow-sm border">
            <div class="card-body py-2 px-3">
                <p class="text-muted mb-0 small">
                    <i class="fas fa-bolt text-warning me-2"></i>
                    ¡Sigue así! Estás a solo <strong>5 registros</strong> de superar tu récord anterior.
                </p>
            </div>
        </div>

    </div>

    <!-- COLUMNA DERECHA (KPIs) -->
    <div class="col-lg-4 mb-4">
        @include('content.dashboard.partials.charts')
    </div>
</div>
@include('content.dashboard.partials.modals')
