<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Socios - Funder</title>

    <style>
        @page {
            margin: 30px 40px;
            footer: pie;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5px;
            margin: 0;
            color: #000;
        }

        /* ENCABEZADO */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1.8px solid #0D47A1;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .left-info {
            font-size: 10px;
            color: #333;
        }

        .center-title {
            text-align: center;
            flex-grow: 1;
            font-weight: bold;
            font-size: 14px;
            color: #0D47A1;
            line-height: 1.2;
        }

        .center-title div:first-child {
            font-size: 15px;
        }

        .logo img {
            height: 55px;
        }

        /* TABLA */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 10px;
        }

        .table th, .table td {
            border: 1px solid #000;
            padding: 4px 3px;
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #E3ECF9; /* Azul muy claro FUNDER */
            font-weight: bold;
            color: #0D47A1;
        }

        .table tbody tr:nth-child(even) {
            background-color: #F7F7F7;
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
        <div>Reporte de Socios</div>
    </div>

    <div class="logo">
        <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo Funder">
    </div>
</div>

{{-- TABLA --}}
<table class="table" role="table" aria-label="Tabla de reporte de socios">
    <thead>
        <tr role="row">
            <th>No.</th>
            <th>Nombre</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Género</th>
            <th>Estado Civil</th>
            <th>Nivel Educativo</th>
            <th>Departamento</th>
            <th>Municipio</th>
            <th>Comunidad</th>
            <th>Tipo Socio</th>
            <th>Estado</th>
        </tr>
    </thead>

    <tbody>
        @php $count = 1; @endphp

        @forelse($socios as $socio)
        <tr>
            <td>{{ $count++ }}</td>
            <td style="text-align:left;">{{ $socio->Nombre_Beneficiario }}</td>
            <td>{{ $socio->DNI }}</td>
            <td>{{ $socio->Telefono }}</td>
            <td>{{ $socio->genero }}</td>
            <td>{{ $socio->estado_civil }}</td>
            <td>{{ $socio->nivel_educativo }}</td>
            <td>{{ $socio->departamento }}</td>
            <td>{{ $socio->municipio }}</td>
            <td>{{ $socio->comunidad }}</td>
            <td>{{ $socio->Tipo_De_Socio }}</td>
            <td>{{ $socio->estado == 1 ? 'Activo' : 'Inactivo' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="12" style="text-align:center; font-style:italic;">
                No hay socios para mostrar.
            </td>
        </tr>
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
            $x = $width - 120;
            $y = $pdf->get_height() - 28;
            $pdf->page_text($x, $y, $pageText, $font, $size, [0,0,0]);
        }
    </script>
@endif

</body>
</html>
