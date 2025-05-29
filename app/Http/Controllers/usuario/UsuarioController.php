<?php

namespace App\Http\Controllers\usuario;

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
use App\Models\Rol;
use App\Models\Salida;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = \App\Models\User::with('rol')->get();
        return view('content.usuarios.index', compact('usuarios'));
    }
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Rol::all();

        return view('content.usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'telefono' => 'nullable|string|max:100',
            'rol_id' => 'required|integer',
            'photo' => 'nullable|image|max:2048'
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;
        $usuario->rol_id = $request->rol_id;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('usuarios', 'public');
            $usuario->photo = $path;
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }


    public function create()
    {
        $roles = Rol::all();
        return view('content.usuarios.new-usuarios', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'rol_id' => 'required|exists:rol,rol_id',
            'telefono' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'email', 'rol_id', 'telefono');
        $data['password'] = Hash::make($request->password);
        $data['email_password'] = $request->password; // Guarda la contraseña sin cifrar (no recomendado, pero si la quieres)

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('usuarios', 'public');
            $data['photo'] = $path;
        }

        User::create($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }





    public function destroy($id)
    {
        $usuario = User::with(['cotizaciones', 'events', 'eventUsers', 'salidas'])->findOrFail($id);

        if (
            $usuario->cotizaciones->count() > 0 ||
            $usuario->events->count() > 0 ||
            $usuario->eventUsers->count() > 0 ||
            $usuario->salidas->count() > 0
        ) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No se puede eliminar el usuario porque está relacionado con cotizaciones, eventos o salidas.');
        }

        if ($usuario->photo && Storage::disk('public')->exists($usuario->photo)) {
            Storage::disk('public')->delete($usuario->photo);
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
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
