<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;

class ForgotPasswordBasic extends Controller
{
  // Mostrar el formulario de solicitud de restablecimiento de contraseña
  public function index()
  {
    return view('content.authentications.auth-forgot-password-basic');
  }

  // Enviar el enlace de restablecimiento de contraseña
  public function sendResetLink(Request $request)
  {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
      ? redirect()->route('auth-forgot-password')->with('status', __($status))
      : redirect()->route('auth-forgot-password')->withErrors(['email' => __($status)]);
  }



  // Mostrar el formulario de restablecimiento de contraseña
  public function showResetForm($token)
  {
    return view('content.authentications.auth-reset-password-basic', ['token' => $token]);
  }

  // Procesar el restablecimiento de contraseña
  public function reset(Request $request)
  {
    $request->validate([
      'token' => 'required',
      'email' => 'required|email',
      'password' => 'required|confirmed|min:8',
    ]);

    $status = Password::reset(
      $request->only('email', 'password', 'password_confirmation', 'token'),
      function ($user, $password) {
        $user->password = bcrypt($password);
        $user->save();
      }
    );

    return $status === Password::PASSWORD_RESET
      ? redirect()->route('auth-login-basic')->with('status', __($status))
      : back()->withErrors(['email' => __($status)]);
  }
}
