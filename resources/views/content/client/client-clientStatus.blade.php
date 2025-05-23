@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Detalle /</span> Clientes
</h4>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i>
                    Cliente</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.notification', $cliente->id) }}"><i
                        class="bx bx-bell me-1"></i> Notificacion</a></li>

            @php
            // Verifica si hay reuniones para el cliente actual
            $reunionesCliente = $reuniones->where('cliente_id', $cliente->id);
            @endphp

            @if ($reunionesCliente->isNotEmpty())
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.connections', $cliente->id) }}">
                    <i class="bx bx-link-alt me-1"></i> Reunion
                </a>
            </li>
            @endif





        </ul>
        <div class="card mb-4">
            <h5 class="card-header">{{ old('nombre', $cliente->nombre) }}</h5>
            <!-- Account -->
            <form action="{{ route('client.uploadPhoto', $cliente->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Tu código para la subida de fotos aquí -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        @php
                        // Verifica si el cliente tiene una foto
                        $photoUrl = $cliente->photo
                        ? asset('storage/avatars/' . $cliente->photo)
                        : asset('assets/img/avatars/7.png'); // Ruta a la imagen predeterminada
                        @endphp

                        <img src="{{ $photoUrl }}" alt="user-avatar" class="d-block rounded" height="100" width="100"
                            id="uploadedAvatar" />



                        <!-- Imprime la URL para depuración -->




                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Subir Foto</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" name="photo" class="account-file-input" hidden
                                    accept="image/png, image/jpeg" />
                            </label>

                            <button type="submit" class="btn btn-primary mb-4 me-2">Guardar</button>

                            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Limpiar</span>
                            </button>

                            <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                        </div>
                    </div>
                </div>

            </form>

            <hr class="my-0">
            <div class="card-body">
                <form action="{{ route('client.update', $cliente->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <!-- Campos del formulario -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="nombre">Nombre</label>
                        <div class="col-sm-10">
                            <input type="text" name="nombre" class="form-control" id="nombre"
                                value="{{ old('nombre', $cliente->nombre) }}" placeholder="John Doe" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="empresa">Empresa</label>
                        <div class="col-sm-10">
                            <input type="text" name="empresa" class="form-control" id="empresa"
                                value="{{ old('empresa', $cliente->empresa) }}" placeholder="ACME Inc." />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 form-label" for="telefono">Telefono No</label>
                        <div class="col-sm-10">
                            <input type="text" name="telefono" class="form-control" id="telefono"
                                value="{{ old('telefono', $cliente->telefono) }}" placeholder="658 799 8941" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="email">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control" id="email"
                                value="{{ old('email', $cliente->email) }}" placeholder="john.doe@example.com" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="tipo_cliente">Tipo Cliente</label>
                        <div class="col-sm-10">
                            <select name="tipo_cliente" class="form-control" id="tipo_cliente">
                                <option value="Intermedio"
                                    {{ $cliente->tipo_cliente == 'Intermedio' ? 'selected' : '' }}>Intermedio</option>
                                <option value="Potencial" {{ $cliente->tipo_cliente == 'Potencial' ? 'selected' : '' }}>
                                    Potencial</option>
                                <option value="Indeciso" {{ $cliente->tipo_cliente == 'Indeciso' ? 'selected' : '' }}>
                                    Indeciso</option>
                                <option value="Potencial" {{ $cliente->tipo_cliente == 'Potencial' ? 'selected' : '' }}>
                                    Potencial</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="servicios">Servicios</label>
                        <div class="col-sm-10">
                            <select name="servicios" class="form-control" id="servicios">
                                <option value="CCTV" {{ $cliente->servicios == 'CCTV' ? 'selected' : '' }}>CCTV</option>
                                <option value="MODULO" {{ $cliente->servicios == 'MODULO' ? 'selected' : '' }}>MÓDULO
                                </option>
                                <option value="SOFTWARE" {{ $cliente->servicios == 'SOFTWARE' ? 'selected' : '' }}>
                                    SOFTWARE</option>
                                <option value="SOPORTE TI" {{ $cliente->servicios == 'SOPORTE TI' ? 'selected' : '' }}>
                                    SOPORTE TI</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 form-label" for="mensaje">Mensaje</label>
                        <div class="col-sm-10">
                            <textarea name="mensaje" class="form-control" id="mensaje"
                                placeholder="Hi, Do you have a moment to talk Joe?">{{ old('mensaje', $cliente->mensaje) }}</textarea>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
        <!-- <div class="card">
            <h5 class="card-header">Delete Account</h5>
            <div class="card-body">
                <div class="mb-3 col-12 mb-0">
                    <div class="alert alert-warning">
                        <h6 class="alert-heading fw-medium mb-1">Are you sure you want to delete your account?</h6>
                        <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                    </div>
                </div>
                <!-- <form id="formAccountDeactivation" onsubmit="return false">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="accountActivation"
                            id="accountActivation" />
                        <label class="form-check-label" for="accountActivation">I confirm my account
                            deactivation</label>
                    </div>
                    <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account</button>
                </form> -->
    </div>
</div>
</div>
</div>
@endsection