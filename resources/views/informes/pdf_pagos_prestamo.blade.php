<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagos del Préstamo #{{ $prestamo->id }}</title>

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

        /* ===== ENCABEZADO ===== */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #000;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .left-info {
            font-size: 9px;
            line-height: 1.2;
        }

        .center-title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            flex-grow: 1;
        }

        .logo img {
            height: 50px;
            width: auto;
        }

        /* ===== INFO DEL PRÉSTAMO ===== */
        .info-box {
            border: 1px solid #ccc;
            background: #fafafa;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        /* ===== TABLA ===== */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 4px 3px;
            vertical-align: middle;
        }

        .table th {
            background-color: #e6f2e6;
            font-size: 9px;
            text-align: center;
            font-weight: bold;
        }

        .right { text-align: right; }
        .center { text-align: center; }

        .no-data {
            text-align: center;
            padding: 8px;
            font-style: italic;
            color: #666;
        }

        /* ===== TOTALES ===== */
        .totals-row {
            background: #f7f7f7;
            font-weight: bold;
        }
    </style>
</head>
<body>

{{-- ================= ENCABEZADO ================= --}}
<div class="header">
    <div class="left-info">
        FECHA: {{ now()->format('d/m/Y') }}<br>
        HORA: {{ now()->format('H:i') }}
    </div>

    <div class="center-title">
        FUNDER <br>
        PAGOS DEL PRÉSTAMO #{{ $prestamo->id }}
    </div>

    <div class="logo">
        <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo">
    </div>
</div>


{{-- ================= INFO GENERAL DEL PRÉSTAMO ================= --}}
<div class="info-box">
    <div class="info-row">
        <span><b>Beneficiario:</b> {{ $prestamo->beneficiario->Nombre_Beneficiario ?? 'No definido' }}</span>
        <span><b>Monto Solicitado:</b> L. {{ number_format($prestamo->monto_solicitado, 2, '.', ',') }}</span>
    </div>

    <div class="info-row">
        <span><b>Destino:</b> {{ $prestamo->destino }}</span>
        <span><b>Tipo Crédito:</b> {{ ucfirst($prestamo->tipo_credito) }}</span>
    </div>

    <div class="info-row">
        <span><b>Estado:</b> {{ ucfirst($prestamo->estado) }}</span>
        <span><b>Fecha Solicitud:</b> {{ \Carbon\Carbon::parse($prestamo->fecha_solicitud)->format('d/m/Y') }}</span>
    </div>
</div>


{{-- ================= TABLA DE PAGOS ================= --}}
<table class="table">
    <thead>
        <tr>
            <th>ID Pago</th>
            <th>Fecha Programada</th>
            <th>Monto</th>
            <th>Estado</th>
            <th>Observaciones</th>
        </tr>
    </thead>

    <tbody>
        @php
            $totalPagado = 0;
        @endphp

        @forelse($prestamo->pagos as $pago)
            @php
                $totalPagado += $pago->monto_pagado;
            @endphp

            <tr>
                <td class="center">{{ $pago->id }}</td>

                <td class="center">
                    {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                </td>

                <td class="right">
                    L. {{ number_format($pago->monto_pagado, 2, '.', ',') }}
                </td>

                <td class="center">{{ ucfirst($pago->estado) }}</td>

                <td>{{ $pago->observaciones ?: '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="no-data">No hay pagos registrados para este préstamo.</td>
            </tr>
        @endforelse

        {{-- ===== TOTAL ===== --}}
        @if(!$prestamo->pagos->isEmpty())
        <tr class="totals-row">
            <td colspan="2" class="center">TOTAL PAGADO</td>
            <td class="right">L. {{ number_format($totalPagado, 2, '.', ',') }}</td>
            <td colspan="2"></td>
        </tr>
        @endif

    </tbody>
</table>

</body>
</html>
