@forelse ($modulos as $index => $modulo)
<tr>
    <td>{{ $modulos->firstItem() + $index }}</td>
    <td>{{ $modulo->codigo_modulo }}</td>
    <td>{{ $modulo->marca }}</td>
    <td>{{ $modulo->modelo }}</td>
    <td>{{ Str::limit($modulo->descripcion, 40) }}</td>
    <td>${{ number_format($modulo->precio_compra, 2) }}</td>
    <td>${{ number_format($modulo->precio_venta, 2) }}</td>
    <td>
        @if($modulo->stock_total <= $modulo->stock_minimo)
            <span class="badge bg-danger">Bajo</span> {{ $modulo->stock_total }}
            @else
            {{ $modulo->stock_total }}
            @endif
    </td>
    <td>{{ \Carbon\Carbon::parse($modulo->fecha_registro)->format('Y-m-d') }}</td>


    <td>
        @if($modulo->estado)
        <span class="badge bg-success">Activo</span>
        @else
        <span class="badge bg-secondary">Inactivo</span>
        @endif
    </td>
    <td>
        <div class="d-flex gap-1">
            <a href="{{ route('modulos.edit', $modulo->id) }}" class="btn btn-sm btn-warning">
                <i class="bx bx-edit"></i>
            </a>
            <button class="btn btn-sm btn-danger delete-modulo" data-id="{{ $modulo->id }}">
                <i class="bx bx-trash"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="11" class="text-center">No hay módulos registrados.</td>
</tr>
@endforelse