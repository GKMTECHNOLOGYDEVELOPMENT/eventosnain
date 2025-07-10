@extends('layouts/contentNavbarLayout')
@section('title', 'Dashboard - Analytics')

@section('vendor-style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
@endsection

@section('vendor-script')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-funnel"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts-liquidfill/dist/echarts-liquidfill.min.js"></script>
    <script src="{{ asset('assets/js/dashboard-kpis.js') }}"></script>
    <script src="{{ asset('assets/js/eventos.js') }}"></script>
    <script src="{{ asset('assets/js/modalsmetricas.js') }}"></script>
    <script src="{{ asset('assets/js/vendedoreskpis.js') }}"></script>
    <script src="{{ asset('assets/js/canales-contacto.js') }}"></script>
    <script src="{{ asset('assets/js/cotizaciones.js') }}"></script>
    <script src="{{ asset('assets/js/semanal.js') }}"></script>

        

@endpush


@section('content')
    <div class="row">
        @include('content.dashboard.partials.cards')
    </div>
@endsection
