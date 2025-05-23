<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;

class AccountSettingsAccount extends Controller
{
  public function index($id = null)
  {
      $user = Auth::user();

      // Si se proporciona un ID en la URL, buscar el usuario por ese ID
      if ($id) {
          $user = User::find($id);
          if (!$user) {
              return redirect()->route('home')->withErrors(['error' => 'User not found.']);
          }
      }

      return view('content.pages.pages-account-settings-account', compact('user'));
  }



public function update(Request $request)
  {
      $user = Auth::user();

      $request->validate([
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
          'current_password' => 'nullable|current_password',
          'password' => 'nullable|confirmed|min:8',
          'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      ]);

      $user->name = $request->input('name');
      $user->email = $request->input('email');

      if ($request->filled('current_password')) {
          if (!Hash::check($request->input('current_password'), $user->password)) {
              return redirect()->back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
          }

          $user->password = Hash::make($request->input('password'));
      }

      if ($request->hasFile('photo')) {
          // Eliminar la foto anterior si existe
          if ($user->photo && Storage::exists($user->photo)) {
              Storage::delete($user->photo);
          }

          // Subir la nueva foto
          $path = $request->file('photo')->store('photos', 'public');
          $user->photo = $path;
      }

      $user->save();

      return redirect()->route('pages-account-settings-account', ['id' => $user->id])->with('success', 'Perfil actualizado correctamente.');
  }



}
