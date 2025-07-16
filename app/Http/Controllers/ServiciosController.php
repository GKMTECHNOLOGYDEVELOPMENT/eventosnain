<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
{
    $search = request('search');
    $query = Servicio::query();

    if ($search) {
        $query->where('nombre', 'like', "%$search%");
    }

    $servicios = $query->orderBy('created_at', 'desc')->paginate(10);

    if (request()->ajax()) {
        return view('content.servicios.partials._serviciosTable', compact('servicios'))->render();
    }

    return view('content.servicios.index', compact('servicios'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.servicios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    
public function edit($id)
{
    $servicios = Servicio::findOrFail($id);
    return response()->json($servicios);
}

    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, $id)
{
    $request->validate([
        'nombre' => 'required|max:100',
        'descripcion' => 'required',
    ]);

    $servicios = Servicio::findOrFail($id);
    $servicios->update($request->only('nombre', 'descripcion'));

    return response()->json(['success' => true, 'message' => 'Servicio actualizada correctamente.']);
}


    /**
     * Remove the specified resource from storage.
     */
  public function destroy($id)
{
    Servicio::destroy($id);
    return response()->json(['success' => true, 'message' => 'Servicio eliminada.']);
}

 public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string'
        ]);

        $servicio = Servicio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Servicio guardada exitosamente',
            'data' => $servicio
        ]);
    }

     public function search(Request $request)
    {
        $search = $request->get('search');
        $page = $request->get('page', 1);
        $perPage = 10;

        $query = DB::table('cliente')
            ->where('status', 'activo') // Filtro opcional
            ->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                    ->orWhere('empresa', 'like', "%$search%");
            })
            ->select(['id', 'nombre', 'empresa']);

        $total = $query->count();
        $results = $query->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->nombre . ($item->empresa ? ' - ' . $item->empresa : ''),
                    'nombre' => $item->nombre,
                    'empresa' => $item->empresa
                ];
            });

        return response()->json([
            'results' => $results,
            'total_count' => $total
        ]);
    }

}
