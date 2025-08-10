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
        }
        .header {
            position: relative;
            padding-bottom: 8px;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
        }
        .left-info {
            font-size: 9px;
        }
        .center-title {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin-right: 130px; /* deja espacio para el logo */
        }
        .logo {
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
        }
        .logo img {
            max-height: 45px;
            width: auto;
            display: block;
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
    </style>
</head>
<body>

<div class="header">
    <div class="left-info">
        FECHA: {{ now()->format('Y/m/d') }}
    </div>
    <div class="center-title">
        <div>FUNDER</div>
        <div>Pagos del Préstamo #{{ $prestamo->id }}</div>
    </div>
    <div class="logo">
        <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo">
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID Pago</th>
            <th>Beneficiario</th>
            <th>Fecha Programada</th>
            <th>Monto</th>
            <th>Estado</th>
            <th>Observaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($prestamo->pagos as $pago)
            <tr>
                <td>{{ $pago->id }}</td>
                <td>{{ $prestamo->beneficiario ? $prestamo->beneficiario->Nombre_Beneficiario : 'No definido' }}</td>
                <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                <td>L. {{ number_format($pago->monto_pagado, 2, '.', ',') }}</td>
                <td>{{ ucfirst($pago->estado) }}</td>
                <td>{{ $pago->observaciones }}</td>
            </tr>
        @endforeach
        @if($prestamo->pagos->isEmpty())
            <tr>
                <td colspan="6">No hay pagos registrados para este préstamo.</td>
            </tr>
        @endif
    </tbody>
</table>

</body>
</html>
