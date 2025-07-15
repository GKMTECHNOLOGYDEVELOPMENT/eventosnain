<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Analytics extends Controller
{

public function index()
{
    $eventos = DB::table('salida')
        ->select('id', 'title')
        ->orderBy('start', 'desc')
        ->get();

    $usuarios = DB::table('users')
        ->select('id', 'name')
        ->orderBy('name', 'asc')
        ->get();

    return view('content.dashboard.dashboards-analytics', compact('eventos', 'usuarios'));
}




}
