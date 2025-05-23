<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización {{ $cotizacion->codigo_cotizacion }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
                font-size: 12pt;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-after: always;
            }

            .header {
                text-align: center;
                margin-bottom: 20px;
                border-bottom: 2px solid #333;
                padding-bottom: 10px;
            }

            .info-cliente {
                margin-bottom: 30px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            .totals {
                margin-top: 20px;
                float: right;
                width: 300px;
            }

            .footer {
                margin-top: 50px;
                padding-top: 20px;
                border-top: 1px solid #333;
                text-align: center;
                font-size: 10pt;
            }
        }
    </style>
</head>

<body>
    <div class="no-print text-center py-4">
        <button onclick="window.print()" class="btn btn-primary me-2">
            <i class="fas fa-print me-2"></i> Imprimir
        </button>
        <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a>
    </div>

    <div class="container">
        <div class="header">
            <h2>COTIZACIÓN</h2>
            <h3>N° {{ $cotizacion->codigo_cotizacion }}</h3>
            <p>Fecha: {{ $cotizacion->fecha_emision->format('d/m/Y') }}</p>
        </div>

        <div class="info-cliente">
            <h4>Información del Cliente</h4>
            <p><strong>Nombre:</strong> {{ $cotizacion->cliente->nombre }}</p>
            <p><strong>Empresa:</strong> {{ $cotizacion->cliente->empresa }}</p>
            <p><strong>Contacto:</strong> {{ $cotizacion->cliente->email }} | {{ $cotizacion->cliente->telefono }}</p>
            <p><strong>Validez:</strong> {{ $cotizacion->validez }} días</p>
            <p><strong>Condiciones:</strong> {{ ucfirst(str_replace('_', ' ', $cotizacion->condiciones_comerciales)) }}
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Ítem</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>P. Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cotizacion->productos as $index => $producto)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $producto->modulo->codigo_modulo }}</strong><br>
                        {{ $producto->modulo->marca }} {{ $producto->modulo->modelo }}<br>
                        {{ $producto->modulo->descripcion }}
                    </td>
                    <td>{{ $producto->cantidad }}</td>
                    <td>${{ number_format($producto->precio_unitario, 2) }}</td>
                    <td>${{ number_format($producto->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr>
                    <th>Subtotal:</th>
                    <td>${{ number_format($cotizacion->subtotal_sin_igv, 2) }}</td>
                </tr>
                <tr>
                    <th>IGV (18%):</th>
                    <td>${{ number_format($cotizacion->igv, 2) }}</td>
                </tr>
                <tr>
                    <th>TOTAL:</th>
                    <td><strong>${{ number_format($cotizacion->total_con_igv, 2) }}</strong></td>
                </tr>
            </table>
        </div>

        @if($cotizacion->observaciones)
        <div class="observaciones mt-4">
            <h4>Observaciones</h4>
            <p>{{ $cotizacion->observaciones }}</p>
        </div>
        @endif

        <div class="footer">
            <p>{{ config('app.name') }} - {{ config('app.address') }}</p>
            <p>Teléfono: {{ config('app.phone') }} | Email: {{ config('app.email') }}</p>
        </div>
    </div>

    <script>
        // Auto-imprimir si se accede directamente a la URL
        if (window.location.search.includes('autoprint=true')) {
            window.print();
        }

        // Cerrar después de imprimir si se abrió en nueva pestaña
        window.onafterprint = function() {
            if (window.location.search.includes('autoprint=true')) {
                window.close();
            }
        };
    </script>
</body>

</html>