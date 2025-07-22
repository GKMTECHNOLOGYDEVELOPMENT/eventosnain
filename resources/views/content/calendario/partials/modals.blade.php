<style>
    .flatpickr-day.today:not(.selected) {
        background: none !important;
        border-color: transparent !important;
        color: inherit !important;
        box-shadow: none !important;
    }

    /* Estilo single select adaptado */
    .select2-container--default .select2-selection--single {
        border: 1px solid #696cff;
        border-radius: 0.5rem;
        padding: 0.45rem 0.75rem;
        background-color: #fff;
        box-shadow: 0 0 0 0.15rem rgba(105, 108, 255, 0.15);
        height: auto;
        transition: all 0.2s ease-in-out;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #333;
        font-weight: 500;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 10px;
        right: 10px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        border-right: none !important;
    }

    /* Contenedor múltiple estilizado */
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #696cff;
        border-radius: 0.5rem;
        padding: 0.25rem 0.5rem;
        min-height: 42px;
        background-color: #fff;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 0 0 0.15rem rgba(105, 108, 255, 0.25);
    }


    /* Chips seleccionados */
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #ebe8ff;
        color: #696cff;
        border: 1px solid #696cff;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
        margin: 4px 5px 4px 0;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #696cff;
        margin-right: 5px;
        font-weight: bold;
    }

    /* Resultados dropdown */
    .select2-container--default .select2-results__option {
        padding: 10px;
        font-size: 0.95rem;
        border-bottom: 1px solid #f1f1f1;
    }

    .select2-container--default .select2-results__option--highlighted {
        background-color: #696cff;
        color: white;
    }

    .select2-container--default .select2-dropdown {
        border-radius: 0.5rem;
        border: 1px solid #d9dee3;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .flatpickr-calendar {
        max-width: 320px !important;
        width: 100% !important;
        border-radius: 0.5rem;
    }

    .swal2-container {
        z-index: 2000 !important;
        /* Bootstrap offcanvas usa 1055 */
    }
</style>


