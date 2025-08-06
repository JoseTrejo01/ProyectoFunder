<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Microempresas - Funder</title>
    <style>
        @page {
            margin: 30px 40px;
            footer: pie;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5px;
            margin: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 5px;
        }

        .left-info {
            font-size: 10px;
        }

        .center-title {
            text-align: center;
            flex-grow: 1;
            font-weight: bold;
            font-size: 13px;
        }

        .logo {
            text-align: right;
        }

        .logo img {
            height: 55px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .table th, .table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #f0f0f0;
        }

        .totals {
            font-weight: bold;
            background-color: #e6e6e6;
        }
    </style>
</head>
<body>

{{-- ENCABEZADO --}}
<div class="header">
    <div class="left-info">
        FECHA: {{ now()->format('Y/m/d') }}
    </div>

    <div class="center-title">
        <div>FUNDER</div>
        <div>Reporte de Emprendimientos</div>
    </div>

    <div class="logo">
        <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo">
    </div>
</div>

{{-- TABLA DE DATOS --}}
<table class="table">
    <thead>
        <tr>
            <th>No.</th>
            <th>Caja Rural</th>
            <th>Municipio</th>
            <th>Comunidad</th>
            <th>Fecha de inicio de operaciones</th>
            <th>Socios(as)<br>H / M / Total</th>
            <th>Tipo de Negocio</th>
            <th>Ventas Trimestrales</th>
            <th>Empleos<br>H / M / Total</th>
            <th>Nombre del Técnico</th>
            <th>Fecha de levantamiento</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalSociosH = 0;
            $totalSociosM = 0;
            $totalEmpleosH = 0;
            $totalEmpleosM = 0;
            $count = 1;
        @endphp

        @forelse($emprendimientos as $emp)
            @php
                $socH = $emp->Socios_Hombres ?? 0;
                $socM = $emp->Socios_Mujeres ?? 0;
                $empH = $emp->Empleos_Hombres ?? 0;
                $empM = $emp->Empleos_Mujeres ?? 0;

                $totalSociosH += $socH;
                $totalSociosM += $socM;
                $totalEmpleosH += $empH;
                $totalEmpleosM += $empM;
            @endphp
            <tr>
                <td>{{ $count++ }}</td>
                <td>{{ $emp->Caja_Rural }}</td>
                <td>{{ $emp->municipio->Nombre_Municipio ?? 'N/D' }}</td>
                <td>{{ $emp->Comunidad ?? 'N/D' }}</td>
                <td>{{ \Carbon\Carbon::parse($emp->Fecha_Inicio_Operaciones)->translatedFormat('d \d\e F \d\e\l Y') }}</td>
                <td>{{ $socH }} / {{ $socM }} / {{ $socH + $socM }}</td>
                <td style="text-align: left;">{{ $emp->Tipo_Negocio }}</td>
                <td>L {{ number_format($emp->Ventas_Trimestrales, 2, '.', ',') }}</td>
                <td>{{ $empH }} / {{ $empM }} / {{ $empH + $empM }}</td>
                <td>{{ $emp->tecnico->Nombre_Usuario ?? 'N/D' }}</td>
                <td>{{ \Carbon\Carbon::parse($emp->Fecha_Levantamiento)->format('d/m/Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="11">No hay datos para mostrar.</td></tr>
        @endforelse

        {{-- TOTALES --}}
        <tr class="totals">
            <td colspan="5">Totales Generales</td>
            <td>{{ $totalSociosH }} / {{ $totalSociosM }} / {{ $totalSociosH + $totalSociosM }}</td>
            <td></td>
            <td></td>
            <td>{{ $totalEmpleosH }} / {{ $totalEmpleosM }} / {{ $totalEmpleosH + $totalEmpleosM }}</td>
            <td colspan="2"></td>
        </tr>
    </tbody>
</table>

{{-- PIE DE PÁGINA CON PAGINACIÓN --}}
@if (isset($pdf))
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont("DejaVu Sans", "normal");
            $size = 9;
            $pageText = "Página {PAGE_NUM} de {PAGE_COUNT}";
            
            // Posición en la esquina inferior derecha
            $width = $pdf->get_width();
            $x = $width - 100; // ajusta el valor según necesites
            $y = $pdf->get_height() - 30;

            $pdf->page_text($x, $y, $pageText, $font, $size, [0,0,0]);
        }
    </script>
@endif


</body>
</html>
