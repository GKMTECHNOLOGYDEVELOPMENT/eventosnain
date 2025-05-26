@extends('layouts/contentNavbarLayout')

@section('title', 'Lista de Módulos')

<!-- jQuery (debe estar antes de los otros scripts) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Inventario /</span> Módulos</h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Módulos</h5>
            <a href="{{ route('new-modulo') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Nuevo Módulo
            </a>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="modulo-table" class="table table-hover nowrap" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Descripción</th>
                            <th>Compra</th>
                            <th>Venta</th>
                            <th>Stock</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>


        </div>
    </div>

@endsection




@push('scripts')
    <script>
        $('#modulo-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('api.modulos') }}',
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'codigo_modulo'
                },
                {
                    data: 'marca'
                },
                {
                    data: 'modelo'
                },
                {
                    data: 'descripcion'
                },
                {
                    data: 'precio_compra'
                },
                {
                    data: 'precio_venta'
                },
                {
                    data: 'stock_total'
                },
                {
                    data: 'fecha_registro'
                },
                {
                    data: 'estado',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'acciones',
                    orderable: false,
                    searchable: false
                }
            ],
            language: {
                processing: "Procesando...",
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando de _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando ningún registro.",
                infoFiltered: "(filtrado de un total de _MAX_ registros)",
                zeroRecords: "No se encontraron registros",
                emptyTable: "No hay datos disponibles en la tabla",
                paginate: {
                    first: "Primero",
                    previous: "Anterior",
                    next: "Siguiente",
                    last: "Último"
                }
            }
        });
    </script>
    <script>
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            $.ajax({
                url: url,
                success: function(data) {
                    $('#modulo-table tbody').html($(data).find('tbody').html());
                    $('.pagination').html($(data).find('.pagination').html());
                },
                error: function() {
                    alert('Error al cargar la paginación');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Eliminar módulo - Versión mejorada
            $(document).on('click', '.delete-modulo', function(e) {
                e.preventDefault();
                e.stopPropagation();

                console.log('Botón eliminar clickeado');

                const moduloId = $(this).data('id');
                const url = "{{ route('modulos.destroy', ':id') }}".replace(':id', moduloId);

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción no se puede deshacer!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Eliminado!',
                                        text: response.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        location.reload();

                                    });
                                }
                            },
                            error: function(xhr) {
                                let errorMsg = xhr.responseJSON?.message ||
                                    'Error al eliminar el módulo';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMsg
                                });
                            }
                        });
                    }
                });
            });

            // Paginación AJAX (mantén tu código actual)
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                $.ajax({
                    url: url,
                    success: function(data) {
                        $('#modulo-table tbody').html($(data).find('tbody').html());
                        $('.pagination').html($(data).find('.pagination').html());
                    }
                });
            });
        });
    </script>
@endpush
