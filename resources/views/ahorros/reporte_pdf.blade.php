<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ahorros</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 25px;
        }

        h2 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: 700;
            color: #1b263b;
        }

        .subtitle {
            text-align: center;
            font-size: 13px;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #1b263b;
            color: white;
            padding: 8px;
            border: 1px solid #000;
            font-size: 13px;
            text-align: center;
            font-weight: bold;
        }

        td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 12px;
        }

        tr:nth-child(even) {
            background: #f5f5f5;
        }

        .amount {
            font-weight: bold;
            color: #0a611c;
        }

        footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>

<body>

    <h2>Reporte de Ahorros</h2>
    <div class="subtitle">
        Fecha de generación: {{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}
    </div>

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

                    <td class="amount">
                        L. {{ number_format($ahorro->Monto, 2, '.', ',') }}
                    </td>

                    <td>{{ \Carbon\Carbon::parse($ahorro->Fecha)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        Sistema de Gestión de Ahorros &mdash; Funder © {{ date('Y') }}
    </footer>

</body>
</html>
