<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ahorros</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>Reporte de Ahorros</h2>
    <table>
        <thead>
            <tr>
                <th>Socio</th>
                <th>Monto</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ahorros as $ahorro)
            <tr>
                <td>{{ $ahorro->beneficiario->Nombre_Beneficiario ?? 'N/A' }}</td>
                <td>{{ number_format($ahorro->Monto, 2) }}</td>
                <td>{{ $ahorro->Fecha }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
