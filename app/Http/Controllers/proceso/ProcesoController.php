<?php

namespace App\Http\Controllers\proceso;
use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProcesoController extends Controller
{
   public function index(Request $request)
{
    $search = $request->input('search');

    // Define la cuota mensual
    $cuotaMensual = 500;

    // Fecha actual (puedes ajustarla por mes si lo necesitas)
    $mesActual = now()->format('Y-m');

    // Traer cotizaciones aprobadas filtradas
    $query = DB::table('cliente')
        ->join('cotizaciones', 'cliente.id', '=', 'cotizaciones.cliente_id')
        ->join('servicios', 'cotizaciones.id_servicio', '=', 'servicios.id')
        ->join('users', 'cotizaciones.user_id', '=', 'users.id')
        ->where('cotizaciones.estado', 'aprobada')
        ->whereRaw("DATE_FORMAT(cotizaciones.fecha_emision, '%Y-%m') = ?", [$mesActual])
        ->select(
            'cliente.*',
            'cotizaciones.codigo_cotizacion',
            'cotizaciones.total_con_igv',
            'cotizaciones.subtotal_sin_igv',
            'cotizaciones.user_id as vendedor_id',
            'users.name as vendedor_nombre',
            'servicios.nombre as servicio_nombre',
            'cotizaciones.fecha_emision'
        )
        ->distinct();

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('cliente.nombre', 'like', "%{$search}%")
              ->orWhere('cliente.empresa', 'like', "%{$search}%")
              ->orWhere('cliente.telefono', 'like', "%{$search}%")
              ->orWhere('cliente.email', 'like', "%{$search}%")
              ->orWhere('servicios.nombre', 'like', "%{$search}%")
              ->orWhere('cotizaciones.codigo_cotizacion', 'like', "%{$search}%")
              ->orWhere('users.name', 'like', "%{$search}%");
        });
    }

    $clientes = $query->paginate(10);

    // Calcular total vendido por vendedor en el mes
    $totalesPorVendedor = DB::table('cotizaciones')
        ->select('user_id', DB::raw('SUM(subtotal_sin_igv) as total_vendido'))
        ->where('estado', 'aprobada')
        ->whereRaw("DATE_FORMAT(fecha_emision, '%Y-%m') = ?", [$mesActual])
        ->groupBy('user_id')
        ->pluck('total_vendido', 'user_id');

    // Agregar comisión y avance por cuota a cada cliente
    foreach ($clientes as $cliente) {
        $totalVendedor = $totalesPorVendedor[$cliente->vendedor_id] ?? 0;
        $cliente->total_vendedor_mes = $totalVendedor;
        $cliente->porcentaje_cuota = min(100, round(($totalVendedor / $cuotaMensual) * 100, 2));

        // Solo gana comisión si pasó los 50,000
        if ($totalVendedor >= $cuotaMensual) {
            $cliente->comision = round($cliente->subtotal_sin_igv * 0.025, 2);
        } else {
            $cliente->comision = 0.00;
        }
    }

    

    return view('content.proceso.index', [
        'clientes' => $clientes,
        'search' => $search
    ]);
}


public function comision()
{
    $cuotaMensual = 500;
    $mesActual = now()->format('Y-m');

    $comisiones = DB::table('cotizaciones')
        ->join('users', 'cotizaciones.user_id', '=', 'users.id')
        ->where('cotizaciones.estado', 'aprobada')
        ->whereRaw("DATE_FORMAT(cotizaciones.fecha_emision, '%Y-%m') = ?", [$mesActual])
        ->select(
            'users.id as vendedor_id',
            'users.name as vendedor_nombre',
            DB::raw('SUM(cotizaciones.subtotal_sin_igv) as total_vendido'),
            DB::raw('COUNT(cotizaciones.id) as total_cotizaciones')
        )
        ->groupBy('users.id', 'users.name')
        ->paginate(10);

    // Calcular comisión y porcentaje por cuota
    foreach ($comisiones as $vendedor) {
        $vendedor->porcentaje_cuota = min(100, round(($vendedor->total_vendido / $cuotaMensual) * 100, 2));

        // Solo comisiona si supera la cuota
        if ($vendedor->total_vendido >= $cuotaMensual) {
            $vendedor->total_comision = round($vendedor->total_vendido * 0.025, 2);
        } else {
            $vendedor->total_comision = 0.00;
        }
    }

    return view('content.comisiones.index', [
        'comisiones' => $comisiones
    ]);
}




}