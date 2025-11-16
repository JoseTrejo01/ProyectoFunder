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
            font-size: 10px;
            margin: 0;
        }
        table {
            width: 100%;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th,
        .table td {
            border: 1px solid #000;
            padding: 4px 3px;
            text-align: center;
        }
        .table th {
            background-color: #d9ead3;
            font-weight: bold;
        }
        .header-table td {
            padding-bottom: 5px;
        }
    </style>
</head>
<body>

{{-- ENCABEZADO --}}
<table class="header-table">
    <tr>
        <td style="text-align: left; font-size: 11px;">
            FECHA: {{ now()->format('Y/m/d') }}
        </td>
        <td style="text-align: center;">
            <span style="font-size: 14px; font-weight: bold;">FUNDER</span><br>
            <span style="font-size: 12px;">Reporte de Evaluaciones</span>
        </td>
        <td style="text-align: right;">
            <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo" height="55">
        </td>
    </tr>
</table>

{{-- TABLA PRINCIPAL --}}
<table class="table">
    <thead>
        <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2">Organización</th>
            <th rowspan="2">Departamento</th>

            <th colspan="4">Evaluación Inicial</th>
            <th colspan="4">Evaluación Actualizada</th>

            <th rowspan="2">% Crecimiento</th>
        </tr>
        <tr>
            <th>Inst.</th>
            <th>Finan.</th>
            <th>Total</th>
            <th>Cat.</th>

            <th>Inst.</th>
            <th>Finan.</th>
            <th>Total</th>
            <th>Cat.</th>
        </tr>
    </thead>

    <tbody>

    @foreach($actualizadas as $index => $evaAct)

        @php
            // Buscar evaluación inicial
            $evaIni = $evaluaciones->firstWhere('id_organizacion', $evaAct->id_organizacion);
            $esActualizada = $evaIni && $evaIni->updated_at > $evaIni->created_at;

            // Helpers
            $calcTotal = fn($inst, $fin) => round((($inst + $fin) / (315 + 400)) * 100, 2);
            $calcCat = fn($t) =>
                $t >= 90 ? 'A' :
                ($t >= 71 ? 'B' :
                ($t >= 50 ? 'C' : 'D'));

            // Evaluación inicial
            $instIni = $evaAct->desempeno_institucional;
            $finIni = $evaAct->total_financiero;
            $totalIni = $calcTotal($instIni, $finIni);
            $catIni = $calcCat($totalIni);

            // Evaluación actual (si existe)
            $instAct = $esActualizada ? $evaIni->desempeno_institucional : null;
            $finAct = $esActualizada ? $evaIni->total_financiero : null;
            $totalAct = $esActualizada ? $calcTotal($instAct, $finAct) : null;
            $catAct = $esActualizada ? $calcCat($totalAct) : null;

            // Crecimiento
            $crecimiento = $esActualizada ? round($totalAct - $totalIni, 2) : 0;
        @endphp

        <tr>
            {{-- Número --}}
            <td>{{ $index + 1 }}</td>

            {{-- Organización --}}
            <td>{{ $evaAct->organizacion->Nombre_Organizacion ?? 'Sin nombre' }}</td>

            {{-- Departamento --}}
            <td>{{ $evaAct->organizacion->aldea->municipio->departamento->Nombre_Departamento ?? 'Sin dato' }}</td>

            {{-- Inicial --}}
            <td>{{ round(($instIni / 315) * 100, 2) }}%</td>
            <td>{{ round(($finIni / 400) * 100, 2) }}%</td>
            <td>{{ $totalIni }}%</td>
            <td>{{ $catIni }}</td>

            {{-- Actual --}}
            @if($esActualizada)
                <td>{{ round(($instAct / 315) * 100, 2) }}%</td>
                <td>{{ round(($finAct / 400) * 100, 2) }}%</td>
                <td>{{ $totalAct }}%</td>
                <td>{{ $catAct }}</td>
            @else
                <td colspan="4" style="color: gray;">Sin datos</td>
            @endif

            {{-- Crecimiento --}}
            <td>{{ $crecimiento }}%</td>
        </tr>

    @endforeach

    </tbody>
</table>

{{-- PIE DE PÁGINA --}}
@if (isset($pdf))
<script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->getFont("DejaVu Sans", "normal");
        $pdf->page_text(
            760, 570,
            "Página {PAGE_NUM} de {PAGE_COUNT}",
            $font, 9
        );
    }
</script>
@endif

</body>
</html>
