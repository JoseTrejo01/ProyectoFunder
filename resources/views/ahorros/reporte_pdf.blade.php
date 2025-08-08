<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ahorros - Funder</title>
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
        <div>Reporte de Ahorros</div>
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
            <th>Caja Rural</th>
            <th>Beneficiario</th>
            <th>Tipo</th>
            
            <th>Monto (Lps)</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @php
            $count = 1;
            $totalMonto = 0;
        @endphp

        @forelse($ahorros as $ahorro)
            @php
                $monto = $ahorro->Monto ?? 0;
                $totalMonto += $monto;
            @endphp
            <tr>
                <td>{{ $count++ }}</td>
                <td>{{ $ahorro->organizacion->Nombre_Organizacion ?? 'N/D' }}</td>
                <td>{{ $ahorro->beneficiario->Nombre_Beneficiario ?? 'N/D' }}</td>
                <td>{{ $ahorro->beneficiario->Tipo_De_Socio ?? 'N/D' }}</td>
                
                <td>L {{ number_format($monto, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($ahorro->Fecha)->format('d/m/Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="6">No hay datos para mostrar.</td></tr>
        @endforelse

        {{-- TOTAL GENERAL --}}
        <tr class="totals">
            <td colspan="4">Total General</td>
            <td colspan="2">L {{ number_format($totalMonto, 2) }}</td>
        </tr>
    </tbody>
</table>

{{-- PIE DE PÁGINA CON PAGINACIÓN --}}
@if (isset($pdf))
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont("DejaVu Sans", "normal");
            $size = 9;
            $pageText = "Página {PAGE_NUM} de {PAGE_COUNT}";
            
            // Posición en la esquina inferior derecha
            $width = $pdf->get_width();
            $x = $width - 100; // ajusta si es necesario
            $y = $pdf->get_height() - 30;

            $pdf->page_text($x, $y, $pageText, $font, $size, [0,0,0]);
        }
    </script>
@endif

</body>
</html>
