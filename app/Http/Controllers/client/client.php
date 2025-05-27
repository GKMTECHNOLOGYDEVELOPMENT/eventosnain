<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\ClienteRegistrado;
use App\Mail\ClienteRegistradoseG;
use App\Mail\ClienteRegistradoseM;
use App\Mail\ClienteRegistradoser;
use App\Mail\ClienteRegistradoses;
use App\Mail\CotizacionMail;
use App\Mail\FormSubmissionMail;
use App\Models\Atencion;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Event;
use App\Models\EventUser;
use App\Models\Informacion;
use App\Models\Llamada;
use App\Models\Observacion;
use App\Models\Reunion;
use App\Models\Salida;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Client extends Controller
{
  public function index()
  {
    $usuarioAutenticado = auth()->user();

    // Obtener los clientes con paginación (10 por página)
    if ($usuarioAutenticado->rol_id == 1 || $usuarioAutenticado->rol_id == 3) {
      $clientes = Cliente::with('salida')->paginate(10);
    } else {
      $clientes = Cliente::with('salida')
        ->where('user_id', $usuarioAutenticado->id)
        ->paginate(10);
    }

    // Obtener reuniones, observaciones y otros datos relacionados
    $clienteIds = $clientes->pluck('id');
    $reuniones = Reunion::whereIn('cliente_id', $clienteIds)->get()->groupBy('cliente_id');
    $usuarios = User::where('rol_id', 1)->get();
    $observaciones = Observacion::whereIn('id_cliente', $clienteIds)->get()->keyBy('id_cliente');

    // Si es una solicitud AJAX, solo devolver la tabla y la paginación
    if (request()->ajax()) {
      return view('content.client.partials._clientTable', compact('clientes', 'reuniones', 'observaciones'))->render();
    }

    return view('content.client.client-clientList', compact('clientes', 'usuarios', 'reuniones', 'observaciones'));
  }

  public function getClientes(Request $request)
  {
    try {
      $columns = [
        'id',
        'nombre',
        'empresa',
        'telefono',
        'servicios',
        'status',
        'events_id',
        'correo',
        'whatsapp',
        'llamada',
        'reunion'
      ];

      $query = Cliente::query();
      $total = Cliente::count();

      if (!empty($request->search['value'])) {
        $search = $request->search['value'];
        $query->where(function ($q) use ($search) {
          $q->where('nombre', 'like', "%{$search}%")
            ->orWhere('empresa', 'like', "%{$search}%")
            ->orWhere('telefono', 'like', "%{$search}%")
            ->orWhere('correo', 'like', "%{$search}%");
        });
      }

      $filtered = $query->count();

      $clientes = $query
        ->offset($request->start)
        ->limit($request->length)
        ->orderBy($columns[$request->order[0]['column']] ?? 'id', $request->order[0]['dir'] ?? 'desc')
        ->get();

      $data = [];
      foreach ($clientes as $i => $c) {
        $data[] = [
          'DT_RowIndex' => $request->start + $i + 1,
          'nombre' => $c->nombre,
          'empresa' => $c->empresa,
          'telefono' => $c->telefono,
          'servicios' => $c->servicios,
          'status' => '<span class="badge bg-' . ($c->status == 'Activo' ? 'success' : 'secondary') . '">' . $c->status . '</span>',
          'events_id' => $c->events_id ?? '—',
          'correo' => $c->correo,
          'whatsapp' => $c->whatsapp,
          'llamada' => $c->llamada,
          'reunion' => $c->reunion,
          'acciones' => '
            <div class="action-btns">
                <a href="' . route('client.edit', $c->id) . '" class="btn btn-warning"><i class="bx bx-edit"></i></a>
                <button class="btn btn-danger delete-cliente" data-id="' . $c->id . '"><i class="bx bx-trash"></i></button>
            </div>',

        ];
      }

      return response()->json([
        'draw' => intval($request->draw),
        'recordsTotal' => $total,
        'recordsFiltered' => $filtered,
        'data' => $data,
      ]);
    } catch (\Throwable $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => $e->getFile()
      ], 500);
    }
  }




  public function reunion()
  {
    // Obtener todos los clientes
    $clientes = Cliente::where('proceso', 'si')
      ->where('status', 'Atendido')
      ->get();

    // Obtener todas las reuniones y agruparlas por cliente_id
    $reuniones = Reunion::whereIn('cliente_id', $clientes->pluck('id'))->get()->groupBy('cliente_id');

    // Obtener todos los usuarios
    $usuarios = User::where('rol_id', 4)->get();

    // Obtener todos los eventos asociados a los clientes
    $eventos = Event::whereIn('id', $clientes->pluck('events_id'))->get()->pluck('title', 'id');

    // Mostrar la vista con todos los clientes, usuarios, reuniones y eventos
    return view('content.client.client-clientReunion', compact('clientes', 'usuarios', 'reuniones', 'eventos'));
  }


  public function actualizar(Request $request)
  {
    // Validar los datos de la solicitud
    $validated = $request->validate([
      'llamada_id' => 'required|integer|exists:llamada,id_llamada',
      'observaciones' => 'nullable|string',
      'estado' => 'required|string|in:PENDIENTE,REALIZADO',
      'fecha' => 'required|date_format:Y-m-d', // Validar el formato de la fecha
      'hora' => 'required|date_format:H:i', // Validar el formato de la hora
    ]);

    try {
      // Buscar la llamada por su ID
      $llamada = Llamada::find($validated['llamada_id']);

      // Si no se encuentra la llamada, devolver un mensaje de error
      if (!$llamada) {
        return response()->json(['success' => false, 'message' => 'Llamada no encontrada']);
      }

      // Combinar la fecha y la hora en un solo campo datetime
      $date = $validated['fecha'] . ' ' . $validated['hora'] . ':00';

      // Actualizar los campos de la llamada
      $llamada->update([
        'observaciones' => $validated['observaciones'],
        'estado' => $validated['estado'],
        'date' => $date, // Actualizar la fecha y hora
      ]);

      // Devolver una respuesta exitosa
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      // Registrar el error y devolver una respuesta de error
      Log::error('Error al actualizar llamada: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => 'Error interno del servidor']);
    }
  }









  public function actualizarreunion(Request $request)
  {
    $validated = $request->validate([
      'reunion_id' => 'required|integer|exists:reunion,id_reunion',
      'observacion' => 'nullable|string|max:255',
      'estado' => 'required|string|in:PENDIENTE,REALIZADO',
      'fecha' => 'required|date',
      'hora' => 'required|date_format:H:i',
      'zoom' => 'nullable|string|max:255',
    ]);

    try {
      $reunion = Reunion::find($validated['reunion_id']);

      if (!$reunion) {
        return response()->json(['success' => false, 'message' => 'Reunión no encontrada']);
      }

      // Combina la fecha y la hora en un solo campo datetime
      $fechaHora = $validated['fecha'] . ' ' . $validated['hora'];

      $reunion->update([
        'observacion' => $validated['observacion'],
        'estado' => $validated['estado'],
        'fecha_hora' => $fechaHora,
        'zoom' => $validated['zoom'],
      ]);

      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      Log::error('Error al actualizar reunión: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => 'Error interno del servidor']);
    }
  }



  public function actualizarinformacion(Request $request)
  {
    $validated = $request->validate([
      'informacion_id' => 'required|integer|exists:informacion,id_informacion',
      'direccion' => 'nullable|string|max:255',
      'observacion' => 'nullable|string',
    ]);

    try {
      $informacion = Informacion::find($validated['informacion_id']);

      if (!$informacion) {
        return response()->json(['success' => false, 'message' => 'Información no encontrada']);
      }

      $informacion->update([
        'direccion' => $validated['direccion'],
        'observacion' => $validated['observacion'],
      ]);

      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      Log::error('Error al actualizar información: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => 'Error interno del servidor']);
    }
  }



  public function eliminar(Request $request)
  {
    try {
      $llamada = Llamada::find($request->input('llamada_id'));

      if ($llamada) {
        $llamada->delete();
        return response()->json(['success' => true]);
      } else {
        return response()->json(['success' => false, 'message' => 'Llamada no encontrada']);
      }
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Error al eliminar la llamada: ' . $e->getMessage()]);
    }
  }



  public function eliminarReunion(Request $request)
  {
    $request->validate([
      'reunion_id' => 'required|integer|exists:reunion,id_reunion',
    ]);

    try {
      $reunion = Reunion::findOrFail($request->input('reunion_id'));
      $reunion->delete();

      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Error al eliminar la reunión.']);
    }
  }


  public function eliminarEvento(Request $request)
  {
    try {
      // Busca el evento con el modelo correcto
      $event = Event::findOrFail($request->input('event_id'));

      // Elimina el evento
      $event->delete();

      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      // Registra el error
      \Log::error('Error al eliminar el evento: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => 'Error al eliminar el evento.']);
    }
  }


  public function actualizarevento(Request $request)
  {
    // Validar los datos de la solicitud
    $validated = $request->validate([
      'event_id' => 'required|integer|exists:events,id',
      'nota' => 'nullable|string|max:255',
      'estado' => 'required|string|in:PENDIENTE,REALIZADO',
      'tipo' => 'required|string|in:REUNION,LEVANTAMIENTO INFORMACION,LLAMADA',
      'fecha' => 'required|date_format:Y-m-d', // Validar el formato de la fecha
      'hora' => 'required|date_format:H:i', // Validar el formato de la hora
    ]);

    try {
      // Buscar el evento por su ID
      $evento = Event::find($validated['event_id']);

      // Si no se encuentra el evento, devolver un mensaje de error
      if (!$evento) {
        return response()->json(['success' => false, 'message' => 'Evento no encontrado']);
      }

      // Combinar la fecha y la hora en un solo campo datetime
      $start = $validated['fecha'] . ' ' . $validated['hora'] . ':00';
      $end = $start; // Ajusta esto si tienes una lógica para calcular la fecha de finalización

      // Actualizar los campos del evento
      $evento->update([
        'note' => $validated['nota'],
        'estado' => $validated['estado'],
        'type' => $validated['tipo'],
        'start' => $start, // Actualizar la fecha y hora de inicio
        'end' => $end,     // Actualizar la fecha y hora de fin
      ]);

      // Devolver una respuesta exitosa
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      // Registrar el error y devolver una respuesta de error
      Log::error('Error al actualizar evento: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => 'Error interno del servidor']);
    }
  }



  // En SalidaController.php
  public function actualizarSalida(Request $request)
  {
    $request->validate([
      'salida_id' => 'required|integer|exists:salida,id',
      'start' => 'required|date_format:Y-m-d\TH:i',
      'end' => 'required|date_format:Y-m-d\TH:i',
      'note' => 'nullable|string',
      'meta_registros' => 'nullable|string',
    ]);

    try {
      $salida = Salida::findOrFail($request->input('salida_id'));

      $salida->start = $request->input('start');
      $salida->end = $request->input('end');
      $salida->note = $request->input('note');
      $salida->meta_registros = $request->input('meta_registros');
      $salida->save();

      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      \Log::error('Error al actualizar la salida: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => 'Error al actualizar la salida.']);
    }
  }

  public function eliminarSalida(Request $request)
  {
    $request->validate([
      'salida_id' => 'required|integer|exists:salida,id',
    ]);

    try {
      $salida = Salida::findOrFail($request->input('salida_id'));
      $salida->delete();

      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      \Log::error('Error al eliminar la salida: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => 'Error al eliminar la salida.']);
    }
  }

  public function guardar(Request $request)
  {
    // Validación de datos
    $request->validate([
      'salida_id' => 'required|integer',
      'user_id' => 'required|integer',
      'meta_usuario' => 'required|integer',
    ]);

    // Verificar si el registro ya existe
    $exists = SalidaUser::where('salida_id', $request->input('salida_id'))
      ->where('user_id', $request->input('user_id'))
      ->exists();

    if ($exists) {
      // Redirigir con un mensaje de error si el registro ya existe
      return redirect()->back()->with('error', 'El registro ya existe.');
    }

    // Crear nuevo registro en la base de datos con fecha actual
    SalidaUser::create([
      'salida_id' => $request->input('salida_id'),
      'user_id' => $request->input('user_id'),
      'meta_usuario' => $request->input('meta_usuario'),
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);

    // Redirigir con un mensaje de éxito
    return redirect()->back()->with('success', 'Registro guardado exitosamente.');
  }



  public function calendario()
  {
    // Obtener el usuario autenticado
    $user = Auth::user();
    $rolId = $user->rol_id;

    // Determinar el filtro de eventos basado en el rol del usuario
    $query = function ($query) use ($user) {
      $query->where('user_id', $user->id);
    };

    if ($rolId == 1 || $rolId == 2) {
      // Usuario con rol_id 1: Obtener todos los eventos
      $reunionEvents = Reunion::all()->map($this->mapReunionEvent());
      $informacionEvents = Informacion::all()->map($this->mapInformacionEvent());
      $llamadaEvents = Llamada::all()->map($this->mapLlamadaEvent());
      $eventsTableEvents = Event::all()->map($this->mapEventTableEvent());
      $salidaEvents = Salida::all()->map($this->mapSalidaEvent()); // Añadido
    } else {
      // Usuario con rol_id 2, 3 o 4: Filtrar eventos asociados al usuario
      $reunionEvents = Reunion::where('userid', $user->id)->get()->map($this->mapReunionEvent());
      $informacionEvents = Informacion::where('users_id', $user->id)->get()->map($this->mapInformacionEvent());
      $llamadaEvents = Llamada::where('user_id', $user->id)->get()->map($this->mapLlamadaEvent());
      $eventsTableEvents = Event::where('user_id', $user->id)->get()->map($this->mapEventTableEvent());
      $salidaEvents = Salida::where('user_id', $user->id)->get()->map($this->mapSalidaEvent()); // Añadido
    }

    // Unir todos los eventos
    $events = $reunionEvents->concat($informacionEvents)
      ->concat($llamadaEvents)
      ->concat($eventsTableEvents)
      ->concat($salidaEvents); // Añadido

    // Mostrar todos los usuarios
    $usuarios = User::all();

    // Mostrar todos los clientes
    $clientes = Cliente::all();

    //llamada
    $llamada = Llamada::where('user_id', $user->id)->get();
    $llamadas = Llamada::all();

    // Debugging: Log the events data
    Log::debug('Events Data:', ['events' => $events]);

    // Obtener todas las salidas
    $salidas = Salida::all();

    // Pasar los eventos a la vista
    return view('content.client.client-clientCalendario', compact('llamada', 'usuarios', 'clientes', 'events', 'llamadas', 'salidas'));
  }

  protected function mapSalidaEvent()
  {
    return function ($salida) {
      $start = Carbon::parse($salida->start);
      $end = Carbon::parse($salida->end);

      return [
        'id_salida' => $salida->id,
        'title' => $salida->title,
        'start' => $start->toDateTimeString(),
        'end' => $end->toDateTimeString(),
        'note' => $salida->note,
        'color' => 'purple', // Puedes personalizar el color
        'type' => 'SALIDA',
        'meta_registros' => $salida->meta_registros,
        'user' => User::find($salida->user_id)->name ?? 'Desconocido',
      ];
    };
  }

  protected function mapReunionEvent()
  {
    return function ($reunion) {
      $fechaHora = Carbon::parse($reunion->fecha_hora);
      $cliente = Cliente::find($reunion->cliente_id);
      $user = User::find($reunion->userid);

      return [
        'id_reunion' => $reunion->id_reunion,
        'title' => "REUNIÓN",
        'start' => $fechaHora->toDateTimeString(),
        'end' => $fechaHora->copy()->addHour()->toDateTimeString(),
        'description' => "Tema: " . $reunion->tema .
          "\nObservación: " . $reunion->observacion .
          "\nCliente: " . ($cliente ? $cliente->nombre : 'Desconocido') .
          "\nEncargado: " . ($user ? $user->name : 'Desconocido'),
        'color' => 'blue',
        'type' => 'REUNIÓN', // Añade el tipo de evento aquí
        'tema' => $reunion->tema,
        'cliente' => $cliente ? $cliente->nombre : 'Desconocido',
        'user' => $user ? $user->name : 'Desconocido',
        'observacion' => $reunion->observacion,
        'fecha' => $fechaHora->toDateString(),
        'hora' => $fechaHora->format('H:i'),
        'zoom' => $reunion->zoom,
        'estado' => $reunion->estado
      ];
    };
  }


  protected function mapInformacionEvent()
  {
    return function ($info) {
      $fecha = Carbon::parse($info->fecha);
      $cliente = Cliente::find($info->cliente_id);
      $user = User::find($info->users_id);

      return [
        'id_informacion' => $info->id_informacion,
        'title' => 'LEVANTAMI..',
        'start' => $fecha->toDateTimeString(),
        'end' => $fecha->copy()->addHour()->toDateTimeString(),
        'observacion' => $info->observacion,
        'color' => 'green',
        'direccion' => $info->dirrecion,
        'cliente' => $cliente ? $cliente->nombre : 'Desconocido',
        'user' => $user ? $user->name : 'Desconocido',
        'fecha' => $fecha->toDateString(),
        'hora' => $fecha->format('H:i'),
        'type' => 'LEVANTAMI..'
      ];
    };
  }

  protected function mapLlamadaEvent()
  {
    return function ($llamada) {
      $fecha = Carbon::parse($llamada->date);
      $cliente = Cliente::find($llamada->cliente_id);
      $usuario = User::find($llamada->user_id);

      Log::info('Llamada :', ['id' => $llamada->id_llamada]);

      return [
        'id_llamada' => $llamada->id_llamada, // Asegúrate de usar 'id_llamada'
        'title' => 'LLAMAR',
        'start' => $fecha->toDateTimeString(),
        'end' => $fecha->copy()->addHour()->toDateTimeString(),
        'description' => $llamada->observaciones,
        'color' => 'orange',
        'client' => $cliente ? $cliente->nombre : 'Desconocido',
        'user' => $usuario ? $usuario->name : 'Desconocido',
        'status' => $llamada->estado,
        'type' => 'LLAMAR'
      ];
    };
  }







  protected function mapEventTableEvent()
  {
    return function ($event) {
      $start = Carbon::parse($event->start);
      $end = Carbon::parse($event->end);
      $cliente = Cliente::find($event->cliente_id);

      // Obtener los usuarios relacionados con el evento
      $participantes = DB::table('event_user')
        ->where('event_id', $event->id)
        ->pluck('user_id'); // Obtiene solo los user_id relacionados con el evento

      // Obtener los datos de los usuarios
      $usuarios = User::whereIn('id', $participantes)->get(); // Trae los usuarios que coinciden

      // Convertir los participantes en una lista HTML o un array de nombres
      $participantesList = $usuarios->map(function ($user) {
        return '<li>' . $user->name . '</li>'; // Creamos una lista <li> para cada participante
      })->implode(''); // Convertimos el array en una cadena HTML de lista

      return [
        'idevento' => $event->id, // Asegúrate de que el ID esté incluido
        'title' => $event->title,
        'start' => $start->toDateTimeString(),
        'end' => $end->toDateTimeString(),
        'note' => $event->note,
        'typeevento' => $event->type,
        'user_id' => $participantesList, // Aquí se incluye la lista de participantes
        'clienteevento' => $cliente ? $cliente->nombre : 'Desconocido',
        'estado' => $event->estado ?? 'PENDIENTE',
        'color' => $this->getEventColor($event->type),
        'type' => 'EVENTO',
      ];
    };
  }













  // Método para obtener el color basado en el tipo de evento
  private function getEventColor($type)
  {
    switch ($type) {
      case 'REUNION':
        return 'blue';
      case 'LEVANTAMIENTO INFORMACION':
        return 'green';
      case 'LLAMADA':
        return 'orange';
      default:
        return 'gray';
    }
  }



  public function proceso($id)
  {

    // Obtener el cliente por ID
    $cliente = Cliente::findOrFail($id);

    // Obtener las reuniones del cliente
    $reuniones = Reunion::where('cliente_id', $id)->get();
    // Mostrar la vista para el estado del cliente
    return view('content.client.client-clientProceso', compact('cliente', 'reuniones'));
  }


  public function cotizaciones($id)
  {

    // Obtener el cliente por ID
    $cliente = Cliente::findOrFail($id);

    // Obtener la reunión por ID de cliente
    $reuniones = Reunion::where('cliente_id', $id)->get();

    // Encargado del usuario
    $usuarios = User::all();
    // Mostrar la vista para el estado del cliente
    return view('content.client.client-clientCotizaciones', compact('cliente', 'reuniones', 'usuarios'));
  }


  public function updateproceso(Request $request)
  {
    // Validar los datos del formulario
    $validatedData = $request->validate([
      'cliente_id' => 'required|integer|exists:cliente,id', // Ajusta según tu estructura
      'users_id' => 'required|integer|exists:users,id',
      'userid' => 'required|integer|exists:users,id',
      'direccion' => 'required|string|max:255',
      'fecha_atencion' => 'required|date',
    ]);

    // Convertir la fecha de atención a un formato de datetime
    $fechaAtencion = Carbon::parse($validatedData['fecha_atencion']);

    // Verificar si ya existe una información para el cliente en esa fecha y para el mismo técnico
    $existingInfo = Informacion::where('cliente_id', $validatedData['cliente_id'])
      ->where('users_id', $validatedData['users_id'])
      ->whereDate('fecha', $fechaAtencion->toDateString())
      ->exists();

    if ($existingInfo) {
      // Redirigir de vuelta con un mensaje de error si ya existe una información
      return redirect()->back()->with('error', 'Ya existe una información registrada para el cliente en esa fecha con el mismo técnico.');
    }

    // Crear una nueva entrada en la tabla 'informacion'
    Informacion::create([
      'cliente_id' => $validatedData['cliente_id'],
      'users_id' => $validatedData['users_id'],
      'dirrecion' => $validatedData['direccion'],
      'fecha' => $validatedData['fecha_atencion'],
    ]);

    // Actualizar el campo 'tecnico' en la tabla 'cliente'
    Cliente::where('id', $validatedData['cliente_id'])
      ->update(['tecnico' => 'ASIGNADO']);

    // Redirigir con un mensaje de éxito
    return redirect()->back()->with('success', 'Información guardada correctamente y estado de técnico actualizado.');
  }





  public function storeEvento(Request $request)
  {
    // Registrar los datos crudos recibidos antes de procesar
    Log::info('Datos recibidos:', $request->all());

    try {
      // Procesar el array de usuarios si viene como una cadena separada por comas
      $usuarios = $request->input('usuarios', []);

      // Si es un array con una sola cadena, dividirla en valores separados
      if (is_array($usuarios) && count($usuarios) === 1 && is_string($usuarios[0])) {
        $usuarios = explode(',', $usuarios[0]); // Dividir la cadena en un array
      }

      // Convertir los valores a enteros y eliminar los no válidos
      $usuarios = array_filter($usuarios, function ($value) {
        return is_numeric($value) && intval($value) > 0;
      });

      // Sobrescribir los usuarios procesados en la solicitud
      $request->merge(['usuarios' => $usuarios]);

      // Validar los datos del formulario
      $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'note' => 'nullable|string',
        'type' => 'required|in:REUNION,LEVANTAMIENTO INFORMACION,LLAMADA,COTIZACION',
        'start' => 'required|date_format:Y-m-d',
        'end' => 'required|date_format:Y-m-d',
        'start_time' => 'nullable|date_format:H:i',
        'end_time' => 'nullable|date_format:H:i',
        'all_day' => 'nullable|boolean',
        'cliente_id' => 'nullable|integer|exists:cliente,id',
        'new_client_name' => 'nullable|string|max:255',
        'usuarios.*' => 'integer|exists:users,id', // Validar cada elemento como un ID válido
      ]);

      // Manejar creación de un nuevo cliente si se proporciona
      if (empty($validatedData['cliente_id']) && !empty($validatedData['new_client_name'])) {
        $nuevoCliente = Cliente::create([
          'nombre' => $validatedData['new_client_name'],
        ]);
        $validatedData['cliente_id'] = $nuevoCliente->id;
      }

      if (empty($validatedData['cliente_id'])) {
        throw new \Exception('Debe seleccionar un cliente o proporcionar un nuevo cliente.');
      }

      // Convertir fecha y hora en formato datetime
      $startDatetime = $validatedData['start'];
      $endDatetime = $validatedData['end'];

      if (!empty($validatedData['start_time'])) {
        $startDatetime .= ' ' . $validatedData['start_time'];
      } else {
        $startDatetime .= ' 00:00:00';
      }

      if (!empty($validatedData['end_time'])) {
        $endDatetime .= ' ' . $validatedData['end_time'];
      } else {
        $endDatetime .= ' 23:59:59';
      }

      // Crear el evento
      $evento = Event::create([
        'title' => $validatedData['title'],
        'note' => $validatedData['note'],
        'type' => $validatedData['type'],
        'start' => $startDatetime,
        'end' => $endDatetime,
        'all_day' => $validatedData['all_day'] ?? 0,
        'cliente_id' => $validatedData['cliente_id'],
      ]);

      // Asignar usuarios al evento
      foreach ($validatedData['usuarios'] as $userId) {
        EventUser::create([
          'event_id' => $evento->id,
          'user_id' => $userId,
        ]);
      }

      return redirect()->back()->with('success', 'Evento y participantes guardados correctamente.');
    } catch (\Exception $e) {
      Log::error('Error al guardar el evento y los participantes:', ['error' => $e->getMessage()]);
      return redirect()->back()->with('error', 'Ocurrió un error al guardar el evento.');
    }
  }

  // public function createe()
  // {
  //     // Obtener todos los eventos
  //     $events = Event::all();

  //     // Pasar los eventos a la vista
  //     return view('tu_vista', compact('events'));
  // }

  public function informacion($id)
  {

    $cliente = Cliente::find($id);
    $informacion = Informacion::where('cliente_id', $id)->get();
    $usuarios = User::all(); // Assuming you need to populate the select options
    $reuniones = Reunion::all();
    $atencion = Atencion::where('cliente_id', $id)->get();



    // Pasar todos los datos necesarios a la vista
    return view('content.client.client-clientInformacion', [
      'cliente' => $cliente,
      'informacion' => $informacion,
      'reuniones' => $reuniones,
      'usuarios' => $usuarios,
      'atencion' => $atencion,
    ]);
  }







  public function create()
  {
    $userId = auth()->id();

    // Obtener eventos a los que el usuario está asignado
    $events = Salida::whereHas('users', function ($query) use ($userId) {
      $query->where('user_id', $userId);
    })->get();

    // $events = salida::all();

    // Mostrar el formulario para crear un nuevo cliente
    return view('content.client.client-newClient', compact('events'));
  }





  public function destroy($id)
  {
    // Obtener el cliente por ID
    $cliente = Cliente::findOrFail($id);



    // Verificar si el cliente tiene reuniones asociadas
    if (Reunion::where('cliente_id', $id)->exists()) {
      return redirect()->route('client-clientList')->with('error', 'El cliente no se puede eliminar porque tiene reuniones asociadas.');
    }

    // Verificar si el cliente tiene llamadas asociadas
    if (Llamada::where('cliente_id', $id)->exists()) {
      return redirect()->route('client-clientList')->with('error', 'El cliente no se puede eliminar porque tiene llamadas asociadas.');
    }

    // Verificar si el cliente tiene atenciones asociadas
    if (Atencion::where('cliente_id', $id)->exists()) {
      return redirect()->route('client-clientList')->with('error', 'El cliente no se puede eliminar porque tiene atenciones asociadas.');
    }

    // Verificar si el cliente tiene información asociada
    if (Informacion::where('cliente_id', $id)->exists()) {
      return redirect()->route('client-clientList')->with('error', 'El cliente no se puede eliminar porque tiene información asociada.');
    }

    // Verificar si el cliente tiene cotizaciones asociadas
    if (Cotizacion::where('cliente_id', $id)->exists()) {
      return redirect()->route('client-clientList')->with('error', 'El cliente no se puede eliminar porque tiene cotizaciones asociadas.');
    }

    // Verificar si el cliente tiene eventos asociados
    if (Event::where('cliente_id', $id)->exists()) {
      return redirect()->route('client-clientList')->with('error', 'El cliente no se puede eliminar porque tiene eventos asociados.');
    }

    // Eliminar las observaciones asociadas al cliente
    Observacion::where('id_cliente', $id)->delete();
    Reunion::where('cliente_id', $id)->delete();
    Llamada::where('cliente_id', $id)->delete();
    Atencion::where('cliente_id', $id)->delete();
    Informacion::where('cliente_id', $id)->delete();
    Cotizacion::where('cliente_id', $id)->delete();
    Event::where('cliente_id', $id)->delete();

    // Eliminar el cliente
    $cliente->delete();

    // Redirigir con mensaje de éxito
    return redirect()->route('client-clientList')->with('success', 'Cliente eliminado con éxito.');
  }





  public function store(Request $request)
  {
    // Validar los datos recibidos
    $request->validate([
      'nombre' => 'required|string|max:255',
      'empresa' => 'nullable|string|max:255',
      'telefono' => 'nullable|string|max:20',
      'email' => 'nullable|email',
      'servicios' => 'nullable|string|max:255',
      'mensaje' => 'nullable|string',
      'documento' => 'nullable|string|max:30', // Validación para el número de documento
      'events_id' => 'nullable|exists:salida,id',
    ]);

    try {
      // Obtener el ID del usuario autenticado
      $userId = auth()->id();

      // Crear un nuevo cliente
      $cliente = Cliente::create([
        'nombre' => $request->input('nombre'),
        'empresa' => $request->input('empresa'),
        'telefono' => $request->input('telefono'),
        'email' => $request->input('email'),
        'servicios' => $request->input('servicios'),
        'mensaje' => $request->input('mensaje', ' '),
        'documento' => $request->input('documento'), // Número de documento
        'status' => 'PENDIENTE',
        'correo' => 'PENDIENTE',
        'whatsapp' => 'PENDIENTE',
        'reunion' => 'PENDIENTE',
        'contrato' => 'PENDIENTE',
        'fecharegistro' => now()->toDateString(),
        'user_id' => $userId,
        'tecnico' => 'NO ASIGNADO',
        'events_id' => $request->input('events_id'),
        'llamada' => 'PENDIENTE',
        'levantamiento' => 'PENDIENTE',
        'cotizacion' => 'PENDIENTE',
      ]);

      return redirect()->route('client-newClient')->with('success', 'Cliente guardado con éxito.');
    } catch (\Exception $e) {
      Log::error('Error al guardar el cliente: ' . $e->getMessage());
      return redirect()->route('client-newClient')->with('error', 'Error al guardar el cliente.');
    }
  }











  public function edit($id)
  {
    // Obtener el cliente por ID
    $cliente = Cliente::findOrFail($id);



    // Mostrar el formulario para editar el cliente
    return view('content.client.client-clientUpdate', compact('cliente'));
  }

  public function status($id)
  {
    // Obtener el cliente por ID
    $cliente = Cliente::findOrFail($id);

    // Obtener las reuniones del cliente
    $reuniones = Reunion::where('cliente_id', $id)->get();
    // Mostrar la vista para el estado del cliente
    return view('content.client.client-clientStatus', compact('cliente', 'reuniones'));
  }

  public function notification($id)
  {
    // Obtener todas las reuniones y agruparlas por cliente_id

    // Obtener el cliente por ID
    $cliente = Cliente::findOrFail($id);

    // Obtener todas las llamadas para el cliente
    $llamadas = Llamada::where('cliente_id', $id)->get();

    // Obtener el cliente con el usuario asociado
    $cliente = Cliente::with('user')->findOrFail($id);

    // Obtener las reuniones del cliente
    $reuniones = Reunion::where('cliente_id', $id)->get();

    $usuarios = User::where('rol_id', 4)->get();


    // Obtener las observaciones del cliente
    $observaciones = Observacion::where('id_cliente', $id)->first();
    if ($observaciones) {
      $observaciones->fechacontrato = Carbon::parse($observaciones->fechacontrato);
    }

    // Asegúrate de que la fecha se maneje como una instancia de Carbon
    foreach ($llamadas as $llamada) {
      $llamada->date = Carbon::parse($llamada->date); // Convertir a Carbon
    }



    // Pasar todos los datos necesarios a la vista
    return view('content.client.client-clientNotification', [
      'cliente' => $cliente,
      'llamadas' => $llamadas,
      'observaciones' => $observaciones,
      'reuniones' => $reuniones,
      'usuarios' => $usuarios
    ]);
  }




  public function connections($id)
  {
    // Obtener el cliente por ID
    $cliente = Cliente::findOrFail($id);

    // Obtener las reuniones por ID de cliente con la relación del usuario cargada
    $reuniones = Reunion::where('cliente_id', $id)->with('user')->get();

    // Encargado del usuario
    $usuarios = User::all();

    // Mostrar la vista para el estado del cliente con las reuniones
    return view('content.client.client-clientConnections', compact('cliente', 'reuniones', 'usuarios'));
  }





  public function updateStatus(Request $request, $id)
  {
    // Validar los datos del formulario
    $request->validate([
      'whatsapp' => 'required|in:SI,NO,PENDIENTE',
      'correo' => 'required|in:SI,NO,PENDIENTE',
      'reunion' => 'required|in:SI,NO,PENDIENTE',
      'observaciones_reunion' => 'nullable|string',
      'fechareunion' => 'nullable|date',
      'llamada' => 'required|in:SI,NO,PENDIENTE',
      'observaciones_llamada' => 'nullable|string',
      'fechallamada' => 'nullable|date',
    ]);

    // Buscar el cliente por ID
    $cliente = Cliente::findOrFail($id);

    // Obtener los valores del formulario
    $whatsapp = $request->input('whatsapp');
    $correo = $request->input('correo');
    $reunion = $request->input('reunion');
    $observaciones_reunion = $request->input('observaciones_reunion') ?: ''; // Asegurar que no sea NULL
    $fechareunion = $request->input('fechareunion');
    $llamada = $request->input('llamada');
    $observaciones_llamada = $request->input('observaciones_llamada');
    $fechallamada = $request->input('fechallamada');

    // Actualizar los campos del cliente
    $cliente->update([
      'whatsapp' => $whatsapp,
      'correo' => $correo,
      'reunion' => $reunion,
      'llamada' => $llamada,
    ]);

    // Determinar el estado
    $siCount = count(array_filter([$whatsapp, $correo, $reunion, $llamada], fn($value) => $value === 'SI'));
    $noCount = count(array_filter([$whatsapp, $correo, $reunion, $llamada], fn($value) => $value === 'NO'));

    if ($siCount == 4) {
      $status = 'Atendido';
    } elseif ($noCount >= 3) {
      $status = 'Pendiente';
    } else {
      $status = 'En Proceso'; // Valor por defecto si hay mezcla de sí y no
    }

    // Actualizar el estado del cliente
    $cliente->status = $status;
    $cliente->save();

    // Encontrar o crear una observación para el cliente
    Observacion::updateOrCreate(
      ['id_cliente' => $id],
      [
        'observacionreunion' => $observaciones_reunion,
        'fechareunion' => $fechareunion ?: now()->toDateString(),
        'observacionllamada' => $observaciones_llamada,
        'fechallamada' => $fechallamada ?: now()->toDateString(),
      ]
    );

    // Redirigir de vuelta con un mensaje de éxito
    return redirect()->back()->with('success', 'Datos actualizados con éxito.');
  }




  public function storeCall(Request $request)
  {
    // Validar los datos del formulario
    $request->validate([
      'cliente_id' => 'required|integer|exists:cliente,id',
      'date' => 'required|date',
      'observaciones' => 'nullable|string',
    ]);

    // Obtener el ID del usuario autenticado
    $userId = auth()->id();

    // Depura los datos para verificar
    Log::info('Datos de la llamada:', $request->all());

    // Crear una nueva llamada
    Llamada::create([
      'cliente_id' => $request->input('cliente_id'),
      'date' => $request->input('date'),
      'observaciones' => $request->input('observaciones') ?: '', // Asegúrate de que no sea NULL
      'user_id' => $userId,
      'estado' => "PENDIENTE",
    ]);

    // Redirigir de vuelta con un mensaje de éxito
    return redirect()->back()->with('success', 'Llamada registrada con éxito.');
  }



  public function storeReunion(Request $request)
  {
    // Validación de los datos del formulario
    $request->validate([
      'fecha_hora' => 'required|date', // Cambia esto a 'datetime' si usas datetime
      'observacion' => 'nullable|string',
      'cliente_id' => 'required|integer|exists:cliente,id',
      'tema' => 'required|string',
      'userid' => 'required|integer|exists:users,id',
    ]);

    // Convertir la fecha y hora a un formato de datetime
    $fechaHora = Carbon::parse($request->input('fecha_hora'));

    // Verificar si el usuario ya tiene una reunión en esa fecha y hora
    $existingMeeting = Reunion::where('userid', $request->input('userid'))
      ->whereDate('fecha_hora', $fechaHora->toDateString())
      ->whereTime('fecha_hora', $fechaHora->toTimeString())
      ->exists();

    if ($existingMeeting) {
      // Redirigir de vuelta con un mensaje de error si ya existe una reunión
      return redirect()->back()->with('error', 'El usuario ya tiene una reunión programada en esa fecha y hora.');
    }

    // Crear una nueva entrada en la tabla reunion
    Reunion::create([
      'fecha_hora' => $request->input('fecha_hora'),
      'observacion' => $request->input('observacion'),
      'cliente_id' => $request->input('cliente_id'),
      'tema' => $request->input('tema'),
      'userid' => $request->input('userid'),
    ]);

    // Redirigir con un mensaje de éxito (opcional)
    return redirect()->back()->with('success', 'Reunión guardada exitosamente.');
  }



  public function update(Request $request, $id)
  {
    // Validar los datos recibidos
    $request->validate([
      'nombre' => 'required|string|max:255',
      'empresa' => 'nullable|string|max:255',
      'telefono' => 'nullable|string|max:20',
      'email' => 'nullable|email',
      'documento' => 'nullable|string|max:30', // Validación para el número de documento
      'servicios' => 'nullable|string|max:255',
      'mensaje' => 'nullable|string',
    ]);

    // Obtener el cliente por ID
    $cliente = Cliente::findOrFail($id);

    // Actualizar el cliente
    $cliente->update([
      'nombre' => $request->input('nombre'),
      'empresa' => $request->input('empresa'),
      'telefono' => $request->input('telefono'),
      'documento' => $request->input('documento'),
      'email' => $request->input('email'),
      'servicios' => $request->input('servicios'),
      'mensaje' => $request->input('mensaje'),
    ]);

    // Redirigir con mensaje de éxito
    return redirect()->back()->with('success', 'Cliente Actualizado Correctamente.');
  }

  public function updateReunion(Request $request, $id)
  {
    // Validación de los datos del formulario
    $request->validate([
      'zoom' => 'nullable|string',
      'cliente_id' => 'required|integer|exists:cliente,id',
      'fecha_hora' => 'required|date',
      'tema' => 'required|string',
      'userid' => 'required|integer|exists:users,id',


    ]);

    // Encontrar la reunión por ID
    $reunion = Reunion::findOrFail($id);

    // Actualizar los datos de la reunión
    $reunion->update([
      'zoom' => $request->input('zoom'),
      'cliente_id' => $request->input('cliente_id'),
      'fecha_hora' => Carbon::parse($request->input('fecha_hora')),
      'tema' => $request->input('tema'),
      'userid' => $request->input('userid')

    ]);

    // Redirigir con un mensaje de éxito
    return redirect()->back()->with('success', 'Reunión actualizada exitosamente.');
  }

  ///LLAMADA

  public function updateLlamada(Request $request, $id)
  {
    // Validación de los datos del formulario
    $request->validate([
      'observaciones' => 'nullable|string',
      'date' => 'required|date_format:Y-m-d H:i:s',
      'cliente_id' => 'required|integer|exists:clientes,id',
      'user_id' => 'required|integer|exists:users,id',
      'estado' => 'nullable|string'
    ]);

    // Encontrar la llamada por ID
    $llamada = Llamada::findOrFail($id);

    // Actualizar los datos de la llamada
    $llamada->update([
      'observaciones' => $request->input('observaciones'),
      'date' => Carbon::parse($request->input('date')),
      'cliente_id' => $request->input('cliente_id'),
      'user_id' => $request->input('user_id'),
      'estado' => $request->input('estado')
    ]);

    // Redirigir con un mensaje de éxito
    return redirect()->back()->with('success', 'Llamada actualizada exitosamente.');
  }




  public function uploadPhoto(Request $request, $id)
  {
    // Validar la solicitud
    $request->validate([
      'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:800',
    ]);

    // Encontrar el cliente por ID
    $cliente = Cliente::findOrFail($id);

    if ($request->hasFile('photo')) {
      // Obtener el archivo
      $file = $request->file('photo');

      // Generar un nombre único para el archivo
      $filename = time() . '.' . $file->getClientOriginalExtension();

      // Guardar el archivo en el directorio 'public/avatars'
      $file->storeAs('public/avatars', $filename);

      // Actualizar el nombre del archivo en la base de datos
      $cliente->photo = $filename;
      $cliente->save();
    }

    return redirect()->back()->with('success', 'Foto actualizada con éxito.');
  }


  // app/Http/Controllers/ClientController.php
  public function updateContractStatus(Request $request, $id)
  {
    // Validar los datos del formulario
    $request->validate([
      'sendNotification' => 'required|in:SI,NO'
    ]);

    // Buscar el cliente por ID
    $cliente = Cliente::findOrFail($id);

    // Actualizar el estado del contrato
    $cliente->proceso = $request->input('sendNotification');
    $cliente->save();

    // Redirigir con un mensaje de éxito
    return redirect()->back()->with('success', 'Estado del proceso actualizado exitosamente.');
  }



  public function Atencion(Request $request)
  {
    // Validar los datos del formulario
    $validatedData = $request->validate([
      'fecha' => 'required|date',
      'conclusion' => 'required|string',
      'cliente_id' => 'required|integer|exists:cliente,id', // Ajusta para que verifique el campo 'id'
    ]);

    // Crear una nueva entrada en la tabla 'atencion'
    Atencion::create([
      'fecha' => $validatedData['fecha'],
      'conclusion' => $validatedData['conclusion'],
      'cliente_id' => $validatedData['cliente_id'],
      'user_id' => auth()->user()->id, // Asume que el usuario autenticado es el que realiza la actualización
    ]);

    // Actualizar el campo 'levantamiento' en la tabla 'cliente' a "REALIZADO"
    Cliente::where('id', $validatedData['cliente_id'])
      ->update(['levantamiento' => 'REALIZADO']);

    // Redirigir con un mensaje de éxito
    return redirect()->back()->with('success', 'Observaciones guardadas correctamente y campo levantamiento actualizado a REALIZADO.');
  }



  public function updatei(Request $request)
  {
    // Validar los datos del formulario
    $validatedData = $request->validate([
      'dirrecion' => 'required|string|max:255',
      'fecha' => 'required|date_format:Y-m-d\TH:i',
      'users_id' => 'required|integer|exists:users,id',
      'cliente' => 'required|string|max:255',
      'id_informacion' => 'required|integer|exists:informacion,id_informacion',
      'observacion' => 'required|string|max:255',
      'cliente_id' => 'required|integer|exists:cliente,id',
    ]);

    // Actualizar la entrada en la base de datos
    $informacion = Informacion::find($validatedData['id_informacion']);
    if (!$informacion) {
      return redirect()->back()->withErrors(['error' => 'Información no encontrada.']);
    }

    $informacion->update([
      'dirrecion' => $validatedData['dirrecion'],
      'fecha' => $validatedData['fecha'],
      'users_id' => $validatedData['users_id'],
      'observacion' => $validatedData['observacion'],
      'cliente_id' => $validatedData['cliente_id'],
    ]);

    Cliente::where('id', $validatedData['cliente_id'])
      ->update(['levantamiento' => 'REPROGRAMADO']);

    // Redirigir con un mensaje de éxito
    return redirect()->back()->with('success', 'Información actualizada correctamente.');
  }


  public function enviarCotizacion(Request $request)
  {
    // Validar los datos recibidos
    $request->validate([
      'recipient_email' => 'required|email',
      'subject' => 'required|string|max:255',
      'message' => 'required|string',
      'attachment' => 'nullable|file|mimes:pdf|max:2048', // Validar archivo adjunto
      'cliente_id' => 'required|integer|exists:cliente,id', // Validar que el cliente exista
      'money' => 'nullable|numeric|min:0',
    ]);

    // Verificar si la cotización ya existe
    $exists = Cotizacion::where('recipient_email', $request->input('recipient_email'))
      ->where('subject', $request->input('subject'))
      ->exists();

    if ($exists) {
      return back()->withErrors([
        'recipient_email' => 'La cotización con este correo y número de cotización ya está registrada.',
      ])->withInput();
    }

    // Obtener el ID del usuario autenticado
    $userId = auth()->id();

    // Crear una nueva cotización
    $cotizacion = Cotizacion::create([
      'recipient_email' => $request->input('recipient_email'),
      'subject' => $request->input('subject'),
      'message' => $request->input('message'),
      'user_id' => $userId,
      'cliente_id' => $request->input('cliente_id'),
      'money' => $request->input('money'),
    ]);

    // Manejar la carga del archivo adjunto si existe
    if ($request->hasFile('attachment')) {
      $file = $request->file('attachment');
      $filename = $file->getClientOriginalName(); // Obtener el nombre original del archivo
      $path = $file->storeAs('attachments', $filename, 'public'); // Almacenar el archivo con el nombre original en 'public/attachments'
      $cotizacion->attachment = $filename; // Guardar solo el nombre del archivo en la base de datos
      $cotizacion->save(); // Guardar la cotización con el nombre del archivo en la base de datos
    }

    // Actualizar el estado del cliente
    $cliente = Cliente::find($request->input('cliente_id'));
    if ($cliente) {
      $cliente->cotizacion = 'Realizado'; // Actualizar el campo 'cotizacion' a 'Realizado'
      $cliente->save(); // Guardar los cambios en la base de datos
    }

    // Redirigir con mensaje de éxito
    return redirect()->back()->with('success', 'COTIZACION GUARDADA Y ESTADO DEL CLIENTE ACTUALIZADO.');
  }

  public function updateContrato(Request $request)
  {
    $request->validate([
      'cliente_id' => 'required|integer|exists:cliente,id',
      'contrato' => 'required|string|in:PENDIENTE,REALIZADO',
    ]);

    $cliente = Cliente::find($request->input('cliente_id'));
    if ($cliente) {
      $cliente->contrato = $request->input('contrato');
      $cliente->save();
      return redirect()->back()->with('success', 'Estado del contrato actualizado con éxito.');
    }

    return redirect()->back()->with('error', 'No se pudo encontrar el cliente.');
  }



  public function salida(Request $request)
  {
    // Validar los datos del formulario
    $request->validate([
      'title' => 'required|string|max:255',
      'note' => 'nullable|string',
      'start' => 'required|date_format:Y-m-d',
      'start_time' => 'required|date_format:H:i',
      'end' => 'required|date_format:Y-m-d',
      'end_time' => 'required|date_format:H:i',
      'all_day' => 'nullable|boolean',
      'meta_registros' => 'required|integer|min:0',
      'usuarios' => 'nullable|string',
    ]);

    // Crear el nuevo evento
    $salida = Salida::create([
      'title' => $request->input('title'),
      'note' => $request->input('note'),
      'start' => $request->input('start') . ' ' . $request->input('start_time'),
      'end' => $request->input('end') . ' ' . $request->input('end_time'),
      'all_day' => $request->input('all_day') ? 1 : 0,
      'meta_registros' => $request->input('meta_registros'),

    ]);

    // Asociar los usuarios seleccionados con el evento y asignar la meta individual
    if ($request->input('usuarios')) {
      $usuarios = explode(',', $request->input('usuarios')); // Convertir la cadena en un array

      if (count($usuarios) > 0) {
        // Calcular la meta por usuario
        $metaPorUsuario = intval($request->input('meta_registros') / count($usuarios));

        // Preparar el array para el método attach
        $usuariosConMeta = [];
        foreach ($usuarios as $userId) {
          $usuariosConMeta[$userId] = ['meta_usuario' => $metaPorUsuario];
        }

        // Asociar los usuarios con el evento y sus metas
        $salida->users()->attach($usuariosConMeta);
      }
    }

    // Redirigir con un mensaje de éxito
    return redirect()->back()->with('success', 'Evento creado y metas asignadas exitosamente.');
  }




  // UPDATE EVENTO
  public function updateevento(Request $request)
  {
    $validated = $request->validate([
      'id' => 'required|exists:events,id',
      'title' => 'required|string|max:255',
      'start' => 'required|date_format:Y-m-d\TH:i',
      'note' => 'nullable|string',
      'type' => 'required|in:REUNION,LEVANTAMIENTO INFORMACION,LLAMADA',
      'user_id' => 'nullable|integer|exists:users,id',
      'cliente_id' => 'nullable|integer|exists:clientes,id',
    ]);

    $event = Event::find($validated['id']);

    if (!$event) {
      return redirect()->back()->with('error', 'Evento no encontrado.');
    }

    $event->update([
      'title' => $validated['title'],
      'start' => \Carbon\Carbon::parse($validated['start']),
      'end' => \Carbon\Carbon::parse($validated['start']), // Ajusta esto según tus necesidades
      'note' => $validated['note'],
      'type' => $validated['type'],
      'user_id' => $validated['user_id'],
      'cliente_id' => $validated['cliente_id'],
    ]);

    return redirect()->back()->with('success', 'Evento actualizado correctamente.');
  }


  public function destroyevento($id)
  {
    $event = Event::findOrFail($id);
    $event->delete();

    return response()->json(['success' => true]);
  }
  public function getAll()
  {
    $cliente = Cliente::all();

    return response()->json(
      $cliente
    );
  }
}
