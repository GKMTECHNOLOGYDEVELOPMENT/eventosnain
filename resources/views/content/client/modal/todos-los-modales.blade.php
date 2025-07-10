<!-- Botones de acción -->
<div class="action-btns">
    <!-- Botón Estado -->
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
        data-bs-target="#statusModal{{ $cliente->id }}" title="Estado">
        <i class="fas fa-info-circle"></i>
    </button>

    <!-- Botón Llamada -->
    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
        data-bs-target="#llamadaModal{{ $cliente->id }}" title="Registro de llamadas">
        <i class="fa-solid fa-phone"></i>
    </button>

    <!-- Botón Video -->
    @php
        $reunionesCliente = $reuniones->get($cliente->id);
    @endphp
    <button type="button" id="video-button{{ $cliente->id }}" class="btn btn-info btn-sm" data-bs-toggle="modal"
        data-bs-target="#videoModal{{ $cliente->id }}" title="Video Conferencia"
        {{ $reunionesCliente && $reunionesCliente->isNotEmpty() ? 'disabled' : '' }}>
        <i class="fa-solid fa-video"></i>
    </button>

    <!-- Editar -->
    <a href="{{ route('client.edit', $cliente->id) }}" class="btn btn-warning btn-sm" title="Editar">
        <i class="bx bx-edit"></i>
    </a>

    <!-- Ver estado -->
    <a href="{{ route('client.status', $cliente->id) }}" class="btn btn-primary btn-sm" title="Detalles">
        <i class="fas fa-file-alt"></i>
    </a>

    <!-- Eliminar -->
    @if (auth()->check() && auth()->user()->rol_id == 1)
        <form id="delete-form-{{ $cliente->id }}" action="{{ route('client.destroy', $cliente->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
        </form>
        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $cliente->id }})">
            <i class="fas fa-trash-alt"></i>
        </button>
    @endif
</div>

<!-- Modales -->
@include('content.client.modal.modal-estado', ['cliente' => $cliente, 'usuarios' => $usuarios, 'observaciones' => $observaciones])
@include('content.client.modal.modal-llamada', ['cliente' => $cliente])
@include('content.client.modal.modal-video', ['cliente' => $cliente, 'usuarios' => $usuarios])
