<?php

namespace App\Http\Controllers\Seguimiento;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
    public function index()
    {

        $eventos = DB::table('salida')
            ->select('id', 'title')
            ->orderBy('start', 'desc')
            ->get();
        return view('content.dashboard.seguimiento-analytics');
    }
}
