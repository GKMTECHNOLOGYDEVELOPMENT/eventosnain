<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cotización - {{ $cotizacion->codigo_cotizacion }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @page {
            margin: 0;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            padding: 60px 20px 20px 20px;
            /* top, right, bottom, left */
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: url('{{ $logoFondo }}') no-repeat center center;
            background-size: cover;
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
    <div class="background"></div>
    <main>

        <div class="grid grid-cols-2 gap-4 text-xs mb-4 uppercase">
            <div>
                @if (!empty($cotizacion->cliente->nombre))
                    <p><span class="font-bold">Cliente:</span> {{ strtoupper($cotizacion->cliente->nombre) }}</p>
                @endif
                @if (!empty($cotizacion->cliente->empresa))
                    <p><span class="font-bold">Empresa:</span> {{ strtoupper($cotizacion->cliente->empresa) }}</p>
                @endif
                @if (!empty($cotizacion->cliente->telefono))
                    <p><span class="font-bold">Teléfono:</span> {{ $cotizacion->cliente->telefono }}</p>
                @endif
                @if (!empty($cotizacion->cliente->email))
                    <p><span class="font-bold">Email:</span> {{ $cotizacion->cliente->email }}</p>
                @endif
                @if (!empty($cotizacion->cliente->direccion))
                    <p><span class="font-bold">Dirección:</span> {{ strtoupper($cotizacion->cliente->direccion) }}</p>
                @endif
            </div>
            <div class="text-right">
                <p><span class="font-bold">Código:</span> {{ $cotizacion->codigo_cotizacion }}</p>
                <p><span class="font-bold">Fecha:</span> {{ $cotizacion->fecha_emision->format('d/m/Y') }}</p>
                @if (!empty($cotizacion->encargado?->name))
                    <p><span class="font-bold">Encargado:</span> {{ strtoupper($cotizacion->encargado->name) }}</p>
                @endif
            </div>
        </div>

        @php
            $todasImagenes = [];
        @endphp

        <!-- Tabla de productos -->
        <table class="w-full table-auto text-xs border" style="border-collapse: collapse; border: 1px solid #D32F2F;">
            <thead style="background-color: #D32F2F; color: white;" class="uppercase font-bold">
                <tr>
                    <th class="p-2" style="border: 1px solid #D32F2F;">N°</th>
                    <th class="p-2" style="border: 1px solid #D32F2F;">Marca</th>
                    <th class="p-2" style="border: 1px solid #D32F2F;">Modelo</th>
                    <th class="p-2" style="border: 1px solid #D32F2F;">Descripción</th>
                    <th class="p-2" style="border: 1px solid #D32F2F;">Cantidad</th>
                    <th class="p-2" style="border: 1px solid #D32F2F;">Precio</th>
                    <th class="p-2" style="border: 1px solid #D32F2F;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cotizacion->productos as $item)
                    <tr class="text-center uppercase" style="vertical-align: middle;">
                        <td class="p-2" style="border: 1px solid #D32F2F;">{{ $loop->iteration }}</td>
                        <td class="p-2 uppercase" style="border: 1px solid #D32F2F;">{{ $item->modulo->marca ?? '--' }}
                        </td>
                        <td class="p-2 uppercase" style="border: 1px solid #D32F2F;">{{ $item->modulo->codigo_modulo }}
                        </td>
                        <td class="p-2 uppercase" style="border: 1px solid #D32F2F;">{{ $item->modulo->descripcion }}
                        </td>
                        <td class="p-2" style="border: 1px solid #D32F2F;">{{ $item->cantidad }}</td>
                        <td class="p-2" style="border: 1px solid #D32F2F;">
                            ${{ number_format($item->precio_unitario, 2) }}</td>
                        <td class="p-2" style="border: 1px solid #D32F2F;">${{ number_format($item->subtotal, 2) }}
                        </td>
                    </tr>

                    @php
                        if (!empty($item->imagenes_base64)) {
                            foreach ($item->imagenes_base64 as $img) {
                                $todasImagenes[] = $img;
                            }
                        }
                    @endphp
                @endforeach
            </tbody>
        </table>

        <!-- Totales con ancho 1/6 y bordes definidos -->
        <div class="text-xs mt-2 w-1/6 ml-auto">
            <table class="w-full" style="border-collapse: collapse; border: 1px solid #D32F2F;">
                <tr>
                    <td class="text-right font-semibold p-2" style="border: 1px solid #D32F2F;">Subtotal:</td>
                    <td class="text-right p-2" style="border: 1px solid #D32F2F;">
                        ${{ number_format($cotizacion->subtotal_sin_igv, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right font-semibold p-2" style="border: 1px solid #D32F2F;">IGV:</td>
                    <td class="text-right p-2" style="border: 1px solid #D32F2F;">
                        ${{ number_format($cotizacion->igv, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right font-bold p-2" style="border: 1px solid #D32F2F;">Total:</td>
                    <td class="text-right font-bold p-2" style="border: 1px solid #D32F2F;">
                        ${{ number_format($cotizacion->total_con_igv, 2) }}</td>
                </tr>
            </table>
        </div>



        <!-- Imágenes agrupadas de todos los productos -->
        @if (count($todasImagenes))
            <div class="mt-4">
                <table class="w-full">
                    <tr>
                        <td class="py-2">
                            <div class="flex justify-center flex-wrap gap-6">
                                @foreach ($todasImagenes as $imgBase64)
                                    <div
                                        style="width: 300px; height: 160px; overflow: hidden; border-radius: 8px; box-shadow: 0 1px 5px rgba(0,0,0,0.25);">
                                        <img src="{{ $imgBase64 }}" alt="img"
                                            style="width: 100%; height: 100%;">
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        @endif

    </main>

    @php
        $totalProductos = count($cotizacion->productos);
        $paddingTop = match (true) {
            $totalProductos === 1 => '180px',
            $totalProductos === 2 => '10px',
            $totalProductos === 3 => '120px',
            default => '0',
        };
    @endphp

    <footer style="page-break-inside: avoid; width: 100%; padding: {{ $paddingTop }} 0px 0 0px;">

        <div class="mt-10 text-xs">
            <div class="w-full px-4 py-2 uppercase font-bold text-white rounded-t-md"
                style="background-color: #D32F2F;">
                Condiciones Comerciales
            </div>
            <div class="px-4 py-3 rounded-b-md leading-relaxed">
                @if (!empty($cotizacion->observaciones))
                    <p class="whitespace-pre-line uppercase">{{ $cotizacion->observaciones }}</p>
                @else
                    <div style="height: 80px;"></div> {{-- Espacio vacío si no hay texto --}}
                @endif
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-4 text-xs">
            <!-- BCP -->
            <div class="flex-1 p-3">
                <div class="flex items-center gap-2">
                    <img src="{{ $logoBCP }}" alt="BCP Logo"
                        style="width: 60px; height: 25px; object-fit: contain;">
                    <div class="leading-relaxed">
                        <p><strong></strong>191-2264695-0-05</p>
                        <p style="white-space: nowrap;"><strong>CCI:</strong>002-19100226469500559</p>
                    </div>
                </div>
            </div>

            <!-- BBVA -->
            <div class="flex-1 p-3">
                <div class="flex items-center gap-2">
                    <img src="{{ $logoBBVA }}" alt="BBVA Logo"
                        style="width: 60px; height: 25px; object-fit: contain;">
                    <div class="leading-relaxed">
                        <p><strong></strong>0011-0124-01100035752</p>
                        <p style="white-space: nowrap;"><strong>CCI:</strong>011-124-000100035752-53</p>
                    </div>
                </div>
            </div>

            <!-- Interbank -->
            <div class="flex-1 p-3">
                <div class="flex items-center gap-2">
                    <img src="{{ $logoInterbank }}" alt="Interbank Logo"
                        style="width: 60px; height: 25px; object-fit: contain;">
                    <div class="leading-relaxed">
                        <p><strong></strong>200-3004670297</p>
                        <p style="white-space: nowrap;"><strong>CCI:</strong>003-200-003004670297-30</p>
                    </div>
                </div>
            </div>
        </div>


    </footer>

</body>

</html>
