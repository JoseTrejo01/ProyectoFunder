<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Reporte de Cargos Directivos - FUNDER</title>

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
            line-height: 1.25;
        }

        /* ========================
           ENCABEZADO
        ========================= */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 8px;
        }

        .left-info {
            font-size: 10px;
            font-weight: bold;
        }

        .center-title {
            text-align: center;
            flex-grow: 1;
            font-weight: bold;
            font-size: 13px;
            line-height: 1.3;
        }

        .logo img {
            height: 55px;
            filter: contrast(115%);
        }

        /* ========================
           TABLAS
        ========================= */
        table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table td,
        .resumen-departamentos th,
        .resumen-departamentos td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }

        .table th,
        .resumen-departamentos th {
            background-color: #e6e6e6;
            color: #000;
            font-weight: bold;
            font-size: 10px;
        }

        .table td,
        .resumen-departamentos td {
            font-size: 9px;
        }

        .org-name {
            text-align: left !important;
        }

        .departamento-header {
            background-color: #c7c7c7 !important;
            color: #000;
            font-weight: bold;
            text-align: left !important;
            font-size: 10px;
        }

        .summary-row {
            background-color: #f4f4f4 !important;
            font-weight: bold;
        }

        .departamento-cell {
            text-align: left !important;
        }

        /* ========================
           INFO FILTRO
        ========================= */
        .filtro-info {
            text-align: center;
            font-size: 11px;
            margin: 10px 0;
            font-style: italic;
        }

        /* ========================
           TITULOS
        ========================= */
        h3 {
            font-size: 12px;
            font-weight: bold;
            margin: 8px 0;
        }

        /* ========================
           RESPONSIVE/PRINT
        ========================= */
        @media print {
            .table th,
            .table td {
                padding: 6px;
            }
        }
    </style>
</head>

<body role="document" aria-label="Reporte completo de cargos directivos">

{{-- ============================
     ENCABEZADO
============================= --}}
<div class="header" role="banner">
    <div class="left-info">
        FECHA: {{ $fecha ?? now()->format('d/m/Y') }}
    </div>

    <div class="center-title" role="heading" aria-level="1">
        <div>FUNDER</div>
        <div>{{ $titulo ?? 'Distribución de Cargos por Caja Rural' }}</div>
        @if($filtro_departamento)
            <div style="font-size: 10px; font-weight: normal;">
                Departamento: {{ $filtro_departamento }}
            </div>
        @endif
    </div>

    <div class="logo">
        <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}"
             alt="Logo institucional de FUNDER" />
    </div>
</div>

{{-- ============================
     INFORMACIÓN DE FILTRO
============================= --}}
@if($filtro_departamento)
    <div class="filtro-info" role="note">
        Mostrando resultados filtrados por el departamento:
        <strong>{{ $filtro_departamento }}</strong>
    </div>
@endif


