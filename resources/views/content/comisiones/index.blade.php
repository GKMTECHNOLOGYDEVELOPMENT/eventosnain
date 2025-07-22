@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard de Comisiones')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-4 text-primary fw-bold">
                        Detalle de Comisiones por Vendedor
                    </h4>

                    <!-- Gráfico -->
                    <div class="mb-5">
                        <div id="graficoComisiones" class="rounded shadow-sm "
                            style="height: 400px; width: 100%; background-color: #ffffff;"></div>
                    </div>

                    <!-- Tabla -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Vendedor</th>
                                    <th>Total Vendido</th>
                                    <th>Avance a Cuota (%)</th>
                                    <th>Cotizaciones Aprobadas</th>
                                    <th>Comisión Total (5%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comisiones as $index => $vendedor)
                                    <tr class="text-center">
                                        <td>{{ ($comisiones->currentPage() - 1) * $comisiones->perPage() + $index + 1 }}
                                        </td>
                                        <td class="text-capitalize">{{ $vendedor->vendedor_nombre }}</td>
                                        <td><span class="fw-semibold text-success">S/
                                                {{ number_format($vendedor->total_vendido, 2) }}</span></td>
                                        <td>
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="small">{{ $vendedor->porcentaje_cuota }}%</span>
                                                <div class="progress w-75" style="height: 8px;">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                        style="width: {{ $vendedor->porcentaje_cuota }}%"
                                                        aria-valuenow="{{ $vendedor->porcentaje_cuota }}" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $vendedor->total_cotizaciones }}</td>
                                        <td>
                                            @if ($vendedor->total_comision > 0)
                                                <span class="badge bg-label-success text-uppercase">S/
                                                    {{ number_format($vendedor->total_comision, 2) }}</span>
                                            @else
                                                <span class="badge bg-label-secondary text-uppercase">No aplica</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No hay datos disponibles.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($comisiones->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $comisiones->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ECharts -->
    <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartDom = document.getElementById('graficoComisiones');
            const myChart = echarts.init(chartDom);

            const datos = @json($comisionesTotales ?? $comisiones->items());
            const nombres = datos.map(v => v.vendedor_nombre);
            const comisiones = datos.map(v => parseFloat(v.total_comision));

            const colores = [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e',
                '#e74a3b', '#858796', '#fd7e14', '#20c997',
                '#6f42c1', '#2c3e50', '#17a2b8', '#dc3545'
            ];

            const option = {
                title: {
                    text: 'Resumen de Comisiones por Vendedor',
                    left: 'center',
                    textStyle: {
                        fontSize: 16,
                        fontWeight: 'bold'
                    }
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    },
                    backgroundColor: '#fff',
                    borderColor: '#ddd',
                    borderWidth: 1,
                    textStyle: {
                        color: '#000'
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '5%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: nombres,
                    axisLabel: {
                        rotate: 20,
                        fontSize: 12
                    }
                },
                yAxis: {
                    type: 'value',
                    name: 'Comisión Total (S/.)'
                },
                series: [{
                    name: 'Comisión Total',
                    type: 'bar',
                    data: comisiones,
                    itemStyle: {
                        borderRadius: [4, 4, 0, 0],
                        color: function(params) {
                            return colores[params.dataIndex % colores.length];
                        }
                    },
                    barWidth: '60%'
                }]
            };
            myChart.setOption(option);
            window.addEventListener('resize', () => myChart.resize());
        });
    </script>
@endsection
