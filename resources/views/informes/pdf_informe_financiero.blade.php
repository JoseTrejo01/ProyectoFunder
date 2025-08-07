<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe Financiero por Departamento - FUNDER</title>
    <style>
        @page {
            margin: 30px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #000;
            padding-bottom: 8px;
            margin-bottom: 5px;
        }

        .left-info {
            font-size: 9px;
        }

        .center-title {
            text-align: center;
            flex-grow: 1;
            font-weight: bold;
            font-size: 12px;
        }

        .logo {
            text-align: right;
        }

        .logo img {
            height: 45px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .table th, .table td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #f0f0f0;
            font-size: 9px;
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
        <div>Informe Financiero por Departamento</div>
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
            <th>Departamento</th>
            <th>Préstamos por cobrar</th>
            <th>Total monto solicitado</th>
            <th>Saldo préstamo mora</th>
            <th>Depósitos ahorro</th>
            <th>Préstamos por pagar</th>
            <th>Total capital social</th>
            <th>Total capital trabajo</th>
            <th>Total reservas</th>
            <th>Total intereses cobrados</th>
            <th>Total capital semilla</th>
            <th>Total préstamos registrados</th>
            <th>Porcentaje mora (%)</th>
        </tr>
    </thead>
    <tbody>
        @php
            $count = 1;
            $totales = [
                'prestamos_por_cobrar' => 0,
                'total_monto_solicitado' => 0,
                'saldo_prestamo_mora' => 0,
                'depositos_ahorro' => 0,
                'prestamos_por_pagar' => 0,
                'total_capital_social' => 0,
                'total_capital_trabajo' => 0,
                'total_reservas' => 0,
                'total_intereses_cobrados' => 0,
                'total_capital_semilla' => 0,
                'total_prestamos_registrados' => 0,
            ];
        @endphp

        @forelse($resultados as $row)
            @php
                $totales['prestamos_por_cobrar'] += $row->prestamos_por_cobrar;
                $totales['total_monto_solicitado'] += $row->total_monto_solicitado;
                $totales['saldo_prestamo_mora'] += $row->saldo_prestamo_mora;
                $totales['depositos_ahorro'] += $row->depositos_ahorro;
                $totales['prestamos_por_pagar'] += $row->prestamos_por_pagar;
                $totales['total_capital_social'] += $row->total_capital_social;
                $totales['total_capital_trabajo'] += $row->total_capital_trabajo;
                $totales['total_reservas'] += $row->total_reservas;
                $totales['total_intereses_cobrados'] += $row->total_intereses_cobrados;
                $totales['total_capital_semilla'] += $row->total_capital_semilla;
                $totales['total_prestamos_registrados'] += $row->total_prestamos_registrados;
            @endphp
            <tr>
                <td>{{ $count++ }}</td>
                <td>{{ $row->departamento }}</td>
                <td>L {{ number_format($row->prestamos_por_cobrar, 2, '.', ',') }}</td>
                <td>L {{ number_format($row->total_monto_solicitado, 2, '.', ',') }}</td>
                <td>L {{ number_format($row->saldo_prestamo_mora, 2, '.', ',') }}</td>
                <td>L {{ number_format($row->depositos_ahorro, 2, '.', ',') }}</td>
                <td>L {{ number_format($row->prestamos_por_pagar, 2, '.', ',') }}</td>
                <td>L {{ number_format($row->total_capital_social, 2, '.', ',') }}</td>
                <td>L {{ number_format($row->total_capital_trabajo, 2, '.', ',') }}</td>
                <td>L {{ number_format($row->total_reservas, 2, '.', ',') }}</td>
                <td>L {{ number_format($row->total_intereses_cobrados, 2, '.', ',') }}</td>
                <td>L {{ number_format($row->total_capital_semilla, 2, '.', ',') }}</td>
                <td>{{ $row->total_prestamos_registrados }}</td>
                <td>{{ $row->porcentaje_mora }}%</td>
            </tr>
        @empty
            <tr><td colspan="14">No hay datos para mostrar.</td></tr>
        @endforelse

        {{-- FILA DE TOTALES --}}
        <tr class="totals">
            <td colspan="2">Totales</td>
            <td>L {{ number_format($totales['prestamos_por_cobrar'], 2, '.', ',') }}</td>
            <td>L {{ number_format($totales['total_monto_solicitado'], 2, '.', ',') }}</td>
            <td>L {{ number_format($totales['saldo_prestamo_mora'], 2, '.', ',') }}</td>
            <td>L {{ number_format($totales['depositos_ahorro'], 2, '.', ',') }}</td>
            <td>L {{ number_format($totales['prestamos_por_pagar'], 2, '.', ',') }}</td>
            <td>L {{ number_format($totales['total_capital_social'], 2, '.', ',') }}</td>
            <td>L {{ number_format($totales['total_capital_trabajo'], 2, '.', ',') }}</td>
            <td>L {{ number_format($totales['total_reservas'], 2, '.', ',') }}</td>
            <td>L {{ number_format($totales['total_intereses_cobrados'], 2, '.', ',') }}</td>
            <td>L {{ number_format($totales['total_capital_semilla'], 2, '.', ',') }}</td>
            <td>{{ $totales['total_prestamos_registrados'] }}</td>
            <td>-</td>
        </tr>
    </tbody>
</table>

</body>
</html>
