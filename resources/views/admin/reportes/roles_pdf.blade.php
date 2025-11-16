<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Roles - FUNDER</title>

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

        /* ====== ENCABEZADO ====== */
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
            flex-grow: 1;
            text-align: center;
            font-size: 13px;
            font-weight: bold;
        }

        .logo img {
            height: 55px;
        }

        /* ====== TABLA ====== */
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
            text-align: center;
        }

        table th {
            background-color: #eaeaea;
            font-weight: bold;
        }

        td.text-left {
            text-align: left;
        }

        .no-data {
            font-style: italic;
            text-align: center;
            padding: 10px;
            background-color: #fafafa;
        }
    </style>
</head>

<body>

    {{-- ========= ENCABEZADO ========= --}}
    <div class="header">
        <div class="left-info">
            <strong>FECHA:</strong> {{ now()->format('d/m/Y') }}
        </div>

        <div class="center-title">
            <div>FUNDER</div>
            <div>Reporte de Roles</div>
        </div>

        <div class="logo">
            <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo FUNDER">
        </div>
    </div>

    {{-- ========= TABLA ========= --}}
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No.</th>
                <th style="width: 170px;">Rol</th>
                <th>Descripción</th>
                <th style="width: 80px;">Estado</th>
            </tr>
        </thead>

        <tbody>
            @php $count = 1; @endphp

            @forelse($roles as $rol)
                <tr>
                    <td>{{ $count++ }}</td>
                    <td class="text-left">{{ $rol->Rol }}</td>
                    <td class="text-left">{{ $rol->Descripcion ?: 'Sin descripción' }}</td>
                    <td>{{ $rol->Estado }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="no-data">No hay roles para mostrar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ========= PIE DE PÁGINA ========= --}}
    @if (isset($pdf))
        <script type="text/php">
            if (isset($pdf)) {
                $font = $fontMetrics->getFont("DejaVu Sans", "normal");
                $size = 9;

                $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
                $width = $pdf->get_width();
                $x = $width - 120;
                $y = $pdf->get_height() - 28;

                $pdf->page_text($x, $y, $text, $font, $size, [0,0,0]);
            }
        </script>
    @endif

</body>
</html>
