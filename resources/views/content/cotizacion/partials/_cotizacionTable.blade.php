<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="12%">Código</th>
                <th width="10%">Fecha</th>
                <th width="12%">Cliente</th>
                <th width="12%">Usuario</th>
                <th width="10%">Subtotal</th>
                <th width="8%">IGV</th>
                <th width="10%">Total</th>
                <th width="10%">Estado</th>
                <th width="15%">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($cotizaciones as $cotizacion)
            <tr>
                <td>{{ $cotizacion->codigo_cotizacion }}</td>
                <td>{{ \Carbon\Carbon::parse($cotizacion->fecha_emision)->format('d/m/Y') }}</td>
                <td>
                    {{ $cotizacion->cliente->nombre }}<br>
                    <small class="text-muted">{{ $cotizacion->cliente->empresa }}</small>
                </td>
                <td>
                    {{ $cotizacion->usuario->name ?? 'No asignado' }}<br>
                    <small class="text-muted">{{ $cotizacion->usuario->email ?? '' }}</small>
                </td>

                <td>$ {{ number_format($cotizacion->subtotal_sin_igv, 2) }}</td>
                <td>$ {{ number_format($cotizacion->igv, 2) }}</td>
                <td>$ {{ number_format($cotizacion->total_con_igv, 2) }}</td>
                <td>
                    @php
                    $badgeClass = [
                    'pendiente' => 'bg-label-warning',
                    'aprobada' => 'bg-label-success',
                    'rechazada' => 'bg-label-danger',
                    'vencida' => 'bg-label-secondary'
                    ][$cotizacion->estado] ?? 'bg-label-primary';
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($cotizacion->estado) }}</span>
                </td>
                <td>
                    <div class="d-flex">
                        <a href="{{ route('cotizaciones.edit', $cotizacion->id) }}"
                            class="btn btn-sm btn-icon btn-warning me-2" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>

                        <a href="" class="btn btn-sm btn-icon btn-danger me-2" title="Descargar PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>

                        <a href="{{ route('cotizaciones.imprimir', $cotizacion->id) }}" target="_blank"
                            class="btn btn-sm btn-icon btn-primary me-2" title="Imprimir">
                            <i class="fas fa-print"></i>
                        </a>
                        <!-- Ver detalles -->
                        <a href="{{ route('cotizaciones.show', $cotizacion->id) }}"
                            class="btn btn-sm btn-icon btn-info me-2" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-secondary me-2 btn-actualizar-estado"
                            data-bs-toggle="modal" data-bs-target="#modalActualizarEstado"
                            data-id="{{ $cotizacion->id }}" data-estado="{{ $cotizacion->estado }}"
                            title="Actualizar Estado">
                            <i class="fas fa-sync-alt"></i>
                        </button>

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No se encontraron cotizaciones</td>
            </tr>
            @endforelse
        </tbody>

    </table>


    <!-- Paginación mejorada -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="showing-results text-muted small">
            Mostrando {{ $cotizaciones->firstItem() }} a {{ $cotizaciones->lastItem() }} de {{ $cotizaciones->total() }}
            registros
        </div>

        <div class="pagination-custom">
            {{ $cotizaciones->onEachSide(1)->links() }}
        </div>
    </div>
</div>

<!-- Modal para actualizar estado -->
<div class="modal fade" id="modalActualizarEstado" tabindex="-1" aria-labelledby="modalActualizarEstadoLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form id="formActualizarEstado">
            @csrf
            @method('PUT')
            <input type="hidden" name="cotizacion_id" id="cotizacion_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalActualizarEstadoLabel">Actualizar Estado de Cotización</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nuevo_estado" class="form-label">Estado</label>
                        <select class="form-select" id="nuevo_estado" name="estado" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="aprobada">Aprobada</option>
                            <option value="rechazada">Rechazada</option>
                            <option value="vencida">Vencida</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('modalActualizarEstado');
        var cotizacionIdInput = document.getElementById('cotizacion_id');
        var nuevoEstadoSelect = document.getElementById('nuevo_estado');
        var form = document.getElementById('formActualizarEstado');

        // Al abrir modal, cargar datos del botón que lo llamó
        modal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var cotizacionId = button.getAttribute('data-id');
            var estadoActual = button.getAttribute('data-estado');

            cotizacionIdInput.value = cotizacionId;
            nuevoEstadoSelect.value = estadoActual;
        });

        // Manejar envío del formulario con AJAX
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            var cotizacionId = cotizacionIdInput.value;
            var nuevoEstado = nuevoEstadoSelect.value;

            fetch(`/cotizaciones/${cotizacionId}/estado`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        estado: nuevoEstado
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error actualizando el estado');
                    return response.json();
                })
                .then(data => {
                    // Cerrar modal
                    var modalBootstrap = bootstrap.Modal.getInstance(modal);
                    modalBootstrap.hide();

                    // Opcional: refrescar la página o actualizar el badge del estado en la fila
                    location.reload();
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
        });
    });
</script>