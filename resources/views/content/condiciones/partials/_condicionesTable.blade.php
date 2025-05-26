<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($condiciones as $condicion)
            <tr>
                <td>{{ $condicion->nombre }}</td>
                <td>{{ $condicion->descripcion }}</td>
                <td>
                    <button class="btn btn-sm btn-warning btn-editar" data-id="{{ $condicion->id }}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-eliminar" data-id="{{ $condicion->id }}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">No hay condiciones comerciales</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $condiciones->onEachSide(1)->links() }}
    </div>
</div>
