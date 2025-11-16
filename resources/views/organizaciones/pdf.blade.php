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
            color: #000000;
        }

        /* ENCABEZADO */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid #000;
            padding-bottom: 8px;
            margin-bottom: 6px;
        }

        .left-info {
            font-size: 9px;
            line-height: 1.4;
            max-width: 35%;
        }

        .left-info-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .center-title {
            text-align: center;
            flex-grow: 1;
            font-weight: bold;
            font-size: 13px;
            line-height: 1.4;
        }

        .report-subtitle {
            font-size: 11px;
            font-weight: normal;
            margin-top: 2px;
        }

        .logo-qr-wrapper {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
            max-width: 30%;
        }

        .logo {
            text-align: right;
        }

        .logo img {
            height: 52px;
        }

        .qr-wrapper {
            text-align: center;
            font-size: 8px;
        }

        .qr-wrapper img {
            width: 60px;
            height: 60px;
        }

        /* TABLAS */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .table caption {
            caption-side: top;
            text-align: left;
            font-weight: bold;
            margin-bottom: 4px;
            font-size: 11px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }

        /* Mayor contraste para encabezados */
        .table thead th {
            background-color: #d9d9d9; /* gris más oscuro para mejor contraste */
            font-weight: bold;
            font-size: 9.5px;
        }

        /* Filas alternadas para mejorar legibilidad */
        .table tbody tr:nth-child(odd) {
            background-color: #f7f7f7;
        }

        .table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .text-left {
            text-align: left;
        }

        .estado-activo {
            font-weight: bold;
            background-color: #1b5e20;
            color: #ffffff;
            padding: 1px 3px;
            border-radius: 2px;
        }

        .estado-inactivo {
            font-weight: bold;
            background-color: #b71c1c;
            color: #ffffff;
            padding: 1px 3px;
            border-radius: 2px;
        }

        /* SECCIÓN DE RESUMEN / TOTALES */
        .summary-section {
            margin-top: 12px;
        }

        .summary-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 4px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #000;
            padding: 3px;
            text-align: left;
            vertical-align: middle;
        }

        .summary-table th {
            background-color: #c5e0b4; /* verde claro con buen contraste */
        }

        .summary-table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .summary-table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .summary-label {
            font-weight: bold;
        }

        .summary-highlight {
            font-weight: bold;
            background-color: #ffe699;
        }

        /* TABLA POR DEPARTAMENTO */
        .dept-table-wrapper {
            margin-top: 10px;
        }

        .dept-table caption {
            font-weight: bold;
            margin-bottom: 4px;
            font-size: 11px;
        }

        .dept-table th {
            background-color: #bdd7ee; /* azul suave con buen contraste */
        }

        /* SECCIÓN DE FIRMAS */
        .signatures {
            margin-top: 24px;
        }

        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 9.5px;
        }

        .signatures-table td {
            text-align: center;
            padding: 10px 6px 0 6px;
            vertical-align: bottom;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin: 0 auto 2px auto;
            width: 80%;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 2px;
        }

        .signature-role {
            font-size: 9px;
        }

        .signature-date {
            font-size: 9px;
            margin-top: 2px;
        }

        /* OTROS */
        .small-text {
            font-size: 8.5px;
        }
    </style>
</head>
<body>

