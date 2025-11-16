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
        }

        /* ENCABEZADO */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .left-info {
            font-size: 9px;
            line-height: 1.2;
        }

        .center-title {
            text-align: center;
            flex-grow: 1;
            font-weight: bold;
            font-size: 13px;
            line-height: 1.2;
        }

        .logo img {
            height: 48px;
        }

        /* TABLA */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .table th, .table td {
            border: 1px solid #000;
            padding: 4px 3px;
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #e8e8e8;
            font-weight: bold;
            font-size: 9px;
        }

        .right {
            text-align: right !important;
        }

        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
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
        FUNDER<br>
        Listado de Préstamos
    </div>

    <div class="logo">
        <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo">
    </div>

</div>

{{-- TABLA --}}
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Socio</th>
            <th>Monto</th>
            <th>Destino</th>
            <th>Tipo Crédito</th>
            <th>Estado</th>
            <th>Fecha Solicitud</th>
        </tr>
    </thead>

    <tbody>
        @forelse($prestamos as $p)
            <tr>
                <td>{{ $p->id }}</td>

                <td>{{ $p->socio }}</td>

                <td class="right">
                    L {{ number_format($p->monto_solicitado, 2, '.', ',') }}
                </td>

                <td>{{ $p->destino }}</td>

                <td>{{ ucfirst($p->tipo_credito) }}</td>

                <td>{{ ucfirst($p->estado) }}</td>

                <td>{{ \Carbon\Carbon::parse($p->fecha_solicitud)->format('d/m/Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="no-data">No hay préstamos para mostrar.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
