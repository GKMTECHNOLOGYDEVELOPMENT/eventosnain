@extends('layouts/contentNavbarLayout')
@section('title', 'Nuevo Módulo')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')

    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Formulario /</span> Registrar Módulo</h4>

    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Información del Módulo</h5>
                    <small class="text-muted float-end">Complete todos los campos</small>
                </div>
                <div class="card-body">
                    <form id="moduloForm" method="POST" action="{{ route('modulos.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="codigo_modulo">
                                    <i class="fas fa-barcode me-2"></i>Código del Módulo
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                    <input type="text" class="form-control" id="codigo_modulo" name="codigo_modulo"
                                        placeholder="MOD-001" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="fecha_registro">
                                    <i class="fas fa-calendar-alt me-2"></i>Fecha de Registro
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                    <input type="text" class="form-control" id="fecha_registro" name="fecha_registro"
                                        value="{{ now()->format('Y-m-d') }}" readonly />
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="marca">
                                    <i class="fas fa-trademark me-2"></i>Marca
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="fas fa-copyright"></i></span>
                                    <input type="text" class="form-control" id="marca" name="marca"
                                        placeholder="INTI FOLD" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="modelo">
                                    <i class="fas fa-cube me-2"></i>Modelo
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="fas fa-shapes"></i></span>
                                    <input type="text" class="form-control" id="modelo" name="modelo"
                                        placeholder="Ej: XYZ-2000" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="descripcion">
                                <i class="fas fa-align-left me-2"></i>Descripción
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                    placeholder="Descripción detallada del módulo"></textarea>
                            </div>
                        </div>


                        <div class="mb-3">
                            <label class="form-label" for="detalles">
                                <i class="fas fa-align-left me-2"></i>Detalles Tecnicos
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                <textarea class="form-control" id="detalles" name="detalles" rows="3" placeholder="Detalles Tecnicos del módulo"></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="precio_compra">
                                    <i class="fas fa-money-bill-wave me-2"></i>Precio de Compra
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">$</span>
                                    <input type="text" class="form-control currency-input" id="precio_compra"
                                        name="precio_compra" placeholder="0.00" oninput="formatCurrency(this)" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="precio_venta">
                                    <i class="fas fa-tag me-2"></i>Precio de Venta
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">$</span>
                                    <input type="text" class="form-control currency-input" id="precio_venta"
                                        name="precio_venta" placeholder="0.00" oninput="formatCurrency(this)" />
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="stock_total">
                                    <i class="fas fa-boxes me-2"></i>Stock Total
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                    <input type="number" class="form-control" id="stock_total" name="stock_total"
                                        placeholder="0" min="0" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="stock_minimo">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Stock Mínimo
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="fas fa-thermometer-half"></i></span>
                                    <input type="number" class="form-control" id="stock_minimo" name="stock_minimo"
                                        placeholder="0" min="0" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="estado">
                                <i class="fas fa-toggle-on me-2"></i>Estado
                            </label>
                            <div class="input-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="estado" id="estado_activo"
                                        value="1" checked />
                                    <label class="form-check-label" for="estado_activo">
                                        <i class="fas fa-check-circle text-success me-1"></i> Activo
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="estado" id="estado_inactivo"
                                        value="0" />
                                    <label class="form-check-label" for="estado_inactivo">
                                        <i class="fas fa-times-circle text-danger me-1"></i> Inactivo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="imagenes">
                                <i class="fas fa-images me-2"></i>Imágenes del Módulo
                            </label>
                            <input type="file" class="form-control" id="imagenes" name="imagenes[]" multiple
                                accept="image/*">
                            <small class="text-muted">Puedes seleccionar múltiples imágenes (JPEG, PNG, JPG, GIF)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="imagen_principal">
                                <i class="fas fa-star me-2"></i>Imagen Principal
                            </label>
                            <select class="form-control" id="imagen_principal" name="imagen_principal">
                                <option value="">Seleccionar imagen principal</option>
                                <!-- Las opciones se llenarán dinámicamente con JavaScript -->
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-eraser me-2"></i> Limpiar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Guardar Módulo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para formatear moneda
        function formatCurrency(input) {
            // Eliminar todo lo que no sea número o punto decimal
            let value = input.value.replace(/[^0-9.]/g, '');

            // Separar parte entera y decimal
            let parts = value.split('.');
            let integerPart = parts[0] || '0';
            let decimalPart = parts.length > 1 ? '.' + parts[1].substring(0, 2) : '';

            // Formatear el valor
            input.value = integerPart + decimalPart;
        }

        // Inicializar los campos de moneda al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            // No necesitamos flatpickr ya que la fecha es fija
            // Convertir los campos de precio a tipo text para el formato de moneda
            document.getElementById('precio_compra').type = 'text';
            document.getElementById('precio_venta').type = 'text';
        });
    </script>

    <script>
        document.getElementById('moduloForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Lista de campos obligatorios en el orden que deseas validar
            const requiredFields = [{
                    id: 'codigo_modulo',
                    name: 'Código del Módulo',
                    type: 'text'
                },
                {
                    id: 'marca',
                    name: 'Marca',
                    type: 'text'
                },
                {
                    id: 'modelo',
                    name: 'Modelo',
                    type: 'text'
                },
                {
                    id: 'descripcion',
                    name: 'Descripción',
                    type: 'textarea'
                },
                {
                    id: 'detalles',
                    name: 'Detalles Técnicos',
                    type: 'textarea'
                },
                {
                    id: 'precio_compra',
                    name: 'Precio de Compra',
                    type: 'text'
                },
                {
                    id: 'precio_venta',
                    name: 'Precio de Venta',
                    type: 'text'
                },
                {
                    id: 'stock_total',
                    name: 'Stock Total',
                    type: 'number'
                },
                {
                    id: 'stock_minimo',
                    name: 'Stock Mínimo',
                    type: 'number'
                },
                {
                    id: 'imagenes',
                    name: 'Imágenes del Módulo',
                    type: 'file'
                },
                {
                    id: 'imagen_principal',
                    name: 'Imagen Principal',
                    type: 'select'
                }
            ];

            // Función para validar campos uno por uno
            const validateFields = async (fields) => {
                for (const field of fields) {
                    const element = document.getElementById(field.id);
                    let isEmpty = false;

                    // Validación según el tipo de campo
                    switch (field.type) {
                        case 'file':
                            isEmpty = element.files.length === 0;
                            break;
                        case 'select':
                            isEmpty = element.value === '';
                            break;
                        case 'textarea':
                        case 'text':
                        case 'number':
                        default:
                            isEmpty = !element.value.trim();
                            break;
                    }

                    if (isEmpty) {
                        // Resaltar campo vacío
                        element.classList.add('is-invalid');

                        // Mostrar SweetAlert para este campo
                        await Swal.fire({
                            icon: 'error',
                            title: 'Campo obligatorio vacío',
                            html: `El campo <strong>${field.name}</strong> es obligatorio`,
                            footer: 'Por favor complete este campo',
                            confirmButtonText: 'Entendido',
                            allowOutsideClick: false
                        });

                        // Hacer scroll al campo vacío
                        element.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        // Enfocar el campo vacío
                        element.focus();

                        return false; // Detener la validación
                    } else {
                        // Remover clase de error si el campo está lleno
                        element.classList.remove('is-invalid');
                    }
                }
                return true; // Todos los campos están llenos
            };

            // Validar campos uno por uno
            validateFields(requiredFields).then((allValid) => {
                if (!allValid) return; // Si hay campos vacíos, detener el proceso

                // Verificar que al menos se haya seleccionado una imagen
                const imagenes = document.getElementById('imagenes');
                if (imagenes.files.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Imágenes requeridas',
                        text: 'Debe seleccionar al menos una imagen del módulo',
                        confirmButtonText: 'Entendido'
                    });
                    imagenes.classList.add('is-invalid');
                    imagenes.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    imagenes.focus();
                    return;
                }

                // Verificar que se haya seleccionado una imagen principal
                const imagenPrincipal = document.getElementById('imagen_principal');
                if (imagenPrincipal.value === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Imagen principal requerida',
                        text: 'Debe seleccionar una imagen principal del módulo',
                        confirmButtonText: 'Entendido'
                    });
                    imagenPrincipal.classList.add('is-invalid');
                    imagenPrincipal.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    imagenPrincipal.focus();
                    return;
                }

                const form = this;
                const submitButton = form.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;

                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
                submitButton.disabled = true;

                const formatCurrencyToNumber = (value) => parseFloat(value.replace(/[^0-9.]/g, '')) || 0;
                const precioCompra = formatCurrencyToNumber(form.precio_compra.value);
                const precioVenta = formatCurrencyToNumber(form.precio_venta.value);

                let errors = [];

                if (precioCompra < 0) errors.push("El precio de compra no puede ser negativo");
                if (precioVenta < 0) errors.push("El precio de venta no puede ser negativo");
                if (precioVenta < precioCompra) errors.push(
                    "El precio de venta no puede ser menor al precio de compra");
                if (form.stock_total.value < 0) errors.push("El stock total no puede ser negativo");
                if (form.stock_minimo.value < 0) errors.push("El stock mínimo no puede ser negativo");

                if (errors.length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        html: errors.join('<br>')
                    });
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                    return;
                }

                // ✅ Usamos FormData
                const formData = new FormData(form);

                // Set valores numéricos convertidos
                formData.set('precio_compra', precioCompra);
                formData.set('precio_venta', precioVenta);
                formData.set('_token', document.querySelector('meta[name="csrf-token"]').content);

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw err;
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        form.reset();
                        document.getElementById('imagen_principal').innerHTML =
                            '<option value="">Seleccionar imagen principal</option>';
                    })
                    .catch(error => {
                        let errorMsg = 'Error al guardar';
                        if (error.errors) {
                            errorMsg = Object.values(error.errors).join('<br>');
                        } else if (error.message) {
                            errorMsg = error.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMsg
                        });
                    })
                    .finally(() => {
                        submitButton.innerHTML = originalButtonText;
                        submitButton.disabled = false;
                    });
            });
        });
    </script>


    <script>
        document.getElementById('imagenes').addEventListener('change', function(e) {
            const select = document.getElementById('imagen_principal');
            // Limpiar opciones anteriores
            select.innerHTML = '<option value="">Seleccionar imagen principal</option>';

            // Agregar nuevas opciones
            Array.from(e.target.files).forEach((file, index) => {
                const option = document.createElement('option');
                option.value = index;
                option.textContent = file.name;
                select.appendChild(option);
            });
        });
        const textarea = document.getElementById('descripcion');
        const maxWords = 50;

        textarea.addEventListener('input', function(e) {
            const words = this.value.trim().split(/\s+/);
            if (words.length > maxWords) {
                // Previene escribir más
                const trimmed = words.slice(0, maxWords).join(' ');
                this.value = trimmed;
            }
        });

        // Bloquea teclas extra si ya llegó al límite
        textarea.addEventListener('keydown', function(e) {
            const words = this.value.trim().split(/\s+/);
            const allowedKeys = ['Backspace', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Delete', 'Tab'];
            if (words.length >= maxWords && !allowedKeys.includes(e.key)) {
                const selection = window.getSelection();
                if (!selection || selection.toString().length === 0) {
                    e.preventDefault();
                }
            }
        });
    </script>
@endsection
