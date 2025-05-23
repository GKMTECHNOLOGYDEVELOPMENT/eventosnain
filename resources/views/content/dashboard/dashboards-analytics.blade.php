@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Hola {{ Auth::user()->name }}! 🎉</h5>

                        <p class="mb-4">
                            El evento <span class="fw-medium" id="evento-seleccionado">No seleccionado</span>
                            tiene un total de <span class="fw-medium" id="total-clientes">0</span>
                            clientes registrados, con una meta de <span class="fw-medium" id="meta-registros">0</span>
                            registros.

                        </p>


                        </ul>
                        </p>
                    </div>

                    <!-- <div class="dropdown m-3">
                            <button class="btn btn-sm btn-outline-danger dropdown-toggle" type="button" id="growthReportId"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                SELECT USUARIO
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId2">
                                @foreach ($user as $usuario)
                                <a class="dropdown-item" href="javascript:void(0);" data-usuario-id="{{ $usuario->id }}">
                                    {{ $usuario->name }}
                                </a>
                                @endforeach
                            </div>
                        </div> -->

                    <!-- Modal para seleccionar evento y rango de fechas -->
                    <div class="modal fade" id="selectEventDateModal" tabindex="-1"
                        aria-labelledby="selectEventDateLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="selectEventDateLabel">Seleccionar Evento y Rango de
                                        Fechas</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="eventDateForm">
                                        <div class="mb-3">
                                            <label for="evento" class="form-label">Evento</label>
                                            <select class="form-select" id="evento" required>
                                                @foreach ($eventos as $evento)
                                                <option value="{{ $evento->id }}">{{ $evento->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                                            <input type="date" class="form-control" id="fechaInicio" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="fechaFin" class="form-label">Fecha de Fin</label>
                                            <input type="date" class="form-control" id="fechaFin" required>
                                        </div>
                                        <input type="hidden" id="selectedUserId">
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-primary" id="generateReportButton">Generar
                                        Reporte</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.querySelectorAll('.dropdown-item[data-usuario-id]').forEach(item => {
                            item.addEventListener('click', function() {
                                var userId = this.getAttribute('data-usuario-id');
                                document.getElementById('selectedUserId').value = userId;
                                var modal = new bootstrap.Modal(document.getElementById(
                                    'selectEventDateModal'));
                                modal.show();
                            });
                        });

                        document.getElementById('generateReportButton').addEventListener('click', function() {
                            var userId = document.getElementById('selectedUserId').value;
                            var eventoId = document.getElementById('evento').value;
                            var fechaInicio = document.getElementById('fechaInicio').value;
                            var fechaFin = document.getElementById('fechaFin').value;

                            if (userId && eventoId && fechaInicio && fechaFin) {
                                window.location.href = '/generar-pdf-usuario/' + userId + '/' + eventoId + '/' +
                                    fechaInicio + '/' + fechaFin;
                            } else {
                                alert('Por favor, selecciona un evento y un rango de fechas.');
                            }
                        });
                    </script>



                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{asset('assets/img/illustrations/man-with-laptop-light.png')}}" height="120"
                            alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png">
                    </div>
                </div>

                <!-- <div class="card-body">
                    <div class="text-center">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-danger dropdown-toggle" type="button"
                                id="growthReportId" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                SELECT USUARIO
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId2">
                                @foreach ($user as $usuario)
                                <a class="dropdown-item" href="javascript:void(0);"
                                    data-usuario-id="{{ $usuario->id }}">
                                    {{ $usuario->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>



    <!-- Clientes -->
    <div class="col-lg-4 col-md-2 order-1">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assets/img/icons/unicons/chart-success.png') }}" alt="clients icon"
                                    class="rounded">
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                    <a class="dropdown-item" href="javascript:void(0);">Ver Detalles</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Eliminar</a>
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">CLIENTES</span>
                        <h3 class="card-title mb-2" id="total-clientes-card">0</h3> <!-- ID cambiado aquí -->
                        <small class="text-success fw-semibold"><i class='bx bx-up-arrow-alt'></i></small>
                    </div>
                </div>
            </div>


            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assets/img/icons/unicons/potencial.png') }}" alt="Potencial"
                                    class="rounded">
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">POTENCIALES</span>
                        <h3 class="card-title mb-2" id="total-potenciales-card">0</h3> <!-- ID cambiado aquí -->
                        <small class="text-success fw-semibold"><i class='bx bx-up-arrow-alt'></i></small>
                    </div>
                </div>
            </div>

        </div>
    </div>



    <!-- Total Revenue -->

    <!-- GRAFICA DE BARRAS PARA LOS DIAS -->

    <!-- <script>
    const totalRevenueChartEl = document.querySelector('#totalRevenueChart');

    // Mapeo de nombres de meses de inglés a español
    const mesesEnEspanol = {
        'January': 'Enero',
        'February': 'Febrero',
        'March': 'Marzo',
        'April': 'Abril',
        'May': 'Mayo',
        'June': 'Junio',
        'July': 'Julio',
        'August': 'Agosto',
        'September': 'Septiembre',
        'October': 'Octubre',
        'November': 'Noviembre',
        'December': 'Diciembre'
    };

    const totalRevenueChartOptions = {
        series: [{
            name: '2024',
            data: window.dashboardData.counts // Datos para 2024
        }],
        chart: {
            height: 300,
            stacked: true,
            type: 'bar',
            toolbar: {
                show: false
            } // Desactivar la barra de herramientas
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '33%',
                borderRadius: 12,
                startingShape: 'rounded',
                endingShape: 'rounded'
            }
        },
        colors: [config.colors.primary], // Color único para 2024
        dataLabels: {
            enabled: false // Desactivar etiquetas de datos
        },
        stroke: {
            curve: 'smooth',
            width: 6,
            lineCap: 'round',
            colors: [cardColor]
        },
        legend: {
            show: true,
            horizontalAlign: 'left',
            position: 'top',
            markers: {
                height: 8,
                width: 8,
                radius: 12,
                offsetX: -3
            },
            labels: {
                colors: axisColor
            },
            itemMargin: {
                horizontal: 10
            }
        },
        grid: {
            borderColor: borderColor,
            padding: {
                top: 0,
                bottom: -8,
                left: 20,
                right: 20
            }
        },
        xaxis: {
            categories: window.dashboardData.months.map(month => mesesEnEspanol[month] ||
            month), // Etiquetas del eje X en español
            labels: {
                style: {
                    fontSize: '13px',
                    colors: axisColor
                }
            },
            axisTicks: {
                show: false // Desactivar marcas del eje X
            },
            axisBorder: {
                show: false // Desactivar borde del eje X
            }
        },
        yaxis: {
            labels: {
                style: {
                    fontSize: '13px',
                    colors: axisColor
                }
            }
        },
        responsive: [{
                breakpoint: 1700,
                options: {
                    plotOptions: {
                        bar: {
                            borderRadius: 10,
                            columnWidth: '32%'
                        }
                    }
                }
            },
            {
                breakpoint: 1580,
                options: {
                    plotOptions: {
                        bar: {
                            borderRadius: 10,
                            columnWidth: '35%'
                        }
                    }
                }
            }
        ],
        states: {
            hover: {
                filter: {
                    type: 'none' // Sin filtro al pasar el ratón
                }
            },
            active: {
                filter: {
                    type: 'none' // Sin filtro cuando está activo
                }
            }
        }
    };

    if (totalRevenueChartEl) {
        const totalRevenueChart = new ApexCharts(totalRevenueChartEl, totalRevenueChartOptions);
        totalRevenueChart.render();
    } -->
    </script>

    <!-- Total Clientes -->
    <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-2">
        <div class="card">
            <div class="row row-bordered g-0">
                <div class="col-md-7">
                    <h5 class="card-header m-0 me-2 pb-3">CLIENTES</h5>

                    <!-- <h3 class="card-title mb-2 fs-4" id="usuarios_cantidad">0</h3> -->
                    <div id="totalRevenueChart" class="px-2"></div>
                    <!-- <div id="funnelChart"></div> -->
                </div>





                <div class="col-md-4">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-danger dropdown-toggle" type="button"
                                    id="growthReportId" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    SELECCIONE UN EVENTO
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                                    @foreach ($eventos as $evento)
                                    <a class="dropdown-item" href="javascript:void(0);"
                                        data-evento-id="{{ $evento->id }}">
                                        {{ $evento->title }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>





                    <div id="growthChart"></div>
                    <div class="text-center fw-medium pt-3 mb-2" style="min-height: 170px;">
                        <span id="porcentajeProgreso">PORCENTAJE</span> GENERAL %
                    </div>



                    <style>
                        /* Estilo personalizado para los botones de control */
                        .carousel-control-next {
                            color: #dc3545;
                            /* Rojo Bootstrap */
                        }

                        .carousel-control-next-icon {
                            background-color: #dc3545;
                            /* Fondo rojo para el icono de siguiente */
                        }

                        /* Estilo para los números de las estadísticas */
                        .text-danger {
                            font-size: 1.5rem;
                            /* Tamaño de fuente mayor para los números */
                            font-weight: bold;
                        }

                        .carousel-control-prev-icon {
                            background-color: #6c757d;
                            /* Gris oscuro para el icono de anterior */
                        }

                        /* Opcional: Ajuste de espacio entre los elementos del carrusel */
                        .stat-item {
                            margin: 0 10px;
                        }

                        /* Estilo adicional para darle un diseño bonito */
                        .carousel-inner {
                            padding: 10px;
                            background: #f8f9fa;
                            /* Fondo claro */
                            border-radius: 10px;
                            /* Bordes redondeados */
                            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                            /* Sombra suave */
                        }
                    </style>



                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilo para la tabla */
        #conteodellamadasyreunion {
            font-size: 0.8rem;
            /* Reduce el tamaño de la fuente */
            margin: 0 auto;
            /* Centra la tabla si es necesario */
            width: 80%;
            /* Ajusta el ancho de la tabla */
        }

        /* Estilo para las celdas de la tabla */
        #conteodellamadasyreunion th,
        #conteodellamadasyreunion td {
            padding: 0.5rem;
            /* Reduce el padding */
            text-align: left;
            /* Alinea el texto a la izquierda */
        }

        /* Estilo para el encabezado de la tabla */
        #conteodellamadasyreunion thead th {
            background-color: #f4f4f4;
            /* Color de fondo para el encabezado */
            border-bottom: 1px solid #ddd;
            /* Línea inferior para el encabezado */
        }

        /* Estilo para las filas de la tabla */
        #conteodellamadasyreunion tbody tr:nth-child(even) {
            background-color: #f9f9f9;
            /* Color de fondo para filas pares */
        }

        #conteodellamadasyreunion tbody tr:hover {
            background-color: #f1f1f1;
            /* Color de fondo para filas al pasar el ratón */
        }
    </style>


    <style>
        .scroll-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding: 0 1rem;
            /* Añadir un padding en los extremos */
        }

        .scroll-content {
            display: inline-flex;
            min-width: 100%;
            padding: 1rem 0;
            /* Reducir padding superior e inferior */
        }

        .stat-item {
            flex: 0 0 auto;
            padding: 0.5rem 1rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            margin-right: 1rem;
        }

        .scroll-container::-webkit-scrollbar {
            display: none;
        }

        .scroll-container {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>


    <script>
        // Inicializar las instancias de los gr�ficos
        let growthChart = null;
        let funnelChart = null;
        let pieChart = null;

        function totalRevenueChart() {
            const totalRevenueChartEl = document.querySelector('#totalRevenueChart');

            if (typeof window.dashboardData.usuariosConDatos === 'object' && Array.isArray(window.dashboardData.fechas)) {
                const usuariosArray = Object.values(window.dashboardData.usuariosConDatos);
                const fechas = window.dashboardData.fechas;

                const datosPorUsuario = {};
                const metasDiariasPorUsuario = {};

                usuariosArray.forEach(usuario => {
                    const nombreUsuario = usuario.usuario_nombre;
                    datosPorUsuario[nombreUsuario] = new Array(fechas.length).fill(0);
                    metasDiariasPorUsuario[nombreUsuario] = Math.round(usuario.metaDiaria); // Redondear meta diaria

                    usuario.datos.forEach((cantidad, index) => {
                        datosPorUsuario[nombreUsuario][index] += Math.round(cantidad); // Redondear cantidad
                    });
                });

                const categoriasUsuarios = Object.keys(datosPorUsuario);
                const chartSeries = fechas.map((_, index) => {
                    return {
                        name: fechas[index],
                        data: categoriasUsuarios.map(usuario => datosPorUsuario[usuario][index])
                    };
                });

                const totalesPorUsuario = categoriasUsuarios.map(usuario => ({
                    nombre: usuario,
                    total: datosPorUsuario[usuario].reduce((acc, cantidad) => acc + cantidad, 0),
                    metaDiaria: metasDiariasPorUsuario[usuario],
                    porcentaje: usuariosArray.find(u => u.usuario_nombre === usuario).porcentajeAlcanzado
                }));

                // Ordenar usuarios por porcentaje de mayor a menor
                totalesPorUsuario.sort((a, b) => b.porcentaje - a.porcentaje);

                const categoriasOrdenadas = totalesPorUsuario.map(usuario => usuario.nombre);
                const datosOrdenados = categoriasOrdenadas.map(nombreUsuario => datosPorUsuario[nombreUsuario]);

                // Reordenar series para que los datos est�n en el mismo orden que las categor�as
                const chartSeriesOrdenado = fechas.map((_, index) => {
                    return {
                        name: fechas[index],
                        data: datosOrdenados.map(data => data[index])
                    };
                });

                const totalRevenueChartOptions = {
                    series: chartSeriesOrdenado,
                    chart: {
                        height: 300, // Reducir altura del gr�fico
                        type: 'bar',
                        stacked: true,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true, // Configurar gr�fica horizontal
                            columnWidth: '60%', // Ajustar el ancho de las columnas
                            borderRadius: 8
                        }
                    },
                    colors: [
                        'rgba(75, 192, 192, 0.2)', // Color 1
                        'rgba(153, 102, 255, 0.2)', // Color 2
                        'rgba(255, 159, 64, 0.2)', // Color 3
                        'rgba(255, 99, 132, 0.2)', // Color 4
                        'rgba(54, 162, 235, 0.2)', // Color 5
                        'rgba(255, 206, 86, 0.2)', // Color 6
                        'rgba(201, 203, 207, 0.2)', // Color 7
                        'rgba(75, 192, 192, 0.4)', // Color 8
                        'rgba(153, 102, 255, 0.4)', // Color 9
                        'rgba(255, 159, 64, 0.4)' // Color 10
                    ], // Ajustar los colores seg�n el n�mero de fechas
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px', // Reducir tama�o de las etiquetas
                            colors: ['#333']
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 1 // Reducir grosor de la l�nea
                    },
                    legend: {
                        show: true,
                        horizontalAlign: 'left',
                        position: 'top',
                        markers: {
                            height: 6, // Reducir altura de los marcadores
                            width: 6, // Reducir anchura de los marcadores
                            radius: 8, // Reducir radio de los marcadores
                            offsetX: -2
                        },
                        labels: {
                            colors: '#333',
                            fontSize: '10px', // Reducir tama�o de la fuente de la leyenda
                            formatter: function(value) {
                                return value.toUpperCase(); // Convertir texto a may�sculas
                            }
                        },
                        itemMargin: {
                            horizontal: 5 // Reducir margen horizontal de los �tems
                        }
                    },
                    grid: {
                        borderColor: '#e0e0e0',
                        padding: {
                            top: 0,
                            bottom: -8,
                            left: 20,
                            right: 20
                        }
                    },
                    xaxis: {
                        categories: categoriasOrdenadas.map(cat => cat
                            .toUpperCase()), // Convertir categor�as a may�sculas
                        labels: {
                            style: {
                                fontSize: '10px', // Reducir tama�o de las etiquetas del eje X
                                colors: '#333',
                                textTransform: 'uppercase' // Convertir texto a may�sculas
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                fontSize: '10px', // Reducir tama�o de las etiquetas del eje Y
                                colors: '#333',
                                textTransform: 'uppercase' // Convertir texto a may�sculas
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function(val, {
                                seriesIndex,
                                dataPointIndex,
                                w
                            }) {
                                const usuario = categoriasOrdenadas[seriesIndex];
                                const metaDiaria = totalesPorUsuario.find(u => u.nombre === usuario).metaDiaria;
                                return `CLIENTES: ${val} - META DIARIA: ${metaDiaria}`;
                            }
                        }
                    }
                };



                if (window.totalRevenueChartInstance) {
                    window.totalRevenueChartInstance.destroy();
                }

                const totalRevenueChart = new ApexCharts(totalRevenueChartEl, totalRevenueChartOptions);
                totalRevenueChart.render();

                // Guarda la instancia del gr�fico en una variable global para poder destruirlo m�s tarde
                window.totalRevenueChartInstance = totalRevenueChart;
            }
        }

















        //GRAFICA DE BARRAS POR USUARIO

        // Función para renderizar el gráfico de crecimiento
        function renderGrowthChart() {
            const growthChartEl = document.querySelector('#growthChart');
            if (growthChartEl !== null) {
                const totalClientes = window.dashboardData.porcentaje_alcanzado || 0;

                const growthChartOptions = {
                    series: [totalClientes],
                    labels: ['CLIENTES'],
                    chart: {
                        height: 240,
                        type: 'radialBar'
                    },
                    plotOptions: {
                        radialBar: {
                            size: 150,
                            offsetY: 10,
                            startAngle: -150,
                            endAngle: 150,
                            hollow: {
                                size: '55%'
                            },
                            track: {
                                background: '#f4f4f4',
                                strokeWidth: '100%'
                            },
                            dataLabels: {
                                name: {
                                    offsetY: 15,
                                    color: '#333',
                                    fontSize: '15px',
                                    fontWeight: '500',
                                    fontFamily: 'Public Sans'
                                },
                                value: {
                                    offsetY: -25,
                                    color: '#333',
                                    fontSize: '22px',
                                    fontWeight: '500',
                                    fontFamily: 'Public Sans'
                                }
                            }
                        }
                    },
                    colors: ['#FF4560'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            shadeIntensity: 0.5,
                            gradientToColors: ['#FF4560'],
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 0.6,
                            stops: [30, 70, 100]
                        }
                    },
                    stroke: {
                        dashArray: 5
                    },
                    grid: {
                        padding: {
                            top: -35,
                            bottom: -10
                        }
                    },
                    states: {
                        hover: {
                            filter: {
                                type: 'none'
                            }
                        },
                        active: {
                            filter: {
                                type: 'none'
                            }
                        }
                    }
                };

                // Si el gráfico ya existe, destrúyelo
                if (growthChart) {
                    growthChart.destroy();
                }

                // Crear y renderizar el gráfico
                growthChart = new ApexCharts(growthChartEl, growthChartOptions);
                growthChart.render();
            } else {
                console.error('No se encontró el elemento #growthChart');
            }
        }




















        // Función para renderizar el gráfico de pastel
        function renderPieChart(data) {
            const ctx = document.getElementById('pie-chart').getContext('2d');

            // Si el gráfico ya existe, destrúyelo
            if (pieChart) {
                pieChart.destroy();
            }

            pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    // labels: 'LLAMADA, CORREO ',
                    labels: data.map(item => item.name),
                    datasets: [{
                        label: 'LLLAMADA',
                        data: data.map(item => item.llamadas_si),


                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    }
                }
            });
        }

        function renderBarChart() {
            const servicesChartEl = document.querySelector('#servicesChart');
            if (servicesChartEl !== null) {
                const data = window.dashboardData;

                // Verificar que las fechas est�n presentes y tienen datos
                if (data.fechas && data.fechas.length > 0) {
                    // Crear un array de valores para las fechas, usando registrosPorFecha
                    const values = data.fechas.map(fecha => data.registrosPorFecha[fecha] || 0);

                    // Crear un array de metas por fecha, redondeando los valores a enteros
                    const metaValues = data.fechas.map(fecha => Math.round(data.metaPorFecha[fecha] || 0));

                    // Preparar opciones para el gr�fico de barras y l�neas
                    const servicesChartOptions = {
                        series: [{
                                name: 'Cantidad de Registros',
                                type: 'bar', // Tipo de gr�fico para esta serie
                                data: values, // Valores asociados a cada fecha
                                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color de fondo de las barras
                                borderColor: 'rgba(75, 192, 192, 1)', // Color del borde de las barras
                                borderWidth: 1 // Ancho del borde de las barras
                            },
                            {
                                name: 'Meta Diaria',
                                type: 'line', // Tipo de gr�fico para esta serie (l�nea)
                                data: metaValues, // Meta diaria para cada fecha (redondeada)
                                backgroundColor: 'rgba(255, 159, 64, 0.2)', // Color de fondo de la l�nea
                                borderColor: 'rgba(255, 159, 64, 1)', // Color del borde de la l�nea
                                borderWidth: 2 // Ancho de la l�nea
                            }
                        ],
                        chart: {
                            height: 350,
                            type: 'line', // Tipo general del gr�fico (pero ser� combinado)
                            stacked: false // No apilar las series
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                endingShape: 'rounded'
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            width: [0, 4] // Ancho de las l�neas, 0 para la barra y 4 para la l�nea
                        },
                        xaxis: {
                            categories: data.fechas, // Fechas en el eje X
                            labels: {
                                rotate: -45 // Opcional: rota las etiquetas para mejorar la visibilidad
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Cantidad'
                            }
                        },
                        fill: {
                            opacity: [1, 0.9] // Opacidad de las barras y l�neas
                        },
                        colors: ['#4BC0C0', '#FF9F40'], // Colores para las series
                        grid: {
                            borderColor: '#f1f1f1',
                            padding: {
                                left: 0,
                                right: 0
                            }
                        }
                    };

                    // Si ya existe un gr�fico, destr�yelo si es una instancia v�lida
                    if (window.servicesChart && typeof window.servicesChart.destroy === 'function') {
                        window.servicesChart.destroy();
                    }

                    // Crear y renderizar el nuevo gr�fico
                    window.servicesChart = new ApexCharts(servicesChartEl, servicesChartOptions);
                    window.servicesChart.render();
                } else {
                    console.error('No se encontraron fechas para el gr�fico.');
                }
            } else {
                console.error('No se encontr� el elemento #servicesChart');
            }
        }









        // Ejecutar renderScrollchar cuando el DOM esté completamente cargado
        // document.addEventListener("DOMContentLoaded", function() {
        //     renderScrollchar();
        // });


        // // Ejecutar renderScrollchar cuando el DOM esté completamente cargado
        // document.addEventListener("DOMContentLoaded", function() {
        //     renderScrollchar();
        // });


        function renderReunionesYCotizacionesChart(data) {
            const ctx = document.getElementById('reuniones-chart').getContext('2d');

            // Si el gráfico ya existe, destrúyelo
            if (window.reunionesChart) {
                window.reunionesChart.destroy();
            }

            // Datos para la gráfica
            const labels = ['Reunión', 'Cotización', 'Contrato Realizado'];
            const values = [data.reuniones_count, data.cotizaciones_count, data.contratos_realizados_count];

            // Colores personalizados
            const colors = [
                'rgba(75, 192, 192, 0.2)', // Reunión
                'rgba(153, 102, 255, 0.2)', // Cotización
                'rgba(255, 159, 64, 0.2)' // Contrato Realizado
            ];
            const borderColors = [
                'rgba(75, 192, 192, 1)', // Reunión
                'rgba(153, 102, 255, 1)', // Cotización
                'rgba(255, 159, 64, 1)' // Contrato Realizado
            ];

            window.reunionesChart = new Chart(ctx, {
                type: 'doughnut', // Gráfico tipo doughnut
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Cantidad',
                        data: values,
                        backgroundColor: colors,
                        borderColor: borderColors,
                        borderWidth: 2,
                        hoverOffset: 4 // Desplazamiento al pasar el cursor
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: '#333',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return `${tooltipItem.label}: ${tooltipItem.raw} `;
                                }
                            }
                        },
                        datalabels: {
                            display: true,
                            color: '#333',
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            formatter: function(value) {
                                return `${value} units`;
                            }
                        }
                    },
                    layout: {
                        padding: 20 // Espaciado alrededor del gráfico
                    },
                    cutout: '70%', // Tamaño del hueco central del doughnut
                    elements: {
                        arc: {
                            borderWidth: 2 // Ancho del borde de los segmentos
                        }
                    }
                }
            });
        }





        // Configuración del evento para los elementos del menú desplegable
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                const eventoId = this.getAttribute('data-evento-id');

                // Actualizar el texto del evento seleccionado
                document.getElementById('evento-seleccionado').textContent = this.textContent;

                // Realizar la solicitud AJAX al controlador para obtener los datos
                fetch('http://127.0.0.1:8000/obtener-meta-por-evento', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Asegúrate de incluir el token CSRF
                        },
                        body: JSON.stringify({
                            evento_id: eventoId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log("evento:", eventoId);

                            // Actualizar window.dashboardData con los datos necesarios


                            // console.log('Datos actualizados:', window.dashboardData);

                            // // Renderizar los gráficos
                            // renderGrowthChart();
                            // renderPieChart(data.usuarios_con_correos_llamadas);
                            // renderReunionesPorMesChart(data.reuniones_por_mes);

                            // // Actualizar la tabla de conteo
                            // const conteoTableBody = document.getElementById('conteodellamadasyreunion');
                            // conteoTableBody.innerHTML = '';
                            // const tableHeader = `
                            //     <thead>
                            //         <tr>
                            //             <th>#</th>
                            //             <th>Usuario</th>
                            //             <th>Correo</th>
                            //             <th>Llamada</th>
                            //             <th>Whasat</th>
                            //         </tr>
                            //     </thead>
                            // `;
                            // conteoTableBody.innerHTML += tableHeader;
                            // const tableBody = document.createElement('tbody');

                            // if (Array.isArray(data.usuarios_con_correos_llamadas)) {
                            //     data.usuarios_con_correos_llamadas.forEach((usuario, index) => {
                            //         const row = document.createElement('tr');
                            //         row.innerHTML = `
                            //             <td>${index + 1}</td>
                            //             <td>${usuario.name}</td>
                            //             <td>${usuario.correos_si}</td>
                            //             <td>${usuario.llamadas_si}</td>
                            //             <td>${usuario.whasat_si}</td>
                            //         `;
                            //         tableBody.appendChild(row);
                            //     });
                            //     conteoTableBody.appendChild(tableBody);
                            // } else {
                            //     console.error(
                            //         'La propiedad usuarios_con_correos_llamadas no es un array o no está definida'
                            //     );
                            // }

                            // Actualizar la lista de usuarios con más registros
                            const usuariosList = document.getElementById('usuarios-con-mas-registros');
                            usuariosList.innerHTML = '';
                            data.usuarios_con_mas_registros.forEach(usuario => {
                                const listItem = document.createElement('li');
                                listItem.classList.add('d-flex', 'mb-4', 'pb-1');
                                listItem.innerHTML = `
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class='bx bx-user'></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">${usuario.name}</h6>
                                    </div>
                                    <div class="user-progress d-flex align-items-center gap-1">
                                        <h6 class="mb-0">${usuario.registros}</h6>
                                    </div>
                                </div>
                            `;
                                usuariosList.appendChild(listItem);
                            });
                        } else {
                            console.error('Error al obtener datos:', data.message);
                        }
                    })
                    .catch(error => console.error('Error en la solicitud:', error));
            });
        });

        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                let eventoId = this.getAttribute('data-evento-id');

                // Actualizar el texto del evento seleccionado
                document.getElementById('evento-seleccionado').textContent = this.textContent;



                // Realizar la solicitud AJAX al controlador para obtener los datos
                fetch('http://127.0.0.1:8000/obtener-meta-por-evento', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Asegúrate de incluir el token CSRF
                        },
                        body: JSON.stringify({
                            evento_id: eventoId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {

                            console.log("evento:", eventoId);



                            // Suponiendo que `data` es la respuesta de tu solicitud AJAX
                            document.getElementById('destacadoUsuarioNombre').textContent = data
                                .nombre_usuario ? data.nombre_usuario : 'Desconocido';
                            document.getElementById('total_registros').textContent = data
                                .total_registros;
                            document.getElementById('total_llamadas_si').textContent = data
                                .total_llamadas_si;
                            document.getElementById('total_correos_si').textContent = data
                                .total_correos_si;

                            // Agregar los nombres de los usuarios para llamadas y correos
                            document.getElementById('nombre_usuario_llamadas').textContent = data
                                .nombre_usuario_llamadas ? data.nombre_usuario_llamadas : 'Desconocido';
                            document.getElementById('nombre_usuario_correos').textContent = data
                                .nombre_usuario_correos ? data.nombre_usuario_correos : 'Desconocido';

                            // Mostrar valores espec�ficos en la consola
                            console.log('Nombre Usuario con m�s registros:', data.nombre_usuario ? data
                                .nombre_usuario : 'Desconocido');
                            console.log('Total Registros:', data.total_registros);
                            console.log('Total Llamadas SI:', data.total_llamadas_si);
                            console.log('Total Correos SI:', data.total_correos_si);
                            console.log('Nombre Usuario con m�s llamadas SI:', data
                                .nombre_usuario_llamadas ? data.nombre_usuario_llamadas :
                                'Desconocido');
                            console.log('Nombre Usuario con m�s correos SI:', data
                                .nombre_usuario_correos ? data.nombre_usuario_correos :
                                'Desconocido');



                            const funnelData = {
                                labels: ['Interesados', 'Contactados', 'Calificados', 'Propuestas',
                                    'Clientes'
                                ],
                                values: [
                                    data.data,
                                    data.total_clientes_potencial,
                                    data.total_clientes_indeciso,
                                    data.total_clientes_cctv,
                                    data.total_clientes
                                ]
                            };
                            // renderFunnelChart(funnelData);



                            // Actualizar window.dashboardData con los datos necesarios
                            window.dashboardData = {
                                total_clientes: data.total_clientes,
                                usuarios_con_correos_llamadas: data.usuarios_con_correos_llamadas,
                                reuniones_count: data.reuniones_count,
                                cotizaciones_count: data.cotizaciones_count,
                                contratos_realizados_count: data.contratos_realizados_count,
                                // Incluir los datos de clientes por usuario para el gráfico de embudo
                                clientes_por_usuario: data.clientes_por_usuario,
                                // otros datos que necesites
                                dias: data.dias,
                                fechas: data.fechas,
                                usuariosConDatos: data.usuariosConDatos,
                                porcentaje_alcanzado: data.porcentaje_alcanzado,
                                fechasfechas: data.fechasfechas,
                                registrosPorFecha: data.registrosPorFecha,
                                metaPorFecha: data.metaPorFecha,

                            };

                            console.log('Datos actualizados:', window.dashboardData);

                            console.log('Preparando gráfico de embudo');

                            // renderFunnelChart(window.dashboardData.clientes_por_usuario);


                            //  console.log('Datos actualizados:', window.dashboardData);
                            totalRevenueChart();
                            renderGrowthChart();

                            // console.log(renderScrollchar)
                            renderBarChart();

                            console.log('Preparando gráfico de pastel');
                            // Renderizar el gráfico de pastel
                            renderPieChart(data.usuarios_con_correos_llamadas);

                            // Renderizar el gráfico de reuniones y cotizaciones
                            renderReunionesYCotizacionesChart({
                                reuniones_count: data.reuniones_count,
                                cotizaciones_count: data.cotizaciones_count,
                                contratos_realizados_count: data.contratos_realizados_count
                            });










                            // Actualizar las tareas pendientes en la tabla
                            //                         const tareasTableBody = document.getElementById('tareas-pendientes');
                            //                         tareasTableBody.innerHTML = '';

                            //                         // Contador para las filas
                            //                         let contador = 1;
                            //                         data.tareas_pendientes.forEach(tarea => {
                            //                             const row = document.createElement('tr');

                            //                             // Definir el color del badge según el estado
                            //                             const estadoBadge = tarea.estado === 'PENDIENTE' ?
                            //                                 '<span class="badge bg-danger fs-7">PENDIENTE</span>' :
                            //                                 '<span class="badge bg-success fs-7">REALIZADO</span>';

                            //                             // Construir el contenido de la fila
                            //                             row.innerHTML = `
                            //     <td class="fs-7">${contador++}</td> <!-- Contador que se incrementa -->
                            //     <td class="fs-7">${tarea.usuario_nombre || 'Desconocido'}</td> <!-- Mostrar nombre del usuario -->
                            //     <td class="fs-7">${tarea.titulo}</td>

                            //     <td class="fs-7">${tarea.cliente_nombre || 'Desconocido'}</td> <!-- Mostrar nombre del cliente -->
                            //     <td class="fs-7">${estadoBadge}</td>
                            // `;

                            //                             tareasTableBody.appendChild(row);
                            //                         });






                            // Actualizar la meta de registros y el total de clientes
                            document.getElementById('meta-registros').textContent = data
                                .meta_registros;
                            document.getElementById('total-clientes').textContent = data
                                .total_clientes;

                            // PENDIENTE

                            document.getElementById('total_clientes_pendiente').textContent = data
                                .total_clientes_pendiente;

                            // ATENDIDO

                            document.getElementById('total_clientes_atendido').textContent = data
                                .total_clientes_atendido;
                            // EN PROCESO

                            document.getElementById('total_clientes_en_proceso').textContent = data
                                .total_clientes_en_proceso;

                            // CONTRATO

                            document.getElementById('total_clientes_contrato').textContent = data
                                .total_clientes_contrato;


                            // // usuario por evento 
                            // document.getElementById('usuarios_por_evento').textContent = data
                            //     .usuarios_por_evento


                            // Agregando los card


                            // Total clientes
                            document.getElementById('total-clientes-card').textContent = data
                                .total_clientes;

                            // Total Clientes Potenciales
                            document.getElementById('total-potenciales-card').textContent = data
                                .total_clientes_potencial || 0;

                            // Total Clientes Indeciso
                            document.getElementById('total-indeciso-card').textContent = data
                                .total_clientes_indeciso || 0;

                            // Total de clientes Intermedio
                            document.getElementById('total-intermedio-card').textContent = data
                                .total_clientes_intermedio || 0;

                            // Agregando para servicio 
                            // Modulo
                            document.getElementById('total-servicio-modulo').textContent = data
                                .total_clientes_modulo || 0;
                            //CCTV
                            document.getElementById('total-servicio-cctv').textContent = data
                                .total_clientes_cctv || 0;

                            // SOFTWARE
                            document.getElementById('total-servicio-software').textContent = data
                                .total_clientes_software || 0;

                            // SERVICE DESK
                            document.getElementById('total-servicio-service-desk').textContent =
                                data
                                .total_clientes_service_desk || 0;


                            //  CLIENTES
                            document.getElementById('usuarios_cantidad').textContent = data
                                .cantidad_usuarios;





                            // Actualizar la tarjeta de total de clientes
                            document.getElementById('total-clientes-card-card').textContent = data
                                .total_clientes;

                            document.getElementById('total-clientes-registros').textContent = data
                                .total_clientes;

                            // Actualizar la cantidad de clientes según el tipo
                            document.getElementById('total-clientes-intermedio').textContent = data
                                .total_clientes_intermedio || 0;
                            document.getElementById('total-clientes-potencial').textContent = data
                                .total_clientes_potencial || 0;
                            document.getElementById('total-clientes-indeciso').textContent = data
                                .total_clientes_indeciso || 0;

                            // Actualizar la cantidad de clientes según el servicio
                            document.getElementById('total-clientes-cctv').textContent = data
                                .total_clientes_cctv || 0;
                            document.getElementById('total-clientes-modulo').textContent = data
                                .total_clientes_modulo || 0;
                            document.getElementById('total-clientes-software').textContent = data
                                .total_clientes_software || 0;
                            document.getElementById('total-clientes-service-desk').textContent =
                                data
                                .total_clientes_service_desk || 0;





                            // Actualizar porcentajes
                            document.getElementById('porcentaje-intermedio').textContent = (data
                                .porcentaje_intermedio || 0) + '%';
                            document.getElementById('porcentaje-potencial').textContent = (data
                                .porcentaje_potencial || 0) + '%';
                            document.getElementById('porcentaje-indecisos').textContent = (data
                                .porcentaje_indeciso || 0) + '%';


                            // CANTIDAD DE MONEY POR EVENTO
                            document.getElementById('money').textContent = data.dinero_por_evento;



                        } else {
                            console.error('Error:', data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>












    <!--/ Total Revenue -->
    <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
        <div class="row">
            <div class="col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/indeciso.png')}}" alt="Credit Card"
                                    class="rounded">
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">INDECISO</span>
                        <h3 class="card-title mb-2" id="total-indeciso-card">0</h3> <!-- ID cambiado aquí -->
                        <small class="text-success fw-semibold"><i class='bx bx-up-arrow-alt'></i></small>
                    </div>
                </div>
            </div>



            <div class="col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/intermedio.png')}}" alt="Credit Card"
                                    class="rounded">
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">INTERMEDIO</span>
                        <h3 class="card-title mb-2" id="total-intermedio-card">0</h3> <!-- ID cambiado aquí -->
                        <small class="text-success fw-semibold"><i class='bx bx-up-arrow-alt'></i></small>
                    </div>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-12 mb-4">
                <!-- Tarjeta para Usuario Destacado -->
                <div class="card shadow-sm border-0 mb-4" style="width: 430px; height: 200px;">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-sm-row align-items-start justify-content-between gap-3">
                            <div class="d-flex flex-column align-items-start">
                                <div class="d-flex align-items-center mb-1">
                                    <!-- Icono m�s peque�o -->
                                    <i class="bi bi-person-circle text-primary me-2" style="font-size: 1.2rem;"></i>
                                    <!-- T�tulo m�s peque�o -->
                                    <h5 class="text-primary fw-bold mb-0" style="font-size: 0.7rem;">Usuario Destacado
                                    </h5>
                                </div>
                            </div>
                            <div class="container mt-1 p-0">
                                <!-- Mensaje adicional -->

                                <small class="text-success d-flex align-items-center mb-1">
                                    <i class="bi bi-trophy text-warning me-1" style="font-size: 0.7rem;"></i> Mejor
                                    rendimiento del evento
                                </small>

                                <!-- Nombre del usuario destacado con tipograf�a estilizada -->
                                <h4 class="mb-1 text-uppercase" id="destacadoUsuarioNombre" style="font-size: 0.7rem;">
                                    Desconocido</h4>

                                <!-- M�tricas relevantes con estilo -->
                                <div class="mb-1">
                                    <p class="mb-0 text-muted" style="font-size: 0.8rem;">Total Registros:</p>
                                    <p class="mb-0 text-success fw-bold" style="font-size: 0.7rem;">
                                        <span id="total_registros">0</span>
                                    </p>
                                </div>

                                <div class="mb-1">
                                    <p class="mb-0 text-muted" style="font-size: 0.8rem;">Total Llamadas (SI):</p>
                                    <p class="mb-0 text-success fw-bold" style="font-size: 0.7rem;">
                                        <span id="total_llamadas_si">0</span>
                                    </p>
                                    <p class="text-muted" style="font-size: 0.7rem;">por <span
                                            id="nombre_usuario_llamadas" class="text-success fw-bold">Desconocido</span>
                                    </p>
                                </div>

                                <div class="mb-4" style="display: none">
                                    <p class="mb-1 text-muted" style="font-size: 1.2rem; font-weight: 600;">
                                        Total Correos (SI):
                                    </p>
                                    <p class="mb-1 text-success fw-bold" style="font-size: 1.5rem;">
                                        <span id="total_correos_si">0</span>
                                    </p>
                                    <p class="text-muted" style="font-size: 1.1rem;">
                                        por <span id="nombre_usuario_correos"
                                            class="text-success fw-bold">Desconocido</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Tarjeta para Usuario Más Destacado en Llamadas -->

        </div>

    </div>
</div>
</div>

<!-- Script para inicializar los gráficos -->



<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div class="row">

    <!--/ Order Statistics -->
    <div class="col-md-6 col-lg-4 order-1 mb-4">
        <div class="card h-100">
            <div class="card-header p-2">
                <h5 class="m-0 fs-6">Estadísticas </h5>
            </div>
            <div class="card-body p-2">
                <div class="mt-2">
                    <!-- Gráfica Radial -->
                    <div id="servicesChart"></div>

                    <!-- <span id="porcentajeProgreso">PORCENTAJE</span> GENERAL % -->
                </div>
            </div>
        </div>
    </div>











    <!-- Mostrar solo si el rol_id es 1 -->
    <div class="col-md-6 col-lg-4 order-2 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between p-2">
                <h5 class="card-title m-0 fs-6 me-2">SERVICIOS</h5>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                        <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                        <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                        <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                    </div>
                </div>
            </div>
            <div class="card-body p-2">
                <ul class="p-0 m-0">
                    <!-- CCTV -->
                    <li class="d-flex mb-3 pb-1 align-items-center">
                        <div class="avatar flex-shrink-0 me-2">
                            <i class="bi bi-camera-video" style="font-size: 30px; color: #4bc0c0;"></i>
                        </div>
                        <div class="w-100">
                            <small class="text-muted d-block mb-1 fs-6"></small>
                            <h6 class="mb-0 fs-6">CCTV</h6>
                            <div class="progress mt-2">
                                <div id="progressCCTV" class="progress-bar" role="progressbar"
                                    style="width: 50%; background-color: rgba(75, 192, 192, 0.2); border: 1px solid rgba(75, 192, 192, 1);"
                                    aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted fs-6" id="total-servicio-cctv">50%</small>
                            </div>
                        </div>
                    </li>

                    <!-- MODULO -->
                    <li class="d-flex mb-3 pb-1 align-items-center">
                        <div class="avatar flex-shrink-0 me-2">
                            <i class="bi bi-puzzle" style="font-size: 30px; color: #9966ff;"></i>
                        </div>
                        <div class="w-100">
                            <small class="text-muted d-block mb-1 fs-6"></small>
                            <h6 class="mb-0 fs-6">MODULO</h6>
                            <div class="progress mt-2">
                                <div id="progressModulo" class="progress-bar" role="progressbar"
                                    style="width: 30%; background-color: rgba(153, 102, 255, 0.2); border: 1px solid rgba(153, 102, 255, 1);"
                                    aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted fs-6" id="total-servicio-modulo">30%</small>
                            </div>
                        </div>
                    </li>

                    <!-- SOFTWARE -->
                    <li class="d-flex mb-3 pb-1 align-items-center">
                        <div class="avatar flex-shrink-0 me-2">
                            <i class="bi bi-cpu" style="font-size: 30px; color: #4bc0c0;"></i>
                        </div>
                        <div class="w-100">
                            <small class="text-muted d-block mb-1 fs-6"></small>
                            <h6 class="mb-0 fs-6">SOFTWARE</h6>
                            <div class="progress mt-2">
                                <div id="progressSoftware" class="progress-bar" role="progressbar"
                                    style="width: 40%; background-color: rgba(75, 192, 192, 0.2); border: 1px solid rgba(75, 192, 192, 1);"
                                    aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted fs-6" id="total-servicio-software">40%</small>
                            </div>
                        </div>
                    </li>

                    <!-- SERVICE DESK -->
                    <li class="d-flex mb-3 pb-1 align-items-center">
                        <div class="avatar flex-shrink-0 me-2">
                            <i class="bi bi-headset" style="font-size: 30px; color: #ff9f40;"></i>
                        </div>
                        <div class="w-100">
                            <small class="text-muted d-block mb-1 fs-6"></small>
                            <h6 class="mb-0 fs-6">SERVICE DESK</h6>
                            <div class="progress mt-2">
                                <div id="progressServiceDesk" class="progress-bar" role="progressbar"
                                    style="width: 20%; background-color: rgba(255, 159, 64, 0.2); border: 1px solid rgba(255, 159, 64, 1);"
                                    aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted fs-6" id="total-servicio-service-desk">20%</small>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    <!--/ Expense Overview -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Transactions -->

    <div class="col-md-6 col-lg-4 order-2 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between p-2">
                <h5 class="card-title m-0 fs-6 me-2">ESTADO DE CLIENTE</h5>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                        <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                        <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                        <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                    </div>
                </div>
            </div>
            <div class="card-body p-2">
                <ul class="p-0 m-0">
                    <li class="d-flex mb-3 pb-1 align-items-center">
                        <div class="avatar flex-shrink-0 me-2">
                            <i class="bi bi-hourglass-split" style="font-size: 30px; color: #4bc0c0;"></i>
                        </div>
                        <div class="w-100">
                            <small class="text-muted d-block mb-1 fs-6"></small>
                            <h6 class="mb-0 fs-6">PENDIENTE</h6>
                            <div class="progress mt-2">
                                <div id="progressPendiente" class="progress-bar" role="progressbar"
                                    style="width: 0%; background-color: rgba(75, 192, 192, 0.2); border: 1px solid rgba(75, 192, 192, 1);"
                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted fs-6" id="total_clientes_pendiente">50%</small>
                            </div>
                        </div>
                    </li>

                    <li class="d-flex mb-3 pb-1 align-items-center">
                        <div class="avatar flex-shrink-0 me-2">
                            <i class="bi bi-check-circle" style="font-size: 30px; color: #9966ff;"></i>
                        </div>
                        <div class="w-100">
                            <small class="text-muted d-block mb-1 fs-6"></small>
                            <h6 class="mb-0 fs-6">ATENDIDO</h6>
                            <div class="progress mt-2">
                                <div id="progressAtendido" class="progress-bar" role="progressbar"
                                    style="width: 0%; background-color: rgba(153, 102, 255, 0.2); border: 1px solid rgba(153, 102, 255, 1);"
                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted fs-6" id="total_clientes_atendido">30%</small>
                            </div>
                        </div>
                    </li>

                    <li class="d-flex mb-3 pb-1 align-items-center">
                        <div class="avatar flex-shrink-0 me-2">
                            <i class="bi bi-arrow-repeat" style="font-size: 30px; color: #4bc0c0;"></i>
                        </div>
                        <div class="w-100">
                            <small class="text-muted d-block mb-1 fs-6"></small>
                            <h6 class="mb-0 fs-6">EN PROCESO</h6>
                            <div class="progress mt-2">
                                <div id="progressProceso" class="progress-bar" role="progressbar"
                                    style="width: 0%; background-color: rgba(75, 192, 192, 0.2); border: 1px solid rgba(75, 192, 192, 1);"
                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted fs-6" id="total_clientes_en_proceso">40%</small>
                            </div>
                        </div>
                    </li>

                    <li class="d-flex mb-3 pb-1 align-items-center">
                        <div class="avatar flex-shrink-0 me-2">
                            <i class="bi bi-briefcase" style="font-size: 30px; color: #ff9f40;"></i>
                        </div>
                        <div class="w-100">
                            <small class="text-muted d-block mb-1 fs-6"></small>
                            <h6 class="mb-0 fs-6">CONTRATO</h6>
                            <div class="progress mt-2">
                                <div id="progressContrato" class="progress-bar" role="progressbar"
                                    style="width: 0%; background-color: rgba(255, 159, 64, 0.2); border: 1px solid rgba(255, 159, 64, 1);"
                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted fs-6" id="total_clientes_contrato">20%</small>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    <!-- JavaScript para actualizar las barras de progreso -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ejemplos de valores, estos pueden ser dinámicos
            const pendienteValue = 60;
            const atendidoValue = 75;
            const procesoValue = 50;
            const contratoValue = 40;

            // Función para actualizar la barra de progreso
            function updateProgressBar(id, value) {
                const progressBar = document.getElementById(id);
                const label = document.getElementById('total_' + id);
                if (progressBar && label) {
                    console.log(`Updating ${id} to ${value}%`);
                    progressBar.style.width = value + '%';
                    progressBar.setAttribute('aria-valuenow', value);
                    label.textContent = value + '%';
                } else {
                    console.log(`Element with ID ${id} or label total_${id} not found`);
                }
            }

            // Actualizar cada barra de progreso
            updateProgressBar('progressPendiente', pendienteValue);
            updateProgressBar('progressAtendido', atendidoValue);
            updateProgressBar('progressProceso', procesoValue);
            updateProgressBar('progressContrato', contratoValue);
        });
    </script>

    <!--/ Transactions -->

</div>

<div class="row">
    <!-- Tareas Pendientes -->
    <div class="col-12 col-lg-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-header p-2">
                <h5 class="m-0 fs-6">Tareas Pendientes</h5>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table id="tareas-table" class="table table-sm table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Usuario</th>
                                <th>Actividad</th>
                                <!-- <th>Observaciones</th> -->
                                <!-- <th>Fecha y Hora</th> -->
                                <th>Cliente</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tareas-pendientes">
                            <!-- Las filas de la tabla se llenarán aquí mediante JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteo de Correos y Llamadas -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between p-2">
                <h5 class="card-title m-0 fs-6">Conteo de Correos y Llamadas</h5>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="registrosFaltantesDropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="registrosFaltantesDropdown">
                        <a class="dropdown-item" href="javascript:void(0);" data-evento-id="1">Evento 1</a>
                        <a class="dropdown-item" href="javascript:void(0);" data-evento-id="2">Evento 2</a>
                        <!-- Más opciones según sea necesario -->
                    </div>
                </div>
            </div>
            <div class="card-body p-2">
                <canvas id="pie-chart" width="300" height="300"></canvas>
                <table class="table table-sm table-bordered mt-2 mb-0" id="conteodellamadasyreunion">

                </table>
            </div>
        </div>
    </div>

    <!-- Estado de Cliente -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between p-2">
                <h5 class="card-title m-0 fs-6">GRAFICA PARA REUNION, COTIZACION Y CERRADO</h5>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                        <a class="dropdown-item" href="javascript:void(0);">Últimos 28 Días</a>
                        <a class="dropdown-item" href="javascript:void(0);">Último Mes</a>
                        <a class="dropdown-item" href="javascript:void(0);">Último Año</a>
                    </div>
                </div>
            </div>
            <div class="card-body p-2">
                <canvas id="reuniones-chart" width="400" height="200"></canvas>

            </div>
        </div>
    </div>


</div>

<!-- <script>
    // Espera a que el DOM esté completamente cargado
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('estado-cliente-line-chart').getContext('2d');
        var estadoClienteLineChart = new Chart(ctx, {
            type: 'line', // Tipo de gráfico
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov',
                    'Dic'
                ], // Etiquetas para el eje x
                datasets: [{
                    label: 'Clientes Activos', // Etiqueta para la leyenda
                    data: [10, 15, 8, 20, 30, 25, 15, 18, 22, 24, 28, 30], // Datos para el gráfico
                    borderColor: 'rgba(75, 192, 192, 1)', // Color de la línea
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color de fondo de la línea
                    fill: true, // Si la línea debe estar rellena
                    tension: 0.1 // Suaviza la línea
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script> -->















<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-chart-funnel"></script>



<script>

</script>



</div>
<!--/ Transactions -->
</div>



@endsection