{{-- Cálculos previos --}}
@php
    $totalOrganizaciones = $organizaciones->count();
    $totalActivas = 0;
    $totalInactivas = 0;
    $totalConRTN = 0;
    $totalSinRTN = 0;
    $totalConPersoneria = 0;
    $totalSinPersoneria = 0;
    $totalConCuenta = 0;
    $totalSinCuenta = 0;
    $totalSinCoordenadas = 0;

    $porDepartamento = [];

    foreach ($organizaciones as $org) {
        // Estado
        if ($org->Estado_Organizacion === 'ACTIVO') {
            $totalActivas++;
        } else {
            $totalInactivas++;
        }

        // RTN
        if (!empty($org->rtn)) {
            $totalConRTN++;
        } else {
            $totalSinRTN++;
        }

        // Personería
        if ($org->tiene_personeria_juridica) {
            $totalConPersoneria++;
        } else {
            $totalSinPersoneria++;
        }

        // Cuenta bancaria
        if ($org->tiene_cuenta_bancaria) {
            $totalConCuenta++;
        } else {
            $totalSinCuenta++;
        }

        // Coordenadas (por municipio)
        $tieneCoord = $org->aldea
            && $org->aldea->municipio
            && $org->aldea->municipio->coordenada
            && $org->aldea->municipio->coordenada->coordenada_x !== null
            && $org->aldea->municipio->coordenada->coordenada_y !== null;

        if (!$tieneCoord) {
            $totalSinCoordenadas++;
        }

        // Conteo por departamento
        $nombreDepto = $org->aldea && $org->aldea->municipio && $org->aldea->municipio->departamento
            ? $org->aldea->municipio->departamento->Nombre_Departamento
            : 'No definido';

        if (!isset($porDepartamento[$nombreDepto])) {
            $porDepartamento[$nombreDepto] = 0;
        }
        $porDepartamento[$nombreDepto]++;
    }
@endphp

{{-- ENCABEZADO --}}
<header class="header" role="banner" aria-label="Encabezado del reporte de organizaciones">
    <div class="left-info">
        <div class="left-info-title">Funder</div>
        <div>Fecha de emisión: {{ now()->format('Y/m/d') }}</div>
        <div>Dirección: <span>___________________________</span></div>
        <div>Tel.: <span>________________</span></div>
        <div>Correo: <span>info@funder.org.hn</span></div>
        <div>Sitio web: <span>https://funder.org.hn/</span></div>
    </div>

    <div class="center-title">
        <div>FUNDER</div>
        <div>Reporte de Organizaciones</div>
        <div class="report-subtitle">Listado consolidado de organizaciones rurales registradas</div>
    </div>

    <div class="logo-qr-wrapper">
        <div class="logo">
            <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logotipo de Funder">
        </div>
        <div class="qr-wrapper">
            <div class="small-text">Ir al sitio web</div>
            <img
                src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=https://funder.org.hn/"
                alt="Código QR que apunta al sitio web de Funder (https://funder.org.hn/)"
            >
        </div>
    </div>
</header>

