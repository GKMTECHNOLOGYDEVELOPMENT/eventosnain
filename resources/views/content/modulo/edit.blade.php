@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Módulo')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<h4 class="py-3 mb-4"><span class="text-muted fw-light">Formulario /</span> Editar Módulo</h4>
<input type="hidden" name="_token" value="{{ csrf_token() }}">

<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Editar Módulo: {{ $modulo->codigo_modulo }}</h5>
            </div>
            <div class="card-body">
                <!-- Formulario principal para editar datos del módulo (sin imagenes) -->
                <form id="moduloEditForm" method="POST" action="{{ route('modulos.update', $modulo->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="codigo_modulo">
                                <i class="fas fa-barcode me-2"></i>Código del Módulo
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                <input type="text" class="form-control" id="codigo_modulo" name="codigo_modulo"
                                    value="{{ $modulo->codigo_modulo }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="fecha_registro">
                                <i class="fas fa-calendar-alt me-2"></i>Fecha de Registro
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                <input type="text" class="form-control" id="fecha_registro" name="fecha_registro"
                                    value="{{ $modulo->fecha_registro->format('Y-m-d') }}" readonly />
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
                                    value="{{ $modulo->marca}}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="modelo">
                                <i class="fas fa-cube me-2"></i>Modelo
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-shapes"></i></span>
                                <input type="text" class="form-control" id="modelo" name="modelo"
                                    value="{{ $modulo->modelo }}" required />
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
                                placeholder="Descripción detallada del módulo">{{ $modulo->descripcion }}</textarea>
                        </div>
                    </div>


                    <div class="mb-3">
                        <label class="form-label" for="detalles">
                            <i class="fas fa-align-left me-2"></i>Detalles
                        </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                            <textarea class="form-control" id="detalles" name="detalles" rows="3"
                                placeholder="Detalles detallada del módulo">{{ $modulo->detalles }}</textarea>
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
                                    name="precio_compra" value="{{ number_format($modulo->precio_compra, 2, '.', '') }}"
                                    required oninput="formatCurrency(this)" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="precio_venta">
                                <i class="fas fa-tag me-2"></i>Precio de Venta
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control currency-input" id="precio_venta"
                                    name="precio_venta" value="{{ number_format($modulo->precio_venta, 2, '.', '') }}"
                                    required oninput="formatCurrency(this)" />
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
                                    value="{{ $modulo->stock_total }}" required min="0" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="stock_minimo">
                                <i class="fas fa-exclamation-triangle me-2"></i>Stock Mínimo
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-thermometer-half"></i></span>
                                <input type="number" class="form-control" id="stock_minimo" name="stock_minimo"
                                    value="{{ $modulo->stock_minimo }}" required min="0" />
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="estado">
                            <i class="fas fa-toggle-on me-2"></i>Estado
                        </label>
                        <div class="input-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="estado" id="estado_activo" value="1"
                                    {{ $modulo->estado ? 'checked' : '' }} />
                                <label class="form-check-label" for="estado_activo">
                                    <i class="fas fa-check-circle text-success me-1"></i> Activo
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="estado" id="estado_inactivo"
                                    value="0" {{ !$modulo->estado ? 'checked' : '' }} />
                                <label class="form-check-label" for="estado_inactivo">
                                    <i class="fas fa-times-circle text-danger me-1"></i> Inactivo
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="imagen_principal">
                            <i class="fas fa-star me-2"></i>Imagen Principal
                        </label>
                        <select class="form-control" id="imagen_principal" name="imagen_principal">
                            <option value="">Seleccionar imagen principal</option>
                            @foreach($imagenes as $img)
                            <option value="{{ $img->id }}" {{ $img->es_principal ? 'selected' : '' }}>
                                {{ $img->nombre_archivo }}
                            </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('modulo-list') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Actualizar Módulo
                        </button>
                    </div>
                </form>

                <!-- Sección fuera del form para subir imágenes -->
                <div class="mb-3 mt-4">
                    <label class="form-label"><i class="fas fa-upload me-2"></i>Subir Imágenes</label>
                    <input type="file" class="form-control" id="imagenes" name="imagenes[]" multiple>
                    <button type="button" class="btn btn-secondary mt-2" id="subirImagenesBtn">
                        <i class="fas fa-upload"></i> Subir Imágenes
                    </button>
                </div>


                <!-- Sección para mostrar imágenes y eliminar (fuera del form principal) -->
                @if($modulo->imagenes->count())
                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-images me-2"></i>Imágenes del módulo</label>
                    <div class="row g-3">
                        @foreach($modulo->imagenes as $imagen)
                        <div class="col-md-3 text-center imagen-card" data-id="{{ $imagen->id }}">
                            <div class="card">
                                <img src="{{ asset('storage/modulos/' . $imagen->nombre_archivo) }}"
                                    class="card-img-top img-thumbnail" alt="Imagen del módulo"
                                    style="max-height: 180px; object-fit: cover;">
                                <div class="card-body p-2">
                                    @if($imagen->es_principal)
                                    <span class="badge bg-success">
                                        <i class="fas fa-star me-1"></i> Principal
                                    </span>
                                    @else
                                    <span class="badge bg-secondary">Secundaria</span>
                                    @endif

                                    <button type="button" class="btn btn-danger btn-sm mt-2 btn-eliminar-imagen"
                                        data-id="{{ $imagen->id }}">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

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
        // Convertir los campos de precio a tipo text para el formato de moneda
        document.getElementById('precio_compra').type = 'text';
        document.getElementById('precio_venta').type = 'text';
    });

    // Configurar el formulario para enviar por AJAX
    document.getElementById('moduloEditForm').addEventListener('submit', function(e) {
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

            // Mostrar loading
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Actualizando...';
            submitButton.disabled = true;

            // Convertir campos de moneda
            const formatCurrencyToNumber = (value) => parseFloat(value.replace(/[^0-9.]/g, '')) || 0;
            const precioCompra = formatCurrencyToNumber(form.precio_compra.value);
            const precioVenta = formatCurrencyToNumber(form.precio_venta.value);

            // Validaciones frontend
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

            // Preparar datos
            const data = {
                _token: form._token.value,
                _method: 'PUT',
                codigo_modulo: form.codigo_modulo.value,
                marca: form.marca.value,
                modelo: form.modelo.value,
                descripcion: form.descripcion.value,
                detalles: form.detalles.value,
                precio_compra: precioCompra,
                precio_venta: precioVenta,
                stock_total: form.stock_total.value,
                stock_minimo: form.stock_minimo.value,
                fecha_registro: form.fecha_registro.value,
                estado: document.querySelector('input[name="estado"]:checked').value,
                imagen_principal: form.imagen_principal.value // <-- esta es la clave
            };

            // Enviar por AJAX
            fetch(form.action, {
                    method: 'POST', // Laravel necesita POST para PUT/PATCH/DELETE
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': form._token.value // <-- aquí agregas el token

                    },
                    body: JSON.stringify(data)
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
                    }).then(() => {
                        window.location.href = "{{ route('modulo-list') }}";
                    });
                })
                .catch(error => {
                    let errorMsg = 'Error al actualizar';
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
    document.getElementById('subirImagenesBtn').addEventListener('click', function() {
        const input = document.getElementById('imagenes');
        const files = input.files;

        if (!files.length) {
            Swal.fire('Advertencia', 'Selecciona al menos una imagen para subir.', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        for (const file of files) {
            formData.append('imagenes[]', file);
        }

        fetch(`{{ route('modulos.uploadImagenes', $modulo->id) }}`, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Éxito', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(err => {
                Swal.fire('Error', err.message, 'error');
            });
    });
</script>

<script>
    document.querySelectorAll('.btn-eliminar-imagen').forEach(button => {
        button.addEventListener('click', function() {
            const imagenId = this.dataset.id;

            Swal.fire({
                title: '¿Eliminar imagen?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/modulo/eliminar/imagenes/${imagenId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')
                                    .value,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                _method: 'DELETE'
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                // Eliminar del DOM
                                document.querySelector(`.imagen-card[data-id="${imagenId}"]`)
                                    .remove();
                            } else {
                                throw new Error(data.message || 'Error al eliminar');
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', err.message, 'error');
                        });
                }
            });
        });
    });
</script>

<script>
    document.getElementById('imagenes').addEventListener('change', function(e) {
        const select = document.getElementById('imagen_principal');

        // No borrar las opciones del servidor, solo agregar las nuevas temporalmente
        const existingOptions = Array.from(select.options).map(opt => opt.textContent);

        Array.from(e.target.files).forEach((file, index) => {
            if (!existingOptions.includes(file.name)) {
                const option = document.createElement('option');
                option.value = 'nuevo_' + index; // evitar conflicto con IDs de la base de datos
                option.textContent = file.name;
                select.appendChild(option);
            }
        });
    });
</script>




@endsection