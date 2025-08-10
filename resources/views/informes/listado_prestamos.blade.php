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
        .monto {
            text-align: right;
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
        <div>Listado de Préstamos</div>
    </div>

    <div class="logo">
        <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo">
    </div>
</div>

{{-- TABLA DE DATOS --}}
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Socio</th>
            <th>Monto</th>
            <th>Destino</th>
            <th>Tipo Crédito</th>  {{-- Nueva columna --}}
            <th>Estado</th>
            <th>Fecha de Solicitud</th>
        </tr>
    </thead>
    <tbody>
        @forelse($prestamos as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->socio }}</td>
                <td class="monto">L. {{ number_format($p->monto_solicitado, 2, '.', ',') }}</td>
                <td>{{ $p->destino }}</td>
                <td>{{ ucfirst($p->tipo_credito) }}</td> {{-- Mostrar tipo crédito --}}
                <td>{{ ucfirst($p->estado) }}</td>
                <td>{{ \Carbon\Carbon::parse($p->fecha_solicitud)->format('d/m/Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="7">No hay préstamos para mostrar.</td></tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
