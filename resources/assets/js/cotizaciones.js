document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let currentScope = 'general';
    let currentEventId = null;
    let currentYear = new Date().getFullYear();
    
    // Gráficos
    let chartPromedio, chartEstados;
    
    // Inicializar gráficos
    function initCharts() {
        chartPromedio = echarts.init(document.getElementById('chart-promedio-cotizaciones'));
        chartEstados = echarts.init(document.getElementById('estado-cotizaciones-chart'));
        
        window.addEventListener('resize', function() {
            chartPromedio.resize();
            chartEstados.resize();
        });
    }
    
    // Cargar datos iniciales
    function loadInitialData() {
        loadPromedioData();
        loadEstadosData();
    }
    
    // Cargar datos de promedio
    function loadPromedioData() {
        const url = `/cotizaciones-promedio/${currentScope}${currentScope === 'evento' && currentEventId ? '/' + currentEventId : ''}?anio=${currentYear}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                renderPromedioChart(data);
            })
            .catch(error => {
                console.error('Error cargando datos de promedio:', error);
                showChartError(chartPromedio);
            });
    }
    
    // Cargar datos de estados
    function loadEstadosData() {
        const url = `/cotizaciones-estado/${currentScope}${currentScope === 'evento' && currentEventId ? '/' + currentEventId : ''}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                renderEstadosChart(data);
            })
            .catch(error => {
                console.error('Error cargando datos de estados:', error);
                showChartError(chartEstados);
            });
    }
    
 function renderPromedioChart(data) {
    // Asegurarnos que los promedios sean números válidos
    const promedios = data.promedios.map(val => {
        const num = Number(val);
        return isNaN(num) ? 0 : num;
    });

    chartPromedio.setOption({
        tooltip: {
            trigger: 'axis',
            formatter: function(params) {
                const value = params[0].data;
                return `${params[0].axisValue}: S/ ${value.toLocaleString('es-PE', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })}`;
            }
        },
        xAxis: {
            type: 'category',
            data: data.meses,
            axisLabel: { 
                fontSize: 13,
                rotate: data.meses.length > 6 ? 45 : 0,
                interval: 0
            }
        },
        yAxis: {
            type: 'value',
            name: 'S/',
            axisLabel: {
                formatter: 'S/ {value}'
            },
            splitLine: { lineStyle: { type: 'dashed' } }
        },
        grid: { 
            left: 50, 
            right: 30, 
            bottom: data.meses.length > 6 ? 70 : 50, 
            top: 40 
        },
        series: [{
            name: 'Promedio por Cotización',
            type: 'line',
            data: promedios,
            smooth: true,
            symbolSize: 8,
            lineStyle: { width: 3, color: '#7367F0' },
            itemStyle: { color: '#7367F0' }
        }]
    });
}
    
    function renderEstadosChart(data) {
    // Asegurarnos que los valores sean números y que el total sea mayor que cero
    const total = Number(data.total) || 1; // Evitar división por cero
    const aprobadas = Number(data.estados.Aprobadas) || 0;
    const pendientes = Number(data.estados.Pendientes) || 0;
    const rechazadas = Number(data.estados.Rechazadas) || 0;

    chartEstados.setOption({
        tooltip: {
            trigger: 'axis',
            axisPointer: { type: 'shadow' },
            formatter: function(params) {
                const value = params[0].data;
                const porcentaje = total > 0 ? Math.round((value / total) * 100) : 0;
                return `${params[0].name}: ${value} (${porcentaje}%)`;
            }
        },
        xAxis: {
            type: 'category',
            data: ['Aprobadas', 'Pendientes', 'Rechazadas'],
            axisLabel: { fontSize: 13 }
        },
        yAxis: {
            type: 'value',
            splitLine: { lineStyle: { type: 'dashed' } }
        },
        grid: { left: 40, right: 20, bottom: 40, top: 30 },
        series: [{
            type: 'bar',
            data: [
                { 
                    value: aprobadas, 
                    name: 'Aprobadas',
                    itemStyle: { color: '#28C76F' }
                },
                { 
                    value: pendientes, 
                    name: 'Pendientes',
                    itemStyle: { color: '#FF9F43' }
                },
                { 
                    value: rechazadas, 
                    name: 'Rechazadas',
                    itemStyle: { color: '#EA5455' }
                }
            ],
            barWidth: '50%',
            itemStyle: {
                borderRadius: [6, 6, 0, 0]
            },
            label: {
                show: true,
                position: 'top',
                fontWeight: 'bold',
                color: '#555',
                formatter: function(params) {
                    const porcentaje = total > 0 ? Math.round((params.data / total) * 100) : 0;
                    return `${params.data} (${porcentaje}%)`;
                }
            }
        }]
    });
}
    
    // Mostrar error en gráfico
    function showChartError(chart) {
        chart.setOption({
            title: {
                text: 'Error cargando datos',
                subtext: 'Intente nuevamente más tarde',
                left: 'center',
                top: 'center',
                textStyle: {
                    color: '#ff4d4f',
                    fontSize: 16,
                    fontWeight: 'bold'
                },
                subtextStyle: {
                    color: '#666',
                    fontSize: 14
                }
            },
            xAxis: { show: false },
            yAxis: { show: false },
            series: []
        });
    }
    
    // Configurar eventos
    function setupEventListeners() {
        // Cambio de año
        document.getElementById('anio-promedio').addEventListener('change', function() {
            currentYear = this.value;
            loadPromedioData();
        });
        
        // Cambio de evento
        $('#selectEvento').on('change', function() {
            currentEventId = $(this).val();
            if (currentScope === 'evento') {
                loadPromedioData();
                loadEstadosData();
            }
        });
        
        // Cambio entre general/evento (si tienes un toggle)
        const scopeToggle = document.getElementById('toggle-scope');
        if (scopeToggle) {
            scopeToggle.addEventListener('change', function() {
                currentScope = this.checked ? 'evento' : 'general';
                loadPromedioData();
                loadEstadosData();
            });
        }
    }
    
    // Inicializar todo
    initCharts();
    setupEventListeners();
    loadInitialData();
});