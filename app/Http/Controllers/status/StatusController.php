<?php 

namespace App\Http\Controllers\status;
use App\Http\Controllers\Controller;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:50'
        ]);
        
        // Obtener el último orden para asignar el siguiente
        $lastOrder = Status::forUser(Auth::id())->max('order') ?? 0;
        
        $status = Status::create([
            'name' => $request->name,
            'color' => $request->color,
            'order' => $lastOrder + 1,
            'user_id' => Auth::id()
        ]);
        
        return response()->json(['status' => $status]);
    }

        public function edit(Status $status)
    {
        // Verificar que el status pertenezca al usuario autenticado
        if ($status->user_id != Auth::id()) {
            abort(403, 'No tienes permiso para editar este estado');
        }
        
        return response()->json($status);
    }
    public function update(Request $request, Status $status)
    {
        // Verificar que el estado pertenezca al usuario
        if ($status->user_id != Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:50'
        ]);
        
        $status->update($request->all());
        
        return response()->json(['status' => $status]);
    }

    public function destroy(Status $status)
    {
        // Verificar que el estado pertenezca al usuario
        if ($status->user_id != Auth::id()) {
            abort(403);
        }
        
        // Verificar que no haya tareas asociadas
        if ($status->tasks()->exists()) {
            return response()->json([
                'error' => 'No se puede eliminar un estado que tiene tareas asociadas'
            ], 422);
        }
        
        $status->delete();
        
        return response()->json(['success' => true]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'statuses' => 'required|array',
            'statuses.*.id' => 'required|exists:statuses,id,user_id,'.Auth::id()
        ]);
        
        foreach ($request->statuses as $index => $statusData) {
            Status::where('id', $statusData['id'])
                ->where('user_id', Auth::id())
                ->update(['order' => $index + 1]);
        }
        
        return response()->json(['success' => true]);
    }
}