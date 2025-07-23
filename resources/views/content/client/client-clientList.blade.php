@extends('layouts/contentNavbarLayout')

@section('title', 'Lista de Clientes')

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Clientes/</span> Lista de Clientes</h4>

    <!-- Basic Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Clientes</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('client-newClient') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Nuevo Cliente
                </a>
                {{-- <a href="#" id="btnExportar" class="btn btn-success">
                    <i class="bx bx-file"></i> Exportar Excel
                </a> --}}
            </div>
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
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">Filtrar por Servicio</label>
                    <select id="servicioFilter" class="form-select w-100">
                        <option value="">Todos</option>
                    </select>
                </div>
                {{-- <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">Filtrar por Evento</label>
                    <select id="eventoFilter" class="form-select w-100">
                        <option value="">Todos</option>
                    </select>
                </div> --}}
            </div>


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
                            {{-- <th>Evento</th> --}}
                            <th>Correo</th>
                            <th class="text-center">WhatsApp</th>
                            <th class="text-center">Llamada</th>
                            <th class="text-center">Reunión</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- Preloader -->
            <div id="preloader" class="text-center my-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Cargando más clientes...</p>
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
            // 🔄 Cargar servicios desde el backend
            $.get('{{ route('api.client.servicios') }}', function(data) {
                const $select = $('#servicioFilter');
                data.forEach(function(servicio) {
                    $select.append(new Option(servicio, servicio));
                });

                // Inicializa Select2 una vez cargado
                $select.select2({
                    placeholder: 'Selecciona un servicio',
                    allowClear: true,
                    width: 'resolve'
                });
            });
            // Filtro de eventos
            // $.get('{{ route('api.client.eventos') }}', function(data) {
            //     const $eventoSelect = $('#eventoFilter');
            //     data.forEach(function(evento) {
            //         $eventoSelect.append(new Option(evento.title, evento.id));
            //     });
            //     $eventoSelect.select2({
            //         placeholder: 'Selecciona un evento',
            //         allowClear: true,
            //         width: 'resolve'
            //     });
            // });


            // 🔄 Inicializa DataTable con filtro por servicio
            let table = $('#client-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('api.clientes') }}',
                    data: function(d) {
                        d.servicio = $('#servicioFilter').val();
                        d.evento_id = $('#eventoFilter').val();
                    }

                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nombre'
                    },
                    {
                        data: 'empresa'
                    },
                    {
                        data: 'telefono'
                    },
                    {
                        data: 'servicios'
                    },
                    {
                        data: 'status',
                        orderable: false,
                        searchable: false
                    },
                    // {
                    //     data: 'events_id'
                    // },
                    {
                        data: 'correo'
                    },
                    {
                        data: 'whatsapp'
                    },
                    {
                        data: 'llamada'
                    },
                    {
                        data: 'reunion'
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

            // 🔁 Actualiza la tabla al cambiar servicio
            $('#servicioFilter').on('change', function() {
                table.ajax.reload();
            });
            $('#eventoFilter').on('change', function() {
                table.ajax.reload();
            });

        });
    </script>
@endpush
