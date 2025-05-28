@extends('layouts/contentNavbarLayout')
@section('title', 'Nueva Condicion')
<!-- jQuery (debe estar antes de los otros scripts) -->
@section('vendor-script')
<!-- jQuery debe ir primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Librerías -->
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

@endsection
@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<h4 class="py-3 mb-4"><span class="text-muted fw-light">Formulario /</span> Registrar Condición</h4>
<style>
    .select2-result-cliente .cliente-nombre {
        font-weight: bold;
        margin-bottom: 2px;
    }

    .select2-result-cliente .cliente-empresa {
        font-size: 0.85em;
        color: #6c757d;
    }

    .select2-results__option--highlighted .select2-result-cliente .cliente-empresa {
        color: #f8f9fa;
    }
</style>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Información de la Condición</h5>
                <small class="text-muted float-end">Complete todos los campos</small>
            </div>
            <div class="card-body">
                <form id="formCondicion">
                    @csrf
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="nombre">Nombre</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                placeholder="Ej: Pago al contado" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="descripcion">Descripción</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                placeholder="Ej: Pago completo al momento de la entrega" required></textarea>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Guardar Condición</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
    $(document).ready(function() {
        // Configurar token CSRF para todas las peticiones AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Interceptar el envío del formulario
        $('#formCondicion').on('submit', function(e) {
            e.preventDefault(); // Detiene el envío tradicional

            $.ajax({
                url: "{{ route('condiciones.guardar') }}",
                method: "POST",
                data: {
                    nombre: $('#nombre').val(),
                    descripcion: $('#descripcion').val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Éxito',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });

                        $('#formCondicion')[0].reset();
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    let errorMessage = '';

                    if (errors) {
                        for (let field in errors) {
                            errorMessage += errors[field][0] + '\n';
                        }
                    } else {
                        errorMessage = 'Ocurrió un error al procesar la solicitud';
                    }

                    Swal.fire({
                        title: 'Error',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        });
    });
</script>

@endpush