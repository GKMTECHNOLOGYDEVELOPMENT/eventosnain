@extends('layouts/contentNavbarLayout')
@section('title', 'Dashboard - Analytics')

@section('vendor-style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

@endsection

@section('vendor-script')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-funnel"></script>
@endsection

@section('page-script')

@endsection

@push('scripts')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts-liquidfill/dist/echarts-liquidfill.min.js"></script>
    <script src="{{ asset('assets/js/seguimiento/seguimiento.js') }}"></script>
    <script src="{{ asset('assets/js/seguimiento/cliente.js') }}"></script>
    <script src="{{ asset('assets/js/seguimiento/funel.js') }}"></script>
        <script src="{{ asset('assets/js/seguimiento/contactabilidad.js') }}"></script>

        <script src="{{ asset('assets/js/seguimiento/estado.js') }}"></script>
        <script src="{{ asset('assets/js/seguimiento/fecha.js') }}"></script>

contactabilidad.js
        

@endpush


@section('content')
    <div class="row">
        @include('content.dashboard.seguimiento.cards')
    </div>
@endsection
