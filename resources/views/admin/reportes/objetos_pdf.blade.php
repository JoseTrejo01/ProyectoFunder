<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Objetos - FUNDER</title>

    <style>
        @page {
            margin: 30px 40px;
            footer: pie;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10.5px;
            margin: 0;
            line-height: 1.35;
        }

        /* ================= ENCABEZADO ================= */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 8px;
            margin-bottom: 12px;
            border-bottom: 1px solid #000;
        }

        .left-info {
            font-size: 10px;
        }

        .center-title {
            text-align: center;
            flex-grow: 1;
            font-size: 13px;
            font-weight: bold;
        }

        .logo img {
            height: 55px;
        }

        /* ================= TABLA ================= */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
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

        td.text-left {
            text-align: left;
        }

        tr.no-data td {
            padding: 10px;
            text-align: center;
            font-style: italic;
            background-color: #fafafa;
        }

        .totals {
            font-weight: bold;
            background-color: #e6e6e6;
        }
    </style>
</head>

<body>

    {{-- ================= ENCABEZADO ================= --}}
    <div class="header">
        <div class="left-info">
            <strong>FECHA:</strong> {{ now()->format('d/m/Y') }}
        </div>

        <div class="center-title">
            <div>FUNDER</div>
            <div>Reporte de Objetos</div>
        </div>

        <div class="logo">
            <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo FUNDER">
        </div>
    </div>

    {{-- ================= TABLA ================= --}}
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No.</th>
                <th style="width: 60px;">ID</th>
                <th style="width: 160px;">Nombre del Objeto</th>
                <th>Descripción</th>
                <th style="width: 120px;">Tipo de Objeto</th>
                <th style="width: 70px;">Estado</th>
            </tr>
        </thead>

        <tbody>
            @php $count = 1; @endphp

            @forelse($objetos as $objeto)
                <tr>
                    <td class="text-center">{{ $count++ }}</td>
                    <td class="text-center">{{ $objeto->Id_Objeto }}</td>
                    <td class="text-left">{{ $objeto->Objeto }}</td>
                    <td class="text-left">{{ $objeto->Descripcion ?: 'Sin descripción' }}</td>
                    <td class="text-center">{{ $objeto->Tipo_Objeto ?: 'No especificado' }}</td>
                    <td class="text-center">{{ $objeto->Estado }}</td>
                </tr>
            @empty
                <tr class="no-data">
                    <td colspan="6">No hay objetos para mostrar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ================= PIE DE PÁGINA ================= --}}
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
