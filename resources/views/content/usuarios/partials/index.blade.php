<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Rol</th>
            <th>Foto</th>
            <th>Acciones</th> {{-- Nueva columna --}}
        </tr>
    </thead>
    <tbody>
        @foreach($usuarios as $usuario)
        <tr>
            <td>{{ $usuario->id }}</td>
            <td>{{ $usuario->name }}</td>
            <td>{{ $usuario->email }}</td>
            <td>{{ $usuario->telefono }}</td>
            <td>{{ $usuario->rol->nombre ?? 'Sin Rol' }}</td>
            <td>
                @if($usuario->photo)
                <img src="{{ asset('storage/' . $usuario->photo) }}" alt="Foto" width="50" class="rounded-circle">
                @else
                <span class="text-muted">Sin foto</span>
                @endif
            </td>
            <td>
                <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-info me-1">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST"
                    style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>