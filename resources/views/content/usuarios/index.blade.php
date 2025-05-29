@extends('layouts.contentNavbarLayout')

@section('title', 'Listado de Usuarios')

@section('content')

@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Usuarios</h5>
        <div>
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Nuevo Usuario
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Filtros -->
        <div class="row mb-4">
            <!-- Aquí puedes agregar futuros campos de búsqueda o filtrado -->
        </div>

        <!-- Tabla de usuarios -->
        @include('content.usuarios.partials.index', ['usuarios' => $usuarios])

    </div>
</div>

@endsection