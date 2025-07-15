<?php

namespace App\Http\Controllers\Seguimiento;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
 public function index()
{
    // Obtener usuarios que tienen al menos un cliente registrado
    $users = User::select('users.id', 'users.name')
        ->join('cliente', 'users.id', '=', 'cliente.user_id')
        ->groupBy('users.id', 'users.name')
        ->orderBy('users.name', 'asc')
        ->get();
    
    return view('content.dashboard.seguimiento-analytics', compact('users'));
}
public function getClientData(Request $request)
{
    try {
        $query = Cliente::query();
        
        // Aplicar filtros
        $this->applyFilters($query, $request);
        
        // Obtener resultados según los filtros
        $result = $this->getResults($query, $request);
        
        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'labels' => $result['labels'],
            'total' => $result['total'],
            'message' => 'Datos obtenidos correctamente'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'data' => array_fill(0, 12, 0),
            'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            'total' => 0,
            'message' => 'Error al obtener datos: ' . $e->getMessage()
        ], 500);
    }
}

private function applyFilters($query, $request)
{
    // Filtro por usuario
    if ($request->has('user_id') && $request->user_id) {
        $query->where('user_id', $request->user_id);
    }
    
    // Filtro por rango de fechas
    if ($request->has('start_date') && $request->start_date && $request->has('end_date') && $request->end_date) {
        $query->whereBetween('fecharegistro', [$request->start_date, $request->end_date]);
    } else {
        // Si no hay filtro de fechas, mostrar últimos 12 meses
        $query->where('fecharegistro', '>=', now()->subMonths(12));
    }
}

private function getResults($query, $request)
{
    // Determinar si hay un rango de fechas específico
    $hasDateRange = $request->has('start_date') && $request->start_date && $request->has('end_date') && $request->end_date;
    
    if ($hasDateRange) {
        return $this->getDateRangeResults($query, $request->start_date, $request->end_date);
    }
    
    return $this->getDefaultResults($query);
}

