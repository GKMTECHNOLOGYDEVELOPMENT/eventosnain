document.addEventListener('DOMContentLoaded', function () {
    const totalClientes = 142;
    const totalCotizaciones = 128;
    const tasaExito = 61;
    const cotizacionesVencidas = 14;

    function renderGauge(elementId, valor, color, titulo, max = 1000) {
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
                            color: color // color personalizado de la barra de carga
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


    renderGauge('total-clientes-chart', totalClientes, '#696cff', 'Clientes');
    renderGauge('total-cotizaciones-chart', totalCotizaciones, '#03c3ec', 'Cotizaciones');
    renderGauge('tasa-exito-chart', tasaExito, '#71dd37', 'Éxito (%)', 100);
    renderGauge('cotizaciones-vencidas-chart', cotizacionesVencidas, '#ff3e1d', 'Vencidas', 100);



    const chartActual = echarts.init(document.getElementById('liquidMesActual'));
    chartActual.setOption({
        series: [{
            type: 'liquidFill',
            data: [0.81],
            radius: '85%',
            itemStyle: { color: '#7367F0' },
            label: {
                formatter: '81%',
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

    const chartAnterior = echarts.init(document.getElementById('liquidMesAnterior'));
    chartAnterior.setOption({
        series: [{
            type: 'liquidFill',
            data: [0.69],
            radius: '85%',
            itemStyle: { color: '#28C76F' },
            label: {
                formatter: '69%',
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

    window.addEventListener('resize', () => {
        chartActual.resize();
        chartAnterior.resize();
    });

    // Seguimiento Activo (torta)
    const seguimientoChartDom = document.getElementById('seguimiento-activo-chart');
    if (seguimientoChartDom) {
        const seguimientoChart = echarts.init(seguimientoChartDom);
        seguimientoChart.setOption({
            tooltip: { trigger: 'item', formatter: '{b}: {c} ({d}%)' },
            legend: { bottom: 0, left: 'center' },
            series: [{
                name: 'Interacciones',
                type: 'pie',
                radius: '65%',
                data: [
                    { value: 45, name: 'Con Interacción' },
                    { value: 55, name: 'Sin Interacción' }
                ],
                itemStyle: { borderRadius: 8 }
            }]
        });

        seguimientoChart.on('click', function (params) {
            if (params.name === 'Con Interacción') {
                const modal = new bootstrap.Modal(document.getElementById('modalClientesInteraccion'));
                modal.show();

                const clientes = [
                    { nombre: 'Juan Pérez', telefono: '51912345678' },
                    { nombre: 'Ana Torres', telefono: '51987654321' },
                    { nombre: 'Carlos Mejía', telefono: '51945678901' }
                ];

                let html = `
                    <table class="table table-sm table-striped">
                        <thead><tr><th>Nombre</th><th>WhatsApp</th></tr></thead>
                        <tbody>`;
                clientes.forEach(c => {
                    html += `
                        <tr>
                            <td>${c.nombre}</td>
                            <td>
                                <a href="https://wa.me/${c.telefono}" target="_blank" class="btn btn-success btn-sm">
                                    Contactar
                                </a>
                            </td>
                        </tr>`;
                });
                html += `</tbody></table>`;
                document.getElementById('tabla-clientes-interaccion').innerHTML = html;
            }
        });
    }

    const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May'];
    const clientes = ['Cliente A', 'Cliente B', 'Cliente C', 'Cliente D'];
    const vendedores = ['Vendedor 1', 'Vendedor 2', 'Vendedor 3', 'Vendedor 4'];

    const datosClientes = {
        'Cliente A': [5000, 8000, 12000, 14000, 16000],
        'Cliente B': [4000, 6000, 7000, 9000, 9500],
        'Cliente C': [3000, 5000, 7000, 10000, 11000],
        'Cliente D': [2000, 4000, 6000, 8000, 10000],
    };

    const datosVendedores = {
        'Vendedor 1': [5, 10, 14, 17, 20],
        'Vendedor 2': [4, 8, 12, 15, 19],
        'Vendedor 3': [6, 9, 11, 14, 18],
        'Vendedor 4': [3, 7, 9, 12, 16],
    };

    function buildLineRaceOption(categorias, datos, meses) {
        const series = categorias.map(nombre => ({
            name: nombre,
            type: 'line',
            data: datos[nombre],
            showSymbol: false,
            smooth: true,
            emphasis: {
                focus: 'series'
            }
        }));

        return {
            animationDuration: 1000,
            animationEasing: 'linear',
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                top: 10,
                data: categorias
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: meses
            },
            yAxis: {
                type: 'value'
            },
            series: series
        };
    }

    echarts.init(document.getElementById('line-race-clientes')).setOption(
        buildLineRaceOption(clientes, datosClientes, meses)
    );

    echarts.init(document.getElementById('line-race-vendedores')).setOption(
        buildLineRaceOption(vendedores, datosVendedores, meses)
    );


    const semanalChartDom = document.getElementById('comparacion-semanal-chart');
    if (semanalChartDom) {
        const semanalChart = echarts.init(semanalChartDom);

        const option = {
            animationDuration: 1000,
            animationEasing: 'cubicOut',
            tooltip: {
                trigger: 'axis'
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
                    data: [20, 35, 30, 40, 50, 55, 60],
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
                    data: [18, 25, 28, 33, 40, 42, 48],
                    symbol: 'circle',
                    symbolSize: 8,
                    lineStyle: { color: '#5A8DEE', width: 3 },
                    itemStyle: { color: '#5A8DEE' },
                    areaStyle: {
                        color: 'rgba(90, 141, 238, 0.1)'
                    }
                }
            ]
        };

        semanalChart.setOption(option);
    }

    const treemapServiciosDom = document.getElementById('treemap-servicios');
    if (treemapServiciosDom) {
        const chart = echarts.init(treemapServiciosDom);
        chart.setOption({
            animationDuration: 1000,
            animationEasing: 'cubicOut',
            tooltip: {
                formatter: '{b}: S/ {c}'
            },
            series: [{
                type: 'treemap',
                roam: false,
                nodeClick: false,
                label: {
                    show: true,
                    formatter: '{b}\nS/ {c}',
                    fontSize: 14,
                    color: '#fff'
                },
                upperLabel: { show: false },
                breadcrumb: { show: false },
                itemStyle: {
                    borderColor: '#fff',
                    borderWidth: 2,
                    gapWidth: 4
                },
                data: [
                    { name: 'Infraestructura', value: 24000 },
                    { name: 'Desarrollo', value: 18500 },
                    { name: 'Soporte Técnico', value: 12200 },
                    { name: 'Consultoría', value: 9600 },
                    { name: 'Licencias', value: 5100 }
                ]
            }]
        });
    }




    const chartPromedioDom = document.getElementById('chart-promedio-cotizaciones');
    let chartPromedio = null;

    function renderChartPromedio(anio) {
        if (!chartPromedioDom) return;

        if (!chartPromedio) chartPromedio = echarts.init(chartPromedioDom);

        // Simula datos por año (en producción lo sacas por AJAX o back)
        const dataPorAnio = {
            2025: [1350, 1420, 1200, 1550, 1480, 1620],
            2024: [1100, 1250, 980, 1300, 1200, 1400],
            2023: [900, 1050, 890, 1150, 990, 1250]
        };

        chartPromedio.setOption({
            tooltip: {
                trigger: 'axis',
                formatter: '{b}: S/ {c}'
            },
            xAxis: {
                type: 'category',
                data: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                axisLabel: { fontSize: 13 }
            },
            yAxis: {
                type: 'value',
                name: 'S/',
                splitLine: { lineStyle: { type: 'dashed' } }
            },
            grid: { left: 50, right: 30, bottom: 50, top: 40 },
            series: [{
                name: 'Promedio por Cotización',
                type: 'line',
                data: dataPorAnio[anio] || [],
                smooth: true,
                symbolSize: 8,
                lineStyle: { width: 3, color: '#7367F0' },
                itemStyle: { color: '#7367F0' }
            }]
        });
    }

    document.getElementById('anio-promedio').addEventListener('change', function () {
        renderChartPromedio(this.value);
    });

    // Inicial
    renderChartPromedio('2025');

const estadoCotizacionesDom = document.getElementById('estado-cotizaciones-chart');
if (estadoCotizacionesDom) {
    const chart = echarts.init(estadoCotizacionesDom);
    chart.setOption({
        tooltip: {
            trigger: 'axis',
            axisPointer: { type: 'shadow' },
            formatter: function(params) {
                return `${params[0].name}: ${params[0].value}`;
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
                { value: 68, name: 'Aprobadas' },
                { value: 34, name: 'Pendientes' },
                { value: 26, name: 'Rechazadas' }
            ],
            barWidth: '50%',
            itemStyle: {
                borderRadius: [6, 6, 0, 0],
                color: function(params) {
                    const colors = {
                        'Aprobadas': '#28C76F',
                        'Pendientes': '#FF9F43',
                        'Rechazadas': '#EA5455'
                    };
                    return colors[params.name] || '#888';
                }
            },
            label: {
                show: true,
                position: 'top',
                fontWeight: 'bold',
                color: '#555',
                formatter: '{@value}'
            }
        }]
    });
}


});

$(document).ready(function() {
    $('#selectEvento').select2({
        placeholder: 'Buscar evento...',
        width: '100%'
    });
});