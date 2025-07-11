<?php

namespace App\Exports;

use App\Models\Cliente;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientesExport implements FromCollection, WithHeadings
{
    protected $servicio;
    protected $evento_id;

    public function __construct($servicio, $evento_id)
    {
        $this->servicio = $servicio;
        $this->evento_id = $evento_id;
    }

    public function collection()
    {
        Log::debug('[ClientesExport] Iniciando exportación con filtros', [
            'servicio' => $this->servicio,
            'evento_id' => $this->evento_id,
        ]);

        $query = Cliente::with('evento');

        if (!empty($this->servicio)) {
            $query->where('servicios', $this->servicio);
        }

        if (!empty($this->evento_id)) {
            $query->where('events_id', $this->evento_id);
        }

        $clientes = $query->get();

        Log::info('[ClientesExport] Clientes encontrados para exportar', [
            'total' => $clientes->count()
        ]);

        return $clientes->map(function ($cliente) {
            return [
                $cliente->id,
                $cliente->nombre,
                $cliente->empresa,
                $cliente->telefono,
                $cliente->servicios,
                $cliente->status,
                optional($cliente->evento)->title ?? 'Sin evento',
                $cliente->correo,
                $cliente->whatsapp,
                $cliente->llamada,
                $cliente->reunion,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Empresa',
            'Teléfono',
            'Servicio',
            'Estado',
            'Evento',
            'Correo',
            'WhatsApp',
            'Llamada',
            'Reunión',
        ];
    }
}
