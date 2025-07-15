<?php

namespace App\Http\Controllers\Calendario;

use App\Http\Controllers\Controller;
use App\Models\Informacion;
use Illuminate\Http\Request;
use App\Models\Llamada;
use App\Models\Reunion;
use App\Models\Event;
use App\Models\Salida;
use App\Models\SalidaUser;
use Carbon\Carbon;
use DB;
// use Event;
use Illuminate\Support\Facades\Log;

class Calendario extends Controller
{

public function index(){

    return view('content.calendario.index');
}
}
