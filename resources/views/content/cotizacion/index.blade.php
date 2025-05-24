@extends('layouts.contentNavbarLayout')

@section('title', 'Listado de Cotizaciones')

@section('content')


<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestión de Cotizaciones</h5>
        <div>
            <a href="{{ route('cotizacion-newCotizacion') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Nueva Cotización
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form id="searchForm">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Buscar..."
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="estadoFilter">
                    <option value="todos">Todos los estados</option>
                    @foreach($estados as $value => $label)
                    <option value="{{ $value }}" {{ request('estado') == $value ? 'selected' : '' }}>{{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Tabla de cotizaciones -->
        <div id="cotizacionesTableContainer">
            @include('content.cotizacion.partials._cotizacionTable')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Manejar búsqueda y filtros
        $('#searchForm, #estadoFilter').on('submit change', function(e) {
            e.preventDefault();
            loadCotizaciones();
        });

        // Función para cargar cotizaciones via AJAX
        function loadCotizaciones() {
            const search = $('input[name="search"]').val();
            const estado = $('#estadoFilter').val();

            $.ajax({
                url: "{{ route('cotizaciones.index') }}",
                data: {
                    search: search,
                    estado: estado
                },
                success: function(data) {
                    $('#cotizacionesTableContainer').html(data);
                }
            });
        }

        // Manejar paginación
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            $.ajax({
                url: $(this).attr('href'),
                success: function(data) {
                    $('#cotizacionesTableContainer').html(data);
                    window.scrollTo(0, 0);
                }
            });
        });
    });
</script>
@endpush