<?php

namespace App\Http\Controllers\Calendario;

use App\Http\Controllers\Controller;
use App\Mail\ActualizacionEventoMail;
use App\Mail\CancelacionEventoMail;
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
        'fechainicio' => 'required|date_format:Y-m-d H:i:s',
        'fechafin' => 'nullable|date_format:Y-m-d H:i:s',
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
           'fechainicio' => 'required|date_format:Y-m-d H:i:s', // Formato correcto
    'fechafin' => 'nullable|date_format:Y-m-d H:i:s',
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

   public function destroy($id)
{
    $evento = Actividad::with('invitados.usuario') // Asegúrate de tener la relación 'usuario' en Invitado
        ->where('actividad_id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    // Enviar correo a los invitados antes de eliminar
    foreach ($evento->invitados as $invitado) {
        $usuario = $invitado->usuario;
        if ($usuario && $usuario->email) {
            Mail::to($usuario->email)->queue(new CancelacionEventoMail
            ($evento, $usuario));
        }
    }

    // Eliminar los invitados asociados
    Invitado::where('actividad_id', $evento->actividad_id)->delete();

    // Eliminar el evento
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

    protected function formatEvento($evento)
{
    $evento->loadMissing(['invitados', 'user']); // 👈 Asegura que viene el usuario

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
            'usuario' => $evento->user?->name ?? 'Desconocido' // 👈 Aquí viene ya
        ]
    ];
}

private function getColorByEtiqueta($etiqueta)
{
    // Buscar el color en la tabla etiquetas
    $etiquetaModel = Etiqueta::where('nombre', $etiqueta)->first();

    if ($etiquetaModel && $etiquetaModel->color) {
        return $etiquetaModel->color;
    }

    // Fallback a colores hardcodeados
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

// Método para eliminar etiqueta con validación
public function destroyEtiqueta($id)
{
    // Buscar la etiqueta del usuario autenticado
    $etiqueta = Etiqueta::where('user_id', auth()->id())
                ->findOrFail($id);
    
    // Verificar si la etiqueta está en uso
    $enUso = Actividad::where('user_id', auth()->id())
             ->where('etiqueta', $etiqueta->nombre)
             ->exists();
    
    if ($enUso) {
        return response()->json([
            'message' => 'No se puede eliminar la etiqueta porque está en uso por alguna actividad',
            'en_uso' => true
        ], 422); // Código 422: Unprocessable Entity
    }
    
    // Si no está en uso, proceder con la eliminación
    $etiqueta->delete();

    return response()->json([
        'message' => 'Etiqueta eliminada correctamente',
        'en_uso' => false
    ]);
}
public function showEtiqueta($id)
{
    $etiqueta = Etiqueta::where('user_id', auth()->id())
                ->findOrFail($id);
                
    return response()->json($etiqueta);
}
}
