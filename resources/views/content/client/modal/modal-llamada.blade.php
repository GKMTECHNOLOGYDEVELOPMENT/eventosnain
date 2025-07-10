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