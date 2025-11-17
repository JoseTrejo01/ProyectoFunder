<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Ahorros</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>

    <h2>Reporte General de Ahorros</h2>

    <table>
        <thead>
            <tr>
<<<<<<< HEAD
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
=======
                <th>Beneficiario</th>
                <th>Organización</th>
                <th>Tipo</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ahorros as $a)
                <tr>
                    <td>{{ $a->beneficiario->Nombre_Beneficiario }}</td>
                    <td>{{ $a->organizacion->Nombre_Organizacion }}</td>
                    <td>{{ $a->beneficiario->Tipo_De_Socio }}</td>
                    <td>L. {{ number_format($a->Monto, 2) }}</td>
>>>>>>> origin/cambios-seguridad
                </tr>
            @endforeach
        </tbody>
    </table>

<<<<<<< HEAD
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

=======
>>>>>>> origin/cambios-seguridad
</body>
</html>
