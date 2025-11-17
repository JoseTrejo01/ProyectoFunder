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

        /* ---------- ENCABEZADO FUNDER ---------- */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0D47A1;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .left-info {
            font-size: 9px;
            line-height: 1.4;
            max-width: 35%;
        }

        .left-info-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 4px;
            color: #0D47A1;
            text-transform: uppercase;
        }

        .center-title {
            text-align: center;
            flex-grow: 1;
            font-weight: bold;
            color: #0D47A1;
            line-height: 1.3;
        }

        .center-title .main {
            font-size: 15px;
        }

        .center-title .sub {
            font-size: 13px;
            margin-top: 2px;
        }

        .logo-qr {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 5px;
        }

        .logo-qr img.logo {
            height: 55px;
        }

        .qr-box img {
            width: 65px;
            height: 65px;
        }

        .qr-box {
            text-align: center;
            font-size: 8px;
            color: #444;
        }

        /* ---------- TABLA ---------- */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }

        .table th, .table td {
            border: 1px solid #000;
            padding: 4px 3px;
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #E3ECF9;
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

{{-- ------------ ENCABEZADO ESTILO FUNDER ------------ --}}
<header class="header">
    <div class="left-info">
        <div class="left-info-title">FUNDER</div>
        <div>Fecha de emisión: {{ now()->format('Y/m/d') }}</div>
        <div>Dirección: ___________________________</div>
        <div>Tel.: _______________</div>
        <div>Correo: info@funder.org.hn</div>
        <div>Sitio web: https://funder.org.hn/</div>
    </div>

    <div class="center-title">
        <div class="main">FUNDER</div>
        <div class="sub">Reporte de Socios</div>
        <div style="font-size: 11px; margin-top:3px; font-weight: normal;">
            Listado de socios y clientes registrados
        </div>
    </div>

    <div class="logo-qr">
        <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" class="logo" alt="Logo Funder">

        <div class="qr-box">
            <div>Ir al Sitio Web</div>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=https://funder.org.hn/"
                 alt="Código QR Funder">
        </div>
    </div>
</header>

{{-- ------------ TABLA REPORTES ------------ --}}
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


{{-- ------------ PIE DE PÁGINA ------------ --}}
@if (isset($pdf))
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont("DejaVu Sans", "normal");
            $size = 9;
            $pageText = "Página {PAGE_NUM} de {PAGE_COUNT}  |  https://funder.org.hn/";
            $width = $pdf->get_width();
            $x = $width - 220;
            $y = $pdf->get_height() - 28;
            $pdf->page_text($x, $y, $pageText, $font, $size, [0,0,0]);
        }
    </script>
@endif

</body>
</html>
