document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let currentScopeSemanal = 'general';
    let currentEventIdSemanal = null;
    let semanalChart = null;
    
    // Inicializar gráfico
    function initSemanalChart() {
        const dom = document.getElementById('comparacion-semanal-chart');
        if (!dom) return;
        
        semanalChart = echarts.init(dom);
        window.addEventListener('resize', function() {
            semanalChart.resize();
        });
        
        // Agregar toggle scope si no existe
        if (!document.getElementById('toggle-scope-semanal')) {
            const toggleHtml = `
                <div class="form-check form-switch ms-3">
                    <input class="form-check-input" type="checkbox" id="toggle-scope-semanal">
                    <label class="form-check-label small" for="toggle-scope-semanal">
                        <span id="scope-label-semanal">General</span>
                    </label>
                </div>
            `;
            document.querySelector('#comparacion-semanal-chart').closest('.card-body')
                .querySelector('.d-flex.align-items-center').insertAdjacentHTML('beforeend', toggleHtml);
        }
        
        setupSemanalEventListeners();
        loadSemanalData();
    }
    
    // Cargar datos
    function loadSemanalData() {
        const url = `/comparacion-semanal/${currentScopeSemanal}${currentScopeSemanal === 'evento' && currentEventIdSemanal ? '/' + currentEventIdSemanal : ''}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                renderSemanalChart(data);
            })
            .catch(error => {
                console.error('Error cargando datos semanales:', error);
                showSemanalChartError();
            });
    }
    
    // Renderizar gráfico
    function renderSemanalChart(data) {
        semanalChart.setOption({
            animationDuration: 1000,
            animationEasing: 'cubicOut',
            tooltip: {
                trigger: 'axis',
                formatter: function(params) {
                    return `${params[0].axisValue}<br/>
                            ${params[0].marker} ${params[0].seriesName}: ${params[0].data}<br/>
                            ${params[1].marker} ${params[1].seriesName}: ${params[1].data}`;
                }
            },
            legend: {
                top: 0,
                data: ['Semana Actual', 'Semana Anterior']
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom']
            },
            yAxis: {
                type: 'value',
                splitLine: { lineStyle: { type: 'dashed' } }
            },
            grid: { left: 30, right: 20, bottom: 40, top: 40 },
            series: [
                {
                    name: 'Semana Actual',
                    type: 'line',
                    smooth: true,
                    data: data.semana_actual,
                    symbol: 'circle',
                    symbolSize: 8,
                    lineStyle: { color: '#28C76F', width: 3 },
                    itemStyle: { color: '#28C76F' },
                    areaStyle: {
                        color: 'rgba(40, 199, 111, 0.1)'
                    }
                },
                {
                    name: 'Semana Anterior',
                    type: 'line',
                    smooth: true,
                    data: data.semana_anterior,
                    symbol: 'circle',
                    symbolSize: 8,
                    lineStyle: { color: '#5A8DEE', width: 3 },
                    itemStyle: { color: '#5A8DEE' },
                    areaStyle: {
                        color: 'rgba(90, 141, 238, 0.1)'
                    }
                }
            ]
        });
    }
    
    // Mostrar error
    function showSemanalChartError() {
        semanalChart.setOption({
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
    function setupSemanalEventListeners() {
        // Cambio de scope
        document.getElementById('toggle-scope-semanal')?.addEventListener('change', function() {
            currentScopeSemanal = this.checked ? 'evento' : 'general';
            document.getElementById('scope-label-semanal').textContent = this.checked ? 'Evento' : 'General';
            loadSemanalData();
        });
        
        // Cambio de evento (asumiendo que tienes un select similar al otro gráfico)
        $('#selectEvento').on('change', function() {
            currentEventIdSemanal = $(this).val();
            if (currentScopeSemanal === 'evento') {
                loadSemanalData();
            }
        });
    }
    
    // Inicializar
    initSemanalChart();
});