private function getDateRangeResults($query, $startDate, $endDate)
{
    $start = Carbon::parse($startDate);
    $end = Carbon::parse($endDate);
    
    // Obtener datos agrupados por mes
    $results = $query->selectRaw('MONTH(fecharegistro) as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('count', 'month')
        ->toArray();
    
    // Generar etiquetas y datos para el rango seleccionado
    $labels = [];
    $data = [];
    $current = $start->copy();
    $total = 0;
    
    while ($current <= $end) {
        $monthNum = $current->month;
        $labels[] = $current->locale('es')->shortMonthName;
        $count = $results[$monthNum] ?? 0;
        $data[] = $count;
        $total += $count;
        $current->addMonth();
    }
    
    return [
        'data' => $data,
        'labels' => $labels,
        'total' => $total
    ];
}

private function getDefaultResults($query)
{
    // Obtener datos de los últimos 12 meses
    $results = $query->selectRaw('MONTH(fecharegistro) as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('count', 'month')
        ->toArray();
    
    // Meses predeterminados
    $labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    $data = array_fill(0, 12, 0);
    $total = 0;
    
    foreach ($results as $month => $count) {
        $data[$month - 1] = $count;
        $total += $count;
    }
    
    return [
        'data' => $data,
        'labels' => $labels,
        'total' => $total
    ];
}


public function getDashboardData(Request $request)
{
    try {
        $userId = $request->input('user_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Aplicar filtros base
        $query = Cliente::query();
        if ($userId) $query->where('user_id', $userId);
        if ($startDate && $endDate) {
            $query->whereBetween('fecharegistro', [$startDate, $endDate]);
        }

        // Datos para gráfica de tipos de cliente
        $tiposCliente = $query->selectRaw('tipo_cliente, COUNT(*) as count')
            ->groupBy('tipo_cliente')
            ->get()
            ->pluck('count', 'tipo_cliente');

        // Datos para embudo de ventas
        $embudoVentas = [
            'contactados' => $query->whereNotNull('llamada')->count(),
            'reuniones' => $query->where('reunion', 'Si')->count(),
            'contratos' => $query->where('contrato', 'Si')->count()
        ];

        // Datos para tiempo de primer contacto
        $tiempoContacto = $query->selectRaw('AVG(DATEDIFF(llamada, fecharegistro)) as promedio_dias')
            ->whereNotNull('llamada')
            ->first()->promedio_dias ?? 0;

        // Datos para efectividad por canal
        $efectividad = [
            'correo' => $query->where('correo', 'Si')->count(),
            'whatsapp' => $query->where('whatsapp', 'Si')->count(),
            'llamada' => $query->where('llamada', 'Si')->count()
        ];

        // Datos para estado de clientes
        $estadoClientes = [
            'activos' => $query->where('status', 'Activo')->count(),
            'inactivos' => $query->where('status', 'Inactivo')->count(),
            'sin_seguimiento' => $query->where('status', 'Pendiente')->count()
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'tipos_cliente' => $tiposCliente,
                'embudo_ventas' => $embudoVentas,
                'tiempo_contacto' => $tiempoContacto,
                'efectividad' => $efectividad,
                'estado_clientes' => $estadoClientes
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener datos: ' . $e->getMessage()
        ], 500);
    }
}

public function getSalesFunnelData(Request $request)
{
    try {
        $query = Cliente::query();
        
        // Aplicar los mismos filtros que en getClientData
        $this->applyFilters($query, $request);
        
        // Obtener datos agrupados por mes
        $results = $query->selectRaw('
            MONTH(fecharegistro) as month,
            SUM(CASE WHEN llamada = "si" OR whatsapp = "si" OR correo = "si" THEN 1 ELSE 0 END) as contactados,
            SUM(CASE WHEN reunion = "si" THEN 1 ELSE 0 END) as reuniones,
            SUM(CASE WHEN contrato = "si" THEN 1 ELSE 0 END) as contratos
        ')
        ->groupBy('month')
        ->orderBy('month')
        ->get();
        
        // Procesar resultados
        $months = [];
        $contactados = [];
        $reuniones = [];
        $contratos = [];
        
        foreach ($results as $row) {
            $monthName = Carbon::create()->month($row->month)->locale('es')->shortMonthName;
            $months[] = $monthName;
            $contactados[] = $row->contactados;
            $reuniones[] = $row->reuniones;
            $contratos[] = $row->contratos;
        }
        
        return response()->json([
            'success' => true,
            'months' => $months,
            'contactados' => $contactados,
            'reuniones' => $reuniones,
            'contratos' => $contratos,
            'message' => 'Datos del embudo obtenidos correctamente'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener datos del embudo: ' . $e->getMessage()
        ], 500);
    }
}

public function getContactEffectivenessData(Request $request)
{
    try {
        $query = Cliente::query();
        
        // Aplicar filtros (usando el mismo método que las otras gráficas)
        $this->applyFilters($query, $request);
        
        // Obtener total de clientes en el filtro
        $totalClients = $query->count();
        
        if ($totalClients === 0) {
            return response()->json([
                'success' => true,
                'contact_initial' => [0, 0, 0],
                'effectiveness' => [0, 0, 0],
                'message' => 'No hay datos para los filtros seleccionados'
            ]);
        }
        
        // Obtener datos de contactos iniciales
        $contactData = $query->selectRaw('
            SUM(CASE WHEN correo = "SI" THEN 1 ELSE 0 END) as email_count,
            SUM(CASE WHEN whatsapp = "SI" THEN 1 ELSE 0 END) as whatsapp_count,
            SUM(CASE WHEN llamada = "SI" THEN 1 ELSE 0 END) as call_count
        ')->first();
        
        // Obtener datos de efectividad (contactos que llevaron a reunión o contrato)
        $effectivenessData = $query->selectRaw('
            SUM(CASE WHEN correo = "SI" AND (reunion = "SI" OR contrato = "SI") THEN 1 ELSE 0 END) as email_effective,
            SUM(CASE WHEN whatsapp = "SI" AND (reunion = "SI" OR contrato = "SI") THEN 1 ELSE 0 END) as whatsapp_effective,
            SUM(CASE WHEN llamada = "SI" AND (reunion = "SI" OR contrato = "SI") THEN 1 ELSE 0 END) as call_effective
        ')->first();
        
        // Calcular porcentajes
        $contactInitial = [
            $totalClients > 0 ? round(($contactData->email_count / $totalClients) * 100) : 0,
            $totalClients > 0 ? round(($contactData->whatsapp_count / $totalClients) * 100) : 0,
            $totalClients > 0 ? round(($contactData->call_count / $totalClients) * 100) : 0
        ];
        
        $effectiveness = [
            $contactData->email_count > 0 ? round(($effectivenessData->email_effective / $contactData->email_count) * 100) : 0,
            $contactData->whatsapp_count > 0 ? round(($effectivenessData->whatsapp_effective / $contactData->whatsapp_count) * 100) : 0,
            $contactData->call_count > 0 ? round(($effectivenessData->call_effective / $contactData->call_count) * 100) : 0
        ];
        
        return response()->json([
            'success' => true,
            'contact_initial' => $contactInitial,
            'effectiveness' => $effectiveness,
            'message' => 'Datos de contactabilidad obtenidos correctamente'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener datos de contactabilidad: ' . $e->getMessage()
        ], 500);
    }
}


public function getClientStatusData(Request $request)
{
    try {
        $query = Cliente::query();
        
        // Aplicar filtros existentes
        $this->applyFilters($query, $request);
        
        // Obtener conteo por estado
        $statusCounts = $query->selectRaw('
            SUM(CASE WHEN status = "PENDIENTE" THEN 1 ELSE 0 END) as pendientes,
            SUM(CASE WHEN status = "EN PROCESO" THEN 1 ELSE 0 END) as en_proceso,
            SUM(CASE WHEN status = "COMPLETADO" THEN 1 ELSE 0 END) as completados,
            COUNT(*) as total
        ')->first();
        
        return response()->json([
            'success' => true,
            'data' => [
                'pendientes' => $statusCounts->pendientes ?? 0,
                'en_proceso' => $statusCounts->en_proceso ?? 0,
                'completados' => $statusCounts->completados ?? 0,
                'total' => $statusCounts->total ?? 0
            ],
            'message' => 'Datos de estado de clientes obtenidos correctamente'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener datos de estado: ' . $e->getMessage()
        ], 500);
    }
}


}
