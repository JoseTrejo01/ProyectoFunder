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
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10px;
            margin: 0;
            line-height: 1.3;
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
            font-size: 10px;
        }

        .center-title {
            text-align: center;
            flex-grow: 1;
            font-size: 12px;
            font-weight: bold;
        }

        .logo img {
            height: 55px;
        }

        /* ===== TABLA ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 4px 3px;
            vertical-align: middle;
        }

        table th {
            background-color: #eaeaea;
            font-weight: bold;
        }

        td.descripcion {
            text-align: left;
        }

        td.center {
            text-align: center;
        }

        .no-data {
            text-align: center;
            font-style: italic;
            padding: 10px;
        }
    </style>
</head>

<body>

    {{-- ===================== ENCABEZADO ===================== --}}
    <div class="header">
        <div class="left-info">
            <strong>FECHA:</strong> {{ now()->format('d/m/Y') }}
        </div>

        <div class="center-title">
            <div>FUNDER</div>
            <div>Reporte de Bitácora</div>
        </div>

        <div class="logo">
            <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo FUNDER">
        </div>
    </div>

    {{-- ===================== TABLA ===================== --}}
    <table>
        <thead>
            <tr>
                <th style="width: 35px;">No.</th>
                <th style="width: 95px;">Fecha</th>
                <th style="width: 110px;">Usuario</th>
                <th style="width: 120px;">Objeto</th>
                <th style="width: 90px;">Acción</th>
                <th>Descripción</th>
            </tr>
        </thead>

        <tbody>
            @php $count = 1; @endphp

            @forelse ($registros as $registro)
                <tr>
                    <td class="center">{{ $count++ }}</td>
                    <td class="center">{{ \Carbon\Carbon::parse($registro->Fecha)->format('d/m/Y H:i') }}</td>
                    <td class="center">{{ $registro->usuario->Nombre_Usuario ?? 'N/D' }}</td>
                    <td class="center">{{ $registro->objeto->Objeto ?? 'N/D' }}</td>
                    <td class="center">{{ $registro->Accion }}</td>
                    <td class="descripcion">{{ $registro->Descripcion }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-data">No hay registros de bitácora.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ===================== PIE DE PÁGINA (NUMERACIÓN) ===================== --}}
    @if (isset($pdf))
        <script type="text/php">
            if (isset($pdf)) {
                $font = $fontMetrics->getFont("DejaVu Sans", "normal");
                $size = 9;
                $pageText = "Página {PAGE_NUM} de {PAGE_COUNT}";
                $width = $pdf->get_width();
                $x = $width - 120;
                $y = $pdf->get_height() - 28;

                $pdf->page_text($x, $y, $pageText, $font, $size, [0, 0, 0]);
            }
        </script>
    @endif

</body>
</html>
