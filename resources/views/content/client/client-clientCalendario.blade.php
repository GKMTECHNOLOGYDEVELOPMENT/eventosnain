@extends('layouts/contentNavbarLayout')
@section('title', 'Calendario')
@section('page-title', 'Calendario')


    <body data-sidebar="colored">
    @endsection
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    @endpush

    @section('content') 
        <style>
            #calendar {
                max-width: 100%;
                margin: 0 auto;
                padding: 20px;
                background-color: white;
                border-radius: 8px;
                min-height: 600px;
            }
        </style>

        <div class="container">
            <div id="calendar"></div>
        </div>
    @endsection

    @section('scripts')

        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
        <script src="{{ asset('assets/js/calendar.js') }}"></script>
    @endsection
