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
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
