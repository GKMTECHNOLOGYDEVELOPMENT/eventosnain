@extends('layouts.contentNavbarLayout')

@section('title', 'Listado de Condiciones Comerciales')
@section('vendor-script')
    <!-- jQuery debe ir antes que cualquier otro script que lo use -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Otros scripts necesarios -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Condiciones Comerciales</h5>
    <div>
        <a href="{{ route('condiciones-newCondiciones') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva Condición
        </a>
    </div>
</div>


    <div class="card-body">
        <!-- Filtro -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form id="searchForm">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Buscar..." value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                    
                </form>
            </div>
        </div>

        <!-- Tabla -->
        <div id="condicionesTableContainer">
            @include('content.condiciones.partials._condicionesTable')
        </div>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditarCondicion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditarCondicion">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Condición</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre</label>
                        <input type="text" id="edit_nombre" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea id="edit_descripcion" name="descripcion" class="form-control" required></textarea>
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
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Buscar
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            loadCondiciones();
        });

        // Cargar tabla con AJAX
        function loadCondiciones() {
            const search = $('input[name="search"]').val();
            $.ajax({
                url: "{{ route('condiciones.index') }}",
                data: { search },
                success: function (data) {
                    $('#condicionesTableContainer').html(data);
                }
            });
        }

        // Paginación AJAX
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                success: function (data) {
                    $('#condicionesTableContainer').html(data);
                    window.scrollTo(0, 0);
                }
            });
        });

        // Editar
        $(document).on('click', '.btn-editar', function () {
            const id = $(this).data('id');
            $.get(`/condiciones/${id}/edit`, function (data) {
                $('#edit_id').val(data.id);
                $('#edit_nombre').val(data.nombre);
                $('#edit_descripcion').val(data.descripcion);
                $('#modalEditarCondicion').modal('show');
            });
        });

        $('#formEditarCondicion').on('submit', function (e) {
            e.preventDefault();
            const id = $('#edit_id').val();
            const data = {
                nombre: $('#edit_nombre').val(),
                descripcion: $('#edit_descripcion').val(),
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'PUT'
            };

            $.ajax({
                url: `/condiciones/${id}`,
                method: 'POST',
                data: data,
                success: function () {
                    $('#modalEditarCondicion').modal('hide');
                    loadCondiciones();
                }
            });
        });

        // Eliminar
        $(document).on('click', '.btn-eliminar', function () {
            const id = $(this).data('id');
            if (confirm('¿Estás seguro de eliminar esta condición?')) {
                $.ajax({
                    url: `/condiciones/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function () {
                        loadCondiciones();
                    }
                });
            }
        });
    });
</script>
@endpush
