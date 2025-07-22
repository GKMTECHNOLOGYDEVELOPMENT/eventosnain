@extends('layouts/contentNavbarLayout')

@section('title', 'Kanban - Proyecto')

@section('content')
<style>
.kanban-board {
    display: flex;
    gap: 24px;
    overflow-x: auto;
    padding: 16px 0;
    min-height: 70vh;
}
.kanban-column {
    background: #f8f9fa;
    border-radius: 12px;
    min-width: 320px;
    padding: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    height: fit-content;
}
.kanban-column h5 {
    font-weight: bold;
    margin-bottom: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.kanban-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    padding: 12px 16px;
    margin-bottom: 12px;
    border-left: 5px solid #696cff;
    cursor: grab;
    transition: transform 0.2s;
}
.kanban-card:hover {
    transform: translateY(-2px);
}
.kanban-card:last-child {
    margin-bottom: 0;
}
.kanban-card .card-actions {
    opacity: 0;
    transition: opacity 0.2s;
}
.kanban-card:hover .card-actions {
    opacity: 1;
}
.add-task-btn {
    width: 100%;
    margin-top: 12px;
    background: rgba(105, 108, 255, 0.1);
    color: #696cff;
    border: 1px dashed #696cff;
    padding: 8px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}
.add-task-btn:hover {
    background: rgba(105, 108, 255, 0.2);
}
.task-form {
    display: none;
    margin-top: 12px;
}
.kanban-card .badge {
    font-size: 0.75rem;
}
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.status-actions {
    opacity: 0;
    transition: opacity 0.2s;
}
.kanban-column:hover .status-actions {
    opacity: 1;
}
.sortable-ghost {
    opacity: 0.5;
    background: #e9ecef;
}
.empty-state {
    text-align: center;
    padding: 20px;
    color: #6c757d;
    font-style: italic;
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


<div class="container-fluid">
    <!-- Botón para añadir nuevo estado -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Gestor de Actividades</h4>
        <button class="btn btn-primary" id="addStatusBtn">
            <i class="bx bx-plus me-1"></i> Añadir Estado
        </button>
    </div>
    
    <div class="kanban-board" id="kanbanBoard">
        @foreach($statuses as $status)
        <div class="kanban-column" data-status-id="{{ $status->id }}">
            <h5>
                <span>{{ $status->name }}</span>
                <div class="d-flex align-items-center">
                    <span class="badge bg-label-primary me-2">{{ $status->tasks->count() }}</span>
                    <div class="status-actions">
                        <button class="btn btn-sm btn-icon edit-status" data-status-id="{{ $status->id }}" title="Editar estado">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-icon delete-status" data-status-id="{{ $status->id }}" title="Eliminar estado">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </div>
            </h5>
            
            <div class="kanban-tasks" data-status-id="{{ $status->id }}">
                @forelse($status->tasks as $task)
                <div class="kanban-card" data-task-id="{{ $task->id }}" draggable="true">
                    <div class="card-header">
                        <strong>{{ $task->title }}</strong>
                        <div class="card-actions">
                            <button class="btn btn-sm btn-icon edit-task" data-task-id="{{ $task->id }}" title="Editar tarea">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-icon delete-task" data-task-id="{{ $task->id }}" title="Eliminar tarea">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </div>
                    <p class="mb-1">{{ Str::limit($task->description, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="badge bg-label-{{ $task->status->color }}">{{ $task->status->name }}</span>
                        <small class="text-muted">{{ $task->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="bx bx-task bx-md mb-2"></i>
                    <p>No hay tareas en este estado</p>
                </div>
                @endforelse
            </div>
            
            <button class="add-task-btn" data-status-id="{{ $status->id }}">
                <i class="bx bx-plus"></i> Añadir tarea
            </button>
            
            <div class="task-form" id="taskForm-{{ $status->id }}">
                <form class="create-task-form" data-status-id="{{ $status->id }}">
                    @csrf
                    <div class="mb-3">
                        <label for="title-{{ $status->id }}" class="form-label">Título</label>
                        <input type="text" class="form-control" id="title-{{ $status->id }}" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description-{{ $status->id }}" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description-{{ $status->id }}" name="description" rows="2"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-label-secondary cancel-task">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal para añadir/editar estado -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalTitle">Añadir Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    @csrf
                    <input type="hidden" id="status_id" name="id">
                    <div class="mb-3">
                        <label for="status_name" class="form-label">Nombre del Estado</label>
                        <input type="text" class="form-control" id="status_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="status_color" class="form-label">Color</label>
                        <select class="form-select" id="status_color" name="color" required>
                            <option value="primary">Azul (Primary)</option>
                            <option value="secondary">Gris (Secondary)</option>
                            <option value="success">Verde (Success)</option>
                            <option value="danger">Rojo (Danger)</option>
                            <option value="warning">Amarillo (Warning)</option>
                            <option value="info">Celeste (Info)</option>
                            <option value="dark">Negro (Dark)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveStatusBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar tarea -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_task_id" name="id">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Título</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Estado</label>
                        <select class="form-select" id="edit_status" name="status_id">
                            @foreach($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveTaskChanges">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="confirmModalBody">
                ¿Estás seguro de que deseas eliminar este elemento?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function() {
    // Variables globales
    let currentDeleteId = null;
    let currentDeleteType = null;
    
    // Inicializar SortableJS para el drag and drop de tareas
    new Sortable(document.getElementById('kanbanBoard'), {
        animation: 150,
        handle: '.kanban-card',
        ghostClass: 'sortable-ghost',
        group: {
            name: 'kanban',
            pull: true,
            put: true
        },
        onEnd: function(evt) {
            const taskId = evt.item.dataset.taskId;
            const newStatusId = evt.to.closest('.kanban-column').dataset.statusId;
            
            $.ajax({
                url: `/tasks/${taskId}/update-status`,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    status_id: newStatusId
                },
                success: function(response) {
                    // Actualizar el badge de la tarea
                    const taskCard = $(`.kanban-card[data-task-id="${taskId}"]`);
                    taskCard.find('.badge').text(response.status.name).removeClass().addClass(`badge bg-label-${response.status.color}`);
                    
                    // Actualizar contadores
                    updateTaskCount(newStatusId);
                    if (evt.from !== evt.to) {
                        const oldStatusId = evt.from.dataset.statusId;
                        updateTaskCount(oldStatusId);
                    }
                    
                    toastr.success('Tarea movida correctamente');
                },
                error: function(error) {
                    toastr.error('Error al mover la tarea');
                    console.error(error);
                }
            });
        }
    });

    // Mostrar formulario para añadir tarea
    $('.add-task-btn').click(function() {
        const statusId = $(this).data('status-id');
        $(`#taskForm-${statusId}`).show();
        $(this).hide();
        $(`#taskForm-${statusId} input[name="title"]`).focus();
    });

    // Cancelar creación de tarea
    $('.cancel-task').click(function() {
        const form = $(this).closest('.task-form');
        form.hide();
        form.siblings('.add-task-btn').show();
        form.find('form')[0].reset();
    });

    // Crear nueva tarea
    $('.create-task-form').submit(function(e) {
        e.preventDefault();
        const statusId = $(this).data('status-id');
        const formData = $(this).serialize();
        
        $.ajax({
            url: '/tasks',
            method: 'POST',
            data: formData + `&status_id=${statusId}`,
            success: function(response) {
                const task = response.task;
                const statusColor = response.status_color;
                
                $(`#taskForm-${statusId}`).hide();
                $(`#taskForm-${statusId}`).siblings('.add-task-btn').show();
                $(`#taskForm-${statusId}`).find('form')[0].reset();
                
                // Eliminar empty state si existe
                $(`.kanban-tasks[data-status-id="${statusId}"] .empty-state`).remove();
                
                const taskHtml = `
                    <div class="kanban-card" data-task-id="${task.id}" draggable="true">
                        <div class="card-header">
                            <strong>${task.title}</strong>
                            <div class="card-actions">
                                <button class="btn btn-sm btn-icon edit-task" data-task-id="${task.id}" title="Editar tarea">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-icon delete-task" data-task-id="${task.id}" title="Eliminar tarea">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                        <p class="mb-1">${task.description ? task.description.substring(0, 100) : ''}</p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="badge bg-label-${statusColor}">${response.status_name}</span>
                            <small class="text-muted">Justo ahora</small>
                        </div>
                    </div>
                `;
                
                $(`.kanban-tasks[data-status-id="${statusId}"]`).prepend(taskHtml);
                updateTaskCount(statusId);
                toastr.success('Tarea creada correctamente');
            },
            error: function(error) {
                if (error.responseJSON && error.responseJSON.errors) {
                    for (const [key, value] of Object.entries(error.responseJSON.errors)) {
                        toastr.error(value[0]);
                    }
                } else {
                    toastr.error('Error al crear la tarea');
                }
                console.error(error);
            }
        });
    });

    // Editar tarea (mostrar modal)
    $(document).on('click', '.edit-task', function() {
        const taskId = $(this).data('task-id');
        
        $.ajax({
            url: `/tasks/${taskId}/edit`,
            method: 'GET',
            success: function(response) {
                $('#edit_task_id').val(response.id);
                $('#edit_title').val(response.title);
                $('#edit_description').val(response.description);
                $('#edit_status').val(response.status_id);
                
                const editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
                editModal.show();
            },
            error: function(error) {
                toastr.error('Error al cargar la tarea');
                console.error(error);
            }
        });
    });

    // Guardar cambios al editar tarea
    $('#saveTaskChanges').click(function() {
        const taskId = $('#edit_task_id').val();
        const formData = $('#editTaskForm').serialize();
        
        $.ajax({
            url: `/tasks/${taskId}`,
            method: 'PUT',
            data: formData,
            success: function(response) {
                const taskCard = $(`.kanban-card[data-task-id="${taskId}"]`);
                taskCard.find('strong').text(response.task.title);
                taskCard.find('p').text(response.task.description ? response.task.description.substring(0, 100) : '');
                taskCard.find('.badge').text(response.status.name).removeClass().addClass(`badge bg-label-${response.status.color}`);
                
                // Si cambió de estado, mover a la columna correspondiente
                if (response.moved) {
                    const newColumn = $(`.kanban-tasks[data-status-id="${response.task.status_id}"]`);
                    
                    // Eliminar empty state si existe en el nuevo estado
                    newColumn.find('.empty-state').remove();
                    
                    taskCard.detach().prependTo(newColumn);
                    updateTaskCount(response.old_status_id);
                    updateTaskCount(response.task.status_id);
                    
                    // Verificar si el estado anterior quedó vacío
                    if ($(`.kanban-tasks[data-status-id="${response.old_status_id}"] .kanban-card`).length === 0) {
                        $(`.kanban-tasks[data-status-id="${response.old_status_id}"]`).html(`
                            <div class="empty-state">
                                <i class="bx bx-task bx-md mb-2"></i>
                                <p>No hay tareas en este estado</p>
                            </div>
                        `);
                    }
                }
                
                $('#editTaskModal').modal('hide');
                toastr.success('Tarea actualizada correctamente');
            },
            error: function(error) {
                if (error.responseJSON && error.responseJSON.errors) {
                    for (const [key, value] of Object.entries(error.responseJSON.errors)) {
                        toastr.error(value[0]);
                    }
                } else {
                    toastr.error('Error al actualizar la tarea');
                }
                console.error(error);
            }
        });
    });

    // Eliminar tarea
    $(document).on('click', '.delete-task', function() {
        currentDeleteId = $(this).data('task-id');
        currentDeleteType = 'task';
        
        $('#confirmModalBody').html('¿Estás seguro de que quieres eliminar esta tarea?');
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        confirmModal.show();
    });

    // Manejar el modal para estados
    $('#addStatusBtn').click(function() {
        $('#statusModalTitle').text('Añadir Estado');
        $('#statusForm')[0].reset();
        $('#status_id').val('');
        $('#status_name').focus();
        
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        statusModal.show();
    });
    
    // Editar estado
    $(document).on('click', '.edit-status', function() {
        const statusId = $(this).data('status-id');
        
        $.ajax({
            url: `/statuses/${statusId}/edit`,
            method: 'GET',
            success: function(response) {
                $('#statusModalTitle').text('Editar Estado');
                $('#status_id').val(response.id);
                $('#status_name').val(response.name);
                $('#status_color').val(response.color);
                
                const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
                statusModal.show();
            },
            error: function(error) {
                toastr.error('Error al cargar el estado');
                console.error(error);
            }
        });
    });
    
   // Guardar estado - Esta es la parte que debemos modificar
$('#saveStatusBtn').click(function() {
    const formData = $('#statusForm').serialize();
    const statusId = $('#status_id').val();
    const method = statusId ? 'PUT' : 'POST';
    const url = statusId ? `/statuses/${statusId}` : '/statuses';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        success: function(response) {
            $('#statusModal').modal('hide');
            
            if (method === 'POST') {
                // Añadir nueva columna
                const columnHtml = `
                    <div class="kanban-column" data-status-id="${response.status.id}">
                        <h5>
                            <span>${response.status.name}</span>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-label-primary me-2">0</span>
                                <div class="status-actions">
                                    <button class="btn btn-sm btn-icon edit-status" data-status-id="${response.status.id}" title="Editar estado">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon delete-status" data-status-id="${response.status.id}" title="Eliminar estado">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </h5>
                        <div class="kanban-tasks" data-status-id="${response.status.id}">
                            <div class="empty-state">
                                <i class="bx bx-task bx-md mb-2"></i>
                                <p>No hay tareas en este estado</p>
                            </div>
                        </div>
                        <button class="add-task-btn" data-status-id="${response.status.id}">
                            <i class="bx bx-plus"></i> Añadir tarea
                        </button>
                        <div class="task-form" id="taskForm-${response.status.id}" style="display: none;">
                            <form class="create-task-form" data-status-id="${response.status.id}">
                                @csrf
                                <div class="mb-3">
                                    <label for="title-${response.status.id}" class="form-label">Título</label>
                                    <input type="text" class="form-control" id="title-${response.status.id}" name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description-${response.status.id}" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="description-${response.status.id}" name="description" rows="2"></textarea>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-label-secondary cancel-task">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                `;
                
                $('#kanbanBoard').append(columnHtml);
                
                // Inicializar los event listeners para la nueva columna
                initializeColumnEvents(response.status.id);
                
                toastr.success('Estado creado correctamente');
            } else {
                // Actualizar columna existente
                const column = $(`.kanban-column[data-status-id="${response.status.id}"]`);
                column.find('h5 span:first').text(response.status.name);
                column.find('.badge').removeClass().addClass(`badge bg-label-${response.status.color}`);
                toastr.success('Estado actualizado correctamente');
            }
        },
        error: function(error) {
            if (error.responseJSON && error.responseJSON.errors) {
                for (const [key, value] of Object.entries(error.responseJSON.errors)) {
                    toastr.error(value[0]);
                }
            } else {
                toastr.error('Error al guardar el estado');
            }
            console.error(error);
        }
    });
});

// Función para inicializar los eventos de una columna recién creada
function initializeColumnEvents(statusId) {
    // Evento para mostrar el formulario de tarea
    $(`.add-task-btn[data-status-id="${statusId}"]`).off('click').on('click', function() {
        const statusId = $(this).data('status-id');
        $(`#taskForm-${statusId}`).show();
        $(this).hide();
        $(`#taskForm-${statusId} input[name="title"]`).focus();
    });

    // Evento para cancelar creación de tarea
    $(`#taskForm-${statusId} .cancel-task`).off('click').on('click', function() {
        const form = $(this).closest('.task-form');
        form.hide();
        form.siblings('.add-task-btn').show();
        form.find('form')[0].reset();
    });

    // Evento para crear nueva tarea
    $(`#taskForm-${statusId} form.create-task-form`).off('submit').on('submit', function(e) {
        e.preventDefault();
        const statusId = $(this).data('status-id');
        const formData = $(this).serialize();
        
        $.ajax({
            url: '/tasks',
            method: 'POST',
            data: formData + `&status_id=${statusId}`,
            success: function(response) {
                const task = response.task;
                const statusColor = response.status_color;
                
                $(`#taskForm-${statusId}`).hide();
                $(`#taskForm-${statusId}`).siblings('.add-task-btn').show();
                $(`#taskForm-${statusId}`).find('form')[0].reset();
                
                // Eliminar empty state si existe
                $(`.kanban-tasks[data-status-id="${statusId}"] .empty-state`).remove();
                
                const taskHtml = `
                    <div class="kanban-card" data-task-id="${task.id}" draggable="true">
                        <div class="card-header">
                            <strong>${task.title}</strong>
                            <div class="card-actions">
                                <button class="btn btn-sm btn-icon edit-task" data-task-id="${task.id}" title="Editar tarea">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-icon delete-task" data-task-id="${task.id}" title="Eliminar tarea">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                        <p class="mb-1">${task.description ? task.description.substring(0, 100) : ''}</p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="badge bg-label-${statusColor}">${response.status_name}</span>
                            <small class="text-muted">Justo ahora</small>
                        </div>
                    </div>
                `;
                
                $(`.kanban-tasks[data-status-id="${statusId}"]`).prepend(taskHtml);
                updateTaskCount(statusId);
                toastr.success('Tarea creada correctamente');
                
                // Inicializar eventos para los nuevos botones de la tarea
                initializeTaskEvents(task.id);
            },
            error: function(error) {
                if (error.responseJSON && error.responseJSON.errors) {
                    for (const [key, value] of Object.entries(error.responseJSON.errors)) {
                        toastr.error(value[0]);
                    }
                } else {
                    toastr.error('Error al crear la tarea');
                }
                console.error(error);
            }
        });
    });
}

// Función para inicializar eventos de una tarea recién creada
function initializeTaskEvents(taskId) {
    // Evento para editar tarea
    $(`.edit-task[data-task-id="${taskId}"]`).off('click').on('click', function() {
        const taskId = $(this).data('task-id');
        
        $.ajax({
            url: `/tasks/${taskId}/edit`,
            method: 'GET',
            success: function(response) {
                $('#edit_task_id').val(response.id);
                $('#edit_title').val(response.title);
                $('#edit_description').val(response.description);
                $('#edit_status').val(response.status_id);
                
                const editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
                editModal.show();
            },
            error: function(error) {
                toastr.error('Error al cargar la tarea');
                console.error(error);
            }
        });
    });

    // Evento para eliminar tarea
    $(`.delete-task[data-task-id="${taskId}"]`).off('click').on('click', function() {
        currentDeleteId = $(this).data('task-id');
        currentDeleteType = 'task';
        
        $('#confirmModalBody').html('¿Estás seguro de que quieres eliminar esta tarea?');
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        confirmModal.show();
    });
}

// Inicializar eventos para todas las columnas existentes al cargar la página
function initializeAllColumnEvents() {
    $('.kanban-column').each(function() {
        const statusId = $(this).data('status-id');
        initializeColumnEvents(statusId);
    });
}

// Inicializar eventos para todas las tareas existentes al cargar la página
function initializeAllTaskEvents() {
    $('.kanban-card').each(function() {
        const taskId = $(this).data('task-id');
        initializeTaskEvents(taskId);
    });
}
    
    // Eliminar estado
    $(document).on('click', '.delete-status', function() {
        currentDeleteId = $(this).data('status-id');
        currentDeleteType = 'status';
        
        $('#confirmModalBody').html('¿Estás seguro de que quieres eliminar este estado? Todas las tareas asociadas también se eliminarán.');
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        confirmModal.show();
    });
    
    // Confirmar eliminación
    $('#confirmDeleteBtn').click(function() {
        const id = currentDeleteId;
        const type = currentDeleteType;
        
        if (type === 'task') {
            $.ajax({
                url: `/tasks/${id}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    const taskCard = $(`.kanban-card[data-task-id="${id}"]`);
                    const statusId = taskCard.closest('.kanban-tasks').data('status-id');
                    taskCard.remove();
                    updateTaskCount(statusId);
                    
                    // Mostrar empty state si no hay más tareas
                    if ($(`.kanban-tasks[data-status-id="${statusId}"] .kanban-card`).length === 0) {
                        $(`.kanban-tasks[data-status-id="${statusId}"]`).html(`
                            <div class="empty-state">
                                <i class="bx bx-task bx-md mb-2"></i>
                                <p>No hay tareas en este estado</p>
                            </div>
                        `);
                    }
                    
                    $('#confirmModal').modal('hide');
                    toastr.success('Tarea eliminada correctamente');
                },
                error: function(error) {
                    $('#confirmModal').modal('hide');
                    toastr.error('Error al eliminar la tarea');
                    console.error(error);
                }
            });
        } else if (type === 'status') {
            $.ajax({
                url: `/statuses/${id}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $(`.kanban-column[data-status-id="${id}"]`).remove();
                    $('#confirmModal').modal('hide');
                    toastr.success('Estado eliminado correctamente');
                },
                error: function(error) {
                    $('#confirmModal').modal('hide');
                    if (error.responseJSON && error.responseJSON.error) {
                        toastr.error(error.responseJSON.error);
                    } else {
                        toastr.error('Error al eliminar el estado');
                    }
                    console.error(error);
                }
            });
        }
    });
    
    // Actualizar orden de estados (drag and drop de columnas)
    new Sortable(document.getElementById('kanbanBoard'), {
        animation: 150,
        handle: '.kanban-column',
        ghostClass: 'sortable-ghost',
        onEnd: function(evt) {
            const statuses = [];
            $('#kanbanBoard .kanban-column').each(function(index) {
                statuses.push({
                    id: $(this).data('status-id'),
                    order: index + 1
                });
            });
            
            $.ajax({
                url: '/statuses/update-order',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    statuses: statuses
                },
                error: function(error) {
                    toastr.error('Error al actualizar el orden');
                    console.error(error);
                }
            });
        }
    });

    // Función para actualizar el contador de tareas
    function updateTaskCount(statusId) {
        const count = $(`.kanban-tasks[data-status-id="${statusId}"] .kanban-card`).length;
        $(`.kanban-column[data-status-id="${statusId}"] h5 .badge`).text(count);
    }
    
    // Mostrar notificaciones con toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };
});

// Inicializar todos los eventos al cargar la página
$(document).ready(function() {
    // ... (resto del código inicial)
    
    initializeAllColumnEvents();
    initializeAllTaskEvents();
    
    // ... (resto del código inicial)
});
</script>
@endsection