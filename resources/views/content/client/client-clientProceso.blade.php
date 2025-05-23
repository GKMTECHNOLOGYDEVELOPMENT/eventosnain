@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{ asset('assets/js/pages-account-settings-account.js') }}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Detalle /</span> Clientes
</h4>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i>
                    CLIENTE</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.informacion', $cliente->id) }}"><i
                        class="bx bx-bell me-1"></i> LEVANTAMIENTO DE INFORMACION</a></li>

         
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.cotizaciones', $cliente->id) }}">
                    <i class="bx bx-link-alt me-1"></i> COTIZACIONES
                </a>
            </li>
            
        </ul>

        <div class="card mb-4">
            <h5 class="card-header">{{ old('nombre', $cliente->nombre) }}</h5>
            <!-- Account -->
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

                    <div class="button-wrapper">
                        <!-- Imprime la URL para depuración
                        <p class="text-muted mb-0">Foto actual:</p> -->

                    </div>
                </div>
            </div>

            <hr class="my-0">
            <div class="card-body">
                <!-- Campos del formulario como etiquetas -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="nombre">Nombre</label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext">{{ old('nombre', $cliente->nombre) }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="empresa">Empresa</label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext">{{ old('empresa', $cliente->empresa) }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 form-label" for="telefono">Telefono No</label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext">{{ old('telefono', $cliente->telefono) }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="email">Email</label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext">{{ old('email', $cliente->email) }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="tipo_cliente">Tipo Cliente</label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext">{{ old('tipo_cliente', $cliente->tipo_cliente) }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="servicios">Servicios</label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext">{{ old('servicios', $cliente->servicios) }}</p>
                    </div>
                </div>

            </div>
            <!-- /Account -->
        </div>

    </div>
</div>
@endsection