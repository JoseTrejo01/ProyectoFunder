<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Bitácora - Funder</title>
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
        }

        .descripcion {
            text-align: left;
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
        <div>Reporte de Bitácora</div>
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
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Objeto</th>
            <th>Acción</th>
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
        @php $count = 1; @endphp
        @forelse($registros as $registro)
            <tr>
                <td>{{ $count++ }}</td>
                <td>{{ \Carbon\Carbon::parse($registro->Fecha)->format('d/m/Y H:i') }}</td>
                <td>{{ $registro->usuario->Nombre_Usuario ?? 'N/D' }}</td>
                <td>{{ $registro->objeto->Objeto ?? 'N/D' }}</td>
                <td>{{ $registro->Accion }}</td>
                <td class="descripcion">{{ $registro->Descripcion }}</td>
            </tr>
        @empty
            <tr><td colspan="6">No hay registros de bitácora.</td></tr>
        @endforelse
    </tbody>
</table>

{{-- PIE DE PÁGINA --}}
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
