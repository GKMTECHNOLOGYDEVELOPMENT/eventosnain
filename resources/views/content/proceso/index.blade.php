@extends('layouts/contentNavbarLayout')

@section('title', 'Clientes - Proceso')

@section('content')
<div class="card">
  <h5 class="card-header">Clientes con Cotización Aprobada</h5>
  <div class="card-body">
    <!-- Barra de búsqueda -->
    <form method="GET" action="{{ url('/proceso') }}" class="mb-4">
      <div class="input-group input-group-merge">
        <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
        <input 
          type="text" 
          class="form-control" 
          placeholder="Buscar cliente..." 
          name="search"
          value="{{ request('search') }}"
          aria-label="Buscar cliente..."
          aria-describedby="basic-addon-search31">
        <button type="submit" class="btn btn-primary">Buscar</button>
      </div>
    </form>
    
    <div class="table-responsive text-nowrap">
      <table class="table">
    <thead class="table-light">
  <tr>
    <th>#</th>
    <th>Código Cotizacion</th>
    <th>Nombre</th>
    <th>Servicio</th>
    <th>Total c/ IGV</th>
    <th>Subtotal (sin IGV)</th>
    <th>Comisión (5%)</th>
    <th>Avance cuota</th>
    <th>Total Vendido</th>
    <th>Vendedor</th>
    <th>Fecha Registro</th>
    <th>Acciones</th>
  </tr>
</thead>
<tbody class="table-border-bottom-0">
  @forelse($clientes as $cliente)
  <tr>
    <td>{{ ($clientes->currentPage() - 1) * $clientes->perPage() + $loop->iteration }}</td>
    <td>{{ $cliente->codigo_cotizacion }}</td>
    <td>{{ $cliente->nombre }}</td>
    <td>{{ $cliente->servicio_nombre }}</td>
    <td>S/ {{ number_format($cliente->total_con_igv, 2) }}</td>
    <td>S/ {{ number_format($cliente->subtotal_sin_igv, 2) }}</td>
    <td>
      @if($cliente->comision > 0)
        <span class="badge bg-success">S/ {{ number_format($cliente->comision, 2) }}</span>
      @else
        <span class="badge bg-secondary">No aplica</span>
      @endif
    </td>
    <td>{{ $cliente->porcentaje_cuota }}%</td>
    <td>S/ {{ number_format($cliente->total_vendedor_mes, 2) }}</td>
    <td>{{ $cliente->vendedor_nombre }}</td>
    <td>{{ \Carbon\Carbon::parse($cliente->fecharegistro)->format('d/m/Y') }}</td>
    <td>
      <a href="{{ url('/cliente/' . $cliente->id) }}" class="btn btn-sm btn-primary">Ver</a>
    </td>
  </tr>
  @empty
  <tr>
    <td colspan="12" class="text-center">No hay clientes con cotización aprobada.</td>
  </tr>
  @endforelse
</tbody>



      </table>
    </div>
    
    <!-- Paginación -->
    <div class="mt-4">
      {{ $clientes->appends(['search' => request('search')])->links() }}
    </div>
  </div>
</div>



@endsection
