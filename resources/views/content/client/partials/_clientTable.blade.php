 @forelse ($clientes as $index => $cliente)
 <tr>
     <td>{{ $index + 1 }}</td> <!-- Contador basado en el índice de la iteración -->
     <td>{{ $cliente->nombre }}</td>
     <td>{{ $cliente->empresa }}</td>
     <td>{{ $cliente->telefono }}</td>
     <td>{{ $cliente->servicios }}</td>
     <!-- Badge for Status -->
     <td>
         @php
         // Inicializa el estado y la clase del badge
         $status = '';
         $badgeClass = '';

         // Determinar el estado y el color del badge basado en los valores
         if ($cliente->correo == 'NO' && $cliente->whatsapp == 'NO' && $cliente->llamada == 'NO' &&
         $cliente->reunion == 'NO') {
         $status = 'Pendiente';
         $badgeClass = 'badge-danger-custom'; // Rojo para Pendiente

         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'NO' && $cliente->llamada == 'NO' &&
         $cliente->reunion == 'NO') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso

         } elseif ($cliente->correo == 'NO' && $cliente->whatsapp == 'SI' && $cliente->llamada ==
         'SI' && $cliente->reunion == 'PENDIENTE') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso



         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'NO' && $cliente->llamada == 'NO' &&
         $cliente->reunion == 'PENDIENTE') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso

         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'PENDIENTE' && $cliente->llamada ==
         'PENDIENTE' &&
         $cliente->reunion == 'PENDIENTE') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso

         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'PENDIENTE' && $cliente->llamada ==
         'PENDIENTE' &&
         $cliente->reunion == 'PENDIENTE') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso


         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'PENDIENTE' && $cliente->llamada ==
         'PENDIENTE' &&
         $cliente->reunion == 'NO') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso

         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'SI' && $cliente->llamada ==
         'PENDIENTE' &&
         $cliente->reunion == 'NO') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso

         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'SI' && $cliente->llamada ==
         'PENDIENTE' &&
         $cliente->reunion == 'PENDIENTE') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso


         } elseif ($cliente->correo == 'NO' && $cliente->whatsapp == 'NO' && $cliente->llamada ==
         'PENDIENTE' &&
         $cliente->reunion == 'NO') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso



         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'SI' && $cliente->llamada ==
         'PENDIENTE' &&
         $cliente->reunion == 'PENDIENTE') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso




         } elseif ($cliente->correo == 'PENDIENTE' && $cliente->whatsapp == 'NO' &&
         $cliente->llamada ==
         'NO' &&
         $cliente->reunion == 'PENDIENTE') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso

         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'NO' && $cliente->llamada ==
         'SI' &&
         $cliente->reunion == 'NO') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso


         } elseif ($cliente->correo == 'PENDIENTE' && $cliente->whatsapp == 'SI' && $cliente->llamada ==
         'PENDIENTE' &&
         $cliente->reunion == 'SI') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso


         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'SI' && $cliente->llamada ==
         'SI' &&
         $cliente->reunion == 'PENDIENTE') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso

         } elseif ($cliente->correo == 'NO' && $cliente->whatsapp == 'SI' && $cliente->llamada ==
         'SI' &&
         $cliente->reunion == 'SI') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso

         } elseif ($cliente->correo == 'NO' && $cliente->whatsapp == 'NO' && $cliente->llamada ==
         'SI' &&
         $cliente->reunion == 'SI') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso

         } elseif ($cliente->correo == 'NO' && $cliente->whatsapp == 'NO' && $cliente->llamada ==
         'NO' &&
         $cliente->reunion == 'SI') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso


         } elseif ($cliente->correo == 'NO' && $cliente->whatsapp == 'NO' && $cliente->llamada ==
         'NO' &&
         $cliente->reunion == 'NO') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso


         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'NO' && $cliente->llamada ==
         'NO' &&
         $cliente->reunion == 'PENDIENTE') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso


         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'SI' && $cliente->llamada ==
         'PENDIENTE' &&
         $cliente->reunion == 'PENDIENTE') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso

         } elseif ($cliente->correo == 'NO' && $cliente->whatsapp == 'NO' && $cliente->llamada ==
         'SI' &&
         $cliente->reunion == 'PENDIENTE') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso

         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'SI' && $cliente->llamada == 'NO' &&
         $cliente->reunion == 'NO') {
         $status = 'Atendido';
         $badgeClass = 'badge-primary-custom'; // Azul para Atendido

         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'SI' && $cliente->llamada == 'SI' &&
         $cliente->reunion == 'NO') {
         $status = 'Atendido';
         $badgeClass = 'badge-Info-custom'; // Azul para Atendido



         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'SI' && $cliente->llamada == 'SI' &&
         $cliente->reunion == 'SI') {
         $status = 'Atendido';
         $badgeClass = 'badge-success-custom'; // Verde para Atendido


         } elseif ($cliente->correo == 'PENDIENTE' || $cliente->whatsapp == 'PENDIENTE' ||
         $cliente->llamada == 'PENDIENTE' || $cliente->reunion == 'PENDIENTE') {
         $status = 'Pendiente';
         $badgeClass = 'badge-danger-custom'; // Rojo para Pendiente

         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'PENDIENTE' && $cliente->llamada ==
         'PENDIENTE' && $cliente->reunion == 'PENDIENTE') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso

         } elseif ($cliente->correo == 'NO' && $cliente->whatsapp == 'SI' && $cliente->llamada ==
         'SI' && $cliente->reunion == 'NO') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso



         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'SI' && $cliente->llamada ==
         'PENDIENTE' && $cliente->reunion == 'PENDIENTE') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso






         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'SI' && $cliente->llamada ==
         'PENDIENTE' && $cliente->reunion == 'PENDIENTE') {
         $status = 'En Proceso';
         $badgeClass = 'badge-warning-custom'; // Amarillo para En Proceso

         } elseif ($cliente->correo == 'SI' && $cliente->whatsapp == 'SI' && $cliente->llamada ==
         'PENDIENTE' && $cliente->reunion == 'SI') {
         $status = 'En Proceso';
         $badgeClass = 'badge-info-custom'; // Azul Claro para En Proceso
         }
         @endphp

         <span class="badge {{ $badgeClass }}">{{ $status }}</span>

     </td>

     <td>{{ $cliente->salida->title ?? 'EXPO PROVEEDORES' }}</td>

     <td>{{ $cliente->correo }}</td>
     <td>{{ $cliente->whatsapp }}</td>
     <td>{{ $cliente->llamada ?? 'PENDIENTE'}} </td>
     <td>{{ $cliente->reunion }}</td>


     <td>
         <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
             data-bs-target="#statusModal{{ $cliente->id }}" title="Estado">
             <i class="fas fa-info-circle"></i>
         </button>
         <!-- Registro de llamada -
         <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
             data-bs-target="#llamadaModal{{ $cliente->id }}" title="Registro de llamadas">
             <i class="fa-solid fa-phone"></i>
         </button> -->
         @php
         // Verifica si hay reuniones para el cliente actual
         $reunionesCliente = $reuniones->get($cliente->id);
         @endphp

         <!-- <button type="button" id="video-button{{ $cliente->id }}" class="btn btn-info btn-sm" data-bs-toggle="modal"
             data-bs-target="#VideoModal{{ $cliente->id }}" title="Video Conferencia"
             {{ $reunionesCliente && $reunionesCliente->isNotEmpty() ? 'disabled' : '' }}>

             <i class="fa-solid fa-video"></i>
         </button> -->
         <!-- Script para manejar la habilitación del botón -->
         <!-- Modal Video -->
         <div class="modal fade" id="videoModal{{ $cliente->id }}" tabindex="-1"
             aria-labelledby="llamadaModalLabel{{ $cliente->id }}" aria-hidden="true">
             <div class="modal-dialog">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title" id="llamadaModalLabel{{ $cliente->id }}">Video
                             Conferencia</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <form action="{{ route('client.store-reunion') }}" method="POST">
                         @csrf
                         <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">
                         <div class="modal-body">
                             <div class="mb-3">
                                 <label for="fechaHora{{ $cliente->id }}" class="form-label">Fecha y Hora
                                     de la Reunión</label>
                                 <input type="datetime-local" id="fechaHora{{ $cliente->id }}" name="fecha_hora"
                                     class="form-control" required>
                             </div>
                             <div class="mb-3">
                                 <label for="observacion{{ $cliente->id }}" class="form-label">Observaciones</label>
                                 <textarea id="observacion{{ $cliente->id }}" name="observacion" class="form-control"
                                     rows="3"></textarea>
                             </div>
                             <div class="mb-3">
                                 <label for="tema{{ $cliente->id }}" class="form-label">Tema</label>
                                 <input type="text" id="tema{{ $cliente->id }}" name="tema" class="form-control"
                                     rows="3">
                             </div>
                             <div class="mb-3">
                                 <label for="user{{ $cliente->id }}" class="form-label">Encargado</label>
                                 <select id="user{{ $cliente->id }}" name="userid" class="form-select">
                                     <option value="">Selecciona un encargado</option>
                                     @foreach ($usuarios as $usuario)
                                     <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                         <div class="modal-footer">
                             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                             <button type="submit" class="btn btn-primary">Guardar</button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
         <!-- Modal Llamadas -->
         <div class="modal fade" id="llamadaModal{{ $cliente->id }}" tabindex="-1"
             aria-labelledby="llamadaModalLabel{{ $cliente->id }}" aria-hidden="true">
             <div class="modal-dialog">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title" id="llamadaModalLabel{{ $cliente->id }}">Registro de
                             Llamadas</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <form action="{{ route('client.storeCall') }}" method="POST">
                         @csrf
                         <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">
                         <div class="modal-body">
                             <div class="mb-3">
                                 <label for="llamadaFecha{{ $cliente->id }}" class="form-label">Fecha y
                                     Hora de la Llamada</label>
                                 <input type="datetime-local" id="llamadaFecha{{ $cliente->id }}" name="date"
                                     class="form-control" required>
                             </div>

                             <div class="mb-3">
                                 <label for="observacionesLlamada{{ $cliente->id }}"
                                     class="form-label">Observaciones</label>
                                 <textarea id="observacionesLlamada{{ $cliente->id }}" name="observaciones"
                                     class="form-control" rows="3"></textarea>
                             </div>
                         </div>
                         <div class="modal-footer">
                             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                             <button type="submit" class="btn btn-primary">Guardar</button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>

         <!-- Modal -->
         <div class="modal fade" id="statusModal{{ $cliente->id }}" tabindex="-1"
             aria-labelledby="statusModalLabel{{ $cliente->id }}" aria-hidden="true">
             <div class="modal-dialog">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title" id="statusModalLabel{{ $cliente->id }}">Actualizar
                             Estado del Cliente</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <form action="{{ route('client.updateStatus', $cliente->id) }}" method="POST">
                         @csrf
                         @method('PUT')
                         <div class="modal-body">
                             <!-- Campos del formulario -->
                             <div class="mb-3">
                                 <label for="correo{{ $cliente->id }}" class="form-label">Correo</label>
                                 <select class="form-select" id="correo{{ $cliente->id }}" name="correo"
                                     data-client-id="{{ $cliente->id }}">
                                     <option value="PENDIENTE"
                                         {{ old('correo', $cliente->correo) === 'PENDIENTE' ? 'selected' : '' }}>
                                         Seleccione una opción</option>
                                     <option value="SI"
                                         {{ old('correo', $cliente->correo) === 'SI' ? 'selected' : '' }}>
                                         Sí</option>
                                     <option value="NO"
                                         {{ old('correo', $cliente->correo) === 'NO' ? 'selected' : '' }}>
                                         No</option>
                                 </select>
                             </div>


                             <!-- Aquí puedes agregar otros campos y detalles -->
                             <div class="mb-3">
                                 <label for="whatsapp{{ $cliente->id }}" class="form-label">WhatsApp</label>
                                 <select class="form-select" id="whatsapp{{ $cliente->id }}" name="whatsapp"
                                     data-client-id="{{ $cliente->id }}">
                                     <option value="PENDIENTE"
                                         {{ old('whatsapp', $cliente->whatsapp) === 'PENDIENTE' ? 'selected' : '' }}>
                                         Seleccione una opción</option>
                                     <option value="SI"
                                         {{ old('whatsapp', $cliente->whatsapp) === 'SI' ? 'selected' : '' }}>
                                         Sí</option>
                                     <option value="NO"
                                         {{ old('whatsapp', $cliente->whatsapp) === 'NO' ? 'selected' : '' }}>
                                         No</option>
                                 </select>
                             </div>
                             <div class="mb-3">
                                 <label for="llamada{{ $cliente->id }}" class="form-label">Llamada</label>
                                 <select class="form-select" id="llamada{{ $cliente->id }}" name="llamada"
                                     data-client-id="{{ $cliente->id }}">
                                     <option value="PENDIENTE"
                                         {{ old('llamada', $cliente->llamada) === 'PENDIENTE' ? 'selected' : '' }}>
                                         Seleccione una opción</option>
                                     <option value="SI"
                                         {{ old('llamada', $cliente->llamada) === 'SI' ? 'selected' : '' }}>
                                         Sí</option>
                                     <option value="NO"
                                         {{ old('llamada', $cliente->llamada) === 'NO' ? 'selected' : '' }}>
                                         No</option>
                                 </select>
                             </div>

                             <!-- Observaciones específicas del cliente -->
                             @php
                             $observacion = $observaciones->firstWhere('id_cliente', $cliente->id);
                             @endphp

                             <div class="mb-3">
                                 <label for="observaciones_llamada{{ $cliente->id }}" class="form-label">Observaciones
                                     de Llamada</label>
                                 <textarea id="observaciones_llamada{{ $cliente->id }}" name="observaciones_llamada"
                                     class="form-control" rows="3">
                                 {{ old('observaciones_llamada', $observacion->observacionllamada ?? '') }}
                                 </textarea>
                             </div>
                             <div class="mb-3">
                                 <label for="reunion{{ $cliente->id }}" class="form-label">Reunión</label>
                                 <select class="form-select" id="reunion{{ $cliente->id }}" name="reunion"
                                     data-client-id="{{ $cliente->id }}">
                                     <option value="PENDIENTE"
                                         {{ old('reunion', $cliente->reunion) === 'PENDIENTE' ? 'selected' : '' }}>
                                         Seleccione una opción</option>
                                     <option value="SI"
                                         {{ old('reunion', $cliente->reunion) === 'SI' ? 'selected' : '' }}>
                                         Sí</option>
                                     <option value="NO"
                                         {{ old('reunion', $cliente->reunion) === 'NO' ? 'selected' : '' }}>
                                         No</option>
                                 </select>
                             </div>

                             <!-- Campo de observaciones de reunión, inicialmente oculto -->
                             <div class="mb-3" id="observaciones-reunion-container{{ $cliente->id }}">
                                 <label for="observaciones-reunion{{ $cliente->id }}" class="form-label">Observaciones
                                     de Reunión</label>
                                 <textarea id="observaciones-reunion{{ $cliente->id }}" name="observaciones_reunion"
                                     class="form-control" rows="3">
                                 {{ old('observaciones_reunion', $observacion->observacionreunion ?? '') }}
                                 </textarea>
                             </div>

                         </div>
                         <div class="modal-footer">
                             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                             <button type="submit" class="btn btn-primary">Actualizar</button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>




         <!-- <a href="{{ route('client.edit', $cliente->id) }}" class="btn btn-success btn-sm"
                            title="Actualizar Datos">
                            <i class="fas fa-edit"></i>
                        </a> -->

         <a href="{{ route('client.status', $cliente->id) }}" class="btn btn-warning btn-sm" title="Detalles">
             <i class="fas fa-file-alt"></i>
         </a>
         @if (auth()->user()->rol_id == 1)
         <form id="delete-form-{{ $cliente->id }}" action="{{ route('client.destroy', $cliente->id) }}" method="POST"
             style="display:inline;">
             @csrf
             @method('DELETE')
         </form>
         <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $cliente->id }})">
             <i class="fas fa-trash-alt"></i>
         </button>
         @endif

         <script>
             function confirmDelete(clienteId) {
                 Swal.fire({
                     title: '¿Estás seguro?',
                     text: "¡No podrás revertir esta acción!",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Sí, eliminarlo!',
                     cancelButtonText: 'Cancelar'
                 }).then((result) => {
                     if (result.isConfirmed) {
                         document.getElementById('delete-form-' + clienteId).submit();
                     }
                 });
             }
         </script>

     </td>
 </tr>
 @empty
 <tr>
     <td colspan="10" class="text-center">No hay clientes registrados.</td>
 </tr>
 @endforelse