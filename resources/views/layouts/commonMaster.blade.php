<!DOCTYPE html>

<html class="light-style layout-menu-fixed" data-theme="theme-default" data-assets-path="{{ asset('/assets') . '/' }}"
    data-base-url="{{url('/')}}" data-framework="laravel" data-template="vertical-menu-laravel-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>GKM TECHNOLOGY </title>
    <meta name="description"
        content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
    <meta name="keywords"
        content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
    <!-- laravel CRUD token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Canonical SEO -->
    <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/icongkm/icongkm.jpg') }}" />

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.11/lottie.min.js"></script>


    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">


    <style>
        .badge-primary-custom {
            background-color: #007bff;
            /* Azul para estado primario */
            color: #fff;
        }

        .badge-warning-custom {
            background-color: #ffc107;
            /* Amarillo para estado de advertencia */
            color: #212529;
        }

        .badge-success-custom {
            background-color: #28a745;
            /* Verde para estado exitoso */
            color: #fff;
        }

        .badge-danger-custom {
            background-color: #dc3545;
            /* Rojo para estado de peligro */
            color: #fff;
        }

        .badge-info-custom {
            background-color: #17a2b8;
            /* Azul claro para información */
            color: #fff;
        }
    </style>




    <!-- <link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.dataTables.min.css"> -->

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->

    <!-- DataTables Buttons CSS
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.bootstrap5.css"> -->

    <!-- Bootstrap 5 CSS -->
    <!-- CSS de Bootstrap 5 -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"> -->

    <!-- CSS de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/jquery.dataTables.min.css">

    <!-- CSS de DataTables Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.min.css">

    <!-- CSS de DataTables Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.bootstrap5.min.css">

    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/uppy.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.steps.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.timepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/quill.snow.css') }}">

    <!-- Simple bar CSS -->
    <!-- <link rel="stylesheet" href="css/simplebar.css"> -->
    <link rel="stylesheet" href="{{ asset('assets/css/simplebar.css') }}">
    <!-- Fonts CSS -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"> -->
    <!-- Icons CSS -->

    <!-- <link rel="stylesheet" href="css/feather.css"> -->
    <link rel="stylesheet" href="{{ asset('assets/css/feather.css') }}">

    <!-- FullCalendar CSS -->

    <!-- <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" <link rel="stylesheet" href="{{ asset('assets/css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/uppy.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.steps.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.timepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/quill.snow.css') }}"> -->




    <!-- <link rel="stylesheet" href="css/select2.css">
    <link rel="stylesheet" href="css/dropzone.css">
    <link rel="stylesheet" href="css/uppy.min.css">
    <link rel="stylesheet" href="css/jquery.steps.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">
    <link rel="stylesheet" href="css/quill.snow.css"> -->





    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
    <!-- <link rel="stylesheet" href="css/daterangepicker.css"> -->


    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/app-light.css') }}" id="lightTheme">

    <!-- <link rel="stylesheet" href="css/app-light.css" id="lightTheme"> -->
    <link rel="stylesheet" href="{{ asset('assets/css/app-dark.css') }}" id="darkTheme" disabled>
    <!-- <link rel="stylesheet" href="css/app-dark.css" id="darkTheme" disabled>  -->

    <!-- Include Styles -->
    @include('layouts/sections/styles')

    <!-- Include Scripts for customizer, helper, analytics, config -->
    @include('layouts/sections/scriptsIncludes')
    <style>
        /* Ensure that the demo table scrolls */
        /* th,
    td {
        white-space: nowrap;
    }

    div.dataTables_wrapper {
        width: 800px;
        margin: 0 auto;
    }

    th input {
        width: 90%;
    } */

        /* Estilos para el preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 1);
            /* Fondo blanco con opacidad */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner {
            border: 8px solid rgba(255, 0, 0);
            /* Color de fondo del spinner */
            border-radius: 50%;
            border-top: 8px solid #000;
            /* Color del spinner */
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin-right: 20px;
            /* Espacio entre el spinner y la barra de progreso */
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Estilos para la barra de progreso */
        .progress-container {
            width: 200px;
            height: 20px;
            background-color: #f3f3f3;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            width: 0;
            background-color: #4caf50;
            text-align: center;
            line-height: 20px;
            /* Centra el texto verticalmente */
            color: white;
            border-radius: 5px;
            transition: width 0.4s ease;
        }
    </style>
</head>



<body>
    <!-- Preloader Container -->
    <!-- Preloader Container -->
    <!-- <div id="preloader"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: white; display: flex; justify-content: center; align-items: center; z-index: 9999;">
        <div id="lottie-animation" style="width: 100px; height: 100px;"></div>
    </div> -->



    <div class="preloader" id="preloader">
        <div class="spinner"></div>

    </div>





    <!-- Layout Content -->
    @yield('layoutContent')
    <!--/ Layout Content -->



    <!-- Include Scripts -->

    @include('layouts/sections/scripts')
</body>
<!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/2.1.2/js/dataTables.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.14/lottie.min.js"></script> -->
<!-- DataTables Buttons JS -->
<!-- <script src="https://cdn.datatables.net/buttons/1.7.2/js/dataTables.buttons.min.js"></script> -->

<!-- JSZip (Required for export options like Excel) -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.2.2/jszip.min.js"></script> -->

<!-- PDFMake (Required for export options like PDF) -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> -->

<!-- Buttons HTML5 (Export options) -->
<!-- <script src="https://cdn.datatables.net/buttons/1.7.2/js/buttons.html5.min.js"></script> -->

<!-- Buttons Print (Print option) -->
<!-- <script src="https://cdn.datatables.net/buttons/1.7.2/js/buttons.print.min.js"></script> -->
<!-- <script>
let table = new DataTable('#myTable');
</script> -->

<!-- <script>
new DataTable('#example', {
    layout: {
        topStart: {
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        }
    }
});
</script> -->


<script>
    new DataTable('#example');
    // $(document).ready(function() {
    //     // Setup - add a text input to each footer cell
    //     $('#example tfoot th').each(function(i) {
    //         var title = $('#example thead th')
    //             .eq($(this).index())
    //             .text();
    //         $(this).html(
    //             '<input type="text" placeholder="' + title + '" data-index="' + i + '" />'
    //         );
    //     });

    //     // DataTable
    //     var table = $('#example').DataTable({
    //         scrollY: '300px',
    //         scrollX: true,
    //         scrollCollapse: true,
    //         paging: false,
    //         fixedColumns: true,
    //         dom: 'Bfrtip', // Define dónde aparecerán los botones
    //         buttons: [
    //             'copy',
    //             'excel',
    //             'pdf',
    //             'print',
    //             'colvis' // Botón para visibilidad de columnas
    //         ]
    //     });

    //     // Filter event handler
    //     $(table.table().container()).on('keyup', 'tfoot input', function() {
    //         table
    //             .column($(this).data('index'))
    //             .search(this.value)
    //             .draw();
    //     });
    // });




    // $(document).ready(function() {
    //     $('#example').DataTable({
    //         dom: 'Bfrtip', // Define where the buttons will appear
    //         buttons: [
    //             'copy', 'excel', 'pdf', 'print', 'colvis' // Define the buttons you want to include
    //         ],
    //         // Additional DataTables configuration options if needed
    //     });
    // });
</script>



<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Bootstrap Bundle JS -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>

<!-- DataTables Bootstrap 5 Integration JS -->
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.js"></script>

<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.bootstrap5.js"></script>

<!-- JSZip (For Excel Export) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- PDFMake (For PDF Export) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- DataTables Buttons Extensions (HTML5, Print, Column Visibility) -->
<script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.colVis.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/jquery.dataTables.min.css">

<!-- DataTables Bootstrap 5 Integration CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.min.css">

<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.bootstrap5.min.css">




<SCript>
    document.addEventListener('DOMContentLoaded', function() {
        var animation = lottie.loadAnimation({
            container: document.getElementById('lottie-animation'), // el contenedor para la animación
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'C:/Users/user/Desktop/eventos-gkm/public/animations/Animation.json' // ruta al archivo JSON de la animación Lottie
        });

        // Elimina el preloader una vez que la página está completamente cargada
        window.addEventListener('load', function() {
            document.getElementById('preloader').style.display = 'none';
        });
    });
</script>

<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    var progressBar = document.getElementById('progress-bar');
    var preloader = document.getElementById('preloader');

    function updateProgress(percentage) {
        progressBar.style.width = percentage + '%';
        progressBar.textContent = percentage + '%';

        // Ocultar el preloader cuando el progreso llegue al 100%
        if (percentage >= 100) {
            setTimeout(function() {
                preloader.style.display = 'none';
            }, 500); // Espera un poco antes de ocultar el preloader
        }
    }

    // Simulación de carga progresiva
    var progress = 0;
    var interval = setInterval(function() {
        progress += 10; // Incrementa el progreso
        updateProgress(progress);

        if (progress >= 100) {
            clearInterval(interval);
        }
    }, 500); // Actualiza cada 0.5 segundos
});
</script> -->

