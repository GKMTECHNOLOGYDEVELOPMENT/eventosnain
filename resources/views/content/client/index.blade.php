@extends('layouts/contentNavbarLayout')

@section('title', 'Lista de Clientes')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Clientes/</span> Lista de Clientes</h4>

<!-- Basic Table -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Clientes</h5>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Empresa</th>
                    <th>Telefono</th>
                    <th>Email</th>
                    <th>Tipo Cliente</th>
                    <th>Servicios</th>
                    <th>Mensaje</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->id }}</td>
                    <td>{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->empresa }}</td>
                    <td>{{ $cliente->telefono }}</td>
                    <td>{{ $cliente->email }}</td>
                    <td>{{ $cliente->tipo_cliente }}</td>
                    <td>{{ $cliente->servicios }}</td>
                    <td>{{ $cliente->mensaje }}</td>
                    <td>
                        <!-- Aquí puedes agregar enlaces para editar y eliminar -->
                        <a href="{{ route('client.edit', $cliente->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('client.destroy', $cliente->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">No hay clientes registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection