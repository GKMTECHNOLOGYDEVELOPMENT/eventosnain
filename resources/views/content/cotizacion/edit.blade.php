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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

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
                <form id="cotizacionForm" method="POST" data-id="{{ $cotizacion->id }}">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="codigo_cotizacion">
                                <i class="fas fa-file-invoice me-2"></i>Código de Cotización
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                <input type="text" class="form-control" id="codigo_cotizacion" name="codigo_cotizacion"
                                    value="{{ $cotizacion->codigo_cotizacion }}" readonly />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="fecha_emision">
                                <i class="fas fa-calendar-alt me-2"></i>Fecha de Emisión
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                <input type="text" class="form-control flatpickr-date" id="fecha_emision"
                                    name="fecha_emision"
                                    value="{{ \Carbon\Carbon::parse($cotizacion->fecha_emision)->format('Y-m-d') }}"
                                    required />
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
                                <select class="form-control" id="cliente_id" name="cliente_id">
                                    @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}"
                                        {{ $cliente->id == $cotizacion->cliente_id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }} - {{ $cliente->empresa }}
                                    </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="validez">
                                <i class="fas fa-calendar-check me-2"></i>Validez (días)
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                <input type="number" class="form-control" id="validez" name="validez"
                                    value="{{ old('validez', $cotizacion->validez) }}" min="1"/>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="condiciones_comerciales">
                                <i class="fas fa-handshake me-2"></i>Condiciones Comerciales
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-file-contract"></i></span>
                                <select class="form-control" id="condiciones_comerciales" name="condiciones_comerciales" onchange="actualizarObservaciones()">
                                    <option value="">Seleccione una opción</option>
                                    @foreach($condicionesComerciales as $condicion)
                                    <option value="{{ $condicion->id }}"
                                        {{ $cotizacion->condiciones_comerciales == $condicion->id ? 'selected' : '' }}>
                                        {{ $condicion->nombre }}
                                    </option>
                                    @endforeach
                                </select>
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
                                placeholder="Notas adicionales sobre la cotización">{{ old('observaciones', $cotizacion->observaciones) }}</textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3"><i class="fas fa-boxes me-2"></i> Productos/Servicios</h5>


                    <!-- Justo antes de la tabla agrega el botón para agregar filas -->
                    <button type="button" id="addRow" class="btn btn-success mb-3">
                        <i class="fas fa-plus"></i> Agregar Producto
                    </button>
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
                                @foreach($cotizacion->detalleProductos as $i => $detalle)
                                <tr>
                                    <td>
                                        <select class="form-control producto-select" name="productos[{{ $i }}][id]"
                                            required>
                                            <option value="">Seleccione un módulo</option>
                                            @foreach($modulos as $modulo)
                                            <option value="{{ $modulo->id }}" data-precio="{{ $modulo->precio_venta }}"
                                                {{ $modulo->id == $detalle->modulo_id ? 'selected' : '' }}>
                                                {{ $modulo->codigo_modulo }} - {{ $modulo->marca }}
                                                {{ $modulo->modelo }}
                                                - ${{ number_format($modulo->precio_venta, 2) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control cantidad"
                                            name="productos[{{ $i }}][cantidad]" value="{{ $detalle->cantidad }}"
                                            required>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" class="form-control precio"
                                                name="productos[{{ $i }}][precio]"
                                                value="{{ number_format($detalle->precio_unitario, 2, '.', '') }}"
                                                required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" class="form-control subtotal" readonly
                                                value="{{ number_format($detalle->subtotal, 2, '.', '') }}">
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-row">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach


                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" class="form-control" id="subtotal_sin_igv" readonly
                                                value="0.00">
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>IGV (18%):</strong></td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" class="form-control" id="igv" readonly value="0.00">
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total con IGV:</strong></td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" class="form-control" id="total_con_igv" readonly
                                                value="0.00">
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>

                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="fas fa-eraser me-2"></i> Limpiar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Actualizar Cotización
                        </button>
                        <button type="button" class="btn btn-secondary" id="generarPDF">
                            <i class="fas fa-file-pdf me-2"></i> Descargar PDF
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@php
$modulosOptions = '';
foreach($modulos as $modulo) {
$modulosOptions .= '<option value="'.$modulo->id.'" data-precio="'.$modulo->precio_venta.'">';
    $modulosOptions .= $modulo->codigo_modulo.' - '.$modulo->marca.' '.$modulo->modelo.' -
    $'.number_format($modulo->precio_venta, 2);
    $modulosOptions .= '</option>';
}
@endphp




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
                        <select class="form-control producto-select" name="productos[${rowCount}][id]" required>
                            <option value="">Seleccione un módulo</option>
                            {!! $modulosOptions !!}
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
            rowCount++;
        });

        // Eliminar fila de producto
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            calcularTotal();
        });

        // Cuando cambia el producto seleccionado, actualizar precio automáticamente
        $(document).on('change', '.producto-select', function() {
            const selectedOption = $(this).find('option:selected');
            const precio = selectedOption.data('precio') || 0;
            const row = $(this).closest('tr');

            row.find('.precio').val(precio.toFixed(2));
            actualizarSubtotal(row);
            calcularTotal();
        });

        // Calcular subtotal y total cuando cambian cantidades o precios
        $(document).on('input change keyup', '.cantidad, .precio', function() {
            const row = $(this).closest('tr');
            actualizarSubtotal(row);
            calcularTotal();
        });

        // Función para actualizar subtotal de una fila
        function actualizarSubtotal(row) {
            const cantidad = parseFloat(row.find('.cantidad').val()) || 0;
            const precio = parseFloat(row.find('.precio').val().replace(/[^0-9.]/g, '')) || 0;
            const subtotal = cantidad * precio;
            row.find('.subtotal').val(subtotal.toFixed(2));
        }

        // Función para calcular el total
        function calcularTotal() {
            let subtotal = 0;
            $('.subtotal').each(function() {
                subtotal += parseFloat($(this).val()) || 0;
            });

            const igv = subtotal * 0.18;
            const total = subtotal + igv;

            $('#subtotal_sin_igv').val(subtotal.toFixed(2));
            $('#igv').val(igv.toFixed(2));
            $('#total_con_igv').val(total.toFixed(2));
        }

        // Formatear moneda en campos de precio
        $(document).on('input', '.precio', function() {
            let value = $(this).val().replace(/[^0-9.]/g, '');
            let parts = value.split('.');
            let integerPart = parts[0] || '0';
            let decimalPart = parts.length > 1 ? '.' + parts[1].substring(0, 2) : '';
            $(this).val(integerPart + decimalPart);
        });

        // Inicializar el cálculo de totales
        calcularTotal();

        // Enviar formulario con AJAX
        $('#cotizacionForm').submit(function(e) {
            e.preventDefault();

            const form = this;
            const submitButton = $(form).find('button[type="submit"]');
            const originalButtonText = submitButton.html();

            submitButton.html('<i class="fas fa-spinner fa-spin me-2"></i> Guardando...');
            submitButton.prop('disabled', true);

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
        let rowCount = 1; // Contador para los índices de productos

        // Función para recalcular subtotales y totales
        function calcularTotal() {
            let subtotal = 0;
            $('.subtotal').each(function() {
                subtotal += parseFloat($(this).val()) || 0;
            });

            const igv = subtotal * 0.18;
            const total = subtotal + igv;

            $('#subtotal_sin_igv').val(subtotal.toFixed(2));
            $('#igv').val(igv.toFixed(2));
            $('#total_con_igv').val(total.toFixed(2));
        }

        // Actualizar subtotal en una fila al cambiar cantidad o precio
        function actualizarSubtotal(row) {
            const cantidad = parseFloat(row.find('.cantidad').val()) || 0;
            const precio = parseFloat(row.find('.precio').val().replace(/[^0-9.]/g, '')) || 0;
            const subtotal = cantidad * precio;
            row.find('.subtotal').val(subtotal.toFixed(2));
        }

        // Función para configurar el evento change en un select de producto
        function configurarSelectProducto(select) {
            $(select).on('change', function() {
                const selectedOption = $(this).find('option:selected');
                // Convertir el precio a número antes de usarlo
                const precio = parseFloat(selectedOption.data('precio')) || 0;
                const row = $(this).closest('tr');

                row.find('.precio').val(precio.toFixed(2));
                actualizarSubtotal(row);
                calcularTotal();
            });
        }

        // Configurar el evento change para el select de la primera fila
        configurarSelectProducto('.producto-select:first');

        // Evento para agregar nueva fila al hacer click en "+"
        $('#addRow').click(function() {
            const newRow = `
                <tr>
                    <td>
                        <select class="form-control producto-select" name="productos[${rowCount}][id]" required>
                            <option value="">Seleccione un módulo</option>
                            {!! $modulosOptions !!}
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

            // Configurar el evento change para el nuevo select
            configurarSelectProducto($('#productosTable tbody tr:last .producto-select'));

            rowCount++;
            calcularTotal();
        });

        // Evento para eliminar fila
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            calcularTotal();
        });

        // Cuando cambian cantidad o precio, recalcular subtotal y total
        $(document).on('input change keyup', '.cantidad, .precio', function() {
            const row = $(this).closest('tr');
            actualizarSubtotal(row);
            calcularTotal();
        });

        // Inicializa el subtotal y totales al cargar
        calcularTotal();
    });
</script>

<script>
    document.getElementById('generarPDF').addEventListener('click', async function() {
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF();

        // Datos principales
        const codigo = document.getElementById('codigo_cotizacion').value;
        const fecha = document.getElementById('fecha_emision').value;
        const cliente = $('#cliente_id option:selected').text();
        const validez = document.getElementById('validez').value;
        const observaciones = document.getElementById('observaciones').value;
        const subtotal = document.getElementById('subtotal_sin_igv').value;
        const igv = document.getElementById('igv').value;
        const total = document.getElementById('total_con_igv').value;

        let y = 10;

        doc.setFontSize(14);
        doc.text(`Cotización: ${codigo}`, 10, y);
        y += 10;
        doc.setFontSize(12);
        doc.text(`Fecha de Emisión: ${fecha}`, 10, y);
        y += 10;
        doc.text(`Cliente: ${cliente}`, 10, y);
        y += 10;
        doc.text(`Validez: ${validez} días`, 10, y);
        y += 10;
        doc.text(`Observaciones: ${observaciones}`, 10, y);
        y += 10;

        y += 10;
        doc.text("Productos/Servicios:", 10, y);
        y += 8;

        // Tabla de productos
        $('#productosTable tbody tr').each(function(index) {
            const producto = $(this).find('.producto-select option:selected').text().trim();
            const cantidad = $(this).find('.cantidad').val();
            const precio = $(this).find('.precio').val();
            const subtotalProd = $(this).find('.subtotal').val();

            doc.text(`• ${producto} - ${cantidad} x $${precio} = $${subtotalProd}`, 12, y);
            y += 8;
        });

        y += 10;
        doc.text(`Subtotal: $${subtotal}`, 10, y);
        y += 8;
        doc.text(`IGV (18%): $${igv}`, 10, y);
        y += 8;
        doc.text(`Total: $${total}`, 10, y);
        y += 8;

        // Descargar PDF
        doc.save(`cotizacion-${codigo}.pdf`);
    });
</script>
<script>
    $(document).ready(function() {
        $('#cotizacionForm').submit(function(e) {
            e.preventDefault();

            const form = this;
            const cotizacionId = $(form).data('id');
            const submitButton = $(form).find('button[type="submit"]');
            const originalButtonText = submitButton.html();

            // Resetear errores visuales
            $('.is-invalid').removeClass('is-invalid');

            // Validación de campos
            // Validar cliente
            if (!$('#cliente_id').val()) {
                $('#cliente_id').addClass('is-invalid');
                mostrarAlertaError('Debe seleccionar un cliente', '#cliente_id');
                return false;
            }

            // Validar validez
            if (!$('#validez').val() || $('#validez').val() <= 0) {
                $('#validez').addClass('is-invalid');
                mostrarAlertaError('La validez debe ser mayor a 0 días', '#validez');
                return false;
            }

            // Validar condiciones comerciales
            if (!$('#condiciones_comerciales').val()) {
                $('#condiciones_comerciales').addClass('is-invalid');
                mostrarAlertaError('Debe seleccionar condiciones comerciales', '#condiciones_comerciales');
                return false;
            }

            // Validar observaciones
            if (!$('#observaciones').val().trim()) {
                $('#observaciones').addClass('is-invalid');
                mostrarAlertaError('Las observaciones son obligatorias', '#observaciones');
                return false;
            }

            // Validar al menos un producto
            if ($('#productosTable tbody tr').length === 0) {
                mostrarAlertaError('Debe agregar al menos un producto');
                return false;
            }

            // Si todo está bien, continuar
            submitButton.html('<i class="fas fa-spinner fa-spin me-2"></i> Actualizando...');
            submitButton.prop('disabled', true);

            const formData = {
                codigo_cotizacion: $('#codigo_cotizacion').val(),
                fecha_emision: $('#fecha_emision').val(),
                cliente_id: $('#cliente_id').val(),
                validez: $('#validez').val(),
                condiciones_comerciales: $('#condiciones_comerciales').val(),
                observaciones: $('#observaciones').val(),
                productos: []
            };

            $('#productosTable tbody tr').each(function(index) {
                const productoId = $(this).find('.producto-select').val();
                const cantidad = $(this).find('.cantidad').val();
                const precio = $(this).find('.precio').val().replace(/[^0-9.]/g, '');

                formData.productos.push({
                    id: productoId,
                    cantidad: cantidad,
                    precio: precio
                });
            });

            $.ajax({
                url: `/cotizaciones/update/${cotizacionId}`,
                type: 'PUT',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cotización actualizada!',
                        html: `
                            <p>La cotización se ha actualizado correctamente.</p>
                            <div class="d-flex justify-content-center gap-2 mt-3">
                                <a href="/cotizaciones/${response.id}/pdf" target="_blank" class="btn btn-danger">
                                    <i class="fas fa-file-pdf me-2"></i> Ver PDF
                                </a>
                                <a href="/cotizaciones" class="btn btn-secondary">
                                    <i class="fas fa-list me-2"></i> Ver Listado
                                </a>
                            </div>
                        `,
                        showConfirmButton: false,
                        showCloseButton: true
                    });
                },
                error: function(xhr) {
                    let errorMessage = 'Error al actualizar la cotización';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorMessage
                    });
                },
                complete: function() {
                    submitButton.html(originalButtonText);
                    submitButton.prop('disabled', false);
                }
            });
        });

        // Función para mostrar el primer error
        function mostrarAlertaError(mensaje, selector = null) {
            Swal.fire({
                icon: 'error',
                title: 'Campo requerido',
                text: mensaje,
                confirmButtonText: 'Entendido',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            }).then(() => {
                if (selector) $(selector).focus();
            });
        }
    });
</script>


<script>
// Variable para controlar si las observaciones han sido modificadas manualmente
let observacionesModificadas = false;

// Función para actualizar observaciones
function actualizarObservaciones() {
    const condicionId = document.getElementById('condiciones_comerciales').value;
    const textareaObservaciones = document.getElementById('observaciones');
    
    if (!condicionId) {
        if (!observacionesModificadas) {
            textareaObservaciones.value = '';
        }
        return;
    }
    
    fetch(`/condiciones-comerciales/${condicionId}/descripcion`)
        .then(response => response.json())
        .then(data => {
            // Solo actualizar si no han sido modificadas manualmente
            if (!observacionesModificadas) {
                textareaObservaciones.value = data.descripcion || '';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Cargar al iniciar
document.addEventListener('DOMContentLoaded', function() {
    const textareaObservaciones = document.getElementById('observaciones');
    const condicionId = document.getElementById('condiciones_comerciales').value;
    
    // Verificar si hay observaciones personalizadas
    const tieneObservacionesPersonales = textareaObservaciones.value.trim() !== '';
    
    // Si no hay observaciones personales, cargar la descripción
    if (condicionId && !tieneObservacionesPersonales) {
        fetch(`/condiciones-comerciales/${condicionId}/descripcion`)
            .then(response => response.json())
            .then(data => {
                textareaObservaciones.value = data.descripcion || '';
            });
    }
    
    // Detectar cambios manuales en las observaciones
    textareaObservaciones.addEventListener('input', function() {
        observacionesModificadas = true;
    });
});
</script>

@endpush