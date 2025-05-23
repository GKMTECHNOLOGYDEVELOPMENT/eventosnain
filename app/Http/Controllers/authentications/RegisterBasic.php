<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterBasic extends Controller
{
  public function index()
  {
    $rol = Rol::all();
    return view('content.authentications.auth-register-basic', ['rol' => $rol]);
  }

  public function register(Request $request)
  {
    // Validar los datos del formulario
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8|confirmed',
      'rol_id' => 'required|exists:rol,rol_id',
    ]);

    if ($validator->fails()) {
      return redirect()->back()
        ->withErrors($validator)
        ->withInput();
    }

    // Crear el usuario
    $user = User::create([
      'name' => $request->input('name'),
      'email' => $request->input('email'),
      'password' => Hash::make($request->input('password')),
      'rol_id' => $request->rol_id,
    ]);

    // Aquí podrías redirigir al usuario a la página de inicio de sesión
    return redirect()->back()->with('success', 'Usuario Registrador con éxito.');
  }
}
