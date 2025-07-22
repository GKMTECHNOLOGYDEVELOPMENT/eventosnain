<?php

namespace App\Http\Controllers\nota;
use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotasController extends Controller
{
    public function index()
    {
        $statuses = Status::forUser(Auth::id())
            ->with(['tasks' => function($query) {
                $query->forUser(Auth::id());
            }])
            ->orderBy('order')
            ->get();
            
        return view('content.notas.index', compact('statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status_id' => 'required|exists:statuses,id,user_id,'.Auth::id()
        ]);
        
        $task = Task::create($request->all() + ['user_id' => Auth::id()]);
        
        return response()->json([
            'task' => $task,
            'status_name' => $task->status->name,
            'status_color' => $task->status->color
        ]);
    }

    public function edit(Task $task)
    {
        // Verificar que la tarea pertenezca al usuario
        if ($task->user_id != Auth::id()) {
            abort(403);
        }
        
        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        // Verificar que la tarea pertenezca al usuario
        if ($task->user_id != Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status_id' => 'required|exists:statuses,id,user_id,'.Auth::id()
        ]);
        
        $oldStatusId = $task->status_id;
        $task->update($request->all());
        
        return response()->json([
            'task' => $task,
            'status' => $task->status,
            'moved' => $oldStatusId != $task->status_id,
            'old_status_id' => $oldStatusId
        ]);
    }

    public function updateStatus(Request $request, Task $task)
    {
        // Verificar que la tarea pertenezca al usuario
        if ($task->user_id != Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'status_id' => 'required|exists:statuses,id,user_id,'.Auth::id()
        ]);
        
        $task->update(['status_id' => $request->status_id]);
        
        return response()->json(['success' => true]);
    }

    public function destroy(Task $task)
    {
        // Verificar que la tarea pertenezca al usuario
        if ($task->user_id != Auth::id()) {
            abort(403);
        }
        
        $task->delete();
        return response()->json(['success' => true]);
    }
}