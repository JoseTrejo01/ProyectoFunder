<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ahorros - FUNDER</title>

    <style>
        @page {
            margin: 30px 40px;
            footer: pie;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
            color: #000;
        }

        /* =====================================
           ENCABEZADO - ESTILO FUNDER
        ====================================== */
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
            font-size: 14px;
            line-height: 1.4;
        }

        .report-subtitle {
            font-size: 11px;
            margin-top: 2px;
        }

        .logo-qr-wrapper {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
            max-width: 30%;
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

        /* =====================================
           TABLA DE AHORROS
        ====================================== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #1b4332;
            color: #fff;
            padding: 7px;
            border: 1px solid #000;
            font-size: 11px;
        }

        td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 10.5px;
        }

        tr:nth-child(even) {
            background: #eef6f1;
        }

        .amount {
            font-weight: bold;
            color: #40916c;
        }

        /* =====================================
           FOOTER
        ====================================== */
        footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #2d6a4f;
            border-top: 1px solid #ccc;
            padding-top: 3px;
        }
    </style>
</head>

<body>

{{-- ======================================
     ENCABEZADO FUNDER
====================================== --}}
<header class="header">
    <div class="left-info">
        <div class="left-info-title">Funder</div>
        <div>Fecha de emisión: {{ now()->format('Y/m/d') }}</div>
        <div>Correo: info@funder.org.hn</div>
        <div>Sitio web: https://funder.org.hn</div>
    </div>

    <div class="center-title">
        <div>FUNDER</div>
        <div>Reporte de Ahorros</div>
        <div class="report-subtitle">Detalle consolidado de depósitos registrados</div>
    </div>

    <div class="logo-qr-wrapper">
        <div class="logo">
            <img src="{{ public_path('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo FUNDER">
        </div>

        <div class="qr-wrapper">
            <div>Sitio web</div>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=https://funder.org.hn/" alt="QR FUNDER">
        </div>
    </div>
</header>

{{-- ===================================
     TABLA DE AHORROS
=================================== --}}
<main>

    <table>
        <thead>
            <tr>
                <th>Socio / Cliente</th>
                <th>Monto (Lps)</th>
                <th>Fecha</th>
            </tr>
        </thead>

        <tbody>
            @foreach($ahorros as $ahorro)
                <tr>
                    <td>{{ $ahorro->beneficiario->Nombre_Beneficiario ?? 'N/A' }}</td>
                    <td class="amount">L. {{ number_format($ahorro->Monto, 2, '.', ',') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ahorro->Fecha)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</main>

{{-- ===================================
     FOOTER CON PAGINACIÓN
=================================== --}}
@if (isset($pdf))
<script type="text/php">
if (isset($pdf)) {
    $font = $fontMetrics->getFont("DejaVu Sans", "normal");
    $size = 9;
    $pageText = "Página {PAGE_NUM} de {PAGE_COUNT}  |  FUNDER — Sistema de Ahorros";
    $width = $pdf->get_width();
    $x = ($width - 200);
    $y = $pdf->get_height() - 28;

    $pdf->page_text($x, $y, $pageText, $font, $size, [0, 0, 0]);
}
</script>
@endif

<footer>
    FUNDER © {{ date('Y') }} — Sistema de Gestión de Ahorros
</footer>

</body>
</html>
