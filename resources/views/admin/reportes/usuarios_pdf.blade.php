<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Usuarios - FUNDER</title>

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

        /* ========= ENCABEZADO ========= */
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

        /* ========= TABLA ========= */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 3px;
            vertical-align: middle;
            text-align: center;
        }

        th {
            background-color: #eaeaea;
            font-weight: bold;
        }

        .text-left {
            text-align: left;
        }

        .no-data {
            text-align: center;
            padding: 10px;
            font-style: italic;
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
            <div>Reporte de Usuarios</div>
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
                <th style="width: 85px;">Usuario</th>
                <th style="width: 170px;">Nombre</th>
                <th style="width: 180px;">Correo</th>
                <th style="width: 110px;">Rol</th>
                <th style="width: 65px;">Estado</th>
                <th style="width: 95px;">Creación</th>
                <th style="width: 110px;">Vencimiento</th>
            </tr>
        </thead>

        <tbody>
            @php $count = 1; @endphp

            @forelse($usuarios as $usuario)
                <tr>
                    <td>{{ $count++ }}</td>
                    <td>{{ $usuario->Usuario }}</td>
                    <td class="text-left">{{ $usuario->Nombre_Usuario }}</td>
                    <td class="text-left">{{ $usuario->Correo_Electronico }}</td>
                    <td>{{ $usuario->rol->Rol ?? 'N/D' }}</td>
                    <td>{{ $usuario->Estado_Usuario }}</td>
                    <td>{{ \Carbon\Carbon::parse($usuario->Fecha_Creacion)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($usuario->Fecha_Vencimiento)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="no-data">No hay usuarios para mostrar.</td>
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
