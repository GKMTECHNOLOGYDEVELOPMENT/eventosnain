<?php

namespace App\Http\Controllers\Calendario;

use App\Http\Controllers\Controller;
use App\Mail\ActualizacionEventoMail;
use App\Mail\InvitacionEventoMail;
use App\Models\Actividad;
use App\Models\Etiqueta;
use App\Models\Informacion;
use Illuminate\Http\Request;
use App\Models\Llamada;
use App\Models\Reunion;
use App\Models\Event;
use App\Models\Invitado;
use App\Models\Salida;
use App\Models\SalidaUser;
use App\Models\User;
use Carbon\Carbon;
use DB;
// use Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Calendario extends Controller
{


  public function show()
{
    // Obtener el ID del usuario autenticado
    $authUserId = auth()->id();
    
    // Obtener todos los usuarios excepto el autenticado
    $users = User::where('id', '!=', $authUserId)
                ->select('id', 'name', 'email')
                ->get()
                ->map(function($user) {
                    return [
                        'id' => $user->id,
                        'text' => $user->name, // Select2 espera 'text' para mostrar
                        'email' => $user->email
                    ];
                });

    return view('content.calendario.index', compact('users'));
}
 // Obtener todos los eventos para el calendario
  public function index()
{
    $authUserId = auth()->id();
    
    $eventos = Actividad::where('user_id', $authUserId)
        ->with(['invitados' => function($query) {
            $query->select('idinvitados', 'actividad_id', 'id_usuarios');
        }])
        ->get()
        ->map(function($evento) {
            return [
                'id' => $evento->actividad_id,
                'title' => $evento->titulo,
                'start' => $evento->fechainicio,
                'end' => $evento->fechafin,
                'allDay' => (bool)$evento->todoeldia,
                'url' => $evento->enlaceevento,
                'color' => $this->getColorByEtiqueta($evento->etiqueta),
                'extendedProps' => [
                    'etiqueta' => $evento->etiqueta,
                    'ubicacion' => $evento->ubicacion,
                    'descripcion' => $evento->descripcion,
                    'invitados' => $evento->invitados->pluck('id_usuarios')->toArray(),
                                    'usuario' => $evento->user?->name, // 👈 Aquí va el nombre del usuario

                    
                ]
            ];
        });

    return response()->json($eventos);
}

    // Guardar un nuevo evento
   public function store(Request $request)
{
    $request->validate([
        'titulo' => 'required|string|max:255',
        'etiqueta' => 'nullable|string|max:255',
        'fechainicio' => 'required|date',
        'fechafin' => 'nullable|date',
        'todoeldia' => 'boolean',
        'enlaceevento' => 'nullable|url',
        'ubicacion' => 'nullable|string|max:255',
        'descripcion' => 'nullable|string',
        'invitados' => 'nullable|array',
        'invitados.*' => 'exists:users,id'
    ]);

    $evento = Actividad::create([
        'titulo' => $request->titulo,
        'etiqueta' => $request->etiqueta,
        'fechainicio' => $request->fechainicio,
        'fechafin' => $request->fechafin,
        'todoeldia' => $request->todoeldia ?? false,
        'enlaceevento' => $request->enlaceevento,
        'ubicacion' => $request->ubicacion,
        'descripcion' => $request->descripcion,
        'user_id' => auth()->id()
    ]);

    // Guardar invitados
    if ($request->invitados) {
        foreach ($request->invitados as $invitadoId) {
            Invitado::create([
                'actividad_id' => $evento->actividad_id,
                'id_usuarios' => $invitadoId
            ]);
            // Enviar correo al invitado
        $usuario = User::find($invitadoId);
        if ($usuario && $usuario->email) {
            Mail::to($usuario->email)->queue(new InvitacionEventoMail($evento, $usuario));
        }
        }
    }

    return response()->json([
        'success' => true,
        'evento' => $this->formatEvento($evento->fresh())
    ]);
}

    // Actualizar un evento existente
    public function update(Request $request, $id)
    {
        $evento = Actividad::where('actividad_id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'titulo' => 'required|string|max:255',
            'etiqueta' => 'nullable|string|max:255',
            'fechainicio' => 'required|date',
            'fechafin' => 'nullable|date',
            'todoeldia' => 'boolean',
            'enlaceevento' => 'nullable|url',
            'ubicacion' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'invitados' => 'nullable|array',
            'invitados.*' => 'exists:users,id'
        ]);

        $evento->update([
            'titulo' => $request->titulo,
            'etiqueta' => $request->etiqueta,
            'fechainicio' => $request->fechainicio,
            'fechafin' => $request->fechafin,
            'todoeldia' => $request->todoeldia ?? false,
            'enlaceevento' => $request->enlaceevento,
            'ubicacion' => $request->ubicacion,
            'descripcion' => $request->descripcion
        ]);

        // Sincronizar invitados
       if ($request->has('invitados')) {
    $evento->invitados()->delete(); // Eliminar todos los invitados existentes
    
    foreach ($request->invitados as $invitadoId) {
        Invitado::create([
            'actividad_id' => $evento->actividad_id,
            'id_usuarios' => $invitadoId
        ]);

        // Enviar correo al invitado
        $usuario = User::find($invitadoId);
        if ($usuario && $usuario->email) {
            Mail::to($usuario->email)->queue(new ActualizacionEventoMail($evento, $usuario));
        }
    }
}


        return response()->json([
            'success' => true,
            'evento' => $this->formatEvento($evento->fresh())
        ]);
    }

    // Eliminar un evento
   public function destroy($id)
{
    $evento = Actividad::with('invitados')
        ->where('actividad_id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    // Primero eliminar los invitados asociados
    if ($evento->invitados->isNotEmpty()) {
        Invitado::where('actividad_id', $evento->actividad_id)->delete();
    }

    // Luego eliminar el evento
    $evento->delete();

    return response()->json([
        'success' => true,
        'message' => 'Evento eliminado correctamente'
    ]);
}
    // Obtener usuarios para invitados (excepto el autenticado)
    public function getUsersForInvites()
    {
        $authUserId = auth()->id();
        
        $users = User::where('id', '!=', $authUserId)
            ->select('id', 'name', 'email')
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name,
                    'email' => $user->email
                ];
            });

        return response()->json($users);
    }

    // Helper para formatear evento para FullCalendar
    private function formatEvento($evento)
    {
        return [
            'id' => $evento->actividad_id,
            'title' => $evento->titulo,
            'start' => $evento->fechainicio,
            'end' => $evento->fechafin,
            'allDay' => (bool)$evento->todoeldia,
            'url' => $evento->enlaceevento,
            'color' => $this->getColorByEtiqueta($evento->etiqueta),
            'extendedProps' => [
                'etiqueta' => $evento->etiqueta,
                'ubicacion' => $evento->ubicacion,
                'descripcion' => $evento->descripcion,
                'invitados' => $evento->invitados->pluck('id_usuarios')->toArray(),
            ]
        ];


    }

    // Helper para obtener color según etiqueta
    private function getColorByEtiqueta($etiqueta)
    {
        $colores = [
            'negocios' => '#0d6efd',
            'personal' => '#dc3545',
            'feriado' => '#198754',
            'otros' => '#0dcaf0'
        ];

        return $colores[$etiqueta] ?? '#696cff';
    }


    // Método para obtener etiquetas
