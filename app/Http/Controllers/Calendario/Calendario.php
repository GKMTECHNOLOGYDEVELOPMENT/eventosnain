<?php

namespace App\Http\Controllers\Calendario;

use App\Http\Controllers\Controller;
use App\Models\Informacion;
use Illuminate\Http\Request;
use App\Models\Llamada;
use App\Models\Reunion;
use App\Models\Event;
use App\Models\Salida;
use App\Models\SalidaUser;
use Carbon\Carbon;
use DB;
// use Event;
use Illuminate\Support\Facades\Log;

class Calendario extends Controller
{
    // public function actualizar(Request $request)
    // {
    //     // Validar los datos de la solicitud
    //     $validated = $request->validate([
    //         'llamada_id' => 'required|integer|exists:llamada,id_llamada',
    //         'observaciones' => 'nullable|string',
    //         'estado' => 'required|string|in:PENDIENTE,REALIZADO',
    //         'fecha' => 'required|date_format:Y-m-d', // Validar el formato de la fecha
    //         'hora' => 'required|date_format:H:i', // Validar el formato de la hora
    //     ]);

    //     try {
    //         // Buscar la llamada por su ID
    //         $llamada = Llamada::find($validated['llamada_id']);

    //         // Si no se encuentra la llamada, devolver un mensaje de error
    //         if (!$llamada) {
    //             return response()->json(['success' => false, 'message' => 'Llamada no encontrada']);
    //         }

    //         // Combinar la fecha y la hora en un solo campo datetime
    //         $date = $validated['fecha'] . ' ' . $validated['hora'] . ':00';

    //         // Actualizar los campos de la llamada
    //         $llamada->update([
    //             'observaciones' => $validated['observaciones'],
    //             'estado' => $validated['estado'],
    //             'date' => $date, // Actualizar la fecha y hora
    //         ]);

    //         // Devolver una respuesta exitosa
    //         return response()->json(['success' => true]);
    //     } catch (\Exception $e) {
    //         // Registrar el error y devolver una respuesta de error
    //         Log::error('Error al actualizar llamada: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Error interno del servidor']);
    //     }
    // }









    // public function actualizarreunion(Request $request)
    // {
    //     $validated = $request->validate([
    //         'reunion_id' => 'required|integer|exists:reunion,id_reunion',
    //         'observacion' => 'nullable|string|max:255',
    //         'estado' => 'required|string|in:PENDIENTE,REALIZADO',
    //         'fecha' => 'required|date',
    //         'hora' => 'required|date_format:H:i',
    //         'zoom' => 'nullable|string|max:255',
    //     ]);

    //     try {
    //         $reunion = Reunion::find($validated['reunion_id']);

    //         if (!$reunion) {
    //             return response()->json(['success' => false, 'message' => 'Reunión no encontrada']);
    //         }

    //         // Combina la fecha y la hora en un solo campo datetime
    //         $fechaHora = $validated['fecha'] . ' ' . $validated['hora'];

    //         $reunion->update([
    //             'observacion' => $validated['observacion'],
    //             'estado' => $validated['estado'],
    //             'fecha_hora' => $fechaHora,
    //             'zoom' => $validated['zoom'],
    //         ]);

    //         return response()->json(['success' => true]);
    //     } catch (\Exception $e) {
    //         Log::error('Error al actualizar reunión: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Error interno del servidor']);
    //     }
    // }



    // public function actualizarinformacion(Request $request)
    // {
    //     $validated = $request->validate([
    //         'informacion_id' => 'required|integer|exists:informacion,id_informacion',
    //         'direccion' => 'nullable|string|max:255',
    //         'observacion' => 'nullable|string',
    //     ]);

    //     try {
    //         $informacion = Informacion::find($validated['informacion_id']);

    //         if (!$informacion) {
    //             return response()->json(['success' => false, 'message' => 'Información no encontrada']);
    //         }

    //         $informacion->update([
    //             'direccion' => $validated['direccion'],
    //             'observacion' => $validated['observacion'],
    //         ]);

    //         return response()->json(['success' => true]);
    //     } catch (\Exception $e) {
    //         Log::error('Error al actualizar información: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Error interno del servidor']);
    //     }
    // }



    // public function eliminar(Request $request)
    // {
    //     try {
    //         $llamada = Llamada::find($request->input('llamada_id'));

    //         if ($llamada) {
    //             $llamada->delete();
    //             return response()->json(['success' => true]);
    //         } else {
    //             return response()->json(['success' => false, 'message' => 'Llamada no encontrada']);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => 'Error al eliminar la llamada: ' . $e->getMessage()]);
    //     }
    // }



    // public function eliminarReunion(Request $request)
    // {
    //     $request->validate([
    //         'reunion_id' => 'required|integer|exists:reunion,id_reunion',
    //     ]);

    //     try {
    //         $reunion = Reunion::findOrFail($request->input('reunion_id'));
    //         $reunion->delete();

