<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cotización - {{ $cotizacion->codigo_cotizacion }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        html {
            background: url('{{ $logoFondo }}') no-repeat center center;
            background-size: 100% 100%;
            width: 100%;
            height: 100%;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 140px 40px 40px 40px;
            /* top, right, bottom, left */
        }


        .img-box {
            max-width: 100px;
            height: auto;
        }

        .totales td {
            padding: 4px;
        }
    </style>
</head>

<body>
    <div class="grid grid-cols-2 gap-4 text-xs mb-4 uppercase">
        <div>
            <p><span class="font-bold">Cliente:</span> {{ strtoupper($cotizacion->cliente->nombre ?? '--') }}</p>
            <p><span class="font-bold">Empresa:</span> {{ strtoupper($cotizacion->cliente->empresa ?? '--') }}</p>
            <p><span class="font-bold">Teléfono:</span> {{ strtoupper($cotizacion->cliente->telefono ?? '--') }}</p>
            <p><span class="font-bold">Email:</span> {{ strtoupper($cotizacion->cliente->email ?? '--') }}</p>
            <p><span class="font-bold">Dirección:</span> {{ strtoupper($cotizacion->cliente->direccion ?? '--') }}</p>
        </div>
        <div class="text-right">
            <p><span class="font-bold">Código:</span> {{ strtoupper($cotizacion->codigo_cotizacion) }}</p>
            <p><span class="font-bold">Fecha:</span> {{ $cotizacion->fecha_emision->format('d/m/Y') }}</p>
            <p><span class="font-bold">Encargado:</span> {{ strtoupper($cotizacion->encargado ?? '--') }}</p>
        </div>
    </div>


    <table class="w-full table-auto text-xs mt-6">
        <thead style="background-color: #D32F2F; color: white;" class="uppercase font-bold">
            <tr>
                <th class="p-2 rounded-tl-lg">Imagen</th>
                <th class="p-2">Modelo</th>
                <th class="p-2">Descripción</th>
                <th class="p-2">Cantidad</th>
                <th class="p-2">P. Unitario</th>
                <th class="p-2 rounded-tr-lg">Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($cotizacion->productos as $item)
                <tr class="border-t border-gray-300">
                    <td class="p-2 text-center uppercase">
                        @php
                            $moduloId = $item->modulo->id;
                            $imagen = \App\Models\ModuloImagen::where('modulo_id', $moduloId)
                                ->where('es_principal', true)
                                ->first();

                            $ruta = $imagen ? public_path('storage/modulos/' . $imagen->nombre_archivo) : null;
                            $imagenBase64 =
                                $ruta && file_exists($ruta)
                                    ? 'data:' .
                                        $imagen->mime_type .
                                        ';base64,' .
                                        base64_encode(file_get_contents($ruta))
                                    : null;
                        @endphp

                        @if ($imagenBase64)
                            <img src="{{ $imagenBase64 }}" alt="{{ $imagen->nombre_archivo }}" class="mx-auto"
                                style="max-height: 120px; object-fit: cover;">
                        @else
                            <span>{{ $imagen->nombre_archivo ?? '--' }}</span>
                        @endif
                    </td>
                    <td class="p-2">{{ $item->modulo->codigo_modulo }}</td>
                    <td class="p-2">{{ $item->modulo->descripcion }}</td>
                    <td class="p-2 text-center">{{ $item->cantidad }}</td>
                    <td class="p-2 text-right">${{ number_format($item->precio_unitario, 2) }}</td>
                    <td class="p-2 text-right">${{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-6 w-1/2 ml-auto text-xs">
        <table class="w-full">
            <tr>
                <td class="text-right font-semibold">Subtotal:</td>
                <td class="text-right">${{ number_format($cotizacion->subtotal_sin_igv, 2) }}</td>
            </tr>
            <tr>
                <td class="text-right font-semibold">IGV (18%):</td>
                <td class="text-right">${{ number_format($cotizacion->igv, 2) }}</td>
            </tr>
            <tr class="border-t border-gray-400">
                <td class="text-right font-bold">Total:</td>
                <td class="text-right font-bold">${{ number_format($cotizacion->total_con_igv, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="text-center text-gray-600 text-xs mt-12">
        <p class="font-bold">GKM TECHNOLOGY S.A.C. - RUC 12345678900</p>
        <p>{{ $cotizacion->observaciones }}</p>
    </div>
</body>

</html>
