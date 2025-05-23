<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Event;
use App\Models\Llamada;
use App\Models\Reunion;
use App\Models\Salida;
use App\Models\SalidaUser;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

use Dompdf\Adapter\PDFLib;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Log;

class Analytics extends Controller
{
  public function index()
  {
    // Obtener todos los clientes
    $clientes = Cliente::all();

    // Obtener el total de usuarios
    $totalUsuario = User::count();

    // Obtener todos los usuarios
    $user = User::all();

    // Obtener los usuarios con más clientes
    $usuarios = User::withCount('clientes')
      ->orderBy('clientes_count', 'desc')
      ->limit(4)
      ->get();

    // Obtener todos los eventos desde la tabla 'salida'
    $eventos = Salida::all();
    
   

    // Obtener los usuarios con menos clientes
    $usuariosConMenosClientes = User::withCount('clientes')
      ->orderBy('clientes_count', 'asc')
      ->limit(4)
      ->get();

    // Consulta para obtener el número de clientes por mes
    $monthlyRegistrations = DB::table('cliente')
      ->select(DB::raw('MONTH(fecharegistro) as month, COUNT(*) as count'))
      ->groupBy(DB::raw('MONTH(fecharegistro)'))
      ->orderBy(DB::raw('MONTH(fecharegistro)'))
      ->get();

    // Inicializar variables para el mes con el registro más alto
    $maxCount = 0;
    $monthWithMaxCount = '';

    // Formatear los datos para el frontend
    $data = [
      'months' => [],
      'counts' => []
    ];

    foreach ($monthlyRegistrations as $registration) {
      if ($registration->count > $maxCount) {
        $maxCount = $registration->count;
        $monthWithMaxCount = date('F', mktime(0, 0, 0, $registration->month, 22)); // Obtiene el nombre completo del mes
      }
      $data['months'][] = date('M', mktime(0, 0, 0, $registration->month, 22));
      $data['counts'][] = $registration->count;
    }

    $usuario = Auth::user();
    $rolId = $usuario->rol_id;

    // Mostrar la vista con los datos del dashboard
    return view('content.dashboard.dashboards-analytics', [
      'user' => $user,
      'totalUsuario' => $totalUsuario,
      'usuarios' => $usuarios,
      'monthlyRegistrations' => $data,
      'monthWithMaxCount' => $monthWithMaxCount,
      'maxCount' => $maxCount,
      'eventos' => $eventos,
      'usuariosConMenosClientes' => $usuariosConMenosClientes,
      'rolId' => $rolId, // Pasar el rol_id a la vista
    ]);
  }


