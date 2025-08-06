<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Evaluaciones</title>
    <style>
        @page {
            size: landscape;
            margin: 30px;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5px;
            margin: 0;
        }
        .header {
            width: 100%;
            margin-bottom: 10px;
        }
        .left {
            float: left;
            font-size: 11px;
        }
        .center {
            position: absolute;
            width: 100%;
            top: 30px;
            text-align: center;
            font-size: 13px;
            font-weight: bold;
        }
        .right {
            float: right;
        }
        .right img {
            height: 60px;
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th,
        .table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }
        .table th {
            background-color: #d9ead3;
            font-weight: bold;
        }
    </style>
</head>
<body>

<table width="100%" style="margin-bottom: 10px;">
    <tr>
        <td style="text-align: left; font-size: 11px;">
            FECHA: {{ now()->format('Y/m/d') }}
        </td>
        <td style="text-align: center;">
            <span style="font-weight: bold; font-size: 13px;">
                FUNDER<br>
                Reporte de Evaluaciones
            </span>
        </td>
        <td style="text-align: right;">
            <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Funder Logo" style="height: 60px;">
        </td>
    </tr>
</table>

<table class="table">
    <thead>
        <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2">Organización</th>
            <th rowspan="2">Departamento</th>
            <th colspan="4">Evaluación Inicial</th>
            <th colspan="4">Evaluación Actualizada</th>
            <th rowspan="2">% de crecimiento</th>
        </tr>
        <tr>
            <th>Desempeño Institucional</th>
            <th>Desempeño Financiero</th>
            <th>Calificación Total</th>
            <th>Categoría</th>

            <th>Desempeño Institucional</th>
            <th>Desempeño Financiero</th>
            <th>Calificación Total</th>
            <th>Categoría</th>
        </tr>
    </thead>
    <tbody>
        @foreach($actualizadas as $index => $evaAct)
            @php
                $evaIni = $evaluaciones->firstWhere('organizacion_id', $evaAct->organizacion_id);
                $esActualizada = $evaIni && $evaIni->updated_at > $evaIni->created_at;

                $instIni = $evaAct->desempeno_institucional;
                $finIni = $evaAct->total_financiero;
                $totalIni = (($instIni + $finIni) / (315 + 400)) * 100;
                $catIni = match(true) {
                    $totalIni >= 90 => 'A',
                    $totalIni >= 71 => 'B',
                    $totalIni >= 50 => 'C',
                    default => 'D',
                };

                $crecimiento = 0;
                if ($evaIni && $esActualizada) {
                    $instAct = $evaIni->desempeno_institucional;
                    $finAct = $evaIni->total_financiero;
                    $totalAct = (($instAct + $finAct) / (315 + 400)) * 100;
                    $catAct = match(true) {
                        $totalAct >= 90 => 'A',
                        $totalAct >= 71 => 'B',
                        $totalAct >= 50 => 'C',
                        default => 'D',
                    };
                    $crecimiento = $totalAct - $totalIni;
                }
            @endphp

            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $evaAct->organizacion->Nombre_Organizacion ?? 'Sin nombre' }}</td>
                <td>{{ $evaAct->organizacion->aldea->municipio->departamento->Nombre_Departamento ?? 'No definido' }}</td>

                {{-- Evaluación Inicial --}}
                <td>{{ round(($instIni / 315) * 100, 2) }}%</td>
                <td>{{ round(($finIni / 400) * 100, 2) }}%</td>
                <td>{{ round($totalIni, 2) }}%</td>
                <td>{{ $catIni }}</td>

                {{-- Evaluación Actualizada --}}
                @if ($evaIni && $esActualizada)
                    <td>{{ round(($instAct / 315) * 100, 2) }}%</td>
                    <td>{{ round(($finAct / 400) * 100, 2) }}%</td>
                    <td>{{ round($totalAct, 2) }}%</td>
                    <td>{{ $catAct }}</td>
                    <td>{{ round($crecimiento, 2) }}%</td>
                @else
                    <td colspan="5" style="color: gray;">Sin datos</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>


    @if (isset($pdf))
        <script type="text/php">
            if (isset($pdf)) {
                $font = $fontMetrics->getFont("DejaVu Sans", "normal");
                $size = 9;
                $x = 720; // Posición X en horizontal para esquina derecha
                $y = 575; // Posición Y en vertical (parte inferior de la página)
                $pdf->page_text($x, $y, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, $size);
            }
        </script>
    @endif

</body>
</html>
