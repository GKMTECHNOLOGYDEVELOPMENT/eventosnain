@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Pages')

@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Detalles /</span> Notificaciones
</h4>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.status', $cliente->id) }}">
                    <i class="bx bx-user me-1"></i> Cliente
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="javascript:void(0);">
                    <i class="bx bx-bell me-1"></i> Notificacion
                </a>
            </li>


          
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.connections', $cliente->id) }}">
                    <i class="bx bx-link-alt me-1"></i> Reunion
                </a>
            </li>
         




        </ul>
        <div class="card">
            <!-- Notifications -->
            <h5 class="card-header" style="text-transform: uppercase;">
                SEGUIMIENTO POR : {{ $cliente->user->name }}
            </h5>

            <div class="card-body">
                <span>EN ESTA PARTE SE MOSTRARAN EL SEGUIMIENTO DEL CLIENTE Y LLAMADAS




                    <span class="notificationRequest"><span class="fw-medium"></span></span>
                </span>
                <div class="error"></div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-borderless border-bottom">
                    <thead>
                        <tr>
                            <th class="text-nowrap">ACTIVIDAD</th>
                            <th class="text-nowrap text-center">✉️ Correo</th>
                            <th class="text-nowrap text-center">🖥 Whatsapp</th>
                            <th class="text-nowrap text-center">👩🏻‍💻 Llamada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-nowrap">ENVIO DE INFORMACION</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <span class="form-check-input" @if ($cliente->correo === 'SI') style="color: green;
                                        font-weight: bold;" @else style="color: grey;" @endif>✔</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <span class="form-check-input" @if ($cliente->whatsapp === 'SI') style="color:
                                        green; font-weight: bold;" @else style="color: grey;" @endif>✔</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <span class="form-check-input" @if ($cliente->llamada === 'SI') style="color: green;
                                        font-weight: bold;" @else style="color: grey;" @endif>✔</span>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Tabla de llamadas -->
            <div class="table-responsive">
                <table class="table table-striped table-borderless border-bottom">
                    <thead>
                        <tr>
                            <th class="text-nowrap">SEGUIMIENTO</th>
                            <th class="text-nowrap text-center">🖥 Llamada</th>
                            <th class="text-nowrap text-center">🖥 Fecha</th>
                            <th class="text-nowrap text-center">👩🏻‍💻 Observación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($llamadas as $llamada)
                        <tr>
                            <td class="text-nowrap">LLAMADA AL CLIENTE</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <span class="form-check-input" @if ($llamada->cliente_id === $cliente->id)
                                        style="color: green; font-weight: bold;" @else style="color: grey;"
                                        @endif>✔</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <span>{{ $llamada->date->format('Y-m-d') }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center" style="text-transform: uppercase;">
                                    <span>{{ $llamada->observaciones }}</span>
                                </div>
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="table-responsive">
                <table class="table table-striped table-borderless border-bottom">
                    <thead>
                        <tr>
                            <th class="text-nowrap">Observaciones</th>

                            <th class="text-nowrap text-center">🖥 LLAMADA</th>
                            <th class="text-nowrap text-center">🖥 Fecha LLAMADA</th>
                            <th class="text-nowrap text-center">👩🏻‍💻 REUNION</th>
                            <th class="text-nowrap text-center">👩🏻‍💻 Fecha REUNION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-nowrap">REUNION O LLAMADA</td>
                            <!-- <td>
                                <div class="form-check d-flex justify-content-center"
                                    style="text-transform: uppercase;">
                                    @if ($cliente)
                                    {{ $cliente->status }}
                                    @else
                                    Status desconocido
                                    @endif
                                </div>

                            </td> -->
                            <td>
                                <div class="d-flex justify-content-center">
                                    <span
                                        style="text-transform: uppercase;">{{ $observaciones ? $observaciones->observacionllamada : 'No hay observación' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <span
                                        style="text-transform: uppercase;">{{ $observaciones ? $observaciones->fechallamada : 'No hay observación' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <span
                                        style="text-transform: uppercase;">{{ $observaciones ? $observaciones->observacionreunion : 'No hay observación' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <span
                                        style="text-transform: uppercase;">{{ $observaciones ? $observaciones->fechareunion : 'No hay observación' }}</span>
                                </div>
                            </td>



                        </tr>
                    </tbody>
                </table>
            </div>



            <div class="card-body">

                <form action="{{ route('updateContractStatus', $cliente->id) }}" method="POST">
                    <h6>SE CONTINUARA CON EL PROCESO?</h6>
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <select id="sendNotification" class="form-select" name="sendNotification">
                                <option value="PENDIENTE"
                                    {{ old('sendNotification', $cliente->proceso) === 'PENDIENTE' ? 'selected' : '' }}>
                                    PENDIENTE
                                </option>
                                <option value="SI"
                                    {{ old('sendNotification', $cliente->proceso) === 'SI' ? 'selected' : '' }}>SI
                                </option>
                                <option value="NO"
                                    {{ old('sendNotification', $cliente->proceso) === 'NO' ? 'selected' : '' }}>NO
                                </option>
                                 <option value="REVISION"
                                    {{ old('sendREVISIONtification', $cliente->proceso) === 'REVISION' ? 'selected' : '' }}>REVISION
                                </option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">CONTINUAR PROCESO</button>
                            <button type="reset" class="btn btn-outline-secondary">CANCELAR</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- <div class="card-body">
                <h6>CLIENTE DESEA CONTINUAR CON EL PROCESO DE LEVANTAMIENTO Y COTIZACION ?</h6>
                <form action="{{ route('updateContractStatus', $cliente->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <select id="sendNotificationes" class="form-select" name="sendNotificationes">
                                <option value="PENDIENTE"
                                    {{ old('sendNotificationes', $cliente->contrato) === 'PENDIENTE' ? 'selected' : '' }}>
                                    PENDIENTE
                                </option>
                                <option value="SI"
                                    {{ old('sendNotificationes', $cliente->contrato) === 'SI' ? 'selected' : '' }}>SI
                                </option>
                                <option value="NO"
                                    {{ old('sendNotificationes', $cliente->contrato) === 'NO' ? 'selected' : '' }}>NO
                                </option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">FINALIZAR PROCESO</button>
                            <button type="reset" class="btn btn-outline-secondary">CANCELAR</button>
                        </div>
                    </div>
                </form>
            </div> -->

            <!-- /Notifications -->
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">LEVANTAMIENTO DE INFORMACION</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label for="user{{ $cliente->id }}" class="form-label">Encargado</label>
                    <select id="user{{ $cliente->id }}" name="userid" class="form-select">
                        <option value="">Selecciona un encargado</option>
                        @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="dirrecion{{ $cliente->id }}" class="form-label">dirrecion</label>
                    <input type="text" id="dirrecion{{ $cliente->id }}" name="dirrecion" class="form-control" rows="3">
                </div>
                <div class="mb-3">
                    <label for="llamadaFecha{{ $cliente->id }}" class="form-label">Fecha de
                        Atencion</label>
                    <input type="date" id="llamadaFecha{{ $cliente->id }}" name="date" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmProceed">Confirmar</button>
            </div>
        </div>
    </div>
</div>

@endsection


<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.getElementById('sendNotificationes');
    const modal = new bootstrap.Modal(document.getElementById('notificationModal'));

    selectElement.addEventListener('change', function() {
        if (selectElement.value === 'SI') {
            modal.show();
        }
    });

    document.getElementById('confirmProceed').addEventListener('click', function() {
        document.querySelector('form').submit();
    });
});
</script>