    //         return response()->json(['success' => true]);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => 'Error al eliminar la reunión.']);
    //     }
    // }


    // public function eliminarEvento(Request $request)
    // {
    //     try {
    //         // Busca el evento con el modelo correcto
    //         $event = Event::findOrFail($request->input('event_id'));

    //         // Elimina el evento
    //         $event->delete();

    //         return response()->json(['success' => true]);
    //     } catch (\Exception $e) {
    //         // Registra el error
    //         \Log::error('Error al eliminar el evento: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Error al eliminar el evento.']);
    //     }
    // }


    // public function actualizarevento(Request $request)
    // {
    //     // Validar los datos de la solicitud
    //     $validated = $request->validate([
    //         'event_id' => 'required|integer|exists:events,id',
    //         'nota' => 'nullable|string|max:255',
    //         'estado' => 'required|string|in:PENDIENTE,REALIZADO',
    //         'tipo' => 'required|string|in:REUNION,LEVANTAMIENTO INFORMACION,LLAMADA',
    //         'fecha' => 'required|date_format:Y-m-d', // Validar el formato de la fecha
    //         'hora' => 'required|date_format:H:i', // Validar el formato de la hora
    //     ]);

    //     try {
    //         // Buscar el evento por su ID
    //         $evento = Event::find($validated['event_id']);

    //         // Si no se encuentra el evento, devolver un mensaje de error
    //         if (!$evento) {
    //             return response()->json(['success' => false, 'message' => 'Evento no encontrado']);
    //         }

    //         // Combinar la fecha y la hora en un solo campo datetime
    //         $start = $validated['fecha'] . ' ' . $validated['hora'] . ':00';
    //         $end = $start; // Ajusta esto si tienes una lógica para calcular la fecha de finalización

    //         // Actualizar los campos del evento
    //         $evento->update([
    //             'note' => $validated['nota'],
    //             'estado' => $validated['estado'],
    //             'type' => $validated['tipo'],
    //             'start' => $start, // Actualizar la fecha y hora de inicio
    //             'end' => $end,     // Actualizar la fecha y hora de fin
    //         ]);

    //         // Devolver una respuesta exitosa
    //         return response()->json(['success' => true]);
    //     } catch (\Exception $e) {
    //         // Registrar el error y devolver una respuesta de error
    //         Log::error('Error al actualizar evento: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Error interno del servidor']);
    //     }
    // }



    // // En SalidaController.php
    // public function actualizarSalida(Request $request)
    // {
    //     $request->validate([
    //         'salida_id' => 'required|integer|exists:salida,id',
    //         'start' => 'required|date_format:Y-m-d\TH:i',
    //         'end' => 'required|date_format:Y-m-d\TH:i',
    //         'note' => 'nullable|string',
    //         'meta_registros' => 'nullable|string',
    //     ]);

    //     try {
    //         $salida = Salida::findOrFail($request->input('salida_id'));

    //         $salida->start = $request->input('start');
    //         $salida->end = $request->input('end');
    //         $salida->note = $request->input('note');
    //         $salida->meta_registros = $request->input('meta_registros');
    //         $salida->save();

    //         return response()->json(['success' => true]);
    //     } catch (\Exception $e) {
    //         \Log::error('Error al actualizar la salida: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Error al actualizar la salida.']);
    //     }
    // }

    // public function eliminarSalida(Request $request)
    // {
    //     $request->validate([
    //         'salida_id' => 'required|integer|exists:salida,id',
    //     ]);

    //     try {
    //         $salida = Salida::findOrFail($request->input('salida_id'));
    //         $salida->delete();

    //         return response()->json(['success' => true]);
    //     } catch (\Exception $e) {
    //         \Log::error('Error al eliminar la salida: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Error al eliminar la salida.']);
    //     }
    // }

    // public function guardar(Request $request)
    // {
    //     // Validación de datos
    //     $request->validate([
    //         'salida_id' => 'required|integer',
    //         'user_id' => 'required|integer',
    //         'meta_usuario' => 'required|integer',
    //     ]);

    //     // Verificar si el registro ya existe
    //     $exists = SalidaUser::where('salida_id', $request->input('salida_id'))
    //         ->where('user_id', $request->input('user_id'))
    //         ->exists();

    //     if ($exists) {
    //         // Redirigir con un mensaje de error si el registro ya existe
    //         return redirect()->back()->with('error', 'El registro ya existe.');
    //     }

    //     // Crear nuevo registro en la base de datos con fecha actual
    //     SalidaUser::create([
    //         'salida_id' => $request->input('salida_id'),
    //         'user_id' => $request->input('user_id'),
    //         'meta_usuario' => $request->input('meta_usuario'),
    //         'created_at' => Carbon::now(),
    //         'updated_at' => Carbon::now(),
    //     ]);

    //     // Redirigir con un mensaje de éxito
    //     return redirect()->back()->with('success', 'Registro guardado exitosamente.');
    // }
}
