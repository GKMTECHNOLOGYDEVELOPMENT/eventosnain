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
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CotizacionController extends Controller
{
    public function index()
    {
        $usuarioAutenticado = auth()->user();

        // Obtener las cotizaciones con relaciones y paginación
        $query = Cotizacion::with(['cliente', 'productos'])
            ->orderBy('fecha_emision', 'desc');



        // Filtro por rol de usuario
        if ($usuarioAutenticado->rol_id != 1 && $usuarioAutenticado->rol_id != 3) {
            // Para usuarios no admin, solo sus cotizaciones relacionadas a sus clientes
            $query->whereHas('cliente', function ($q) use ($usuarioAutenticado) {
                $q->where('user_id', $usuarioAutenticado->id);
            });
        }

        // Búsqueda
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('codigo_cotizacion', 'like', "%$search%")
                    ->orWhereHas('cliente', function ($q) use ($search) {
                        $q->where('nombre', 'like', "%$search%")
                            ->orWhere('empresa', 'like', "%$search%");
                    });
            });
        }

        // Filtro por estado
        if (request()->has('estado') && request('estado') != 'todos') {
            $query->where('estado', request('estado'));
        }

        $cotizaciones = $query->paginate(10);

        // Para solicitudes AJAX (paginación/búsqueda)
        if (request()->ajax()) {
            return view('content.cotizacion.partials._cotizacionTable', compact('cotizaciones'))->render();
        }

        return view('content.cotizacion.index', [
            'cotizaciones' => $cotizaciones,
            'estados' => [
                'pendiente' => 'Pendiente',
                'aprobada' => 'Aprobada',
                'rechazada' => 'Rechazada',
                'vencida' => 'Vencida'
            ]
        ]);
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
        $cotizacion->fecha_emision = \Carbon\Carbon::parse($cotizacion->fecha_emision);

        return view('content.cotizacion.imprimir', compact('cotizacion'));
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
                'id' => $cotizacion->id, // Asegúrate de incluir el ID
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
    public function exportarPdf($id)
    {
        $cotizacion = Cotizacion::with([
            'cliente',
            'productos.modulo.imagenPrincipal'
        ])->findOrFail($id);
    
        $cotizacion->fecha_emision = \Carbon\Carbon::parse($cotizacion->fecha_emision);
    
        $logoFondo = 'data:image/jpg;base64,' . base64_encode(
            file_get_contents(public_path('assets/img/backgrounds/hoja_membretada.jpg'))
        );
    
        $html = view('content.cotizacion.pdf.index', compact('cotizacion', 'logoFondo'))->render();
    
        $pdf = \Spatie\Browsershot\Browsershot::html($html)
            ->format('A4')
            ->showBackground()
            ->pdf();
    
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="cotizacion-' . $cotizacion->codigo_cotizacion . '.pdf"');
    }
    
    public function edit($id)
    {
        $cotizacion = Cotizacion::with('cliente', 'productos')->findOrFail($id);

        $clientes = Cliente::all(); // Todos los clientes

        // Obtener todos los módulos activos
        $modulos = Modulo::where('estado', 1)->get();
        // Si quieres enviar los estados para un select
        $estados = [
            'pendiente' => 'Pendiente',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
            'vencida' => 'Vencida'
        ];

        return view('content.cotizacion.edit', compact('cotizacion', 'estados', 'clientes', 'modulos'));
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        if (is_string($request->productos)) {
            $input['productos'] = json_decode($request->productos, true);
        }

        $validated = $this->validate($request, [
            'codigo_cotizacion' => 'required|string|max:50|unique:cotizaciones,codigo_cotizacion,' . $id,
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

        DB::beginTransaction();

        try {
            $cotizacion = Cotizacion::findOrFail($id);

            $subtotal = 0;
            foreach ($validated['productos'] as $producto) {
                $subtotal += $producto['cantidad'] * $producto['precio'];
            }

            $igv = $subtotal * 0.18;
            $total = $subtotal + $igv;

            // Actualizar la cotización
            $cotizacion->update([
                'codigo_cotizacion' => $validated['codigo_cotizacion'],
                'fecha_emision' => $validated['fecha_emision'],
                'cliente_id' => $validated['cliente_id'],
                'validez' => $validated['validez'],
                'condiciones_comerciales' => $validated['condiciones_comerciales'],
                'observaciones' => $validated['observaciones'] ?? null,
                'subtotal_sin_igv' => $subtotal,
                'igv' => $igv,
                'total_con_igv' => $total,
            ]);

            // Eliminar productos anteriores
            CotizacionProducto::where('cotizacion_id', $cotizacion->id)->delete();

            // Insertar nuevos productos
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
                'message' => 'Cotización actualizada exitosamente',
                'id' => $cotizacion->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualizar la cotización: ' . $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }
}
