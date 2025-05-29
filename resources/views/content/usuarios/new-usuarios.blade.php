@extends('layouts/contentNavbarLayout')

@section('title', 'Nuevo Usuario')

@section('vendor-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<h4 class="py-3 mb-4"><span class="text-muted fw-light">Formulario /</span> Registrar Usuario</h4>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Información del Usuario</h5>
                <small class="text-muted float-end">Complete todos los campos</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('usuarios.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Nombre --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                            value="{{ old('telefono') }}">
                        @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Rol --}}
                    <div class="mb-3">
                        <label for="rol_id" class="form-label">Rol</label>
                        <select name="rol_id" class="form-select @error('rol_id') is-invalid @enderror" required>
                            <option value="">-- Seleccionar Rol --</option>
                            @foreach($roles as $rol)
                            <option value="{{ $rol->rol_id }}" {{ old('rol_id') == $rol->rol_id ? 'selected' : '' }}>
                                {{ $rol->nombre }}
                            </option>
                            @endforeach
                        </select>
                        @error('rol_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Foto --}}
                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto</label>
                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
                        @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Contraseña --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirmar Contraseña --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    {{-- Botón --}}
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Registrar Usuario
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection