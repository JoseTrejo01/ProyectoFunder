<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Socios</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #333;
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #dddddd;
        }
    </style>
</head>
<body>
    <h2>Listado de Socios</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Género</th>
                <th>Tipo de Socio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($socios as $socio)
                <tr>
                    <td>{{ $socio->Nombre_Beneficiario }}</td>
                    <td>{{ $socio->DNI }}</td>
                    <td>{{ $socio->Telefono }}</td>
                    <td>{{ $socio->genero }}</td>
                    <td>{{ $socio->Tipo_De_Socio }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