  public function getTotalClientesPorEvento($eventoId)
  {
    // Asegúrate de que el evento ID es válido
    $totalClientes = Cliente::where('evento_id', $eventoId)->count();

    return response()->json(['totalClientes' => $totalClientes]);
  }





public function obtenerMetaPorEvento(Request $request)
  {
       try {
    $eventoId = $request->input('evento_id');

    // Obtener el total de registros esperados para cada evento
    $evento = Salida::find($eventoId);

    if (!$evento) {
      return response()->json([
        'success' => false,
        'message' => 'Evento no encontrado'
      ]);
    }
    
    Log::info('Evento encontrado: ', $evento->toArray());

    $evento = Salida::withCount('clientes')
      ->find($eventoId);

    // Verificar si el evento existe
    if ($evento) {
      // Obtener la cantidad total de clientes según el tipo_cliente
      $totalClientesIntermedio = Cliente::where('events_id', $eventoId)
        ->where('tipo_cliente', 'Intermedio')
        ->count();

      $totalClientesPotencial = Cliente::where('events_id', $eventoId)
        ->where('tipo_cliente', 'Potencial')
        ->count();

      $totalClientesIndeciso = Cliente::where('events_id', $eventoId)
        ->where('tipo_cliente', 'Indeciso')
        ->count();

      // Obtener la cantidad total de clientes según el servicios
      $totalClientesCCTV = Cliente::where('events_id', $eventoId)
        ->where('servicios', 'CCTV')
        ->count();

      $totalClientesModulo = Cliente::where('events_id', $eventoId)
        ->where('servicios', 'MÓDULO')
        ->count();

      $totalClientesSoftware = Cliente::where('events_id', $eventoId)
        ->where('servicios', 'SOFTWARE')
        ->count();

      $totalClientesServiceDesk = Cliente::where('events_id', $eventoId)
        ->where('servicios', 'SERVICE DESK')
        ->count();

      // Obtener la cantidad de clientes por estado
      $totalClientesPendiente = Cliente::where('events_id', $eventoId)
        ->where('status', 'PENDIENTE')
        ->count();

      $totalClientesAtendido = Cliente::where('events_id', $eventoId)
        ->where('status', 'Atendido')
        ->count();

      $totalClientesEnProceso = Cliente::where('events_id', $eventoId)
        ->where('status', 'En Proceso')
        ->count();

      $totalClientesContrato = Cliente::where('events_id', $eventoId)
        ->where('status', 'CONTRATO')
        ->count();


     $usuariosConMasRegistros = Cliente::where('events_id', $eventoId)
        ->select('user_id', DB::raw('COUNT(*) as registros'))
        ->groupBy('user_id')
        ->orderBy('registros', 'desc')
        ->limit(4)
        ->get()
        ->map(function ($user) {
          // Convertir la variable a objeto de Eloquent si es array
          $usuario = User::find($user->user_id);

          if (!$usuario) {
            return collect([
              'user_id' => $user->user_id,
              'registros' => $user->registros,
              'name' => 'Desconocido',
              'meta' => 'Meta no disponible',
            ]);
          }

          // Obtener la meta del usuario desde la tabla salida_user
          $metaUsuario = DB::table('salida_user')
            ->where('user_id', $user->user_id)
            ->value('meta_usuario');

          return collect([
            'user_id' => $user->user_id,
            'registros' => $user->registros,
            'name' => $usuario->name,
            'meta' => $metaUsuario ?: 'Meta no disponible',
          ]);
        });


      // Obtener la cantidad total de llamadas realizadas por cada usuario en el evento
      $llamadasPorUsuario = Llamada::whereIn('cliente_id', Cliente::where('events_id', $eventoId)->pluck('id'))
        ->select('user_id', DB::raw('COUNT(*) as llamadas'))
        ->groupBy('user_id')
        ->get()
        ->map(function ($llamada) {
          $usuario = User::find($llamada->user_id);

          if (!$usuario) {
            return collect([
              'user_id' => $llamada->user_id,
              'llamadas' => $llamada->llamadas,
              'name' => 'Desconocido',
            ]);
          }

          return collect([
            'user_id' => $llamada->user_id,
            'llamadas' => $llamada->llamadas,
            'name' => $usuario->name,
          ]);
        });


      // Obtener los conteos de 'SI' para correo y llamada por usuario
      $conteosSi = Cliente::where('events_id', $eventoId)
        ->select('user_id', DB::raw('
                SUM(CASE WHEN correo = "SI" THEN 1 ELSE 0 END) as correos_si,
                SUM(CASE WHEN llamada = "SI" THEN 1 ELSE 0 END) as llamadas_si,
                SUM(CASE WHEN whatsapp = "SI" THEN 1 ELSE 0 END) as whasat_si
            '))
        ->groupBy('user_id')
        ->get()
        ->map(function ($user) {
          $usuario = User::find($user->user_id);

          if (!$usuario) {
            return [
              'user_id' => $user->user_id,
              'correos_si' => $user->correos_si,
              'llamadas_si' => $user->llamadas_si,
              'whasat_si' => $user->whasat_si,
              'name' => 'Desconocido',
            ];
          }

          return [
            'user_id' => $user->user_id,
            'correos_si' => $user->correos_si,
            'llamadas_si' => $user->llamadas_si,
            'whasat_si' => $user->whasat_si,
            'name' => $usuario->name,
          ];
        });






      // Obtener todas las llamadas pendientes relacionadas con el evento
      $llamadasPendientes = Llamada::whereIn('cliente_id', Cliente::where('events_id', $eventoId)->pluck('id'))
        ->where('estado', 'PENDIENTE')
        ->get();

      // Obtener todas las reuniones pendientes relacionadas con el evento
      $reunionesPendientes = Reunion::whereIn('cliente_id', Cliente::where('events_id', $eventoId)->pluck('id'))
        ->where('estado', 'PENDIENTE')
        ->get();

      // Obtener todos los eventos pendientes relacionados con el cliente
      $eventosPendientes = Event::where('estado', 'PENDIENTE')
        ->whereIn('cliente_id', Cliente::where('events_id', $eventoId)->pluck('id'))
        ->get();

      // Obtener los IDs de los clientes involucrados
      $clienteIds = $llamadasPendientes->pluck('cliente_id')
        ->merge($reunionesPendientes->pluck('cliente_id'))
        ->merge($eventosPendientes->pluck('cliente_id'))
        ->unique();

      // Obtener los nombres de los clientes
      $clientes = Cliente::whereIn('id', $clienteIds)->pluck('nombre', 'id');

      // Obtener la meta de registros para cada usuario
      $usuariosMeta = SalidaUser::where('salida_id', $eventoId)
        ->get()
        ->mapWithKeys(function ($salidaUser) {
          return [
            $salidaUser->user_id => $salidaUser->meta_usuario
          ];
        });

      // Obtener la cantidad de registros actuales por usuario
      $registrosPorUsuario = Cliente::where('events_id', $eventoId)
        ->select('user_id', DB::raw('COUNT(*) as registros'))
        ->groupBy('user_id')
        ->get()
        ->mapWithKeys(function ($cliente) {
          return [
            $cliente->user_id => $cliente->registros
          ];
        });

      // Obtener nombres de usuarios
     // Obtener nombres de usuarios
        $usuarios = User::whereIn('id', $usuariosMeta->keys())->get()->keyBy('id');


        // Calcular los registros faltantes para cada usuario
        $usuariosFaltantes = $usuariosMeta->map(function ($meta, $userId) use ($registrosPorUsuario, $usuarios) {
          $registros = $registrosPorUsuario->get($userId, 0);
          return [
            'user_id' => $userId,
            'usuario_nombre' => $usuarios->get($userId, 'Desconocido'),
            'meta' => $meta,
            'registros_actuales' => $registros,
            'registros_faltantes' => $meta - $registros
          ];
        });




    //   // Combinar todos los datos en una sola colección
    //   $tareasPendientes = $llamadasPendientes->map(function ($llamada) use ($clientes, $usuarios) {
    //     return [
    //       'id' => $llamada->id_llamada,
    //       'tipo' => 'Llamada',
    //       'titulo' => 'Llamada',
    //       'observaciones' => $llamada->observaciones,
    //       'fecha_hora' => $llamada->date,
    //       'cliente_id' => $llamada->cliente_id,
    //       'cliente_nombre' => $clientes->get($llamada->cliente_id, 'Desconocido'),
    //       'estado' => $llamada->estado,
    //       'usuario_nombre' => $usuarios->get($llamada->user_id, 'Desconocido'), // Cambiado de user_id a usuario_nombre
    //     ];
    //   })->merge(
    //     $reunionesPendientes->map(function ($reunion) use ($clientes, $usuarios) {
    //       return [
    //         'id' => $reunion->id_reunion,
    //         'tipo' => 'Reunión',
    //         'titulo' => $reunion->tema,
    //         'observaciones' => $reunion->observacion,
    //         'fecha_hora' => $reunion->fecha_hora,
    //         'cliente_id' => $reunion->cliente_id,
    //         'cliente_nombre' => $clientes->get($reunion->cliente_id, 'Desconocido'),
    //         'estado' => $reunion->estado,
    //         'usuario_nombre' => $usuarios->get($reunion->userid, 'Desconocido'), // Cambiado de user_id a usuario_nombre
    //       ];
    //     })
    //   )->merge(
    //     $eventosPendientes->map(function ($evento) use ($clientes, $usuarios) {
    //       return [
    //         'id' => $evento->id,
    //         'tipo' => $evento->type,
    //         'titulo' => $evento->title,
    //         'observaciones' => $evento->note,
    //         'fecha_hora' => $evento->start,
    //         'cliente_id' => $evento->cliente_id,
    //         'cliente_nombre' => $clientes->get($evento->cliente_id, 'Desconocido'),
    //         'estado' => $evento->estado,
    //         'usuario_nombre' => $usuarios->get($evento->user_id, 'Desconocido'), // Cambiado de user_id a usuario_nombre
    //       ];
    //     })
    //   );

      // Usando Eloquent
      $cantidadUsuarios = SalidaUser::where('salida_id', $eventoId)->count();


      // Obtener la cantidad de clientes por usuario para el evento
      $clientesPorUsuario = Cliente::where('events_id', $eventoId)
        ->select('user_id', DB::raw('COUNT(*) as cantidad'))
        ->groupBy('user_id')
        ->get()
        ->mapWithKeys(function ($item) {
          return [$item->user_id => $item->cantidad];
        });

      // Obtener la meta de registros para cada usuario
      $usuariosMeta = SalidaUser::where('salida_id', $eventoId)
        ->get()
        ->mapWithKeys(function ($salidaUser) {
          return [
            $salidaUser->user_id => $salidaUser->meta_usuario
          ];
        });

    // Obtener nombres de usuarios
$usuarios = User::whereIn('id', $usuariosMeta->keys())->get()->keyBy('id');



      // Calcular los registros faltantes para cada usuario
      $usuariosFaltantes = $usuariosMeta->map(function ($meta, $userId) use ($clientesPorUsuario, $usuarios) {
        $registros = $clientesPorUsuario->get($userId, 0);
        return [
          'user_id' => $userId,
          'usuario_nombre' => $usuarios->get($userId, 'Desconocido'),
          'meta' => $meta,
          'registros_actuales' => $registros,
          'registros_faltantes' => $meta - $registros
        ];
      });

      // Obtener la cantidad de clientes registrados por usuario para el evento

      $clientesPorUsuariototal = Cliente::where('events_id', $eventoId)
        ->select('user_id', DB::raw('COUNT(*) as cantidad'))
        ->groupBy('user_id')
        ->get()
        ->map(function ($item) {
          $usuario = User::find($item->user_id);

          return [
            'user_id' => $item->user_id,
            'usuario_nombre' => $usuario ? $usuario->name : 'Desconocido',
            'cantidad_clientes' => $item->cantidad,
          ];
        });

      // Verifica el contenido de $conteosSi para depuración
      error_log(print_r($conteosSi, true));





      $reunionesCount = DB::table('reunion')
        ->whereIn('cliente_id', function ($query) use ($eventoId) {
          $query->select('id')
            ->from('cliente')
            ->where('events_id', $eventoId);
        })
        ->count();

      $cotizacionesCount = DB::table('cotizaciones')
        ->whereIn('cliente_id', function ($query) use ($eventoId) {
          $query->select('id')
            ->from('cliente')
            ->where('events_id', $eventoId);
        })
        ->count();

      $contratosRealizadosCount = DB::table('cliente')
        ->where('contrato', 'REALIZADO')
        ->count();


      // Obtener el dinero total por evento
      $dineroPorEvento = DB::table('cotizaciones')
        ->join('cliente', 'cotizaciones.cliente_id', '=', 'cliente.id')
        ->select('cliente.events_id as evento_id', DB::raw('SUM(cotizaciones.money) as total_money'))
        ->groupBy('cliente.events_id')
        ->get();




      // Contar la cantidad de clientes registrados por usuario para un evento específico
      $clientesPorUsuario = DB::table('cliente')
        ->join('users', 'cliente.user_id', '=', 'users.id') // Unir con la tabla users para obtener el nombre del usuario
        ->select('users.name as user_name', DB::raw('COUNT(*) as total_clientes'))
        ->where('cliente.events_id', $eventoId) // Filtrar por el evento seleccionado
        ->groupBy('users.name')
        ->orderBy('total_clientes', 'asc') // Ordenar de menor a mayor por la cantidad de clientes
        ->get();

      // Obtener la meta por usuario
      $metaPorUsuario = DB::table('salida_user')
        ->join('users', 'salida_user.user_id', '=', 'users.id') // Unir con la tabla users para obtener el nombre del usuario
        ->select('users.name as user_name', DB::raw('SUM(salida_user.meta_usuario) as meta_total'))
        ->where('salida_user.salida_id', $eventoId) // Filtrar por el evento seleccionado
        ->groupBy('users.name')
        ->get();

      // Combinar las consultas para incluir meta por usuario
      $clientesYMetas = $clientesPorUsuario->map(function ($cliente) use ($metaPorUsuario) {
        $metaUsuario = $metaPorUsuario->firstWhere('user_name', $cliente->user_name);
        return [
          'user_name' => $cliente->user_name,
          'total_clientes' => $cliente->total_clientes,
          'meta_total' => $metaUsuario ? $metaUsuario->meta_total : 'Usuario no tiene meta asignada', // Si no hay meta, asignar mensaje
        ];
      });





      // Obtener la meta de registros para cada usuario
      $usuariosMeta = SalidaUser::where('salida_id', $eventoId)
        ->get()
        ->mapWithKeys(function ($salidaUser) {
          return [
            $salidaUser->user_id => $salidaUser->meta_usuario
          ];
        });

      // Obtener nombres de usuarios
      $usuarios = User::whereIn('id', $usuariosMeta->keys())->pluck('name', 'id');

      // Obtener las fechas de inicio y fin
      $start = $evento->start;
      $end = $evento->end;

      // Verificar si las fechas son válidas
      if (!$start || !$end) {
        return response()->json([
          'success' => false,
          'message' => 'Fechas de evento no disponibles'
        ]);
      }

      // Crear objetos DateTime para las fechas
      $startDate = new \DateTime($start);
      $endDate = new \DateTime($end);

      // Asegurar que la fecha final sea después de la fecha inicial
      if ($endDate < $startDate) {
        return response()->json([
          'success' => false,
          'message' => 'La fecha de fin no puede ser anterior a la fecha de inicio'
        ]);
      }

      // Calcular la diferencia en días
      $interval = $startDate->diff($endDate);
      $dias = $interval->days + 1; // +1 para incluir el día de inicio en el conteo

      // Crear una lista de todas las fechas entre el inicio y el fin
      $fechas = [];
      $metaPorFecha = [];
      $currentDate = $startDate;

      while ($currentDate <= $endDate) {
        $fechaFormato = $currentDate->format('Y-m-d');
        $fechas[] = $fechaFormato;
        $metaPorFecha[$fechaFormato] = $evento->meta_registros / $dias; // Calcula la meta diaria
        $currentDate->modify('+1 day');
      }

      // Obtener la cantidad de clientes registrados por usuario y fecha
      $clientesPorUsuarioYFecha = Cliente::where('events_id', $eventoId)
        ->select('user_id', DB::raw('DATE(fecharegistro) as fecha_registro'), DB::raw('COUNT(*) as cantidad'))
        ->groupBy('user_id', 'fecha_registro')
        ->get()
        ->groupBy('user_id'); // Agrupar por usuario

      // Preparar los datos para el gráfico
      $usuariosConDatos = $usuariosMeta->map(function ($meta, $userId) use ($usuarios, $clientesPorUsuarioYFecha, $fechas, $dias) {
        $datosPorFecha = array_fill_keys($fechas, 0); // Inicializar todas las fechas con 0 clientes

        if ($clientesPorUsuarioYFecha->has($userId)) {
          foreach ($clientesPorUsuarioYFecha[$userId] as $data) {
            $datosPorFecha[$data->fecha_registro] = $data->cantidad;
          }
        }

        // Calcular la meta diaria
        $metaDiaria = $meta / $dias;

        // Calcular el porcentaje alcanzado basado en la meta diaria
        $totalClientesRegistrados = array_sum($datosPorFecha);
        $totalMetaDiaria = $metaDiaria * count($fechas);
        $porcentajeAlcanzado = $totalMetaDiaria > 0 ? ($totalClientesRegistrados / $totalMetaDiaria) * 100 : 0;

        return [
          'user_id' => $userId,
          'usuario_nombre' => strtoupper($usuarios->get($userId, 'Desconocido')), // Convertir a mayúsculas
          'datos' => array_values($datosPorFecha), // Convertir los datos a un array plano
          'metaDiaria' => $metaDiaria,
          'totalClientesRegistrados' => $totalClientesRegistrados,
          'porcentajeAlcanzado' => $porcentajeAlcanzado
        ];
      });

      // Obtener el porcentaje de según la meta_registros
      $metaRegistros = $evento->meta_registros;

      // Obtener la cantidad de registros alcanzados para el evento
      $cantidadRegistros = Cliente::where('events_id', $eventoId)->count();

      // Calcular el porcentaje alcanzado
      $porcentajeAlcanzado = $metaRegistros > 0 ? ($cantidadRegistros / $metaRegistros) * 100 : 0;

      // Obtener la cantidad de registros por fecha
      $registrosPorFecha = Cliente::where('events_id', $eventoId)
        ->select(DB::raw('DATE(fecharegistro) as fecha_registro'), DB::raw('COUNT(*) as cantidad'))
        ->groupBy('fecha_registro')
        ->pluck('cantidad', 'fecha_registro');

      // Convertir a un array con fechas como claves y cantidades como valores
      $registrosPorFechaArray = $registrosPorFecha->toArray();
      
      
      
      
      
     // Obtener el usuario con más registros de clientes
$usuarioConMasRegistros = Cliente::where('events_id', $eventoId)
->select('user_id', DB::raw('COUNT(*) as total_registros'))
->groupBy('user_id')
->orderBy('total_registros', 'desc')
->first();

// Obtener el usuario con más llamadas "SI"
$usuarioConMasLlamadas = Cliente::where('events_id', $eventoId)
->where('llamada', 'SI')
->select('user_id', DB::raw('COUNT(*) as total_llamadas_si'))
->groupBy('user_id')
->orderBy('total_llamadas_si', 'desc')
->first();

// Obtener el usuario con más correos "SI"
$usuarioConMasCorreos = Cliente::where('events_id', $eventoId)
->where('correo', 'SI')
->select('user_id', DB::raw('COUNT(*) as total_correos_si'))
->groupBy('user_id')
->orderBy('total_correos_si', 'desc')
->first();

// Obtener los detalles del usuario con más registros
$usuarioConMasRegistrosDetalles = User::find($usuarioConMasRegistros ? $usuarioConMasRegistros->user_id : null);
$usuarioConMasLlamadasDetalles = User::find($usuarioConMasLlamadas ? $usuarioConMasLlamadas->user_id : null);
$usuarioConMasCorreosDetalles = User::find($usuarioConMasCorreos ? $usuarioConMasCorreos->user_id : null);

$totalRegistros = $usuarioConMasRegistros ? $usuarioConMasRegistros->total_registros : 0;
$totalLlamadasSI = $usuarioConMasLlamadas ? $usuarioConMasLlamadas->total_llamadas_si : 0;
$totalCorreosSI = $usuarioConMasCorreos ? $usuarioConMasCorreos->total_correos_si : 0;

// Obtener los nombres de los usuarios
$nombreUsuarioConMasRegistros = $usuarioConMasRegistrosDetalles ? $usuarioConMasRegistrosDetalles->name : 'Desconocido';
$nombreUsuarioConMasLlamadas = $usuarioConMasLlamadasDetalles ? $usuarioConMasLlamadasDetalles->name : 'Desconocido';
$nombreUsuarioConMasCorreos = $usuarioConMasCorreosDetalles ? $usuarioConMasCorreosDetalles->name : 'Desconocido';


      return response()->json([
        'success' => true,
        'dias' => $dias,
        'fechas' => $fechas,
        'registrosPorFecha' => $registrosPorFechaArray, // Agrega esta línea
        'usuariosConDatos' => $usuariosConDatos,
        'porcentaje_alcanzado' => round($porcentajeAlcanzado, 2), // Redondear a dos decimales
        'metaPorFecha' => $metaPorFecha, // Incluye la meta diaria para cada fecha
        'registrosPorFecha' => $registrosPorFechaArray, // Agrega esta línea
        'clientes_por_usuario' => $clientesYMetas,
        'fechas' => $fechas,
        'usuariosConDatos' => $usuariosConDatos,
        'fechasfechas' => $fechas,
        
         'nombre_usuario' => $nombreUsuarioConMasRegistros,
    'total_registros' => $totalRegistros,
    'total_llamadas_si' => $totalLlamadasSI,
    'total_correos_si' => $totalCorreosSI,
    'nombre_usuario_llamadas' => $nombreUsuarioConMasLlamadas,
    'nombre_usuario_correos' => $nombreUsuarioConMasCorreos,
        
        
        
        
        'metaPorFecha' => $metaPorFecha,







        // 'dato' => $usuariosConPorcentajeMeta,
        // 'clientes_por_usuario' => $clientesPorUsuario, // Convertir a array para asegurar el formato correcto en JSON
        'dinero_por_evento' => $dineroPorEvento,
        'data' => $clientesPorUsuariototal,
        'cantidad_usuarios' => $usuariosFaltantes->count(),
        'usuarios_faltantes' => $usuariosFaltantes,
        'cantidad_usuarios' => $cantidadUsuarios,
        'meta_registros' => $evento->meta_registros,
        'total_clientes' => $evento->clientes_count ?? 0,
        'total_clientes_intermedio' => $totalClientesIntermedio,
        'total_clientes_potencial' => $totalClientesPotencial,
        'total_clientes_indeciso' => $totalClientesIndeciso,
        'total_clientes_cctv' => $totalClientesCCTV,
        'total_clientes_modulo' => $totalClientesModulo,
        'total_clientes_software' => $totalClientesSoftware,
        'total_clientes_service_desk' => $totalClientesServiceDesk,
        'total_clientes_pendiente' => $totalClientesPendiente,
        'total_clientes_atendido' => $totalClientesAtendido,
        'total_clientes_en_proceso' => $totalClientesEnProceso,
        'total_clientes_contrato' => $totalClientesContrato,
        'usuarios_con_mas_registros' => $usuariosConMasRegistros,
        // 'llamadas_pendientes' => $llamadasPendientes,
        // 'reuniones_pendientes' => $reunionesPendientes,
        // 'eventos_pendientes' => $eventosPendientes,
        // 'tareas_pendientes' => $tareasPendientes,
        'usuarios_faltantes' => $usuariosFaltantes,
        'llamadasPorUsuario' => $llamadasPorUsuario,
        'usuarios_con_correos_llamadas' => $conteosSi->toArray(),
        // 'reuniones_por_mes' => $reunionesPorMes,

        'reuniones_count' => $reunionesCount,
        'cotizaciones_count' => $cotizacionesCount,
        'contratos_realizados_count' => $contratosRealizadosCount,

       ]);
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Evento no encontrado'
        ]);
      }
    } catch (\Exception $e) {
      \Log::error('Error en obtenerMetaPorEvento: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error interno en el servidor'
      ], 500);
    }
  }








  public function getUserDataByEvent($eventId)
  {
    // Obtener usuarios asignados a eventos
    $users = User::with(['clientes' => function ($query) use ($eventId) {
      $query->where('events_id', $eventId);
    }])->get();

    // Filtrar usuarios con conteo de clientes
    $usuarios = $users->map(function ($user) {
      return [
        'name' => $user->name,
        'clientes_count' => $user->clientes->count()
      ];
    });

    return response()->json($usuarios);
  }



  public function generarPdfUsuario($userId, $eventoId, $fechaInicio, $fechaFin)
  {
    // Buscar el usuario por su ID
    $usuario = User::findOrFail($userId);

    // Buscar el evento por su ID
    $evento = DB::table('salida')->where('id', $eventoId)->first();

    // Si el evento no existe, manejar el caso apropiadamente
    if (!$evento) {
      abort(404, 'Evento no encontrado');
    }

    // Obtener el nombre del evento
    $eventoNombre = $evento->title;

    // Obtener las IDs de los clientes que tienen observaciones dentro del rango de fechas proporcionado
    $clientesConObservacionesIds = DB::table('observaciones')
      ->whereBetween('fechareunion', [$fechaInicio, $fechaFin])
      ->orWhereBetween('fechacontrato', [$fechaInicio, $fechaFin])
      ->orWhereBetween('fechallamada', [$fechaInicio, $fechaFin])
      ->pluck('id_cliente');

    // Obtener los clientes que tienen observaciones dentro del rango de fechas proporcionado
    $clientes = DB::table('cliente')
      ->where('user_id', $userId)
      ->where('events_id', $eventoId)
      ->whereIn('id', $clientesConObservacionesIds)
      ->get();

    // Contar la cantidad de clientes
    $cantidadClientes = $clientes->count();

    // Obtener las observaciones para cada cliente filtradas por rango de fechas
    $observaciones = DB::table('observaciones')
      ->whereIn('id_cliente', $clientes->pluck('id'))
      ->where(function ($query) use ($fechaInicio, $fechaFin) {
        $query->whereBetween('fechareunion', [$fechaInicio, $fechaFin])
          ->orWhereBetween('fechacontrato', [$fechaInicio, $fechaFin])
          ->orWhereBetween('fechallamada', [$fechaInicio, $fechaFin]);
      })
      ->get();

    // Unir observaciones con los detalles de los clientes
    $clientesConObservaciones = $clientes->map(function ($cliente) use ($observaciones) {
      // Filtrar observaciones para el cliente actual
      $clienteObservaciones = $observaciones->where('id_cliente', $cliente->id);

      // Añadir observaciones al cliente
      $cliente->observaciones = $clienteObservaciones->first();
      return $cliente;
    });

    // Contar la cantidad de "SI" en correo, WhatsApp y llamada
    $cantidadCorreo = $clientes->where('correo', 'SI')->count();
    $cantidadWhatsapp = $clientes->where('whatsapp', 'SI')->count();
    $cantidadLlamada = $clientes->where('llamada', 'SI')->count();

    // Generar el PDF utilizando la vista correspondiente
    $pdf = PDF::loadView('pdf.usuario', compact('usuario', 'eventoNombre', 'fechaInicio', 'fechaFin', 'clientesConObservaciones', 'cantidadClientes', 'cantidadCorreo', 'cantidadWhatsapp', 'cantidadLlamada'));

    // Descargar el PDF con un nombre basado en el nombre del usuario, el nombre del evento y el rango de fechas
    return $pdf->download('usuario_' . $usuario->name . '_evento_' . str_replace(' ', '_', $eventoNombre) . '_fechas_' . $fechaInicio . '_a_' . $fechaFin . '.pdf');
  }
}