<main role="main" aria-label="Contenido principal del reporte de organizaciones">

    {{-- TABLA DE DATOS --}}
    <section aria-labelledby="tablaOrganizacionesTitulo">
        <table class="table" role="table" aria-describedby="tablaOrganizacionesDescripcion">
            <caption id="tablaOrganizacionesTitulo">
                Organizaciones registradas
            </caption>
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre de la Organización</th>
                    <th scope="col">Departamento</th>
                    <th scope="col">Municipio</th>
                    <th scope="col">Aldea</th>
                    <th scope="col">RTN</th>
                    <th scope="col">Personería Jurídica</th>
                    <th scope="col">Cuenta Bancaria</th>
                    <th scope="col">Estado</th>
                </tr>
            </thead>
            <tbody>
                @php $count = 1; @endphp
                @forelse($organizaciones as $org)
                    @php
                        $depto = $org->aldea && $org->aldea->municipio && $org->aldea->municipio->departamento
                            ? $org->aldea->municipio->departamento->Nombre_Departamento
                            : 'N/D';

                        $muni = $org->aldea && $org->aldea->municipio
                            ? $org->aldea->municipio->Nombre_Municipio
                            : 'N/D';

                        $aldea = $org->aldea
                            ? $org->aldea->Nombre_Aldea
                            : 'N/D';

                        $tieneRTN = !empty($org->rtn);
                        $tienePers = $org->tiene_personeria_juridica ? 'Sí' : 'No';
                        $tieneCuenta = $org->tiene_cuenta_bancaria ? 'Sí' : 'No';
                        $esActivo = $org->Estado_Organizacion === 'ACTIVO';
                    @endphp
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ $org->Id_Organizacion }}</td>
                        <td class="text-left">{{ $org->Nombre_Organizacion }}</td>
                        <td>{{ $depto }}</td>
                        <td>{{ $muni }}</td>
                        <td>{{ $aldea }}</td>
                        <td>{{ $tieneRTN ? $org->rtn : 'N/A' }}</td>
                        <td>{{ $tienePers }}</td>
                        <td>{{ $tieneCuenta }}</td>
                        <td>
                            @if($esActivo)
                                <span class="estado-activo">ACTIVO</span>
                            @else
                                <span class="estado-inactivo">INACTIVO</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">No hay organizaciones para mostrar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p id="tablaOrganizacionesDescripcion" class="small-text">
            Nota: Este listado incluye las organizaciones registradas en el sistema Funder, con información
            sobre su ubicación, estado legal, cuenta bancaria y estado de activación.
        </p>
    </section>

    {{-- RESUMEN Y TOTALES --}}
    <section class="summary-section" aria-labelledby="resumenTitulo">
        <h2 id="resumenTitulo" class="summary-title">Resumen general de organizaciones</h2>

        <table class="summary-table" role="table" aria-label="Resumen estadístico de organizaciones">
            <tbody>
                <tr>
                    <td class="summary-label">Total de organizaciones registradas</td>
                    <td class="summary-highlight">{{ $totalOrganizaciones }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Organizaciones activas</td>
                    <td>{{ $totalActivas }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Organizaciones inactivas</td>
                    <td>{{ $totalInactivas }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Con personería jurídica</td>
                    <td>{{ $totalConPersoneria }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Sin personería jurídica</td>
                    <td>{{ $totalSinPersoneria }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Con cuenta bancaria</td>
                    <td>{{ $totalConCuenta }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Sin cuenta bancaria</td>
                    <td>{{ $totalSinCuenta }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Con RTN</td>
                    <td>{{ $totalConRTN }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Sin RTN</td>
                    <td>{{ $totalSinRTN }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Organizaciones sin coordenadas asociadas</td>
                    <td>{{ $totalSinCoordenadas }}</td>
                </tr>
            </tbody>
        </table>
    </section>

    {{-- RESUMEN POR DEPARTAMENTO --}}
    <section class="dept-table-wrapper" aria-labelledby="resumenDeptosTitulo">
        <h2 id="resumenDeptosTitulo" class="summary-title">Distribución de organizaciones por departamento</h2>

        <table class="summary-table dept-table" role="table" aria-label="Organizaciones por departamento">
            <thead>
                <tr>
                    <th scope="col">Departamento</th>
                    <th scope="col">Número de Organizaciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($porDepartamento as $deptoNombre => $cantidad)
                    <tr>
                        <td>{{ $deptoNombre }}</td>
                        <td>{{ $cantidad }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No hay datos de departamentos para mostrar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    {{-- SECCIÓN DE FIRMAS --}}
    <section class="signatures" aria-labelledby="firmasTitulo">
        <h2 id="firmasTitulo" class="summary-title">Firmas de validación</h2>

        <table class="signatures-table" role="table" aria-label="Firmas de responsables del reporte">
            <tbody>
                <tr>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-name">______________________________</div>
                        <div class="signature-role">Responsable de la información</div>
                        <div class="signature-date">Fecha: __________________</div>
                    </td>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-name">______________________________</div>
                        <div class="signature-role">Coordinación / Supervisión</div>
                        <div class="signature-date">Fecha: __________________</div>
                    </td>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-name">______________________________</div>
                        <div class="signature-role">Dirección de Programa</div>
                        <div class="signature-date">Fecha: __________________</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

</main>

{{-- PIE DE PÁGINA CON PAGINACIÓN --}}
@if (isset($pdf))
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont("DejaVu Sans", "normal");
            $size = 9;
            $pageText = "Página {PAGE_NUM} de {PAGE_COUNT}  |  https://funder.org.hn/";
            $width = $pdf->get_width();
            $x = $width - 220; // un poco más largo para incluir la URL
            $y = $pdf->get_height() - 30;

            $pdf->page_text($x, $y, $pageText, $font, $size, [0, 0, 0]);
        }
    </script>
@endif

</body>
</html>