</script>
<!-- <link rel="stylesheet" href="{{ asset('assets/js/jquery.min.js') }}"> -->
<!-- <script src="js/jquery.min.js"></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/popper.min.js') }}"> -->
<!-- <script src="js/popper.min.js"></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/moment.min.js') }}"> -->
<!-- <script src="js/moment.min.js"></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/bootstrap.min.js') }}"> -->
<!-- <script src="js/bootstrap.min.js"></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/simplebar.min.js') }}"> -->
<!-- <script src="js/simplebar.min.js"></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/daterangepicker.js') }}"> -->
<!-- <script src='js/daterangepicker.js'></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/jquery.stickOnScroll.js') }}"> -->
<!-- <script src='js/jquery.stickOnScroll.js'></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/tinycolor-min.js') }}"> -->
<!-- <script src="js/tinycolor-min.js"></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/config.js') }}"> -->
<!-- <script src="js/config.js"></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/fullcalendar.js') }}"> -->
<!-- <script src='js/fullcalendar.js'></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/fullcalendar.custom.js') }}"> -->
<!-- <script src='js/fullcalendar.custom.js'></script>  -->



<!-- <link rel="stylesheet" href="{{ asset('assets/js/jquery.mask.min.js') }}"> -->
<!-- <script src='js/jquery.mask.min.js'></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/select2.min.js') }}"> -->
<!-- <script src='js/select2.min.js'></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/jquery.steps.min.js') }}"> -->
<!-- <script src='js/jquery.steps.min.js'></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/jquery.validate.min.js') }}"> -->
<!-- <script src='js/jquery.validate.min.js'></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/jquery.timepicker.js') }}"> -->
<!-- <script src='js/jquery.timepicker.js'></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/dropzone.min.js') }}"> -->
<!-- <script src='js/dropzone.min.js'></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/uppy.min.js') }}"> -->
<!-- <script src='js/uppy.min.js'></script> -->
<!-- <link rel="stylesheet" href="{{ asset('assets/js/quill.min.js') }}"> -->
<!-- <script src='js/quill.min.js'></script>  -->

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>


</html>

<!-- <script>

</script>