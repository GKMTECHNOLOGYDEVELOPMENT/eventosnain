@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard de Comisiones')

@section('content')
<div class="row">


  <!-- Tabla de comisiones -->
  <div class="col-12">
    <div class="card">
      <h5 class="card-header">Detalle de Comisiones por Vendedor</h5>
      <div class="table-responsive text-nowrap">
        <table class="table">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Vendedor</th>
              <th>Total Vendido</th>
              <th>Avance a Cuota (%)</th>
              <th>Cotizaciones Aprobadas</th>
              <th>Comisión Total (5%)</th>
            </tr>
          </thead>
          <tbody>
            @forelse($comisiones as $index => $vendedor)
            <tr>
              <td>{{ ($comisiones->currentPage() - 1) * $comisiones->perPage() + $index + 1 }}</td>
              <td>{{ $vendedor->vendedor_nombre }}</td>
              <td>S/ {{ number_format($vendedor->total_vendido, 2) }}</td>
              <td>
                <div class="d-flex align-items-center">
                  <span class="me-2">{{ $vendedor->porcentaje_cuota }}%</span>
                  <div class="progress w-100" style="height: 10px;">
                
                    <div class="progress-bar bg-success"
                         role="progressbar" 
                         style="width: {{ $vendedor->porcentaje_cuota }}%" 
                         aria-valuenow="{{ $vendedor->porcentaje_cuota }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100"></div>
                  </div>
                </div>
              </td>
              <td>{{ $vendedor->total_cotizaciones }}</td>
              <td>
                @if($vendedor->total_comision > 0)
                  <span class="badge bg-success">S/ {{ number_format($vendedor->total_comision, 2) }}</span>
                @else
                  <span class="badge bg-secondary">No aplica</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center">No hay datos disponibles.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($comisiones->hasPages())
      <div class="card-footer">
        <div class="d-flex justify-content-center">
          {{ $comisiones->links() }}
        </div>
      </div>
      @endif
    </div>
  </div>
</div>


@endsection
