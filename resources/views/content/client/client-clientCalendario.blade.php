@extends('layouts/contentNavbarLayout')

@section('title', 'Calendario de Eventos')

@section('content')


<h4 class="py-3 mb-4"><span class="text-muted fw-light">Calendario/</span> Eventos</h4>
<!-- Agregar esto en la parte superior de la vista donde se muestra el formulario -->
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


<!-- Calendario -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="page-title">Calendario</h2>
        <div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#eventoModal">
                ACTIVIDAD
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#salidaModal">
                EVENTO
            </button>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#salidauserModal">
                SALIDA
            </button>
        </div>

    </div>

    <!-- Modal para gestionar actividades y usuarios -->
    <div class="modal fade" id="salidauserModal" tabindex="-1" role="dialog" aria-labelledby="salidauserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="salidauserModalLabel">Registro de Salida</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <form id="salidaForm" action="{{ route('salida_user.guardar') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="salida_id">Seleccionar Salida</label>
                            <select class="form-control" id="salida_id" name="salida_id" required>
                                <option value="">Seleccione una salida</option>
                                @foreach($salidas as $salida)
                                <option value="{{ $salida->id }}">{{ $salida->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="metaUsuario">Meta de Usuarios</label>
                            <input type="number" class="form-control" id="metaUsuario" name="meta_usuario"
                                placeholder="Meta de usuarios" required>
                        </div>
                        <div class="form-group">
                            <label for="userId">Usuario</label>
                            <select class="form-control" id="userId" name="user_id" required>
                                @foreach($usuarios as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="created_at">Fecha de Creación</label>
                            <input type="date" class="form-control" id="created_at" name="created_at"
                                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" readonly>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="updated_at">Fecha de Actualización</label>
                            <input type="date" class="form-control" id="updated_at" name="updated_at"
                                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" readonly>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Guardar Registro</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div id='calendar'></div>

        <div class="modal fade" id="salidaModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="eventModalLabel">Evento</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <form action="{{ route('client.salida') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="eventTitle">Título del Evento</label>
                                <input type="text" class="form-control" id="eventTitle" name="title"
                                    placeholder="Agregar título del evento" required>
                            </div>
                            <div class="form-group">
                                <label for="eventNote">Nota</label>
                                <textarea class="form-control" id="eventNote" name="note"
                                    placeholder="Agregar una nota para tu evento" rows="3"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="drgpicker-start">Fecha de Inicio</label>
                                        <input type="date" class="form-control" id="drgpicker-start" name="start"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start-time">Hora de Inicio</label>
                                        <input type="time" class="form-control" id="start-time" name="start_time"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="drgpicker-end">Fecha de Fin</label>
                                        <input type="date" class="form-control" id="drgpicker-end" name="end" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end-time">Hora de Fin</label>
                                        <input type="time" class="form-control" id="end-time" name="end_time" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="meta_registros">Meta de Registros</label>
                                <input type="number" class="form-control" id="meta_registros" name="meta_registros"
                                    placeholder="Meta de registros" required>
                            </div>
                            <div class="form-group">
                                <label>Seleccionar Participantes</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Lista de Usuarios</h6>
                                        <select multiple class="form-control" id="usuarios-lista" size="8">
                                            @foreach($usuarios as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" id="agregar-usuario"
                                            class="btn btn-primary btn-sm mt-2">Agregar Usuario</button>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Usuarios Seleccionados</h6>
                                        <ul id="usuarios-seleccionados" class="list-group">
                                            <!-- Aquí se agregarán los usuarios seleccionados -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="usuarios" id="usuarios-hidden">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="RepeatSwitch"
                                        name="all_day">
                                    <label class="custom-control-label" for="RepeatSwitch">Todo el día</label>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Guardar Evento</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const agregarBtn = document.getElementById('agregar-usuario');
                const usuariosLista = document.getElementById('usuarios-lista');
                const usuariosSeleccionados = document.getElementById('usuarios-seleccionados');
                const usuariosHidden = document.getElementById('usuarios-hidden');

                agregarBtn.addEventListener('click', function() {
                    const selectedOptions = Array.from(usuariosLista.selectedOptions);

                    selectedOptions.forEach(option => {
                        const li = document.createElement('li');
                        li.classList.add('list-group-item', 'd-flex', 'justify-content-between',
                            'align-items-center');
                        li.textContent = option.text;
                        li.dataset.userId = option.value;

                        const removeBtn = document.createElement('button');
                        removeBtn.classList.add('btn', 'btn-danger', 'btn-sm');
                        removeBtn.textContent = 'Eliminar';
                        removeBtn.addEventListener('click', function() {
                            usuariosSeleccionados.removeChild(li);
                            actualizarUsuariosHidden();
                        });

                        li.appendChild(removeBtn);
                        usuariosSeleccionados.appendChild(li);

                        // Remover la opción de la lista de usuarios
                        option.remove();

                        actualizarUsuariosHidden();
                    });
                });

                function actualizarUsuariosHidden() {
                    const selectedUserIds = Array.from(usuariosSeleccionados.children).map(li => li.dataset.userId);
                    usuariosHidden.value = selectedUserIds.join(',');
                }
            });
        </script>

        <!-- Modal para Nuevo Evento -->
        <div class="modal fade" id="eventoModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel">Nueva Actividad V2</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-4">
                        <form action="{{ route('client.evento') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="eventTitle">Título</label>
                                <input type="text" class="form-control" id="eventTitle" name="title"
                                    placeholder="Agregar título del evento" required>
                            </div>
                            <div class="form-group">
                                <label for="eventNote">Nota</label>
                                <textarea class="form-control" id="eventNote" name="note"
                                    placeholder="Agregar una nota para tu evento" rows="4" required></textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="eventType">Tipo de Actividad</label>
                                    <select id="eventType" name="type" class="form-control" required>
                                        <option value="REUNION">Reunión</option>
                                        <option value="LEVANTAMIENTO INFORMACION">Levantamiento de Información</option>
                                        <option value="COTIZACION">Cotizacion </option>
                                        <option value="LLAMADA">Llamada</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="eventClient">Cliente</label>
                                    <div id="client-select-container">
                                        <select class="form-control select2" id="eventClient" name="cliente_id">
                                            <option value="">Seleccione un cliente</option>
                                            @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" id="createNewClientCheck">
                                        <label class="form-check-label" for="createNewClientCheck">
                                            Crear nuevo cliente
                                        </label>
                                    </div>

                                    <div id="client-input-container" class="mt-3 d-none">
                                        <input type="text" class="form-control" id="newClientName"
                                            name="new_client_name" placeholder="Escriba el nombre del cliente">
                                    </div>
                                </div>
                                <script>
                                    $(document).ready(function() {


                                        // Manejo del checkbox
                                        $('#createNewClientCheck').on('change', function() {
                                            if ($(this).is(':checked')) {
                                                // Ocultar select y mostrar input
                                                $('#client-select-container').addClass('d-none');
                                                $('#client-input-container').removeClass('d-none');
                                                $('#eventClient').val('').trigger(
                                                    'change'); // Limpiar selección del select
                                            } else {
                                                // Mostrar select y ocultar input
                                                $('#client-select-container').removeClass('d-none');
                                                $('#client-input-container').addClass('d-none');
                                                $('#newClientName').val(
                                                    ''); // Limpiar el input de nuevo cliente
                                            }
                                        });
                                    });
                                </script>
                                <script>
                                    $(document).ready(function() {
                                        $('#eventClient').select2({
                                            placeholder: 'Seleccione un cliente',
                                            allowClear: true
                                        });
                                    });
                                </script>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="drgpicker-start">Fecha de Inicio</label>
                                    <input type="date" class="form-control" id="drgpicker-start" name="start" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="start-time">Hora de Inicio</label>
                                    <input type="time" class="form-control" id="start-time" name="start_time"
                                        placeholder="10:00 AM" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="drgpicker-end">Fecha de Fin</label>
                                    <input type="date" class="form-control" id="drgpicker-end" name="end" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="end-time">Hora de Fin</label>
                                    <input type="time" class="form-control" id="end-time" name="end_time"
                                        placeholder="11:00 AM" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Seleccionar Participantes</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Lista de Usuarios</h6>
                                        <select multiple class="form-control" id="usuarios-lista-actividad" size="8">
                                            @foreach($usuarios as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" id="agregar-usuario-actividad"
                                            class="btn btn-primary btn-sm mt-2">Agregar Usuario</button>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Usuarios Seleccionados</h6>
                                        <ul id="usuarios-seleccionados-actividad" class="list-group">
                                            <!-- Aquí se agregarán los usuarios seleccionados -->
                                        </ul>
                                    </div>
                                    <input type="hidden" name="usuarios[]" id="usuarios-hidden-actividad">

                                </div>
                            </div>

                            <div class="modal-footer d-flex justify-content-between">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="RepeatSwitch"
                                        name="all_day">
                                    <label class="custom-control-label" for="RepeatSwitch">Todo el día</label>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar Evento</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- Fin del Modal para Nuevo Evento -->



        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const agregarBtn = document.getElementById('agregar-usuario-actividad');
                const usuariosLista = document.getElementById('usuarios-lista-actividad');
                const usuariosSeleccionados = document.getElementById('usuarios-seleccionados-actividad');
                const usuariosHidden = document.getElementById('usuarios-hidden-actividad');

                agregarBtn.addEventListener('click', function() {
                    const selectedOptions = Array.from(usuariosLista.selectedOptions);

                    selectedOptions.forEach(option => {
                        const li = document.createElement('li');
                        li.classList.add('list-group-item', 'd-flex', 'justify-content-between',
                            'align-items-center');
                        li.textContent = option.text;
                        li.dataset.userId = option.value;

                        const removeBtn = document.createElement('button');
                        removeBtn.classList.add('btn', 'btn-danger', 'btn-sm');
                        removeBtn.textContent = 'Eliminar';
                        removeBtn.addEventListener('click', function() {
                            usuariosSeleccionados.removeChild(li);
                            actualizarUsuariosHidden();
                        });

                        li.appendChild(removeBtn);
                        usuariosSeleccionados.appendChild(li);

                        // Remover la opción de la lista de usuarios
                        option.remove();

                        actualizarUsuariosHidden();
                    });
                });

                function actualizarUsuariosHidden() {
                    const selectedUserIds = Array.from(usuariosSeleccionados.children)
                        .map(li => {
                            const userId = parseInt(li.dataset.userId);
                            console.log("ID del usuario (convertido a entero):",
                                userId); // Añadir para depuración
                            return userId;
                        })
                        .filter(id => !isNaN(id)); // Filtra valores no válidos

                    console.log("IDs seleccionados para enviar:", selectedUserIds); // Mostrar lista de IDs

                    usuariosHidden.value = selectedUserIds.join(','); // Convierte a cadena separada por comas

                    console.log("Valor de usuarios-hidden que se enviará:", usuariosHidden
                        .value); // Mostrar el valor final
                }

            });
        </script>










        <!-- Modal para REUNIÓN -->
        <div class="modal fade" id="reunionModal" tabindex="-1" role="dialog" aria-labelledby="reunionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reunionModalLabel">Reunión</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="reunionForm">
                            @csrf
                            <input type="hidden" id="reunionId" name="reunion_id">
                            <div class="form-group mb-3">
                                <label for="usuarioCliente">Usuario del Cliente</label>
                                <input type="text" id="usuarioCliente" name="usuarioCliente" class="form-control"
                                    readonly>
                            </div>

                            <div class="form-group mb-3">
                                <label for="reunionTitle">Tema</label>
                                <input type="text" id="reunionTitle" name="tema" class="form-control" readonly>
                            </div>

                            <div class="form-group mb-3">
                                <label for="reunionDate">Fecha</label>
                                <input type="date" id="reunionDate" name="fecha" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label for="reunionTime">Hora</label>
                                <input type="time" id="reunionTime" name="hora" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label for="reunionObservation">Observación</label>
                                <textarea id="reunionObservation" name="observacion" class="form-control"></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="reunionClient">Cliente</label>
                                <input type="text" id="reunionClient" name="cliente" class="form-control" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label for="usuarioReunion">Encargado de la Reunión</label>
                                <input type="text" id="usuarioReunion" name="usuarioReunion" class="form-control"
                                    readonly>
                            </div>


                            <div class="form-group mb-3">
                                <label for="reunionZoom">Zoom</label>
                                <input type="text" id="reunionZoom" name="zoom" class="form-control" readonly>
                            </div>

                            <div class="form-group mb-3">
                                <label for="reunionEstado">Estado</label>
                                <select id="reunionEstado" name="estado" class="form-control">
                                    <option value="PENDIENTE">PENDIENTE</option>
                                    <option value="REALIZADO">REALIZADO</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="saveReunion">Guardar cambios</button>
                        <!--<button type="button" class="btn btn-danger" id="deleteReunion">Eliminar</button>-->
                    </div>
                </div>
            </div>
        </div>




        <script>
            document.getElementById('saveReunion').addEventListener('click', function() {
                var form = document.getElementById('reunionForm');
                var formData = new FormData(form);

                // Alerta de confirmación antes de actualizar
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Estás a punto de actualizar esta reunión.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, realiza la actualización
                        fetch("{{ route('reunion.actualizar') }}", {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Mostrar alerta de éxito
                                    Swal.fire({
                                        title: '¡Actualización exitosa!',
                                        text: 'La reunión fue actualizada correctamente.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        $('#reunionModal').modal('hide');
                                        location
                                            .reload(); // Recarga la página para reflejar los cambios
                                    });
                                } else {
                                    // Mostrar alerta de error
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Hubo un error al actualizar la reunión: ' + data
                                            .message,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                // Mostrar alerta de error en caso de excepción
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un error al procesar la solicitud.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            });
                    }
                });
            });


            document.getElementById('deleteReunion').addEventListener('click', function() {
                var reunionId = document.getElementById('reunionId').value;

                // Confirmación antes de eliminar
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Estás a punto de eliminar esta reunión. Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Eliminar la reunión
                        fetch("{{ route('reunion.eliminar') }}", {
                                method: 'POST',
                                body: JSON.stringify({
                                    reunion_id: reunionId
                                }),
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: '¡Eliminación exitosa!',
                                        text: 'La reunión fue eliminada correctamente.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        $('#reunionModal').modal('hide');
                                        location
                                            .reload(); // Recarga la página para reflejar los cambios
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Hubo un error al eliminar la reunión: ' + data
                                            .message,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un error al procesar la solicitud.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            });
                    }
                });
            });
        </script>








        <!-- Modal para LEVANTAMIENTO DE INFORMACIÓN -->
        <div class="modal fade" id="informacionModal" tabindex="-1" role="dialog"
            aria-labelledby="informacionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="informacionModalLabel">Levantamiento de Información</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="informacionForm">
                            @csrf
                            <input type="hidden" id="informacionId" name="informacion_id">

                            <!-- Campo Título (Solo Lectura) -->
                            <!-- <div class="form-group mb-3">
                                <label for="informacionTitle">Título</label>
                                <input type="text" id="informacionTitle" name="titulo" class="form-control" readonly>
                            </div> -->
                            <!-- Campo Cliente (Solo Lectura) -->
                            <div class="form-group mb-3">
                                <label for="informacionClient">Cliente</label>
                                <input type="text" id="informacionClient" name="cliente" class="form-control" readonly>
                            </div>

                            <!-- Campo Usuario (Solo Lectura) -->
                            <div class="form-group mb-3">
                                <label for="informacionUser">Encargado</label>
                                <input type="text" id="informacionUser" name="usuario" class="form-control" readonly>
                            </div>


                            <!-- Campo Fecha y Hora (Solo Lectura) -->
                            <!-- <div class="form-group mb-3">
                                <label for="informacionDateTime">Fecha y Hora</label>
                                <input type="datetime-local" id="informacionDateTime" name="fecha" class="form-control"
                                    readonly>
                            </div> -->

                            <!-- Campo Dirección (Editable) -->
                            <div class="form-group mb-3">
                                <label for="informacionDireccion">Dirección</label>
                                <input type="text" id="informacionDireccion" name="direccion" class="form-control">
                            </div>




                            <!-- Campo Observación (Editable) -->
                            <div class="form-group mb-3">
                                <label for="informacionObservacion">Observación</label>
                                <textarea id="informacionObservacion" name="observacion"
                                    class="form-control"></textarea>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <!--<button type="button" class="btn btn-primary" id="saveInformacion">Actualizar</button>-->
                    </div>
                </div>
            </div>
        </div>











        <!-- Modal para LLAMADA -->
        <div class="modal fade" id="llamadaModal" tabindex="-1" role="dialog" aria-labelledby="llamadaModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="llamadaModalLabel">Actualizar Llamada</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="llamadaId" name="llamada_id" />

                        <div class="form-group">
                            <label for="llamadaClient">Cliente</label>
                            <input type="text" class="form-control" id="llamadaClient" readonly />
                        </div>

                        <div class="form-group">
                            <label for="llamadaUser">Usuario</label>
                            <input type="text" class="form-control" id="llamadaUser" readonly />
                        </div>

                        <div class="form-group">
                            <label for="llamadaDate">Fecha</label>
                            <input type="date" id="llamadaDate" name="fecha" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="llamadaTime">Hora</label>
                            <input type="time" id="llamadaTime" name="hora" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="llamadaObservations">Observaciones</label>
                            <textarea class="form-control" id="llamadaObservations" name="observaciones"
                                rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="llamadaStatus">Estado</label>
                            <select class="form-control" id="llamadaStatus" name="estado">
                                <option value="PENDIENTE">PENDIENTE</option>
                                <option value="REALIZADO">REALIZADO</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="saveLlamada">Actualizar</button>
                        <button type="button" class="btn btn-danger" id="deleteLlamada">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>


        <script>
            // Guardar actualización de llamada
            document.getElementById('saveLlamada').addEventListener('click', function() {
                var llamadaId = document.getElementById('llamadaId').value;
                var estado = document.getElementById('llamadaStatus').value;
                var observaciones = document.getElementById('llamadaObservations').value;
                var fecha = document.getElementById('llamadaDate').value;
                var hora = document.getElementById('llamadaTime').value;

                var formData = new FormData();
                formData.append('llamada_id', llamadaId);
                formData.append('estado', estado);
                formData.append('observaciones', observaciones);
                formData.append('fecha', fecha);
                formData.append('hora', hora);

                // Verifica los datos en la consola
                for (var pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                // Alerta de confirmación antes de actualizar
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Estás a punto de actualizar esta llamada.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, realiza la actualización
                        fetch("{{ route('llamada.actualizar') }}", {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Mostrar alerta de éxito
                                    Swal.fire({
                                        title: '¡Actualización exitosa!',
                                        text: 'La llamada fue actualizada correctamente.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        $('#llamadaModal').modal('hide');
                                        location
                                            .reload(); // Recarga la página para reflejar los cambios
                                    });
                                } else {
                                    // Mostrar alerta de error
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Hubo un error al actualizar la llamada: ' + data
                                            .message,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                // Mostrar alerta de error en caso de excepción
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un error al procesar la solicitud.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            });
                    }


                });







                // Eliminar llamada
                document.getElementById('deleteLlamada').addEventListener('click', function() {
                    var llamadaId = document.getElementById('llamadaId').value;

                    // Confirmación antes de eliminar
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Estás a punto de eliminar esta llamada. Esta acción no se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Eliminar la llamada
                            fetch("{{ route('llamada.eliminar') }}", {
                                    method: 'POST',
                                    body: JSON.stringify({
                                        llamada_id: llamadaId
                                    }),
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'input[name="_token"]').value
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            title: '¡Eliminación exitosa!',
                                            text: 'La llamada fue eliminada correctamente.',
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            $('#llamadaModal').modal('hide');
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'Hubo un error al eliminar la llamada: ' +
                                                data.message,
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Hubo un error al procesar la solicitud.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                });
                        }
                    });



                });





                // Guardar actualización de levantamiento de información
                document.getElementById('saveInformacion').addEventListener('click', function() {
                    var form = document.getElementById('informacionForm');
                    var formData = new FormData(form);

                    // Alerta de confirmación antes de actualizar
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Estás a punto de actualizar esta información.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, actualizar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Si el usuario confirma, realiza la actualización
                            fetch("{{ route('informacion.actualizar') }}", {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'input[name="_token"]').value
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Mostrar alerta de éxito
                                        Swal.fire({
                                            title: '¡Actualización exitosa!',
                                            text: 'La información fue actualizada correctamente.',
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            $('#informacionModal').modal('hide');
                                            location
                                                .reload(); // Recarga la página para reflejar los cambios
                                        });
                                    } else {
                                        // Mostrar alerta de error
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'Hubo un error al actualizar la información: ' +
                                                data.message,
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    // Mostrar alerta de error en caso de excepción
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Hubo un error al procesar la solicitud.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                });
                        }
                    });
                });


            });
        </script>












        <!-- Modal para EVENTO -->
        <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-right modal-dialog-slide" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel">Actividad V2</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="eventForm">
                            @csrf
                            <input type="text" id="eventId" name="event_id">
                            <input type="text" id="eventTitulo" name="title">

                            <div class="form-group">
                                <label for="typeevento">Tipo</label>
                                <select class="form-control" id="typeevento" name="tipo">
                                    <option value="REUNION">REUNION</option>
                                    <option value="LEVANTAMIENTO INFORMACION">LEVANTAMIENTO INFORMACION</option>
                                    <option value="LLAMADA">LLAMADA</option>
                                </select>
                            </div>



                            <div class="form-group mb-3">
                                <label for="eventDate">Fecha</label>
                                <input type="date" id="eventDate" name="fecha" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label for="eventTime">Hora</label>
                                <input type="time" id="eventTime" name="hora" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label for="eventNote">Nota</label>
                                <textarea id="eventNote" name="observaciones" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="eventCliente">Cliente</label>
                                <input type="text" id="eventCliente" name="cliente" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label for="eventUser">Participantes</label>
                                <ul id="eventUser">
                                    <!-- Lista de participantes se llenará aquí -->
                                </ul>
                            </div>

                            <div class="form-group">
                                <label for="eventEstado">Estado</label>
                                <select class="form-control" id="eventEstado" name="estado">
                                    <option value="PENDIENTE">PENDIENTE</option>
                                    <option value="REALIZADO">REALIZADO</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="saveEvent">Actualizar</button>
                        <button type="button" class="btn btn-danger" id="deleteEvent">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>






        <script>
            document.getElementById('deleteEvent').addEventListener('click', function() {
                var eventId = document.getElementById('eventId').value;
                console.log('ID del evento:', eventId); // Para depurar y verificar el ID

                if (!eventId) {
                    Swal.fire({
                        title: 'Error',
                        text: 'ID del evento no válido.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Estás a punto de eliminar este evento.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('evento.eliminar') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    event_id: eventId
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok.');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    $('#eventModal').modal('hide');
                                    Swal.fire({
                                        title: '¡Eliminado!',
                                        text: 'El evento fue eliminado correctamente.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        $('#eventModal').modal('hide');
                                        location
                                            .reload(); // Recarga la página para reflejar los cambios
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Hubo un error al eliminar el evento: ' + data
                                            .message,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un error al procesar la solicitud.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            });
                    }
                });
            });


            document.getElementById('saveEvent').addEventListener('click', function() {
                var form = document.getElementById('eventForm');
                var formData = new FormData(form);

                // Alerta de confirmación antes de actualizar
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Estás a punto de actualizar este evento.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, realiza la actualización
                        fetch("{{ route('evento.actualizar') }}", {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    $('#eventModal').modal('hide');
                                    // Mostrar alerta de éxito
                                    Swal.fire({
                                        title: '¡Actualización exitosa!',
                                        text: 'El evento fue actualizado correctamente.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        $('#eventModal').modal('hide');
                                        location
                                            .reload(); // Recarga la página para reflejar los cambios
                                    });
                                } else {
                                    // Mostrar alerta de error
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Hubo un error al actualizar el evento: ' + data
                                            .message,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                // Mostrar alerta de error en caso de excepción
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un error al procesar la solicitud.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            });
                    }
                });
            });
        </script>


        <!-- Modal for handling salida -->
        <div class="modal fade" id="salidadModal" tabindex="-1" role="dialog" aria-labelledby="salidadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="salidadModalLabel">Detalles de la Salida</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="salidaId" name="salidaId">

                        <div class="form-group">
                            <label for="salidaStart">Fecha de Inicio</label>
                            <input type="datetime-local" class="form-control" id="salidaStart" name="start">
                        </div>
                        <div class="form-group">
                            <label for="salidaEnd">Fecha de Fin</label>
                            <input type="datetime-local" class="form-control" id="salidaEnd" name="end">
                        </div>
                        <div class="form-group">
                            <label for="salidaNote">Nota</label>
                            <textarea class="form-control" id="salidaNote" name="note"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="salidaMetaRegistros">Meta Registros</label>
                            <input type="text" class="form-control" id="salidaMetaRegistros" name="meta_registros">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-danger" id="deleteEventBtn">Eliminar</button>
                        <button type="button" class="btn btn-primary" id="saveEventBtn">Guardar Datos</button>
                    </div>
                </div>
            </div>
        </div>



        <script>
            document.getElementById('deleteEventBtn').addEventListener('click', function() {
                var salidaId = document.getElementById('salidaId').value;
                console.log('ID de la salida:', salidaId); // Para depurar y verificar el ID

                if (!salidaId) {
                    Swal.fire({
                        title: 'Error',
                        text: 'ID de la salida no válido.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Estás a punto de eliminar esta salida.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('salida.eliminar') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    salida_id: salidaId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    $('#salidadModal').modal('hide');
                                    Swal.fire({
                                        title: '¡Eliminado!',
                                        text: 'La salida fue eliminada correctamente.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        $('#salidadModal').modal('hide');
                                        location
                                            .reload(); // Recarga la página para reflejar los cambios
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Hubo un error al eliminar la salida: ' + data
                                            .message,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un error al procesar la solicitud.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            });
                    }
                });
            });

            document.getElementById('saveEventBtn').addEventListener('click', function() {
                var salidaId = document.getElementById('salidaId').value;
                var start = document.getElementById('salidaStart').value;
                var end = document.getElementById('salidaEnd').value;
                var note = document.getElementById('salidaNote').value;
                var metaRegistros = document.getElementById('salidaMetaRegistros').value;

                console.log('hola')

                // Alerta de confirmación antes de actualizar
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Estás a punto de guardar los datos de esta salida.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('salida.actualizar') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    salida_id: salidaId,
                                    start: start,
                                    end: end,
                                    note: note,
                                    meta_registros: metaRegistros
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    $('#salidadModal').modal('hide');
                                    Swal.fire({
                                        title: '¡Actualización exitosa!',
                                        text: 'La salida fue actualizada correctamente.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        $('#salidadModal').modal('hide');
                                        location
                                            .reload(); // Recarga la página para reflejar los cambios
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Hubo un error al actualizar la salida: ' + data
                                            .message,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un error al procesar la solicitud.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            });
                    }
                });
            });
        </script>










        @endsection

        <!-- Scripts -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/moment.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.stickOnScroll.js') }}"></script>
        <script src="{{ asset('assets/js/tinycolor-min.js') }}"></script>
        <script src="{{ asset('assets/js/config.js') }}"></script>
        <script src="{{ asset('assets/js/fullcalendar.js') }}"></script>
        <script src="{{ asset('assets/js/fullcalendar.custom.js') }}"></script>
        <script>
            /** calendario completo */
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                if (calendarEl) {
                    var events = @json($events);

                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        plugins: ['dayGrid', 'timeGrid', 'list', 'bootstrap'],
                        timeZone: 'UTC',
                        themeSystem: 'bootstrap',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        events: events,
                        editable: true,
                        droppable: true,
                        selectable: true,
                        eventClick: function(info) {
                            var event = info.event;
                            var eventType = event.extendedProps.type;

                            // Clear previous values
                            $('#eventId').val('');
                            $('#eventTitulo').val('');
                            $('#eventDate').val('');
                            $('#eventTime').val('');
                            $('#eventNote').val('');
                            $('#eventCliente').val('');
                            $('#eventUser').val('');
                            $('#eventEstado').val('');
                            $('#typeevento').val('');

                            $('#informacionTitle').val('');
                            $('#informacionDate').val('');
                            $('#informacionTime').val('');
                            $('#informacionDireccion').val('');
                            $('#informacionClient').val('');
                            $('#informacionUser').val('');
                            $('#informacionObservacion').val('');

                            // Clear previous values
                            $('#llamadaId').val('');
                            $('#llamadaTitle').val('');
                            $('#llamadaDate').val('');
                            $('#llamadaTime').val('');
                            $('#llamadaObservations').val('');
                            $('#llamadaClient').val('');
                            $('#llamadaUser').val('');
                            $('#llamadaStatus').val('');

                            $('#usernombre').val('');
                            $('#reunionId').val('');
                            $('#reunionTitle').val('');
                            $('#reunionDate').val('');
                            $('#reunionTime').val('');
                            $('#reunionObservation').val('');
                            $('#reunionClient').val('');
                            $('#reunionUser').val('');
                            $('#reunionZoom').val('');
                            $('#reunionEstado').val('');
                            $('#usuarioCliente').val('');
                            $('#usuarioReunion').val('');



                            // Limpiar valores anteriores
                            $('#salidaId').val('');
                            $('#salidaTitle').val('');
                            $('#salidaStart').val('');
                            $('#salidaEnd').val('');
                            $('#salidaNote').val('');
                            $('#salidaMetaRegistros').val('');
                            $('#salidaUser').val('');
                            $('#salidaType').val('');




                            // Set values based on event type
                            if (eventType === 'REUNIÓN') {
                                $('#reunionId').val(event.extendedProps.id_reunion);
                                $('#reunionTitle').val(event.extendedProps.tema);
                                $('#reunionDate').val(event.extendedProps.fecha);
                                $('#reunionTime').val(event.extendedProps.hora);
                                $('#reunionObservation').val(event.extendedProps.observacion);
                                $('#reunionClient').val(event.extendedProps.cliente);
                                $('#reunionUser').val(event.extendedProps
                                    .usuarioReunion); // Encargado de la reunión
                                $('#reunionZoom').val(event.extendedProps.zoom);
                                $('#reunionEstado').val(event.extendedProps.estado);
                                $('#usuarioCliente').val(event.extendedProps
                                    .usuarioCliente); // Usuario del cliente
                                $('#usuarioReunion').val(event.extendedProps
                                    .usuarioReunion); // Encargado de la reunión
                                $('#reunionModal').modal('show');
                                console.log(event.extendedProps)


                            } else if (eventType === 'LLAMAR') {
                                $('#llamadaId').val(event.extendedProps.id_llamada || '');
                                $('#llamadaClient').val(event.extendedProps.client || '');
                                $('#llamadaUser').val(event.extendedProps.user || '');
                                $('#llamadaObservations').val(event.extendedProps.description || '');
                                $('#llamadaStatus').val(event.extendedProps.status || '');

                                // Convertir la fecha y la hora del evento en formato adecuado para los campos
                                const startDate = new Date(event.start);
                                const dateString = startDate.toISOString().split('T')[0];
                                const timeString = startDate.toTimeString().split(' ')[0].substring(0,
                                    5);

                                $('#llamadaDate').val(dateString);
                                $('#llamadaTime').val(timeString);

                                console.log('Event data:', event.extendedProps);
                                $('#llamadaModal').modal('show');
                            } else if (eventType === 'LEVANTAMI..') {
                                $('#informacionId').val(event.extendedProps.id_informacion);
                                $('#informacionTitle').val(event.extendedProps.idtitle);
                                $('#informacionDate').val(event.extendedProps.fecha.split(' ')[0]);
                                $('#informacionTime').val(event.extendedProps.fecha.split(' ')[1]);
                                $('#informacionDireccion').val(event.extendedProps.direccion || '');
                                $('#informacionClient').val(event.extendedProps.cliente || '');
                                $('#informacionUser').val(event.extendedProps.user || '');
                                $('#informacionObservacion').val(event.extendedProps.observacion || '');
                                $('#informacionModal').modal('show');
                                console.log('INFORMACION: ', event.extendedProps.id_informacion);
                            } else if (eventType === 'EVENTO') {
                                $('#eventId').val(event.extendedProps.idevento);
                                $('#eventTitulo').val(event.extendedProps.title || 'No disponible');
                                $('#eventDate').val(moment(event.start).format('YYYY-MM-DD'));
                                $('#eventTime').val(moment(event.start).format('HH:mm'));
                                // Verificar y asignar la observación
                                console.log('Observaciones: ', event.extendedProps.note);
                                $('#eventNote').html(event.extendedProps.note ||
                                    'No disponible');

                                $('#eventCliente').val(event.extendedProps.clienteevento || '');
                                $('#eventUser').val(event.extendedProps.user_id || '');
                                $('#eventEstado').val(event.extendedProps.estado || '');
                                $('#typeevento').val(event.extendedProps.typeevento || '');

                                console.log('Event data:', event.extendedProps);

                                $('#eventModal').modal('show');
                            }
                            if (eventType === 'SALIDA') {
                                $('#salidaId').val(event.extendedProps.id_salida);
                                $('#salidaTitle').val(event.extendedProps.title || 'No disponible');
                                $('#salidaStart').val(moment(event.start).format('YYYY-MM-DD HH:mm'));
                                $('#salidaEnd').val(moment(event.end).format('YYYY-MM-DD HH:mm'));
                                $('#salidaNote').val(event.extendedProps.note || 'No disponible');
                                $('#salidaMetaRegistros').val(event.extendedProps.meta_registros ||
                                    'No disponible');
                                $('#salidaUser').val(event.extendedProps.user || 'Desconocido');
                                $('#salidaType').val(event.extendedProps.type || '');

                                console.log('SALIDA: ', event.extendedProps.id_salida);

                                $('#salidadModal').modal('show');
                            }
                        }
                    });

                    calendar.render();
                }
            });
        </script>

        <SCript>
            document.addEventListener('DOMContentLoaded', function() {
                var selectElement = document.querySelector('.select2');
                new Choices(selectElement, {
                    searchEnabled: true, // Habilitar la búsqueda
                    removeItemButton: true, // Botón para eliminar elementos
                    itemSelectText: 'Seleccionar', // Texto al seleccionar un item
                });
            });
        </SCript>





        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/fullcalendar.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css" />

        <script>

        </script>