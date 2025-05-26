@extends('layouts/contentNavbarLayout')

@section('title', 'Lista de Clientes')

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Clientes/</span> Lista de Clientes</h4>

    <!-- Basic Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Clientes</h5>
            <a href="{{ route('client-newClient') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Nuevo Cliente
            </a>
        </div>

        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tabla de clientes -->
            <div class="table-responsive">
                <table id="client-table" class="table table-hover nowrap" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Empresa</th>
                            <th>Teléfono</th>
                            <th>Servicio</th>
                            <th>Estado</th>
                            <th>Evento</th>
                            <th>Correo</th>
                            <th class="text-center">WhatsApp</th>
                            <th class="text-center">Llamada</th>
                            <th class="text-center">Reunión</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @include('content.client.partials._clientTable', [
                            'clientes' => $clientes,
                            'reuniones' => $reuniones,
                            'observaciones' => $observaciones,
                        ])
                    </tbody>
                </table>
            </div>

            <!-- Preloader -->
            <div id="preloader" class="text-center my-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Cargando más clientes...</p>
            </div>

            <!-- Paginación mejorada -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="text-muted">
                        Mostrando <span class="fw-bold">{{ $clientes->firstItem() }}</span> a
                        <span class="fw-bold">{{ $clientes->lastItem() }}</span> de
                        <span class="fw-bold">{{ $clientes->total() }}</span> registros
                    </div>
                </div>
                <div class="col-md-6">
                    <nav aria-label="Page navigation" class="d-flex justify-content-end">
                        <ul class="pagination pagination-sm mb-0">
                            {{ $clientes->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        #client-table th,
        #client-table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }

        .badge-status {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }

        .action-btns {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .action-btns .btn {
            padding: 0.375rem;
            width: 32px;
            height: 32px;
        }

        /* Estilos para la paginación */
        .pagination {
            margin-bottom: 0;
        }

        .page-item.active .page-link {
            background-color: #696cff;
            border-color: #696cff;
        }

        .page-link {
            color: #696cff;
            padding: 0.375rem 0.75rem;
        }

        .page-link:hover {
            color: #5a5cde;
        }

        .page-item.disabled .page-link {
            color: #b4b7bd;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar DataTable si es necesario
            $('#client-table').DataTable({
                responsive: true,
                searching: true,
                ordering: true,
                paging: false, // Desactivamos la paginación de DataTable porque usamos la de Laravel
                info: false,
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando de _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "Mostrando ningún registro.",
                    infoFiltered: "(filtrado de un total de _MAX_ registros)",
                    infoPostFix: "",
                    loadingRecords: "Cargando registros...",
                    zeroRecords: "No se encontraron registros",
                    emptyTable: "No hay datos disponibles en la tabla",
                    paginate: {
                        first: "Primero",
                        previous: "Anterior",
                        next: "Siguiente",
                        last: "Último"
                    },
                    aria: {
                        sortAscending: ": activar para ordenar la columna ascendente",
                        sortDescending: ": activar para ordenar la columna descendente"
                    }
                }
            });


            // Manejador de clics en los enlaces de paginación
            // $(document).on('click', '.pagination a', function(e) {
            //     e.preventDefault();
            //     let url = $(this).attr('href');

            //     // Mostrar el preloader
            //     $('#preloader').show();
            //     $('html, body').animate({
            //         scrollTop: $('#preloader').offset().top - 100
            //     }, 'slow');

            //     $.ajax({
            //         url: url,
            //         success: function(data) {
            //             // Actualizar el cuerpo de la tabla
            //             $('tbody').html($(data).find('tbody').html());

            //             // Actualizar la información del rango de registros
            //             $('.text-muted').html($(data).find('.text-muted').html());

            //             // Actualizar los links de paginación
            //             $('.pagination').html($(data).find('.pagination').html());

            //             // Ocultar el preloader
            //             $('#preloader').hide();
            //         },
            //         error: function() {
            //             alert('Error al cargar los datos');
            //             $('#preloader').hide();
            //         }
            //     });
            // });
        });
    </script>
@endpush

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar los campos y listeners para cada select con clase .form-select
        document.querySelectorAll('.form-select').forEach(function(selectElement) {
            var clienteId = selectElement.getAttribute('data-client-id');

            // Función para actualizar los campos (asegúrate de que esta func esté definida)
            updateFields(clienteId);

            selectElement.addEventListener('change', function() {
                updateFields(clienteId);
            });

            // Manejo del campo de reunión asociado a este cliente
            var reunionSelect = document.getElementById('reunion' + clienteId);
            var reunionFields = document.getElementById('reunion-fields' + clienteId);

            if (reunionSelect && reunionFields) {
                function handleReunionChange() {
                    if (reunionSelect.value === 'SI') {
                        reunionFields.style.display = 'block';
                    } else {
                        reunionFields.style.display = 'none';
                    }
                }

                // Inicializar el estado del campo de reunión
                handleReunionChange();

                // Listener para el cambio de valor del select de reunión
                reunionSelect.addEventListener('change', handleReunionChange);
            }
        });
    });
</script>
