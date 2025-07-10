<?php

use App\Http\Controllers\Client\Client;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\modulo\ModuloController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::get('/cliente', [Client::class, 'getAll']);
Route::get('/api/modulos', [ModuloController::class, 'getModulos'])->name('api.modulos');
