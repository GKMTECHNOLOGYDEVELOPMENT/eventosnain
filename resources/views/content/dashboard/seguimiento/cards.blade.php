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

        <!-- ✅ TARJETA CLIENTES REGISTRADOS POR USUARIO -->
        <div class="card shadow-sm mt-3">
            <div class="row g-0">
                <!-- 📊 Gráfico dinámico -->
                <div class="col-md-8 border-end">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title text-primary mb-0">
                <i class="fas fa-user-plus me-2"></i> Clientes Registrados
            </h5>
            <div class="badge bg-light-primary rounded-pill">
                Total: <span id="cantidadClientes">0</span>
            </div>
        </div>
        <div id="chartClientesRegistrados" style="height: 250px;"></div>
        <div class="text-end mt-2">
            <small class="text-muted">Datos actualizados al: <?= date('d/m/Y') ?></small>
        </div>
    </div>
</div>

                <!-- 🔽 Filtros -->
                <div class="col-md-4 d-flex flex-column justify-content-between">
                    <div class="card-body">
                        <!-- Usuario -->
                        <div class="mb-3 px-2">
                            <label for="selectUsuario" class="form-label fw-bold text-primary">
                                <i class="fas fa-user me-1"></i> Seleccione un usuario
                            </label>
                            <select id="selectUsuario" class="form-select border-primary w-100">
                                <option value="">Seleccione...</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rango de fechas con Flatpickr -->
                        <div class="mb-3 px-2">
                            <label for="rangoFechas" class="form-label fw-bold text-primary">
                                <i class="fas fa-calendar me-1"></i> Rango de fechas
                            </label>
                            <input type="text" id="rangoFechas" class="form-control border-primary"
                                placeholder="Seleccione un rango">
                        </div>

                    </div>

                    <!-- 📈 Comparación ficticia -->
                    <div class="bg-light text-center py-3 border-top">
                        <h6 class="mb-1 text-muted">Clientes Registrados</h6>
                        <h3 class="fw-bold text-success">
                            <i class="fas fa-users me-1"></i>

                            <span id="totalClientes">0</span>
                        </h3>
                        <p class="text-secondary small m-0">Usuarios con contacto este mes</p>
                    </div>

                </div>
            </div>
        </div>

      <div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-4 text-primary">
            <i class="fas fa-chart-area me-2"></i> Evolución Comercial
        </h5>
        <div id="funnelEmbudoVentas" style="height: 380px;"></div>
    </div>
</div>

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title mb-4 text-primary">
                    <i class="fas fa-chart-area me-2"></i> Tiempo hasta Primer Contacto
                </h5>
                <div id="areaPrimerContacto" style="height: 400px;"></div>
            </div>
        </div>

    </div>

    <!-- COLUMNA DERECHA (KPIs) -->
    <div class="col-lg-4 mb-4">
        @include('content.dashboard.seguimiento.charts')
    </div>
</div>
@include('content.dashboard.seguimiento.modals')