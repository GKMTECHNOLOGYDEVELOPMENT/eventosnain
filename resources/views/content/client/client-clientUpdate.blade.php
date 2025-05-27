@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Cliente - Formulario')

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif


@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif


<h4 class="py-3 mb-4"><span class="text-muted fw-light">Formulario/</span> Editar Cliente</h4>

<!-- Basic Layout & Basic with Icons -->
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Formulario</h5> <small class="text-muted float-end">Editar Cliente</small>
            </div>
            <div class="card-body">
                <!-- Cambiar el action a la ruta para el método update -->
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
                    <!-- Número de Documento -->
                    <div class="row mb-3">
                        <label for="documento" class="col-sm-2 col-form-label">N° Documento</label>
                        <div class="col-sm-10">
                            <input type="text" name="documento" id="documento"
                                class="form-control @error('documento') is-invalid @enderror"
                                placeholder="Ingrese DNI, RUC, etc."
                                value="{{ old('documento', $cliente->documento) }}">

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
                        <label class="col-sm-2 col-form-label" for="servicios">Servicios</label>
                        <div class="col-sm-10">
                            <select name="servicios" class="form-control" id="servicios">
                                <option value="MODULO" {{ $cliente->servicios == 'MODULO' ? 'selected' : '' }}>MÓDULO
                                </option>
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
        </div>
    </div>
</div>
@endsection