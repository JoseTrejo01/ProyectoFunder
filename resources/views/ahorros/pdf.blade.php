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
                <th>Socios<br><small>(Cantidad / Total / Promedio)</small></th>
                <th>No Socios Adultos<br><small>(Cantidad / Total / Promedio)</small></th>
                <th>No Socios Jóvenes<br><small>(Cantidad / Total / Promedio)</small></th>
            </tr>
        </thead>
        <tbody>
            @foreach($agrupados as $caja => $datos)
                <tr>
                    <td>{{ $caja }}</td>
                    <td>
                        {{ $datos['socios']['cantidad'] }}<br>
                        L {{ number_format($datos['socios']['total'], 2) }}<br>
                        L {{ number_format($datos['socios']['promedio'], 2) }}
                    </td>
                    <td>
                        {{ $datos['no_socios_adultos']['cantidad'] }}<br>
                        L {{ number_format($datos['no_socios_adultos']['total'], 2) }}<br>
                        L {{ number_format($datos['no_socios_adultos']['promedio'], 2) }}
                    </td>
                    <td>
                        {{ $datos['no_socios_jovenes']['cantidad'] }}<br>
                        L {{ number_format($datos['no_socios_jovenes']['total'], 2) }}<br>
                        L {{ number_format($datos['no_socios_jovenes']['promedio'], 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
