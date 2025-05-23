@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Pages')

@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">DETALLES /</span> LEVANTAMIENTO DE INFORMACION
</h4>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.proceso', $cliente->id) }}">
                    <i class="bx bx-user me-1"></i> CLIENTE
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="javascript:void(0);">
                    <i class="bx bx-bell me-1"></i> LEVANTAMIENTO DE INFORMACION
                </a>
            </li>
           
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.cotizaciones', $cliente->id) }}">
                    <i class="bx bx-link-alt me-1"></i> COTIZACIONES
                </a>
            </li>
           
        </ul>

        <!-- Botón para abrir el modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
            Editar Información
        </button>

        <div class="row">
            <!-- Información Básica -->
            <div class="col-md-6">
                <div class="card mb-4">
                    @foreach ($informacion as $info)
                    <h5 class="card-header" style="text-transform: uppercase;">
                        TECNICO : {{ $usuarios->firstWhere('id', $info->users_id)->name ?? 'No asignado' }}
                    </h5>
                    @endforeach
                    <div class="card-body demo-vertical-spacing demo-only-element">
                        @foreach ($informacion as $info)
                        <!-- Display Address -->
                        <div class="input-group mb-3">
                            <span class="input-group-text">DIRRECCION:</span>
                            <label class="form-control"
                                style="text-transform: uppercase;">{{ $info->dirrecion }}</label>
                        </div>

                        <!-- Display Date -->
                        <div class="input-group mb-3">
                            <span class="input-group-text">FECHA:</span>
                            <label class="form-control">{{ $info->fecha->format('Y-m-d') }}</label>
                        </div>

                        <!-- Display Encargado -->
                        <div class="input-group mb-3">
                            <span class="input-group-text">SEGUIMIENTO POR:</span>
                            <label class="form-control"
                                style="text-transform: uppercase;">{{ $cliente->user->name }}</label>
                        </div>

                        <!-- Display Cliente ID -->
                        <div class="input-group mb-3">
                            <span class="input-group-text">CLIENTE :</span>
                            <label class="form-control"
                                style="text-transform: uppercase;">{{ old('nombre', $cliente->nombre) }}</label>
                        </div>
                        <!-- Campo para Observacion -->
                        <div class="input-group mb-3">
                            <span class="input-group-text">OBSERVACION:</span>
                            <input type="text" name="observacion" class="form-control"
                                style="text-transform: uppercase;" value="{{ old('observacion', $info->observacion) }}"
                                required>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>




            <!-- Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Actualizar Información</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('actualizarInformacion') }}" method="POST">
                                @csrf
                                @method('PUT')


                                <!-- Campo para Dirección -->
                                <div class="input-group mb-3">
                                    @foreach ($informacion as $info)
                                    <span class="input-group-text">DIRECCIÓN:</span>
                                    <input type="text" name="dirrecion" class="form-control"
                                        style="text-transform: uppercase;"
                                        value="{{ old('dirrecion', $info->dirrecion) }}" required>
                                </div>
                                <!-- Campo para Observacion -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text">OBSERVACION:</span>
                                    <input type="text" name="observacion" class="form-control"
                                        style="text-transform: uppercase;"
                                        value="{{ old('observacion', $info->observacion) }}" required>
                                </div>



                                <!-- Campo para Fecha -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text">FECHA:</span>
                                    <input type="datetime-local" name="fecha" class="form-control"
                                        value="{{ old('fecha', $info->fecha->format('Y-m-d\TH:i')) }}" required>
                                </div>


                                <!-- Campo para Técnico -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text">TÉCNICO:</span>
                                    <select name="users_id" class="form-select" required>
                                        @foreach ($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}"
                                            {{ $usuario->id == $info->users_id ? 'selected' : '' }}>
                                            {{ $usuario->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>


                                <!-- Campo para Cliente (asumimos que es un campo de texto) -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text">CLIENTE:</span>
                                    <input type="text" name="cliente" class="form-control"
                                        style="text-transform: uppercase;"
                                        value="{{ old('cliente', $cliente->nombre) }}" required>
                                </div>

                                <!-- Hidden Field for ID -->
                                <input type="hidden" name="id_informacion" value="{{ $info->id_informacion }}">
                                 <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach




            <!-- Formulario para Observaciones -->
            <div class="col-md-6">
                <form action="{{ route('client.atencion') }}" method="POST">
                    @csrf
                    <div class="card mb-4">
                        <h5 class="card-header">Observaciones de Atención</h5>
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <!-- Campo para la fecha -->
                            <div class="input-group input-group-merge mb-3">
                                <span class="input-group-text" id="basic-addon-date"><i
                                        class="bx bx-calendar"></i></span>
                                <input type="date" name="fecha" class="form-control" placeholder="Fecha"
                                    aria-label="Fecha" aria-describedby="basic-addon-date" required />
                            </div>

                            <!-- Campo para la conclusión -->
                            <div class="input-group input-group-merge mb-3">
                                <span class="input-group-text" id="basic-addon-conclusion"><i
                                        class="bx bx-note"></i></span>
                                <textarea name="conclusion" class="form-control" placeholder="Conclusión"
                                    aria-label="Conclusión" aria-describedby="basic-addon-conclusion" rows="4"
                                    required></textarea>
                            </div>

                            <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">

                            <!-- Botón para enviar el formulario -->
                            <button type="submit" class="btn btn-primary mt-3">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>










            <div class="card">
                @foreach ($informacion as $info)
                <h5 class="card-header" style="text-transform: uppercase;">
                    TECNICO : {{ $usuarios->firstWhere('id', $info->users_id)->name ?? 'No asignado' }}
                </h5>
                @endforeach

                <div class="card-body">
                    <span>EN ESTA SE VISUALIZAN LAS OBSERVACIONES REALIZADAS POR EL TECNICO</span>
                    <div class="error"></div>
                </div>



                <!-- Nueva sección para la tabla de información -->
                <div class="table-responsive">
                    <table class="table table-striped table-borderless border-bottom">
                        <thead>
                            <tr>
                                <th class="text-nowrap">CONCLUSION</th>
                                <th class="text-nowrap text-center">Fecha</th>
                                <th class="text-nowrap text-center">TECNICO</th>
                                <th class="text-nowrap text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($atencion as $aten)
                            <tr>
                                <td class="text-nowrap">{{ $aten->conclusion }}</td>
                                <td class="text-nowrap text-center">{{ $aten->fecha->format('Y-m-d') }}</td>
                                <td class="text-nowrap text-center">
                                    {{ $usuarios->firstWhere('id', $info->users_id)->name ?? 'No asignado' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>




            </div>
        </div>
    </div>
    @endsection