public function getEtiquetas()
{
    $etiquetas = Etiqueta::where('user_id', auth()->id())->get();
    return response()->json($etiquetas);
}

// Método para guardar etiqueta
public function storeEtiqueta(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'color' => 'required|string|max:7',
        'icono' => 'nullable|string|max:255'
    ]);

    $etiqueta = Etiqueta::create([
        'nombre' => $request->nombre,
        'color' => $request->color,
        'icono' => $request->icono,
        'user_id' => auth()->id()
    ]);

    return response()->json($etiqueta);
}

// Método para actualizar etiqueta
public function updateEtiqueta(Request $request, $id)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'color' => 'required|string|max:7',
        'icono' => 'nullable|string|max:255'
    ]);

    $etiqueta = Etiqueta::where('user_id', auth()->id())
                ->findOrFail($id);
                
    $etiqueta->update($request->all());

    return response()->json($etiqueta);
}

// Método para eliminar etiqueta
public function destroyEtiqueta($id)
{
    $etiqueta = Etiqueta::where('user_id', auth()->id())
                ->findOrFail($id);
                
    $etiqueta->delete();

    return response()->json(['message' => 'Etiqueta eliminada']);
}

public function showEtiqueta($id)
{
    $etiqueta = Etiqueta::where('user_id', auth()->id())
                ->findOrFail($id);
                
    return response()->json($etiqueta);
}
}
