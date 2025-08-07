<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Organizaciones - Funder</title>
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
        <div>Reporte de Organizaciones</div>
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
            <th>ID</th>
            <th>Nombre Organización</th>
            <th>Departamento</th>
            <th>Municipio</th>
            <th>Aldea</th>
            <th>RTN</th>
            <th>Personería Jurídica</th>
            <th>Cuenta Bancaria</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @php $count = 1; @endphp
        @forelse($organizaciones as $org)
            <tr>
                <td>{{ $count++ }}</td>
                <td>{{ $org->Id_Organizacion }}</td>
                <td style="text-align: left;">{{ $org->Nombre_Organizacion }}</td>
                <td>{{ $org->aldea && $org->aldea->municipio && $org->aldea->municipio->departamento ? $org->aldea->municipio->departamento->Nombre_Departamento : 'N/D' }}</td>
                <td>{{ $org->aldea && $org->aldea->municipio ? $org->aldea->municipio->Nombre_Municipio : 'N/D' }}</td>
                <td>{{ $org->aldea ? $org->aldea->Nombre_Aldea : 'N/D' }}</td>
                <td>{{ $org->rtn ?? 'N/A' }}</td>
                <td>{{ $org->tiene_personeria_juridica ? 'Sí' : 'No' }}</td>
                <td>{{ $org->tiene_cuenta_bancaria ? 'Sí' : 'No' }}</td>
                <td>{{ $org->Estado_Organizacion }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10">No hay organizaciones para mostrar.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- PIE DE PÁGINA CON PAGINACIÓN --}}
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
