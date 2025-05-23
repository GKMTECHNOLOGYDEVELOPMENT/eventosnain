<?php

namespace App\Http\Controllers\modulo;

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
use Illuminate\Support\Facades\Validator;

class ModuloController extends Controller
{
    public function index()
    {
        $modulos = Modulo::paginate(10); // o el número que desees

        if (request()->ajax()) {
            return view('content.modulo.partials._moduloTable', compact('modulos'))->render();
        }

        return view('content.modulo.index', compact('modulos'));
    }


    public function create()
    {


        return view('content.modulo.new-modulo');
    }

    public function store(Request $request)
    {
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'codigo_modulo' => 'required|unique:modulos|max:20',
            'modelo' => 'required|max:50',
            'descripcion' => 'nullable',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_total' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'fecha_registro' => 'required|date',
            'estado' => 'required|boolean'
        ], [
            'codigo_modulo.unique' => 'El código del módulo ya existe',
            'precio_venta.gte' => 'El precio de venta debe ser mayor o igual al precio de compra',
            'stock_minimo.lte' => 'El stock mínimo no puede ser mayor al stock total'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Crear el módulo
            $modulo = Modulo::create([
                'codigo_modulo' => $request->codigo_modulo,
                'marca' => 'INTIFOLD',
                'modelo' => $request->modelo,
                'descripcion' => $request->descripcion,
                'precio_compra' => $request->precio_compra,
                'precio_venta' => $request->precio_venta,
                'stock_total' => $request->stock_total,
                'stock_minimo' => $request->stock_minimo,
                'fecha_registro' => $request->fecha_registro,
                'estado' => $request->estado
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Módulo registrado correctamente',
                'data' => $modulo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el módulo: ' . $e->getMessage()
            ], 500);
        }
    }


    public function edit(Modulo $modulo)
    {
        return view('content.modulo.edit', compact('modulo'));
    }

    public function update(Request $request, Modulo $modulo)
    {
        $validated = $request->validate([
            'codigo_modulo' => 'required|max:20|unique:modulos,codigo_modulo,' . $modulo->id,
            'modelo' => 'required|max:50',
            'descripcion' => 'nullable',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_total' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'fecha_registro' => 'required|date',
            'estado' => 'required|boolean'
        ]);

        try {
            $modulo->update($validated);
            return response()->json(['success' => true, 'message' => 'Módulo actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Modulo $modulo)
    {
        try {
            $modulo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Módulo eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el módulo: ' . $e->getMessage()
            ], 500);
        }
    }
}