{{-- ============================
     RESUMEN POR DEPARTAMENTOS
============================= --}}
@if(!$filtro_departamento && !empty($resumenDepartamentos))
    <h3 role="heading" aria-level="2">Resumen por Departamento</h3>

    <table class="resumen-departamentos" role="table" aria-label="Resumen general por departamento">
        <thead>
            <tr>
                <th scope="col">Departamento</th>
                <th scope="col">Total Cajas</th>
                <th scope="col">Presidentes</th>
                <th scope="col">Vicepresidentes</th>
                <th scope="col">Secretarios</th>
                <th scope="col">Tesoreros</th>
                <th scope="col">Vocales</th>
                <th scope="col">Total Cargos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resumenDepartamentos as $departamento => $datos)
                <tr>
                    <td class="departamento-cell">{{ $departamento }}</td>
                    <td>{{ $datos['total_cajas'] }}</td>
                    <td>{{ $datos['presidentes'] }}</td>
                    <td>{{ $datos['vicepresidentes'] }}</td>
                    <td>{{ $datos['secretarios'] }}</td>
                    <td>{{ $datos['tesoreros'] }}</td>
                    <td>{{ $datos['vocales'] }}</td>
                    <td>{{ $datos['presidentes'] + $datos['vicepresidentes'] + $datos['secretarios'] + $datos['tesoreros'] + $datos['vocales'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif


{{-- ============================
     DETALLE DE CAJAS
============================= --}}
<h3 role="heading" aria-level="2">Detalle de Cargos por Caja Rural</h3>

<table class="table" role="table" aria-label="Detalle de cargos por caja rural">
    <thead>
        <tr>
            <th scope="col">No.</th>

            @if(!$filtro_departamento)
                <th scope="col">Departamento</th>
            @endif

            <th scope="col">Nombre de la Caja Rural</th>
            <th scope="col">Presidente(a)</th>
            <th scope="col">Vicepresidente(a)</th>
            <th scope="col">Secretario(a)</th>
            <th scope="col">Tesorero(a)</th>
            <th scope="col">Vocal I</th>
            <th scope="col">Vocal II</th>
            <th scope="col">Vocal III</th>
        </tr>
    </thead>

    <tbody>
        @php 
            $count = 1;
            $cajasAgrupadas = $cajas->groupBy('departamento');
        @endphp
        
        @forelse($cajasAgrupadas as $departamento => $cajasDep)

            {{-- Encabezado del departamento --}}
            @if(!$filtro_departamento && $cajasAgrupadas->count() > 1)
                <tr class="departamento-header" role="row" aria-label="Departamento {{ $departamento }}">
                    <td colspan="{{ $filtro_departamento ? 8 : 9 }}">
                        {{ $departamento }}
                    </td>
                </tr>
            @endif

            {{-- Filas de cajas --}}
            @foreach($cajasDep as $caja)
                <tr>
                    <td>{{ $count++ }}</td>

                    @if(!$filtro_departamento)
                        <td>{{ $caja->departamento ?? 'N/D' }}</td>
                    @endif

                    <td class="org-name">{{ $caja->nombre_organizacion }}</td>

                    {{-- Presidente --}}
                    <td>
                        @if($caja->presidente_h && $caja->presidente_m) H/M
                        @elseif($caja->presidente_h) H
                        @elseif($caja->presidente_m) M
                        @else -
                        @endif
                    </td>

                    {{-- Vicepresidente --}}
                    <td>
                        @if($caja->vicepresidente_h && $caja->vicepresidente_m) H/M
                        @elseif($caja->vicepresidente_h) H
                        @elseif($caja->vicepresidente_m) M
                        @else -
                        @endif
                    </td>

                    {{-- Secretario --}}
                    <td>
                        @if($caja->secretario_h && $caja->secretario_m) H/M
                        @elseif($caja->secretario_h) H
                        @elseif($caja->secretario_m) M
                        @else -
                        @endif
                    </td>

                    {{-- Tesorero --}}
                    <td>
                        @if($caja->tesorero_h && $caja->tesorero_m) H/M
                        @elseif($caja->tesorero_h) H
                        @elseif($caja->tesorero_m) M
                        @else -
                        @endif
                    </td>

                    <td>{{ $caja->vocal1 ?? '-' }}</td>
                    <td>{{ $caja->vocal2 ?? '-' }}</td>
                    <td>{{ $caja->vocal3 ?? '-' }}</td>
                </tr>
            @endforeach

            {{-- Subtotales --}}
            @if(!$filtro_departamento && $cajasAgrupadas->count() > 1)
                @php
                    $totalCajas = $cajasDep->count();
                    $presidentes = $cajasDep->filter(fn($c) => $c->presidente_h || $c->presidente_m)->count();
                    $vicepresidentes = $cajasDep->filter(fn($c) => $c->vicepresidente_h || $c->vicepresidente_m)->count();
                    $secretarios = $cajasDep->filter(fn($c) => $c->secretario_h || $c->secretario_m)->count();
                    $tesoreros = $cajasDep->filter(fn($c) => $c->tesorero_h || $c->tesorero_m)->count();
                    $vocales = $cajasDep->filter(fn($c) => $c->vocal1 || $c->vocal2 || $c->vocal3)->count();
                @endphp

                <tr class="summary-row">
                    <td colspan="2"><strong>TOTAL {{ $departamento }}:</strong></td>
                    <td><strong>{{ $totalCajas }} cajas</strong></td>
                    <td><strong>{{ $presidentes }}</strong></td>
                    <td><strong>{{ $vicepresidentes }}</strong></td>
                    <td><strong>{{ $secretarios }}</strong></td>
                    <td><strong>{{ $tesoreros }}</strong></td>
                    <td><strong>{{ $vocales }}</strong></td>
                    <td><strong>-</strong></td>
                </tr>
            @endif

        @empty
            <tr>
                <td colspan="10">No hay cargos directivos para mostrar.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- ============================
     PAGINACIÓN EN PDF
============================= --}}
@if (isset($pdf))
<script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->getFont("DejaVu Sans", "normal");
        $size = 9;
        $pageText = "Página {PAGE_NUM} de {PAGE_COUNT}";
        $width = $pdf->get_width();
        $x = $width - 100;
        $y = $pdf->get_height() - 28;

        $pdf->page_text($x, $y, $pageText, $font, $size, [0,0,0]);
    }
</script>
@endif

</body>
</html>