<!-- Offcanvas lateral derecho -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEvento" aria-labelledby="offcanvasEventoLabel"
    data-bs-backdrop="true" data-bs-scroll="true">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasEventoLabel">Agregar Evento</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body my-auto mx-0 flex-grow-0">
        <form>
            @csrf

            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" class="form-control" placeholder="Título del evento">
            </div>

            <div class="mb-3 d-none" id="creadoPorDiv">
                <label class="form-label">Creado por:</label>
                <input type="text" class="form-control" id="creadoPorInput" readonly>
            </div>



            <!-- Modifica el select de etiquetas para incluir un botón de gestión -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label">Etiqueta</label>

                </div>
                <select id="selectEtiqueta" class="form-select">
                    <option value="">Seleccione una etiqueta</option>
                    <!-- Las opciones se cargarán dinámicamente -->
                </select>
            </div>



            <div class="mb-3">
                <label class="form-label">Fecha de inicio</label>
                <input type="text" id="fechaInicio" class="form-control flatpickr"
                    placeholder="Selecciona una fecha">
            </div>

            <div class="mb-3">
                <label class="form-label">Fecha de fin</label>
                <input type="text" id="fechaFin" class="form-control flatpickr" placeholder="Selecciona una fecha">
            </div>


            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="eventoTodoElDia">
                <label class="form-check-label" for="eventoTodoElDia">Todo el día</label>
            </div>

            <div class="mb-3">
                <label class="form-label">Enlace del evento</label>
                <input type="url" class="form-control" placeholder="https://www.google.com">
            </div>

            <div class="mb-3">
                <label class="form-label text-uppercase text-muted small fw-semibold">Invitados</label>
                <select id="selectInvitados" class="form-select select2" multiple>
                    @foreach ($users as $user)
                        <option value="{{ $user['id'] }}" data-email="{{ $user['email'] }}">
                            {{ $user['text'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ubicación</label>
                <input type="text" class="form-control" placeholder="Ingrese ubicación">
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea id="descripcionEvento" name="descripcion" class="form-control" rows="3"
                    placeholder="Ingrese descripción..."></textarea>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary" id="btnGuardarEvento">
                    <span id="spinnerGuardar" class="spinner-border spinner-border-sm me-2 d-none" role="status"
                        aria-hidden="true"></span>
                    Guardar
                </button>
                <button type="button" class="btn btn-danger d-none" id="btnEliminarEvento">
                    <span id="spinnerEliminar" class="spinner-border spinner-border-sm me-2 d-none" role="status"
                        aria-hidden="true"></span>
                    Eliminar
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
            </div>

        </form>
    </div>
</div>


<!-- Modal para gestionar etiquetas -->
<div class="modal fade" id="modalEtiquetas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestión de Etiquetas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <button class="btn btn-primary" id="btnNuevaEtiqueta">
                        <i class="bx bx-plus me-1"></i> Nueva Etiqueta
                    </button>
                    <div id="formNuevaEtiqueta" class="mt-3 d-none">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="etiquetaNombre"
                                    placeholder="Ej: Reuniones">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Color</label>
                                <input type="color" class="form-control form-control-color" id="etiquetaColor"
                                    value="#696cff" title="Elige un color">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-success" id="btnGuardarEtiqueta">
                                    <i class="bx bx-save"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Color</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="listaEtiquetas">
                            <!-- Las etiquetas se cargarán aquí dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();

    flatpickr("#fechaInicio", {
        enableTime: true,
        time_24hr: true,
        dateFormat: "Y-m-d H:i:S",
        defaultDate: now,
        minDate: now, // <-- aquí limitas a solo fechas futuras
        locale: 'es',
        allowInput: true,
        enableSeconds: false
    });

    flatpickr("#fechaFin", {
        enableTime: true,
        time_24hr: true,
        dateFormat: "Y-m-d H:i:S",
        defaultDate: now,
        minDate: now, // <-- aquí también
        locale: 'es',
        allowInput: true,
        enableSeconds: false
    });
});
</script>




<script>
    // Agrega estas funciones a tu calendar.js

    // Función para cargar etiquetas
    function cargarEtiquetas() {
        fetch(`${window.location.origin}/api/calendario/etiquetas`)
            .then(response => response.json())
            .then(etiquetas => {
                // Actualizar select de etiquetas
                const selectEtiqueta = document.getElementById('selectEtiqueta');
                selectEtiqueta.innerHTML = '<option value="">Seleccione una etiqueta</option>';

                etiquetas.forEach(etiqueta => {
                    const option = document.createElement('option');
                    option.value = etiqueta.nombre;
                    option.textContent = `${etiqueta.icono || '🔘'} ${etiqueta.nombre}`;
                    option.dataset.color = etiqueta.color;
                    selectEtiqueta.appendChild(option);
                });

                // Actualizar lista en modal
                const listaEtiquetas = document.getElementById('listaEtiquetas');
                listaEtiquetas.innerHTML = '';

                etiquetas.forEach(etiqueta => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                    <td>${etiqueta.nombre}</td>
                    <td>
                        <span class="badge" style="background-color: ${etiqueta.color}">
                            ${etiqueta.color}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-warning btn-editar-etiqueta" data-id="${etiqueta.id}">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-eliminar-etiqueta" data-id="${etiqueta.id}">
                            <i class="bx bx-trash"></i>
                        </button>
                    </td>
                `;
                    listaEtiquetas.appendChild(tr);
                });
            });
    }

    // Eventos para el modal de etiquetas
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar etiquetas al inicio
        cargarEtiquetas();

        // Mostrar/ocultar formulario de nueva etiqueta
        document.getElementById('btnNuevaEtiqueta').addEventListener('click', function() {
            document.getElementById('formNuevaEtiqueta').classList.toggle('d-none');
        });

        // Guardar nueva etiqueta
        document.getElementById('btnGuardarEtiqueta').addEventListener('click', function() {
            const nombre = document.getElementById('etiquetaNombre').value;
            const color = document.getElementById('etiquetaColor').value;

            if (!nombre) {
                Swal.fire('Error', 'El nombre es requerido', 'error');
                return;
            }

            fetch(`${window.location.origin}/api/calendario/etiquetas`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        nombre: nombre,
                        color: color
                    })
                })
                .then(response => response.json())
                .then(() => {
                    Swal.fire('Éxito', 'Etiqueta creada correctamente', 'success');
                    cargarEtiquetas();
                    document.getElementById('formNuevaEtiqueta').classList.add('d-none');
                    document.getElementById('etiquetaNombre').value = '';
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'No se pudo crear la etiqueta', 'error');
                });
        });

        // Delegación de eventos para botones de editar/eliminar
        document.getElementById('listaEtiquetas').addEventListener('click', function(e) {
            // Editar etiqueta
            if (e.target.closest('.btn-editar-etiqueta')) {
                const id = e.target.closest('.btn-editar-etiqueta').dataset.id;
                editarEtiqueta(id);
            }

            // Eliminar etiqueta
            if (e.target.closest('.btn-eliminar-etiqueta')) {
                const id = e.target.closest('.btn-eliminar-etiqueta').dataset.id;
                eliminarEtiqueta(id);
            }
        });
    });

    // Función para editar etiqueta
    function editarEtiqueta(id) {
        // Primero obtener los datos de la etiqueta
        fetch(`${window.location.origin}/api/calendario/etiquetas/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener la etiqueta');
                }
                return response.json();
            })
            .then(etiqueta => {
                // Mostrar el modal de edición
                Swal.fire({
                        title: 'Editar Etiqueta',
                        html: `
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input id="swal-nombre" class="swal2-input" value="${etiqueta.nombre}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <input type="color" id="swal-color" class="form-control form-control-color" value="${etiqueta.color}">
                    </div>
                `,
                        focusConfirm: false,
                        showCancelButton: true,
                        confirmButtonText: 'Actualizar',
                        cancelButtonText: 'Cancelar',
                        preConfirm: () => {
                            return {
                                nombre: document.getElementById('swal-nombre').value,
                                color: document.getElementById('swal-color').value
                            }
                        }
                    }).then(result => {
                        if (result.isConfirmed) {
                            // Enviar la actualización
                            return fetch(`${window.location.origin}/api/calendario/etiquetas/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(result.value)
                            });
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al actualizar');
                        }
                        return response.json();
                    })
                    .then(() => {
                        Swal.fire('Éxito', 'Etiqueta actualizada correctamente', 'success');
                        cargarEtiquetas(); // Recargar la lista de etiquetas
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', error.message || 'Hubo un error al procesar la etiqueta', 'error');
                    });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo cargar la etiqueta para edición', 'error');
            });
    }
    // Función para eliminar etiqueta
    function eliminarEtiqueta(id) {
        Swal.fire({
            title: '¿Eliminar etiqueta?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`${window.location.origin}/api/calendario/etiquetas/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            Swal.fire('Éxito', 'Etiqueta eliminada', 'success');
                            cargarEtiquetas();
                        } else {
                            throw new Error('Error al eliminar');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'No se pudo eliminar la etiqueta', 'error');
                    });
            }
        });
    }
</script>
