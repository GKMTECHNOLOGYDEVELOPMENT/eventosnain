 @forelse ($clientes as $index => $cliente)
 <tr>
     <td>{{ $index + 1 }}</td> <!-- Contador basado en el índice de la iteración -->
     <td>{{ $cliente->nombre }}</td>
     <td>{{ $cliente->empresa }}</td>
     <td>{{ $cliente->telefono }}</td>
     <td>{{ $cliente->servicios }}</td>
     <td>{{ $cliente->tipo_cliente }}</td>
     <td>{{ $cliente->salida->title ?? 'EXPO PROVEEDORES' }}</td>
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


     <td>{{ $cliente->correo }}</td>
     <td>{{ $cliente->whatsapp }}</td>
     <td>{{ $cliente->llamada ?? 'PENDIENTE'}} </td>
     <td>{{ $cliente->reunion }}</td>



 </tr>
 @empty
 <tr>
     <td colspan="10" class="text-center">No hay clientes registrados.</td>
 </tr>
 @endforelse