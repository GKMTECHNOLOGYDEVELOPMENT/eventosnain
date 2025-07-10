<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $cotizacion->codigo_cotizacion }}</title>
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
            padding: 4.5rem 20px;
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



        <table class="w-full table-auto text-xs mt-6">
            <thead style="background-color: #D32F2F; color: white;" class="uppercase font-bold">
                <tr>
                    <th class="p-2 rounded-tl-lg">N°</th>
                    <th class="p-2">Marca</th>
                    <th class="p-2">Modelo</th>
                    <th class="p-2">Descripción</th>
                    <th class="p-2">Cantidad</th>
                    <th class="p-2">Precio</th>
                    <th class="p-2 rounded-tr-lg">Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($cotizacion->productos as $item)
                    {{-- Fila de datos --}}
                    <tr class="border-t border-gray-300 uppercase">
                        <td class="p-2 text-center align-top">{{ $loop->iteration }}</td>
                        <td class="p-2 text-center align-top">{{ $item->modulo->marca ?? '--' }}</td>
                        <td class="p-2 text-center align-top">{{ $item->modulo->codigo_modulo }}</td>
                        <td class="p-2 text-justify align-top">{{ $item->modulo->descripcion }}
                        </td>
                        <td class="p-2 text-center align-top">{{ $item->cantidad }}</td>
                        <td class="p-2 text-center align-top">${{ number_format($item->precio_unitario, 2) }}</td>
                        <td class="p-2 text-center align-top">${{ number_format($item->subtotal, 2) }}</td>
                    </tr>

                    {{-- Fila de imágenes debajo --}}
                    <tr>
                        <td colspan="7" class="py-2">
                            <div class="flex justify-center gap-6">
                                @foreach ($item->imagenes_base64 ?? [] as $imgBase64)
                                    <div style="width: 250px; height: 160px; overflow: hidden; border-radius: 6px;">
                                        <img src="{{ $imgBase64 }}" alt="img"
                                            style="width: 100%; height: 100%;">
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <div class="mt-8 w-1/6 ml-auto text-xs">
            <table class="w-full">
                <tr>
                    <td class="text-right font-semibold">Subtotal:</td>
                    <td class="text-right">${{ number_format($cotizacion->subtotal_sin_igv, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right font-semibold">IGV (18%):</td>
                    <td class="text-right">${{ number_format($cotizacion->igv, 2) }}</td>
                </tr>
                <tr class="">
                    <td class="text-right font-bold">Total:</td>
                    <td class="text-right font-bold">${{ number_format($cotizacion->total_con_igv, 2) }}</td>
                </tr>
            </table>
        </div>
        @php
            $totalProductos = count($cotizacion->productos);
            $paddingTopCondiciones = match (true) {
                $totalProductos === 1 => '30px',
                $totalProductos === 2 => '30px',
                $totalProductos === 3 => '120px',
                default => '0',
            };
        @endphp

        <!-- Condiciones Comerciales FUERA del footer -->
        <div class="text-xs" style="padding-top: {{ $paddingTopCondiciones }};">
            <div class="w-full px-4 py-2 uppercase font-bold text-white rounded-t-md"
                style="background-color: #D32F2F;">
                Condiciones Comerciales
            </div>
            <div class="px-4 py-3 rounded-b-md leading-relaxed">
                @if (!empty($cotizacion->observaciones))
                    <p class="whitespace-pre-line uppercase">{{ $cotizacion->observaciones }}</p>
                @else
                    <div style="height: 80px;"></div>
                @endif
            </div>
        </div>
    </main>
    <footer style="page-break-inside: avoid; width: 100%; padding-top: 140px;">
        <!-- NUESTRAS CUENTAS BANCARIAS justo debajo -->
        <div class="w-full px-4 uppercase font-bold text-black text-xs">
            NUESTRAS CUENTAS BANCARIAS
        </div>
        <div class="flex flex-col md:flex-row gap-4 text-xs">
            <!-- BCP -->
            <div class="flex-1 p-3">
                <div class="flex items-center gap-2">
                    <img src="{{ $logoBCP }}" alt="BCP Logo"
                        style="width: 100px; height: 60px; object-fit: contain;">
                    <div class="leading-relaxed">
                        <p><strong></strong>191-2264695-0-05</p>
                        <p style="white-space: nowrap;"><strong>CCI:</strong> 002-19100226469500559</p>
                    </div>
                </div>
            </div>

            <!-- BBVA -->
            <div class="flex-1 p-3">
                <div class="flex items-center gap-2">
                    <img src="{{ $logoBBVA }}" alt="BBVA Logo"
                        style="width: 100px; height: 60px; object-fit: contain;">
                    <div class="leading-relaxed">
                        <p><strong></strong>0011-0124-01100035752</p>
                        <p style="white-space: nowrap;"><strong>CCI:</strong> 011-124-000100035752-53</p>
                    </div>
                </div>
            </div>

            <!-- Interbank -->
            <div class="flex-1 p-3">
                <div class="flex items-center gap-2">
                    <img src="{{ $logoInterbank }}" alt="Interbank Logo"
                        style="width: 100px; height: 60px; object-fit: contain;">
                    <div class="leading-relaxed">
                        <p><strong></strong>200-3004670297</p>
                        <p style="white-space: nowrap;"><strong>CCI:</strong> 003-200-003004670297-30</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>


</body>

</html>
