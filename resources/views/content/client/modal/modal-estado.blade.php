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
               <form class="status-form" action="{{ route('client.updateStatus', $cliente->id) }}" method="POST">
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
                               <option value="SI" {{ old('correo', $cliente->correo) === 'SI' ? 'selected' : '' }}>
                                   Sí</option>
                               <option value="NO" {{ old('correo', $cliente->correo) === 'NO' ? 'selected' : '' }}>
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
                               <option value="SI" {{ old('whatsapp', $cliente->whatsapp) === 'SI' ? 'selected' : '' }}>
                                   Sí</option>
                               <option value="NO" {{ old('whatsapp', $cliente->whatsapp) === 'NO' ? 'selected' : '' }}>
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
                               <option value="SI" {{ old('llamada', $cliente->llamada) === 'SI' ? 'selected' : '' }}>
                                   Sí</option>
                               <option value="NO" {{ old('llamada', $cliente->llamada) === 'NO' ? 'selected' : '' }}>
                                   No</option>
                           </select>
                       </div>


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
                               <option value="SI" {{ old('reunion', $cliente->reunion) === 'SI' ? 'selected' : '' }}>
                                   Sí</option>
                               <option value="NO" {{ old('reunion', $cliente->reunion) === 'NO' ? 'selected' : '' }}>
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