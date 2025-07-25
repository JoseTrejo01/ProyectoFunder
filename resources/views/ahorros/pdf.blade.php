<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Ahorros</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Reporte de Ahorros</h2>
    <table>
        <thead>
            <tr>
                <th>Caja Rural</th>
                <th>Socios (No / Ahorros / Promedio)</th>
                <th>Adultos</th>
                <th>Niños</th>
                <th>Subtotal No Socios</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ahorros as $a)
                <tr>
                    <td>{{ $a->nombre_caja_rural }}</td>
                    <td>{{ $a->socios_no }} / L {{ number_format($a->socios_ahorros, 2) }} / L {{ number_format($a->socios_promedio, 2) }}</td>
                    <td>{{ $a->adultos_no }} / L {{ number_format($a->adultos_ahorros, 2) }} / L {{ number_format($a->adultos_promedio, 2) }}</td>
                    <td>{{ $a->ninos_no }} / L {{ number_format($a->ninos_ahorros, 2) }} / L {{ number_format($a->ninos_promedio, 2) }}</td>
                    <td>{{ $a->subtotal_no_socios_no }} / L {{ number_format($a->subtotal_no_socios_ahorros, 2) }} / L {{ number_format($a->subtotal_no_socios_promedio, 2) }}</td>
                    <td>{{ $a->total_no }} / L {{ number_format($a->total_ahorros, 2) }} / L {{ number_format($a->total_promedio, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
