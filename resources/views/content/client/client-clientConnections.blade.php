@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Pages')

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">INFORMACION PERSONAL DEL CLIENTE / </span> ADMINISTRADOR
</h4>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item"><a class="nav-link" href="{{ route('client.status', $cliente->id) }}"><i
                        class="bx bx-user me-1"></i> Cliente</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.notification', $cliente->id) }}"><i
                        class="bx bx-bell me-1"></i> Notificacion</a></li>
            <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                        class="bx bx-link-alt me-1"></i> Reunion</a></li>
        </ul>
        <div class="row">
            <div class="col-md-6 col-12 mb-md-0 mb-4">
                <div class="card">
                    <h5 class="card-header" style="text-transform: uppercase;">{{ old('nombre', $cliente->nombre) }}
                    </h5>
                    <div class="card-body">
                        <p>INFORMACION DE CONCTACTO DE LOS CLIENTE</p>
                        <!-- Connections -->
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{asset('assets/img/icons/personal/gmail.png')}}" alt="google" class="me-3"
                                    height="30">
                            </div>
                            <div class="flex-grow-1 row">
                                <div class="col-9 mb-sm-0 mb-2">
                                    <h6 class="mb-0">CORREO</h6>
                                    <small class="text-muted"
                                        style="text-transform: uppercase;">{{ old('nombre', $cliente->email) }}</small>
                                </div>
                                <div class="col-3 text-end">
                                    <div class="form-check form-switch">
                                        <!-- <input class="form-check-input float-end" type="checkbox" role="switch"> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{asset('assets/img/icons/personal/telefono.png')}}" alt="slack" class="me-3"
                                    height="30">
                            </div>
                            <div class="flex-grow-1 row">
                                <div class="col-9 mb-sm-0 mb-2">
                                    <h6 class="mb-0">TELEFONO</h6>
                                    <small class="text-muted">{{ old('nombre', $cliente->telefono) }}</small>
                                </div>
                                <div class="col-3 text-end">
                                    <div class="form-check form-switch">
                                        <!-- <input class="form-check-input float-end" type="checkbox" role="switch" checked> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{asset('assets/img/icons/personal/user.png')}}" alt="github" class="me-3"
                                    height="30">
                            </div>
                            <div class="flex-grow-1 row">
                                <div class="col-9 mb-sm-0 mb-2">
                                    <h6 class="mb-0">SEGUIMIENTO</h6>
                                    <small class="text-muted"
                                        style="text-transform: uppercase;">{{ $cliente->user->name }}</small>
                                </div>
                                <div class="col-3 text-end">
                                    <div class="form-check form-switch">
                                        <!-- <input class="form-check-input float-end" type="checkbox" role="switch"> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{asset('assets/img/icons/personal/calen.png')}}" alt="mailchimp" class="me-3"
                                    height="30">
                            </div>
                            <div class="flex-grow-1 row">
                                <div class="col-9 mb-sm-0 mb-2">
                                    <h6 class="mb-0">REGISTRADO</h6>
                                    <small
                                        class="text-muted">{{ $cliente->fecharegistro ? \Carbon\Carbon::parse($cliente->fecharegistro)->format('d/m/Y') : 'No registrado' }}</small>
                                </div>
                                <div class="col-3 text-end">
                                    <div class="form-check form-switch">
                                        <!-- <input class="form-check-input float-end" type="checkbox" role="switch" checked> -->
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <img src="{{asset('assets/img/icons/personal/pote.png')}}" alt="asana" class="me-3"
                                    height="30">
                            </div>
                            <div class="flex-grow-1 row">
                                <div class="col-9 mb-sm-0 mb-2">
                                    <h6 class="mb-0">TIPO DE CLIENTE</h6>
                                    <small class="text-muted"
                                        style="text-transform: uppercase;">{{ old('nombre', $cliente->tipo_cliente) }}</small>
                                </div>
                                <div class="col-3 text-end">
                                    <div class="form-check form-switch">
                                        <!-- <input class="form-check-input float-end" type="checkbox" role="switch" checked> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Connections -->
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="card">
                    <h5 class="card-header">DETALLES DE REUNION</h5>
                    <div class="card-body">
                        <p>SE BRINDAN LOS DETALLES DE LA REUNION CON EL CLIENTE</p>
                        <!-- Social Accounts -->
                        @foreach($reuniones as $reunion)
                        @php
                        // Convertir la cadena de texto a un objeto Carbon
                        $fechaHora = \Carbon\Carbon::parse($reunion->fecha_hora);
                        @endphp

                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('assets/img/icons/reunion/calendario.png') }}" alt="facebook"
                                    class="me-3" height="30">
                            </div>
                            <div class="flex-grow-1 row">
                                <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                                    <h6 class="mb-0">FECHA</h6>
                                    <small class="text-muted">{{ $fechaHora->format('d/m/Y') }}</small>
                                </div>
                                <div class="col-4 col-sm-5 text-end">

                                    <button type="button" id="fecha-button{{ $cliente->id }}"
                                        class="btn btn-icon btn-outline-secondary" data-bs-toggle="modal"
                                        data-bs-target="#fechaModal{{ $cliente->id }}" title="Fecha y Hora">

                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('assets/img/icons/reunion/reloj.png') }}" alt="twitter" class="me-3"
                                    height="30">
                            </div>
                            <div class="flex-grow-1 row">
                                <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                                    <h6 class="mb-0">HORA</h6>
                                    <small class="text-muted">{{ $fechaHora->format('H:i') }}</small>
                                </div>
                                <div class="col-4 col-sm-5 text-end">

                                </div>
                            </div>
                        </div>

                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('assets/img/icons/reunion/tema.png') }}" alt="behance" class="me-3"
                                    height="30">
                            </div>
                            <div class="flex-grow-1 row">
                                <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                                    <h6 class="mb-0">TEMA</h6>
                                    <small class="text-muted">{{ $reunion->tema ?? 'No Disponible' }}</small>
                                </div>

                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{asset('assets/img/icons/reunion/encargado.png')}}" alt="instagram"
                                    class="me-3" height="30">
                            </div>
                            @foreach ($reuniones as $reunion)
                            <div class="flex-grow-1 row">
                                <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                                    <h6 class="mb-0">ENCARGADO</h6>
                                    <span style="text-transform: uppercase">
                                        {{ $reunion->user->name ?? 'No Disponible' }}
                                    </span>
                                </div>
                                <div class="col-4 col-sm-5 text-end">
                                    <!-- Puedes agregar aquí cualquier contenido adicional si es necesario -->
                                </div>
                            </div>
                            @endforeach

                        </div>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{asset('assets/img/icons/reunion/zoom.png')}}" alt="dribbble" class="me-3"
                                    height="30">
                            </div>
                            <div class="flex-grow-1 row">
                                <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                                    <h6 class="mb-0">ZOOM</h6>
                                    <a href="{{$reunion->zoom ?? 'No Disponible'}}"
                                        target="_blank">{{$reunion->zoom ?? 'No Disponible'}}</a>

                                </div>

                            </div>
                        </div>
                        @endforeach
                        <!-- Fecha y Hora -->
                        <div class="modal fade" id="fechaModal{{ $cliente->id }}" tabindex="-1"
                            aria-labelledby="fechaModalLabel{{ $cliente->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="fechaModalLabel{{ $cliente->id }}">Fecha y Hora</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('client.update-reunion', $reunion->id_reunion) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="fechaHora{{ $cliente->id }}" class="form-label">Fecha y
                                                    Hora</label>
                                                <input type="datetime-local" id="fechaHora{{ $cliente->id }}"
                                                    name="fecha_hora" class="form-control"
                                                    value="{{ $reunion->fecha_hora ? \Carbon\Carbon::parse($reunion->fecha_hora)->format('Y-m-d\TH:i') : '' }}"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tema{{ $cliente->id }}" class="form-label">Tema</label>
                                                <input type="text" id="tema{{ $cliente->id }}" name="tema"
                                                    class="form-control" value="{{$reunion->tema}}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="zoom{{ $cliente->id }}" class="form-label">Zoom</label>
                                                <input type="text" id="zoom{{ $cliente->id }}" name="zoom"
                                                    class="form-control" value="{{ $reunion->zoom }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="user{{ $reunion->id_reunion }}"
                                                    class="form-label">Encargado</label>
                                                <select id="user{{ $reunion->id_reunion }}" name="userid"
                                                    class="form-select">
                                                    <option value="">Selecciona un encargado</option>
                                                    @foreach ($usuarios as $usuario)
                                                    <option value="{{ $usuario->id }}"
                                                        {{ $reunion->userid == $usuario->id ? 'selected' : '' }}>
                                                        {{ $usuario->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Actualizar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>






                        <!-- /Social Accounts -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection