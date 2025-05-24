<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="15%">Código</th>
                <th width="15%">Fecha</th>
                <th>Cliente</th>
                <th width="12%">Total</th>
                <th width="12%">Estado</th>
                <th width="15%">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cotizaciones as $cotizacion)
            <tr>
                <td>{{ $cotizacion->codigo_cotizacion }}</td>
                <td>{{ $cotizacion->fecha_emision->format('d/m/Y') }}</td>
                <td>
                    {{ $cotizacion->cliente->nombre }}<br>
                    <small class="text-muted">{{ $cotizacion->cliente->empresa }}</small>
                </td>
                <td>S/ {{ number_format($cotizacion->total_con_igv, 2) }}</td>
                <td>
                    @php
                    $badgeClass = [
                    'pendiente' => 'bg-label-warning',
                    'aprobada' => 'bg-label-success',
                    'rechazada' => 'bg-label-danger',
                    'vencida' => 'bg-label-secondary'
                    ][$cotizacion->estado];
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($cotizacion->estado) }}</span>
                </td>
                <td>
                    <div class="d-flex">
                        <a href="{{ route('cotizaciones.pdf', $cotizacion->id) }}"
                            class="btn btn-sm btn-icon btn-danger me-2" title="Descargar PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        <a href="{{ route('cotizaciones.imprimir', $cotizacion->id) }}" target="_blank"
                            class="btn btn-sm btn-icon btn-primary me-2" title="Imprimir">
                            <i class="fas fa-print"></i>
                        </a>
                        <a href="{{ route('cotizaciones.show', $cotizacion->id) }}" class="btn btn-sm btn-icon btn-info"
                            title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No se encontraron cotizaciones</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $cotizaciones->links() }}
    </div>
</div>