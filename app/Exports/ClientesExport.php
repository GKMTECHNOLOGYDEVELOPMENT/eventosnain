<?php

namespace App\Exports;

use App\Models\Cliente;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientesExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithCustomStartCell
{
    private $clientes;

    public function __construct($servicio = null, $evento_id = null)
    {
        $query = Cliente::with('evento');

        if ($servicio) {
            $query->where('servicios', 'like', '%' . $servicio . '%');
        }

        if ($evento_id) {
            $query->where('events_id', $evento_id);
        }

        $this->clientes = $query->get();
    }

    public function collection()
    {
        return $this->clientes;
    }

    public function map($c): array
    {
        static $i = 1;
        return [
            $i++,
            $c->nombre,
            $c->empresa,
            $c->telefono,
            $c->servicios,
            $c->evento->title ?? '—',
            $c->correo,
            $c->whatsapp,
            $c->llamada,
            $c->reunion,
        ];
    }

    public function headings(): array
    {
        return ['#', 'Nombre', 'Empresa', 'Teléfono', 'Servicio', 'Evento', 'Correo', 'WhatsApp', 'Llamada', 'Reunión'];
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'REPORTE DE CLIENTES');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', 'Generado el: ' . now()->format('d/m/Y H:i'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        $sheet->getStyle('A4:J4')->getFont()->setBold(true);

        return [];
    }
}
