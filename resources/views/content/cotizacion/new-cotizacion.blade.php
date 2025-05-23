@extends('layouts/contentNavbarLayout')
@section('title', 'Nueva Cotización')
<!-- jQuery (debe estar antes de los otros scripts) -->
@section('vendor-script')
<!-- jQuery debe ir primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Librerías -->
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection





@section('content')
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<h4 class="py-3 mb-4"><span class="text-muted fw-light">Formulario /</span> Registrar Cotización</h4>
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
                <h5 class="mb-0">Información de la Cotización</h5>
                <small class="text-muted float-end">Complete todos los campos</small>
            </div>
            <div class="card-body">
                <form id="cotizacionForm" method="POST" action="#">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="codigo_cotizacion">
                                <i class="fas fa-file-invoice me-2"></i>Código de Cotización
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                <input type="text" class="form-control" id="codigo_cotizacion" name="codigo_cotizacion"
                                    value="COT-{{ strtoupper(uniqid()) }}" readonly />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="fecha_emision">
                                <i class="fas fa-calendar-alt me-2"></i>Fecha de Emisión
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                <input type="text" class="form-control flatpickr-date" id="fecha_emision"
                                    name="fecha_emision" value="{{ now()->format('Y-m-d') }}" required />
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="cliente_id">
                                <i class="fas fa-user-tie me-2"></i>Cliente
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-users"></i></span>
                                <select class="form-control select2-ajax" id="cliente_id" name="cliente_id"
                                    required></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validez">
                                <i class="fas fa-calendar-check me-2"></i>Validez (días)
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                <input type="number" class="form-control" id="validez" name="validez" value="15" min="1"
                                    required />
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="observaciones">
                            <i class="fas fa-comment me-2"></i>Observaciones
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="2"
                                placeholder="Notas adicionales sobre la cotización"></textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3"><i class="fas fa-boxes me-2"></i> Productos/Servicios</h5>

                    <div class="table-responsive mb-3">
                        <table class="table" id="productosTable">
                            <thead>
                                <tr>
                                    <th width="40%">Producto/Servicio</th>
                                    <th width="15%">Cantidad</th>
                                    <th width="20%">Precio Unitario</th>
                                    <th width="20%">Subtotal</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control select2 producto-select" name="productos[0][id]"
                                            required>
                                            <option value="">Seleccione</option>
                                            <option value="1">Módulo XYZ-2000</option>
                                            <option value="2">Servicio de instalación</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control cantidad" name="productos[0][cantidad]"
                                            min="1" value="1" required>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" class="form-control precio" name="productos[0][precio]"
                                                value="0.00" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" class="form-control subtotal" readonly value="0.00">
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-row">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" class="form-control" id="total" readonly value="0.00">
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm" id="addRow">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="fas fa-eraser me-2"></i> Limpiar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Guardar Cotización
                        </button>
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
        // Inicializar flatpickr para fechas
        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: true
        });

        // Inicializar select2
        $('.select2').select2({
            placeholder: "Seleccione una opción",
            width: '100%'
        });

        // Agregar nueva fila de producto
        let rowCount = 1;
        $('#addRow').click(function() {
            const newRow = `
                <tr>
                    <td>
                        <select class="form-control select2 producto-select" name="productos[${rowCount}][id]" required>
                            <option value="">Seleccione</option>
                            <option value="1">Módulo XYZ-2000</option>
                            <option value="2">Servicio de instalación</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control cantidad" name="productos[${rowCount}][cantidad]" 
                            min="1" value="1" required>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control precio" name="productos[${rowCount}][precio]" 
                                value="0.00" required>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control subtotal" readonly value="0.00">
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#productosTable tbody').append(newRow);
            $('.select2').select2(); // Re-inicializar select2 para la nueva fila
            rowCount++;
        });

        // Eliminar fila de producto
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            calcularTotal();
        });

        // Calcular subtotal y total cuando cambian cantidades o precios
        $(document).on('change keyup', '.cantidad, .precio', function() {
            const row = $(this).closest('tr');
            const cantidad = parseFloat(row.find('.cantidad').val()) || 0;
            const precio = parseFloat(row.find('.precio').val().replace(/[^0-9.]/g, '')) || 0;
            const subtotal = cantidad * precio;

            row.find('.subtotal').val(subtotal.toFixed(2));
            calcularTotal();
        });

        // Función para calcular el total
        function calcularTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#total').val(total.toFixed(2));
        }

        // Formatear moneda en campos de precio
        $(document).on('input', '.precio', function() {
            let value = $(this).val().replace(/[^0-9.]/g, '');
            let parts = value.split('.');
            let integerPart = parts[0] || '0';
            let decimalPart = parts.length > 1 ? '.' + parts[1].substring(0, 2) : '';
            $(this).val(integerPart + decimalPart);
        });

        // Enviar formulario con AJAX
        $('#cotizacionForm').submit(function(e) {
            e.preventDefault();

            const form = this;
            const submitButton = $(form).find('button[type="submit"]');
            const originalButtonText = submitButton.html();

            submitButton.html('<i class="fas fa-spinner fa-spin me-2"></i> Guardando...');
            submitButton.prop('disabled', true);

            // Aquí iría tu lógica AJAX para enviar el formulario
            // Similar a la que usamos en el formulario de módulos

            // Simulación de envío exitoso
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Cotización creada!',
                    text: 'La cotización se ha guardado correctamente',
                    showConfirmButton: false,
                    timer: 1500
                });
                submitButton.html(originalButtonText);
                submitButton.prop('disabled', false);
            }, 1500);
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Inicializar Select2 con AJAX
        $('#cliente_id').select2({
            placeholder: "Buscar cliente...",
            minimumInputLength: 2,
            ajax: {
                url: "{{ route('clientes.search') }}",
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term, // Término de búsqueda
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            more: (params.page * 10) < data.total
                        }
                    };
                },
                cache: true
            },
            templateResult: formatCliente,
            templateSelection: formatClienteSelection,
            escapeMarkup: function(markup) {
                return markup;
            }
        });

        // Formatear cómo se muestran los resultados
        function formatCliente(cliente) {
            if (cliente.loading) return "Buscando...";

            var markup = "<div class='select2-result-cliente'>" +
                "<div class='cliente-nombre'><strong>" + cliente.nombre + "</strong></div>";

            if (cliente.empresa) {
                markup += "<div class='cliente-empresa text-muted small'>" + cliente.empresa + "</div>";
            }

            markup += "</div>";
            return $(markup);
        }

        // Formatear cómo se muestra la selección
        function formatClienteSelection(cliente) {
            if (!cliente.id) return cliente.text;
            return cliente.nombre || cliente.text;
        }
    });
</script>
@endpush