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