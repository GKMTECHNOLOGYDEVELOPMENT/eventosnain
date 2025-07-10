document.addEventListener('DOMContentLoaded', function() {
    // Variables para los gráficos
    let chartClientes, chartVendedores;
    let currentEventId = 'general';
    
    // Inicializar gráficos
    function initCharts() {
        chartClientes = echarts.init(document.getElementById('line-race-clientes'));
        chartVendedores = echarts.init(document.getElementById('line-race-vendedores'));
        
        window.addEventListener('resize', function() {
            chartClientes.resize();
            chartVendedores.resize();
        });
    }
    
    // Función para construir opciones del gráfico
    function buildLineRaceOption(categorias, datos, meses, title) {
        const series = categorias.map(nombre => ({
            name: nombre,
            type: 'line',
            data: datos[nombre] || Array(meses.length).fill(0),
            showSymbol: true,
            smooth: true,
            lineStyle: {
                width: 3
            },
            emphasis: {
                focus: 'series',
                itemStyle: {
                    borderWidth: 2,
                    shadowBlur: 10,
                    shadowColor: 'rgba(0, 0, 0, 0.3)'
                }
            }
        }));

        return {
            title: {
                text: title,
                left: 'center',
                textStyle: {
                    fontSize: 14,
                    color: '#666'
                }
            },
            animationDuration: 1000,
            animationEasing: 'cubicInOut',
            tooltip: {
                trigger: 'axis',
                formatter: function(params) {
                    let result = params[0].axisValue + '<br>';
                    params.forEach(param => {
                        const value = title.includes('Monto') ? 
                            `S/ ${param.value.toLocaleString('es-PE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}` : 
                            param.value;
                        result += `${param.marker} ${param.seriesName}: <strong>${value}</strong><br>`;
                    });
                    return result;
                }
            },
            legend: {
                top: 30,
                data: categorias,
                type: 'scroll',
                pageIconColor: '#666',
                pageTextStyle: {
                    color: '#666'
                }
            },
            grid: {
                top: 80,
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: meses,
                axisLine: {
                    lineStyle: {
                        color: '#ccc'
                    }
                }
            },
            yAxis: {
                type: 'value',
                axisLine: {
                    lineStyle: {
                        color: '#ccc'
                    }
                },
                axisLabel: {
                    formatter: function(value) {
                        return title.includes('Monto') ? 
                            `S/ ${value.toLocaleString('es-PE')}` : 
                            value;
                    }
                }
            },
            series: series
        };
    }
    
    // Cargar datos iniciales
    function loadInitialData() {
        fetch('/top-metricas/general')
            .then(response => response.json())
            .then(data => {
                updateCharts(data);
            })
            .catch(error => {
                console.error('Error cargando datos iniciales:', error);
                // Mostrar datos vacíos en caso de error
                updateCharts({
                    meses: ['Ene', 'Feb', 'Mar', 'Abr', 'May'],
                    top_clientes: [],
                    datos_clientes: {},
                    top_vendedores: [],
                    datos_vendedores: {}
                });
            });
    }
    
    // Actualizar gráficos con nuevos datos
    function updateCharts(data) {
        // Gráfico de clientes
        chartClientes.setOption(
            buildLineRaceOption(
                data.top_clientes, 
                data.datos_clientes, 
                data.meses,
                'Evolución de Montos Cotizados'
            ),
            true
        );
        
        // Gráfico de vendedores
        chartVendedores.setOption(
            buildLineRaceOption(
                data.top_vendedores, 
                data.datos_vendedores, 
                data.meses,
                'Evolución de Cotizaciones'
            ),
            true
        );
    }
    
    // Configurar evento change para el select
    $('#selectEvento').on('change', function() {
        currentEventId = $(this).val() || 'general';
        fetch(`/top-metricas/${currentEventId}`)
            .then(response => response.json())
            .then(data => {
                updateCharts(data);
            })
            .catch(error => {
                console.error('Error cargando datos del evento:', error);
            });
    });
    
    // Inicializar todo
    initCharts();
    loadInitialData();
});