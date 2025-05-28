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
use App\Models\ModuloImagen;
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
use Illuminate\Support\Facades\Storage;
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

  public function getModulos(Request $request)
  {
    try {
      $columns = [
        'id',
        'codigo_modulo',
        'marca',
        'modelo',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'stock_total',
        'fecha_registro',
        'estado'
      ];

      $query = Modulo::query();

      if (!empty($request->search['value'])) {
        $search = $request->search['value'];
        $query->where(function ($q) use ($search) {
          $q->where('codigo_modulo', 'like', "%{$search}%")
            ->orWhere('marca', 'like', "%{$search}%")
            ->orWhere('modelo', 'like', "%{$search}%")
            ->orWhere('descripcion', 'like', "%{$search}%");
        });
      }

      $totalFiltered = $query->count();

      $modulos = $query
        ->offset($request->start)
        ->limit($request->length)
        ->orderBy($columns[$request->order[0]['column']] ?? 'id', $request->order[0]['dir'] ?? 'desc')
        ->get();

      $data = [];
      foreach ($modulos as $index => $modulo) {
        $data[] = [
          'DT_RowIndex'     => $request->start + $index + 1,
          'codigo_modulo'   => $modulo->codigo_modulo,
          'marca'           => $modulo->marca,
          'modelo'          => $modulo->modelo,
          'precio_compra'   => '$' . number_format($modulo->precio_compra, 2),
          'precio_venta'    => '$' . number_format($modulo->precio_venta, 2),
          'stock_total'     => $modulo->stock_total,
          'fecha_registro'  => $modulo->fecha_registro,
          'estado'          => $modulo->estado ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>',
          'acciones'        => '
            <div class="btn-group">
                <a href="' . route('modulos.edit', $modulo->id) . '" class="btn btn-sm btn-warning me-1">
                    <i class="bx bx-edit"></i>
                </a>
                <button class="btn btn-sm btn-danger delete-modulo" data-id="' . $modulo->id . '">
                    <i class="bx bx-trash"></i>
                </button>
            </div>',
        ];
      }


      return response()->json([
        'draw'            => intval($request->draw),
        'recordsTotal'    => Modulo::count(),
        'recordsFiltered' => $totalFiltered,
        'data'            => $data,
      ]);
    } catch (\Throwable $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => $e->getFile()
      ], 500);
    }
  }


  public function create()
  {


    return view('content.modulo.new-modulo');
  }

  public function store(Request $request)
  {
    Log::info('Inicio de store modulo', $request->all());

    $validator = Validator::make($request->all(), [
      'codigo_modulo' => 'required|unique:modulos|max:20',
      'modelo' => 'required|max:50',
      'descripcion' => 'nullable',
      'precio_compra' => 'required|numeric|min:0',
      'precio_venta' => 'required|numeric|min:0',
      'stock_total' => 'required|integer|min:0',
      'stock_minimo' => 'required|integer|min:0',
      'fecha_registro' => 'required|date',
      'estado' => 'required|boolean',
      'imagenes.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
      'imagen_principal' => 'nullable|integer'
    ], [
      'codigo_modulo.unique' => 'El código del módulo ya existe',
      'precio_venta.gte' => 'El precio de venta debe ser mayor o igual al precio de compra',
      'stock_minimo.lte' => 'El stock mínimo no puede ser mayor al stock total',
      'imagenes.*.max' => 'Cada imagen no debe superar los 2MB',
      'imagenes.*.image' => 'Cada archivo debe ser una imagen válida',
      'imagenes.*.mimes' => 'Las imágenes deben estar en formato: jpeg, png, jpg o gif'
    ]);
    if ($validator->fails()) {
      Log::warning('Errores de validación', $validator->errors()->toArray());

      return response()->json([
        'success' => false,
        'errors' => $validator->errors()
      ], 422);
    }

    DB::beginTransaction();

    try {
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

      Log::info('Módulo creado', ['modulo_id' => $modulo->id]);

      if ($request->hasFile('imagenes')) {
        $files = $request->file('imagenes');
        Log::info('Archivos recibidos', ['cantidad' => count($files)]);
        $principalIndex = $request->input('imagen_principal');

        // Carpeta destino física en public/storage/modulos
        $destinationPath = public_path('storage/modulos');

        // Crear carpeta si no existe
        if (!file_exists($destinationPath)) {
          mkdir($destinationPath, 0755, true);
        }

        foreach ($files as $index => $file) {
          $filename = time() . '_' . $file->getClientOriginalName();

          // Mover archivo a public/storage/modulos
          $file->move($destinationPath, $filename);

          Log::info('Guardando imagen', [
            'filename' => $filename,
            'mime' => $file->getClientMimeType(),
            'principal' => ($index == $principalIndex) ? 1 : 0
          ]);

          $modulo->imagenes()->create([
            'nombre_archivo' => $filename,
            'mime_type' => $file->getClientMimeType(),
            'imagen_data' => null,
            'es_principal' => ($index == $principalIndex) ? 1 : 0,
          ]);
        }
      } else {
        Log::info('No se recibieron imágenes');
      }


      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Módulo registrado correctamente',
        'data' => $modulo
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      Log::error('Error al registrar módulo', ['error' => $e->getMessage()]);

      return response()->json([
        'success' => false,
        'message' => 'Error al registrar el módulo: ' . $e->getMessage()
      ], 500);
    }
  }




  public function edit(Modulo $modulo)
  {
    $modulo->load(['imagenes' => function ($q) {
      $q->orderByDesc('es_principal'); // Principal primero
    }]);
    $imagenes = \App\Models\ModuloImagen::where('modulo_id', $modulo->id)->get();


    return view('content.modulo.edit', compact('modulo', 'imagenes'));
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
      'estado' => 'required|boolean',
      'imagen_principal' => 'nullable|exists:modulo_imagenes,id' // validamos que exista
    ]);

    try {
      $modulo->update($validated);

      // Si se seleccionó una imagen principal
      if ($request->filled('imagen_principal')) {
        // Desmarcar todas como no principal
        \App\Models\ModuloImagen::where('modulo_id', $modulo->id)
          ->update(['es_principal' => false]);

        // Marcar la nueva como principal
        \App\Models\ModuloImagen::where('id', $request->imagen_principal)
          ->where('modulo_id', $modulo->id)
          ->update(['es_principal' => true]);
      }

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
    } catch (\Illuminate\Database\QueryException $e) {
      // Verifica si el error es por restricción de clave foránea
      if ($e->getCode() == '23000') {
        return response()->json([
          'success' => false,
          'message' => 'No se puede eliminar el módulo porque está relacionado con una o más cotizaciones.'
        ], 409); // 409 Conflict
      }

      // Otro error de base de datos
      return response()->json([
        'success' => false,
        'message' => 'Error en la base de datos al intentar eliminar el módulo: ' . $e->getMessage()
      ], 500);
    } catch (\Exception $e) {
      // Cualquier otro error general
      return response()->json([
        'success' => false,
        'message' => 'Error al eliminar el módulo: ' . $e->getMessage()
      ], 500);
    }
  }

  public function destroyImagen(Request $request, $id)
  {
    try {
      $imagen = ModuloImagen::findOrFail($id);

      // Eliminar el archivo de la imagen
      if (Storage::exists('public/modulos/' . $imagen->nombre_archivo)) {
        Storage::delete('public/modulos/' . $imagen->nombre_archivo);
      }

      // Eliminar el registro de la base de datos
      $imagen->delete();

      // Verificar si es una petición AJAX
      if ($request->expectsJson()) {
        return response()->json([
          'success' => true,
          'message' => 'Imagen eliminada correctamente.',
        ]);
      }

      // Fallback en caso de llamada desde formulario tradicional
      return redirect()->route('modulos.edit', $imagen->modulo_id)
        ->with('success', 'Imagen eliminada correctamente.');
    } catch (\Exception $e) {
      if ($request->expectsJson()) {
        return response()->json([
          'success' => false,
          'message' => 'Ocurrió un error al eliminar la imagen.',
          'error' => $e->getMessage()
        ], 500);
      }

      return back()->with('error', 'No se pudo eliminar la imagen.');
    }
  }


  public function uploadImagenes(Request $request, Modulo $modulo)
  {
    $request->validate([
      'imagenes.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120' // Hasta 5MB
    ]);

    try {
      $urls = [];

      // Ruta destino: C:\xampp\htdocs\eventosnain\public\storage\modulos
      $destinationPath = public_path('storage/modulos');

      // Crear carpeta si no existe
      if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
      }

      foreach ($request->file('imagenes') as $file) {
        $filename = time() . '_' . $file->getClientOriginalName();

        // Mover archivo a la ruta física
        $file->move($destinationPath, $filename);

        // Guardar en base de datos
        $modulo->imagenes()->create([
          'nombre_archivo' => $filename,
          'mime_type' => $file->getClientMimeType(),
          'imagen_data' => null,
          'es_principal' => false
        ]);

        // Agregar la URL accesible públicamente
        $urls[] = asset('storage/modulos/' . $filename);
      }

      return response()->json([
        'success' => true,
        'message' => 'Imágenes subidas correctamente.',
        'urls' => $urls
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
      ], 500);
    }
  }
}
