@extends('layouts/contentNavbarLayout')

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calendario de Prueba</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    

</head>
@section('title', 'Calendario')

@section('content')
    <style>
        /* Altura mínima del calendario */
        #calendar {
            min-height: 650px;
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 0.5rem;
        }

        /* Título del mes (ya en mayúsculas por JS) */
        .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Días de la semana en negrita */
        .fc-col-header-cell-cushion {
            font-weight: bold;
            color: #3a3a3a;
        }

        /* Día actual resaltado */
        .fc-day-today {
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
        }

        /* Estilo moderno para los eventos */
        .fc-event {
            border: none;
            border-radius: 0.375rem;
            padding: 2px 6px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #fff !important;
        }

        /* Botón de vista activa */
        .btn-outline-primary.active {
            background-color: #696CFF;
            color: #fff;
            border-color: #696CFF;
        }

        /* Mini calendario */
        #mini-calendar {
            border-radius: 8px;
            overflow: hidden;
            font-size: 0.9rem;
        }

        .flatpickr-calendar {
            width: 100% !important;
            box-shadow: none;
        }

        .flatpickr-day.selected,
        .flatpickr-day.today {
            background-color: #696CFF !important;
            color: #fff !important;
            border-radius: 6px !important;
        }
    </style>



    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Utilidades /</span> Calendario</h4>

    <div class="card app-calendar-wrapper">
        <div class="row g-0">
            <!-- Sidebar izquierdo -->
            <div class="col-lg-3 col-md-12 border-end px-0" id="app-calendar-sidebar">
                <div class="p-4">
                    <button class="btn btn-primary w-100 mb-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEvento">
                        <i class="bx bx-plus me-1"></i> Agregar Evento
                    </button>


                   <!-- Mini calendario -->
<div id="mini-calendar" class="mb-4"></div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <small class="text-muted text-uppercase fw-semibold">Etiqueta</small>
    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEtiquetas">
        <i class="bx bx-cog"></i> Gestionar
    </button>
</div>



                    <hr class="my-4" />

                    <div class="text-center">
                        <img src="{{ asset('assets/img/illustrations/calendario.png') }}" alt="Ilustración calendario"
                            class="img-fluid rounded" style="max-height: 180px;">
                    </div>
                </div>
            </div>

            <!-- Calendario -->
            <div class="col-lg-9 col-md-12 app-calendar-content">
                <div class="card shadow-none border-0 mb-0">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                        <!-- ⬅️ Botones y título dinámico -->
                        <div class="d-flex align-items-center gap-2">
                            <button id="btnPrev" class="btn btn-sm btn-outline-secondary">
                                <i class="bx bx-chevron-left"></i>
                            </button>
                            <h5 id="calendarTitle" class="mb-0 fw-semibold"></h5>
                            <button id="btnNext" class="btn btn-sm btn-outline-secondary">
                                <i class="bx bx-chevron-right"></i>
                            </button>
                        </div>

                        <!-- 📅 Botones de vista -->
                        <div class="d-flex flex-wrap gap-2">
                            <button id="btnHoy" class="btn btn-outline-secondary">
                                <i class="bx bx-calendar me-1"></i> Hoy
                            </button>
                            <div class="btn-group" role="group">
                                <button id="btnVistaMes" class="btn btn-outline-primary active">Mes</button>
                                <button id="btnVistaSemana" class="btn btn-outline-primary">Semana</button>
                                <button id="btnVistaDia" class="btn btn-outline-primary">Día</button>
                                <button id="btnVistaLista" class="btn btn-outline-primary">Lista</button>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ IMPORTANTE: el calendario debe quedar en un body con padding -->
                    <div class="card-body p-3">
                        <div id="calendar" class="bg-white p-2 rounded shadow-sm"></div>
                    </div>
                </div>

            </div>
        </div>
        @include('content.calendario.partials.modals')
    </div>




    <!-- FullCalendar JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        flatpickr("#mini-calendar", {
            inline: true,
            defaultDate: new Date(),
            locale: 'es',
            onChange: function(selectedDates) {
                const calendarApi = FullCalendar.getCalendar ?
                    FullCalendar.getCalendar() :
                    document.querySelector('#calendar')._fullCalendar;

                if (calendarApi && selectedDates[0]) {
                    calendarApi.gotoDate(selectedDates[0]);
                }
            }
        });

        document.getElementById('offcanvasEvento').addEventListener('shown.bs.offcanvas', function() {

            // Select Invitados
            $('#selectInvitados').select2({
                placeholder: "Selecciona invitados...",
                width: '100%',
                dropdownParent: $('#offcanvasEvento')
            });
        });
    </script>

    <!-- Tu script -->
    <script src="{{ asset('assets/js/calendar.js') }}"></script>


    
@endsection
