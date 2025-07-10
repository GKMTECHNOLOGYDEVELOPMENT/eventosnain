document.addEventListener('DOMContentLoaded', function() {
    // Variables para los gráficos
    let liquidChartActual, liquidChartAnterior;
    
    // Inicializar gráficos liquid
    function initLiquidCharts() {
        liquidChartActual = echarts.init(document.getElementById('liquidMesActual'));
        liquidChartAnterior = echarts.init(document.getElementById('liquidMesAnterior'));
        
        window.addEventListener('resize', function() {
            liquidChartActual.resize();
            liquidChartAnterior.resize();
        });
    }

     // Función para calcular y mostrar la variación mensual
    function updateMonthlyComparison(data) {
        const montoActual = parseFloat(data.monto_mes_actual) || 0;
        const montoAnterior = parseFloat(data.monto_mes_anterior) || 0;
        
        // Calcular variación porcentual
        let variacion = 0;
        if (montoAnterior > 0) {
            variacion = ((montoActual - montoAnterior) / montoAnterior) * 100;
        } else if (montoActual > 0) {
            variacion = 100; // Si no había monto anterior pero sí actual
        }
        
        // Determinar color y signo
        const isPositive = variacion >= 0;
        const colorClass = isPositive ? 'text-success' : 'text-danger';
        const sign = isPositive ? '+' : '';
        
        // Formatear montos
        const formatSoles = (monto) => {
            return monto ? `S/ ${parseFloat(monto).toLocaleString('es-PE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}` : 'S/ 0.00';
        };
        
        // Actualizar el HTML
        const comparisonHTML = `
            <h6 class="mb-1 text-muted">Variación Mensual</h6>
            <h3 class="fw-bold ${colorClass}">${sign}${variacion.toFixed(1)}%</h3>
            <p class="text-secondary small m-0">
                ${formatSoles(data.monto_mes_actual)} este mes vs ${formatSoles(data.monto_mes_anterior)} anterior
            </p>
        `;
        
        document.querySelector('.bg-light.text-center.py-3.border-top').innerHTML = comparisonHTML;
    }

   // Función para actualizar gráficos liquid con validación
    function updateLiquidCharts(data) {
        // Validar y calcular porcentajes
        const porcentajeActual = data.total_cotizaciones > 0 ? 
            (data.cotizaciones_mes_actual / data.total_cotizaciones) : 0;
        
        const porcentajeAnterior = data.total_cotizaciones > 0 ? 
            (data.cotizaciones_mes_anterior / data.total_cotizaciones) : 0;
        
        // Actualizar gráfico mes actual
        liquidChartActual.setOption({
            series: [{
                type: 'liquidFill',
                data: [porcentajeActual],
                radius: '85%',
                itemStyle: { color: '#7367F0' },
                label: {
                    formatter: `${Math.round(porcentajeActual * 100)}%`,
                    fontSize: 26,
                    fontWeight: 'bold',
                    color: '#444'
                },
                backgroundStyle: { color: '#f4f4f4' },
                outline: {
                    borderDistance: 4,
                    itemStyle: {
                        borderWidth: 2,
                        borderColor: '#7367F0'
                    }
                }
            }]
        });
        
        // Actualizar gráfico mes anterior
        liquidChartAnterior.setOption({
            series: [{
                type: 'liquidFill',
                data: [porcentajeAnterior],
                radius: '85%',
                itemStyle: { color: '#28C76F' },
                label: {
                    formatter: `${Math.round(porcentajeAnterior * 100)}%`,
                    fontSize: 26,
                    fontWeight: 'bold',
                    color: '#444'
                },
                backgroundStyle: { color: '#f4f4f4' },
                outline: {
                    borderDistance: 4,
                    itemStyle: {
                        borderWidth: 2,
                        borderColor: '#28C76F'
                    }
                }
            }]
        });
        
        // Actualizar montos con formato de soles
        const formatSoles = (monto) => {
            return monto ? `S/ ${parseFloat(monto).toLocaleString('es-PE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}` : 'S/ 0.00';
        };
        
        $('.card-body .row.text-center .fw-bold.text-primary').text(
            formatSoles(data.monto_mes_actual)
        );
        $('.card-body .row.text-center .fw-bold.text-success').text(
            formatSoles(data.monto_mes_anterior)
        );
        
        // Actualizar comparación mensual
        updateMonthlyComparison(data);
    }


    // Función para actualizar KPIs y gráficas
    function updateKPIsAndCharts(data) {
        console.log('Datos recibidos:', data);
        
        // Actualizar KPIs
        $('#total-clientes-kpi').text(data.total_clientes || 0);
        $('#total-clientes').text(data.total_clientes || 0);
        $('#meta-registros').text(data.meta_registros || 0);
        $('#total-cotizaciones-kpi').text(data.total_cotizaciones || 0);
        $('#tasa-exito-kpi').text(Math.round(data.tasa_exito || 0) + '%');
        $('#cotizaciones-vencidas-kpi').text(data.cotizaciones_vencidas || 0);
        
        // Actualizar gráficas gauge
        renderGauge('total-clientes-chart', data.total_clientes || 0, '#696cff', 'Clientes', 
                  Math.max((data.total_clientes || 0) * 1.5, data.meta_registros || 100));
        renderGauge('total-cotizaciones-chart', data.total_cotizaciones || 0, '#03c3ec', 'Cotizaciones', 
                  Math.max((data.total_cotizaciones || 0) * 1.5, 100));
        renderGauge('tasa-exito-chart', data.tasa_exito || 0, '#71dd37', 'Éxito (%)', 100);
        renderGauge('cotizaciones-vencidas-chart', data.cotizaciones_vencidas || 0, '#ff3e1d', 'Vencidas', 
                  Math.max((data.cotizaciones_vencidas || 0) * 2, 20));
        
        // Actualizar gráficas liquid
        updateLiquidCharts(data);
    }

    // Función para renderizar gráficas gauge
    function renderGauge(elementId, valor, color, titulo, max = 100) {
        const dom = document.getElementById(elementId);
        if (!dom) return;

        dom.style.width = '100%';
        dom.style.height = '180px';

        const chart = echarts.init(dom);

        const option = {
            tooltip: {
                formatter: '{a} <br/>{b} : {c}'
            },
            series: [
                {
                    name: titulo,
                    type: 'gauge',
                    min: 0,
                    max: max,
                    progress: {
                        show: true,
                        width: 12,
                        itemStyle: {
                            color: color
                        }
                    },
                    axisLine: {
                        lineStyle: {
                            width: 10,
                            color: [[1, '#e0e0e0']]
                        }
                    },
                    axisTick: {
                        distance: -12,
                        length: 8,
                        lineStyle: {
                            color: '#999',
                            width: 1
                        }
                    },
                    splitLine: {
                        distance: -15,
                        length: 16,
                        lineStyle: {
                            color: '#999',
                            width: 2
                        }
                    },
                    axisLabel: {
                        distance: 10,
                        color: '#666',
                        fontSize: 8
                    },
                    pointer: {
                        length: '60%',
                        width: 6,
                        itemStyle: {
                            color: color
                        }
                    },
                    detail: {
                        valueAnimation: true,
                        formatter: '{value}',
                        color: '#333',
                        fontSize: 18,
                        fontWeight: 'bold',
                        offsetCenter: [0, '60%']
                    },
                    title: {
                        show: false
                    },
                    data: [
                        {
                            value: valor,
                            name: 'TOTAL'
                        }
                    ]
                }
            ]
        };

        chart.setOption(option);
        chart.resize();
    }

    // Inicializar gráficos
    initLiquidCharts();

    // Cargar datos iniciales
    fetch('/evento/general/datos')
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta');
            return response.json();
        })
        .then(data => {
            updateKPIsAndCharts(data);
            $('#evento-seleccionado').text('General');
        })
        .catch(error => {
            console.error('Error cargando datos generales:', error);
            updateKPIsAndCharts({
                total_clientes: 0,
                total_cotizaciones: 0,
                tasa_exito: 0,
                cotizaciones_vencidas: 0,
                meta_registros: 0,
                cotizaciones_mes_actual: 0,
                cotizaciones_mes_anterior: 0,
                monto_mes_actual: 0,
                monto_mes_anterior: 0
            });
        });

    // Configurar evento change para el select
    $('#selectEvento').on('change', function() {
        const eventoId = $(this).val();
        
        if(!eventoId) {
            fetch('/evento/general/datos')
                .then(response => response.json())
                .then(data => {
                    updateKPIsAndCharts(data);
                    $('#evento-seleccionado').text('General');
                });
            return;
        }
        
        fetch(`/evento/${eventoId}/datos`)
            .then(response => {
                if (!response.ok) throw new Error('Error en la respuesta');
                return response.json();
            })
            .then(data => {
                console.log('Datos del evento:', data);
                updateKPIsAndCharts(data);
                $('#evento-seleccionado').text($(this).find('option:selected').text());
            })
            .catch(error => {
                console.error('Error:', error);
                updateKPIsAndCharts({
                    total_clientes: 0,
                    total_cotizaciones: 0,
                    tasa_exito: 0,
                    cotizaciones_vencidas: 0,
                    meta_registros: 0,
                    cotizaciones_mes_actual: 0,
                    cotizaciones_mes_anterior: 0,
                    monto_mes_actual: 0,
                    monto_mes_anterior: 0
                });
                $('#evento-seleccionado').text('Error');
            });
    });
});