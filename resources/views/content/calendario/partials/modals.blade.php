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
            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" class="form-control" placeholder="Título del evento">
            </div>

            <div class="mb-3">
                <label class="form-label">Etiqueta</label>
                <select id="selectEtiqueta" class="form-select">
                    <option value="">Seleccione una etiqueta</option>
                    <option value="negocios">🔵 Negocios</option>
                    <option value="personal">🔴 Personal</option>
                    <option value="feriado">🟢 Feriado</option>
                    <option value="otros">🔘 Otros</option>
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
                    <option>Juan Pérez</option>
                    <option>María Rodríguez</option>
                    <option>Pedro Sánchez</option>
                    <option>Lucía Gutiérrez</option>
                    <option>Antonio Vargas</option>
                </select>

            </div>

            <div class="mb-3">
                <label class="form-label">Ubicación</label>
                <input type="text" class="form-control" placeholder="Ingrese ubicación">
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" rows="3" placeholder="Ingrese descripción..."></textarea>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary" id="btnGuardarEvento">Guardar</button>
                <button type="button" class="btn btn-danger d-none" id="btnEliminarEvento">Eliminar</button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
              </div>
              
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#fechaInicio", {
            dateFormat: "Y-m-d"
        });

        flatpickr("#fechaFin", {
            dateFormat: "Y-m-d"
        });
    });
</script>
