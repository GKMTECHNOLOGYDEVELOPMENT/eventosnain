<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginBasic extends Controller
{
  protected $redirectTo = '/'; // Redirige a la ruta '/' después de iniciar sesión

  // Muestra el formulario de inicio de sesión
  public function index()
  {
    return view('content.authentications.auth-login-basic');
  }

  // Maneja la solicitud de inicio de sesión
  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials, $request->filled('remember'))) {
      // Redirige a la ruta deseada después del inicio de sesión
      return redirect()->intended($this->redirectTo);
    }

    // Si las credenciales no coinciden
    return back()->withErrors([
      'login' => 'El correo electrónico o la contraseña son incorrectos.',
    ])->withInput(); // Mantener los valores ingresados en el formulario
  }

  // Maneja el logout
  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/auth/login-basic');
  }
}
