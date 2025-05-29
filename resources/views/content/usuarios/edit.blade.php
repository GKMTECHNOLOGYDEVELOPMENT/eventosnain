@extends('layouts.contentNavbarLayout')

@section('title', 'Editar Usuario')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Editar Usuario</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nombre --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}"
                    required>
            </div>

            {{-- Teléfono --}}
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control"
                    value="{{ old('telefono', $usuario->telefono) }}">
            </div>

            {{-- Rol --}}
            <div class="mb-3">
                <label for="rol_id" class="form-label">Rol</label>
                <select name="rol_id" class="form-select" required>
                    <option value="">-- Seleccionar Rol --</option>
                    @foreach($roles as $rol)
                    <option value="{{ $rol->rol_id }}" {{ $usuario->rol_id == $rol->rol_id ? 'selected' : '' }}>
                        {{ $rol->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Foto --}}
            <div class="mb-3">
                <label class="form-label">Foto Actual</label><br>
                @if($usuario->photo)
                <img src="{{ asset('storage/' . $usuario->photo) }}" alt="Foto" width="80" class="rounded mb-2">
                @else
                <p class="text-muted">Sin foto</p>
                @endif
                <input type="file" name="photo" class="form-control">
            </div>

            {{-- Botón --}}
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-2"></i>Guardar Cambios
            </button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
        </form>
    </div>
</div>
@endsection