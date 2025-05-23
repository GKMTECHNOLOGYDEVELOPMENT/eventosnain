@extends('layouts/contentNavbarLayout')

@section('title', 'Lista de Clientes')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Clientes/</span> Lista de Clientes</h4>
<!-- Basic Table -->
<div class="card mb-2">
    

    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Empresa</th>
                    <th>Telefono</th>
                    <th>Servicios</th>
                    <th>EVENTO</th>
                    <th>LEVANTAMIENTO <br> INFORMACION</th>
                    <th>Tecnico</th>
                    <th>Cotizacion</th>
                    <th>CONTRATO</th>

                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($clientes as $index => $cliente)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->empresa }}</td>
                    <td>{{ $cliente->telefono }}</td>
                    <td>{{ $cliente->servicios }}</td>
                    <td>
                        @if ($cliente->events_id)
                        {{ $eventos[$cliente->events_id] ?? 'EXPO MINAS' }}
                        @else
                        Sin evento asignado
                        @endif
                    </td>

                    <!-- Badge for Status -->
                    <td>
                        @if ($cliente->levantamiento == 'PENDIENTE')
                        <span class="badge bg-danger">Pendiente</span>
                         @elseif ($cliente->levantamiento == 'REPROGRAMADO')
                        <span class="badge bg-warning">REPROGRAMADO</span>
                        @elseif ($cliente->levantamiento == 'REALIZADO')
                        <span class="badge bg-success">REALIZADO</span>
                        @endif
                    </td>
                    <td>
                        @if ($cliente->tecnico == 'ASIGNADO')
                        <span class="badge bg-success">{{ $cliente->tecnico }}</span>
                        @else
                        <span class="badge bg-danger">{{ $cliente->tecnico ?: 'NO ASIGNADO' }}</span>
                        @endif
                    </td>

                    <td>
                        @if ($cliente->cotizacion == 'PENDIENTE')
                        <span class="badge bg-danger">Pendiente</span>
                        @elseif ($cliente->cotizacion == 'Realizado')
                        <span class="badge bg-success">REALIZADO</span>
                        @endif
                    </td>
                    <td>
                        @if ($cliente->contrato == 'PENDIENTE')
                        <span class="badge bg-danger">Pendiente</span>
                        @elseif ($cliente->contrato == 'REALIZADO')
                        <span class="badge bg-success">REALIZADO</span>
                        @endif
                    </td>
                    <td>
                        @php
                        $registroExiste = \App\Models\Informacion::where('cliente_id', $cliente->id)->exists();
                        @endphp

                        <!-- Button to Open the Contract Status Modal -->
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#contractStatusModal{{ $cliente->id }}" title="Cambiar Estado de Contrato">
                            <i class="fa-solid fa-money-bill"></i>
                        </button>

                        <!-- Button to Open the Status Modal -->
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#statusModal{{ $cliente->id }}" title="Estado" @if($registroExiste) disabled
                            @endif>
                            <i class="fa-solid fa-gear"></i>
                        </button>

                        <a href="{{ route('client.proceso', $cliente->id) }}" class="btn btn-secondary btn-sm"
                            title="Detalles">
                            <i class="fas fa-file-alt"></i>
                        </a>

                        @if (auth()->user()->rol_id == 1)
                        <form action="{{ route('client.destroy', $cliente->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">No hay clientes registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Template for Changing Contract Status -->
@foreach ($clientes as $cliente)
<div class="modal fade" id="contractStatusModal{{ $cliente->id }}" tabindex="-1"
    aria-labelledby="contractStatusModalLabel{{ $cliente->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contractStatusModalLabel{{ $cliente->id }}">Actualizar Estado de Contrato
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('client.updateContrato') }}" method="POST">
                    @csrf
                    <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">

                    <div class="mb-3">
                        <label for="contractStatus{{ $cliente->id }}" class="form-label">Estado del Contrato</label>
                        <select id="contractStatus{{ $cliente->id }}" name="contrato" class="form-select" required>
                            <option value="">Selecciona un estado</option>
                            <option value="PENDIENTE" @if($cliente->contrato == 'PENDIENTE') selected @endif>Pendiente
                            </option>
                            <option value="REALIZADO" @if($cliente->contrato == 'REALIZADO') selected @endif>Realizado
                            </option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Template for Status -->
<div class="modal fade" id="statusModal{{ $cliente->id }}" tabindex="-1"
    aria-labelledby="statusModalLabel{{ $cliente->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel{{ $cliente->id }}">LEVANTAMIENTO DE INFORMACION</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('client.updateproceso') }}" method="POST">
                    @csrf
                    <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">
                    <input type="hidden" name="users_id" value="{{ auth()->user()->id }}">

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
                        <label for="direccion{{ $cliente->id }}" class="form-label">Dirección</label>
                        <input type="text" id="direccion{{ $cliente->id }}" name="direccion" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="llamadaFecha{{ $cliente->id }}" class="form-label">Fecha de Atención</label>
                        <input type="datetime-local" id="llamadaFecha{{ $cliente->id }}" name="fecha_atencion"
                            class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection