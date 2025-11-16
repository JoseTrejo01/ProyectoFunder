<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Emprendimientos - Funder</title>

    <style>
        @page {
            margin: 30px 40px;
            footer: pie;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5px;
            margin: 0;
            color: #000;
        }

        /* -----------------------------------------
           ENCABEZADO
        ----------------------------------------- */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #000;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .left-info {
            font-size: 10px;
        }

        .center-title {
            text-align: center;
            flex-grow: 1;
            font-weight: bold;
            font-size: 14px;
            line-height: 1.2;
        }

        .logo img {
            height: 55px;
        }

        /* -----------------------------------------
           TABLA
        ----------------------------------------- */
        .table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 5px;
        }

        .table th, .table td {
            border: 1px solid #000;
            padding: 4px 3px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .table th {
            background-color: #efefef;
            font-weight: bold;
        }

        .table td.text-left {
            text-align: left;
        }

        .totals {
            background-color: #e6e6e6;
            font-weight: bold;
        }
    </style>
</head>

<body>

{{-- =====================================================
     ENCABEZADO
===================================================== --}}
<div class="header">
    <div class="left-info">
        <strong>FECHA:</strong> {{ now()->format('Y/m/d') }}
    </div>

    <div class="center-title">
        FUNDER <br>
        Reporte de Emprendimientos
    </div>

    <div class="logo">
        <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo Funder">
    </div>
</div>

{{-- =====================================================
     TABLA PRINCIPAL
===================================================== --}}
<table class="table">
    <thead>
        <tr>
            <th>No.</th>
            <th>Caja Rural</th>
            <th>Municipio</th>
            <th>Comunidad</th>
            <th>Inicio Operaciones</th>
            <th>Socios(as)<br>H / M / Total</th>
            <th class="text-left">Tipo de Negocio</th>
            <th>Ventas Trimestrales</th>
            <th>Empleos<br>H / M / Total</th>
            <th>Técnico</th>
            <th>Fecha Levantamiento</th>
        </tr>
    </thead>

    <tbody>
        @php
            $count = 1;
            $totalSociosH = 0;
            $totalSociosM = 0;
            $totalEmpleosH = 0;
            $totalEmpleosM = 0;
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

                <td>
                    @if($emp->Fecha_Inicio_Operaciones)
                        {{ \Carbon\Carbon::parse($emp->Fecha_Inicio_Operaciones)->translatedFormat('d \d\e F \d\e\l Y') }}
                    @else
                        N/D
                    @endif
                </td>

                <td>{{ $socH }} / {{ $socM }} / {{ $socH + $socM }}</td>

                <td class="text-left">{{ $emp->Tipo_Negocio }}</td>

                <td>L {{ number_format($emp->Ventas_Trimestrales, 2, '.', ',') }}</td>

                <td>{{ $empH }} / {{ $empM }} / {{ $empH + $empM }}</td>

                <td>{{ $emp->tecnico->Nombre_Usuario ?? 'N/D' }}</td>

                <td>
                    {{ \Carbon\Carbon::parse($emp->Fecha_Levantamiento)->format('d/m/Y') }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" style="text-align:center;">No hay datos para mostrar.</td>
            </tr>
        @endforelse

        {{-- =====================================================
             FILA DE TOTALES
        ===================================================== --}}
        <tr class="totals">
            <td colspan="5">Totales Generales</td>

            <td>
                {{ $totalSociosH }} /
                {{ $totalSociosM }} /
                {{ $totalSociosH + $totalSociosM }}
            </td>

            <td></td>
            <td></td>

            <td>
                {{ $totalEmpleosH }} /
                {{ $totalEmpleosM }} /
                {{ $totalEmpleosH + $totalEmpleosM }}
            </td>

            <td colspan="2"></td>
        </tr>
    </tbody>
</table>

{{-- =====================================================
     PIE DE PÁGINA - NUMERACIÓN
===================================================== --}}
@if (isset($pdf))
<script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->getFont("DejaVu Sans", "normal");
        $size = 9;
        $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
        
        $width = $pdf->get_width();
        $x = $width - 120;
        $y = $pdf->get_height() - 28;

        $pdf->page_text($x, $y, $text, $font, $size, [0,0,0]);
    }
</script>
@endif

</body>
</html>
