<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Préstamos - FUNDER</title>

    <style>
        @page {
            margin: 30px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            margin: 0;
            color: #000;
        }

        /* ====== ENCABEZADO ====== */
        .header {
            width: 100%;
            border-bottom: 1px solid #000;
            padding-bottom: 6px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .left-info {
            font-size: 9px;
            line-height: 1.1;
        }

        .center-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            line-height: 1.2;
        }

        .logo img {
            height: 48px;
        }

        /* ====== FILTROS ====== */
        .filters {
            font-size: 9px;
            margin-bottom: 8px;
            padding: 5px;
            border: 1px solid #ccc;
            background: #f7f7f7;
        }

        .filters b {
            color: #333;
        }

        /* ====== TABLA ====== */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 4px 3px;
            vertical-align: middle;
        }

        .table th {
            background: #e6f2e6; /* verde claro institucional */
            text-align: center;
            font-weight: bold;
            font-size: 9px;
        }

        td.right {
            text-align: right;
        }

        td.center {
            text-align: center;
        }

        .no-data {
            text-align: center;
            padding: 8px;
            color: #666;
            font-style: italic;
        }

        /* ====== TOTALES ====== */
        .totals-row {
            background: #fafafa;
            font-weight: bold;
        }
    </style>

</head>
<body>

{{-- ========== ENCABEZADO ========== --}}
<div class="header">
    <div class="left-info">
        FECHA: {{ now()->format('d/m/Y') }}<br>
        HORA: {{ now()->format('H:i') }}
    </div>

    <div class="center-title">
        FUNDER <br>
        LISTADO DE PRÉSTAMOS
    </div>

    <div class="logo">
        <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo">
    </div>
</div>

{{-- ========== FILTROS APLICADOS ========== --}}
@if(request()->anyFilled(['tipo_credito','estado','fecha_inicio','fecha_fin']))
<div class="filters">
    <b>Filtros aplicados:</b><br>

    @if(request('tipo_credito'))
        • Tipo de Crédito: <b>{{ ucfirst(request('tipo_credito')) }}</b><br>
    @endif

    @if(request('estado'))
        • Estado: <b>{{ ucfirst(request('estado')) }}</b><br>
    @endif

    @if(request('fecha_inicio') || request('fecha_fin'))
        • Rango de fechas: 
        <b>{{ request('fecha_inicio') ?: '---' }}</b> a 
        <b>{{ request('fecha_fin') ?: '---' }}</b><br>
    @endif
</div>
@endif


{{-- ========== TABLA ========== --}}
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Socio</th>
            <th>Monto Solicitado</th>
            <th>Destino</th>
            <th>Tipo Crédito</th>
            <th>Estado</th>
            <th>Fecha Solicitud</th>
        </tr>
    </thead>

    <tbody>

        @php
            $totalMonto = 0;
        @endphp

        @forelse($prestamos as $p)
            @php
                $totalMonto += $p->monto_solicitado;
            @endphp

            <tr>
                <td class="center">{{ $p->id }}</td>

                <td>{{ $p->socio }}</td>

                <td class="right">
                    L {{ number_format($p->monto_solicitado, 2, '.', ',') }}
                </td>

                <td>{{ $p->destino }}</td>

                <td class="center">{{ ucfirst($p->tipo_credito) }}</td>

                <td class="center">{{ ucfirst($p->estado) }}</td>

                <td class="center">
                    {{ \Carbon\Carbon::parse($p->fecha_solicitud)->format('d/m/Y') }}
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="7" class="no-data">
                    No hay préstamos para mostrar.
                </td>
            </tr>
        @endforelse

        {{-- ========== FILA DE TOTALES ========== --}}
        <tr class="totals-row">
            <td colspan="2" class="center">TOTAL GENERAL</td>
            <td class="right">L {{ number_format($totalMonto, 2, '.', ',') }}</td>
            <td colspan="4"></td>
        </tr>

    </tbody>
</table>

</body>
</html>
