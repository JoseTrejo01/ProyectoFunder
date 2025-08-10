<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Cargos Directivos - Funder</title>
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
            font-size: 9px;
        }

        .table td {
            font-size: 9px;
        }

        .org-name {
            text-align: left !important;
        }

        .departamento-header {
            background-color: #d0d0d0 !important;
            font-weight: bold;
            text-align: left !important;
            font-size: 10px;
        }

        .summary-row {
            background-color: #f8f8f8 !important;
            font-weight: bold;
        }

        .filtro-info {
            text-align: center;
            font-size: 11px;
            margin-bottom: 10px;
            font-style: italic;
        }

        .resumen-departamentos {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .resumen-departamentos th, .resumen-departamentos td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }

        .resumen-departamentos th {
            background-color: #f0f0f0;
            font-size: 10px;
            font-weight: bold;
        }

        .resumen-departamentos td {
            font-size: 9px;
        }

        .departamento-cell {
            text-align: left !important;
        }

    </style>
</head>
<body>

{{-- ENCABEZADO --}}
<div class="header">
    <div class="left-info">
        FECHA: {{ $fecha ?? now()->format('d/m/Y') }}
    </div>

    <div class="center-title">
        <div>FUNDER</div>
        <div>{{ $titulo ?? 'Distribución de Cargos por Caja Rural' }}</div>
        @if($filtro_departamento)
            <div style="font-size: 10px; font-weight: normal;">Departamento: {{ $filtro_departamento }}</div>
        @endif
    </div>

    <div class="logo">
        <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo">
    </div>
</div>

{{-- INFORMACIÓN DE FILTRO --}}
@if($filtro_departamento)
    <div class="filtro-info">
        Mostrando resultados filtrados por el departamento: <strong>{{ $filtro_departamento }}</strong>
    </div>
@endif

{{-- RESUMEN POR DEPARTAMENTOS (solo si no hay filtro) --}}
@if(!$filtro_departamento && !empty($resumenDepartamentos))
    <h3 style="font-size: 12px; margin-bottom: 10px;">Resumen por Departamento</h3>
    <table class="resumen-departamentos">
        <thead>
            <tr>
                <th>Departamento</th>
                <th>Total Cajas</th>
                <th>Presidentes</th>
                <th>Vicepresidentes</th>
                <th>Secretarios</th>
                <th>Tesoreros</th>
                <th>Vocales</th>
                <th>Total Cargos</th>
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

{{-- TABLA DE DATOS DETALLADOS --}}
<h3 style="font-size: 12px; margin-bottom: 10px; margin-top: 20px;">Detalle de Cargos por Caja Rural</h3>
<table class="table">
    <thead>
        <tr>
            <th>No.</th>
            @if(!$filtro_departamento)
                <th>Departamento</th>
            @endif
            <th>Nombre de la Caja Rural</th>
            <th>Presidente(a)</th>
            <th>Vicepresidente(a)</th>
            <th>Secretario(a)</th>
            <th>Tesorero(a)</th>
            <th>Vocal I</th>
            <th>Vocal II</th>
            <th>Vocal III</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $count = 1; 
            $cajasAgrupadas = $cajas->groupBy('departamento');
        @endphp
        
        @forelse($cajasAgrupadas as $departamento => $cajasDep)
            {{-- Mostrar encabezado de departamento solo si no hay filtro --}}
            @if(!$filtro_departamento && $cajasAgrupadas->count() > 1)
                <tr>
                    <td colspan="{{ $filtro_departamento ? 8 : 9 }}" class="departamento-header">
                        {{ $departamento }}
                    </td>
                </tr>
            @endif
            
            @foreach($cajasDep as $caja)
                <tr>
                    <td>{{ $count++ }}</td>
                    @if(!$filtro_departamento)
                        <td>{{ $caja->departamento ?? 'N/D' }}</td>
                    @endif
                    <td class="org-name">{{ $caja->nombre_organizacion }}</td>
                    <td>
                        @if($caja->presidente_h && $caja->presidente_m)
                            H/M
                        @elseif($caja->presidente_h)
                            H
                        @elseif($caja->presidente_m)
                            M
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($caja->vicepresidente_h && $caja->vicepresidente_m)
                            H/M
                        @elseif($caja->vicepresidente_h)
                            H
                        @elseif($caja->vicepresidente_m)
                            M
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($caja->secretario_h && $caja->secretario_m)
                            H/M
                        @elseif($caja->secretario_h)
                            H
                        @elseif($caja->secretario_m)
                            M
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($caja->tesorero_h && $caja->tesorero_m)
                            H/M
                        @elseif($caja->tesorero_h)
                            H
                        @elseif($caja->tesorero_m)
                            M
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $caja->vocal1 ?? '-' }}</td>
                    <td>{{ $caja->vocal2 ?? '-' }}</td>
                    <td>{{ $caja->vocal3 ?? '-' }}</td>
                </tr>
            @endforeach
            
            {{-- Mostrar subtotal del departamento solo si no hay filtro --}}
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
                <td colspan="{{ $filtro_departamento ? 8 : 9 }}">No hay cargos directivos para mostrar.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- PIE DE PÁGINA CON PAGINACIÓN --}}
@if (isset($pdf))
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont("DejaVu Sans", "normal");
            $size = 9;
            $pageText = "Página {PAGE_NUM} de {PAGE_COUNT}";

            $width = $pdf->get_width();
            $x = $width - 100;
            $y = $pdf->get_height() - 30;

            $pdf->page_text($x, $y, $pageText, $font, $size, [0, 0, 0]);
        }
    </script>
@endif

</body>
</html>
