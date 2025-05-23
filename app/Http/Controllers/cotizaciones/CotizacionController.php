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
use App\Models\CotizacionProducto;
use App\Models\Event;
use App\Models\EventUser;
use App\Models\Informacion;
use App\Models\Llamada;
use App\Models\Modulo;
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

        $clientes = Cliente::all(); // Todos los clientes

        // Obtener todos los módulos activos
        $modulos = Modulo::where('estado', 1)->get();

        return view('content.cotizacion.new-cotizacion', compact('clientes', 'modulos'));
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


    public function imprimir($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'productos.modulo'])->findOrFail($id);
        return view('cotizaciones.imprimir', compact('cotizacion'));
    }


    public function store(Request $request)
    {
        // Decodificar el JSON si es necesario
        $input = $request->all();
        if (is_string($request->productos)) {
            $input['productos'] = json_decode($request->productos, true);
        }

        // Validación de los datos del formulario
        $validated = $this->validate($request, [
            'codigo_cotizacion' => 'required|string|max:50|unique:cotizaciones',
            'fecha_emision' => 'required|date',
            'cliente_id' => 'required|exists:cliente,id',
            'validez' => 'required|integer|min:1',
            'condiciones_comerciales' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:modulos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
        ]);

        // Resto del código del controlador permanece igual...
        DB::beginTransaction();

        try {
            $subtotal = 0;
            foreach ($validated['productos'] as $producto) {
                $subtotal += $producto['cantidad'] * $producto['precio'];
            }
            $igv = $subtotal * 0.18;
            $total = $subtotal + $igv;

            $cotizacion = Cotizacion::create([
                'codigo_cotizacion' => $validated['codigo_cotizacion'],
                'fecha_emision' => $validated['fecha_emision'],
                'cliente_id' => $validated['cliente_id'],
                'validez' => $validated['validez'],
                'condiciones_comerciales' => $validated['condiciones_comerciales'],
                'observaciones' => $validated['observaciones'] ?? null,
                'subtotal_sin_igv' => $subtotal,
                'igv' => $igv,
                'total_con_igv' => $total,
                'estado' => 'pendiente'
            ]);

            foreach ($validated['productos'] as $producto) {
                CotizacionProducto::create([
                    'cotizacion_id' => $cotizacion->id,
                    'modulo_id' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio'],
                    'subtotal' => $producto['cantidad'] * $producto['precio']
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Cotización creada exitosamente',
                'redirect' => route('cotizacion-newCotizacion')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al guardar la cotización: ' . $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }
}
