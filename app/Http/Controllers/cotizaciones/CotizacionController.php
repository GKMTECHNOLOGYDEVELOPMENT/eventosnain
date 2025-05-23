<?php

namespace App\Http\Controllers\cotizaciones;

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

class CotizacionController extends Controller
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

    public function create()
    {


        return view('content.cotizacion.new-cotizacion');
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
