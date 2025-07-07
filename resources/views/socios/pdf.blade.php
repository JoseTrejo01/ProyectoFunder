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
<table border="1" width="100%" cellspacing="0" cellpadding="4">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Género</th>
            <th>Estado Civil</th>
            <th>Nivel Educativo</th>
            <th>Medio Comunicación</th>
            <th>Departamento</th>
            <th>Municipio</th>
            <th>Comunidad</th>
            <th>Tipo Cargo</th>
            <th>Tipo Socio</th>
            <th>Categoría</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($socios as $socio)
        <tr>
            <td>{{ $socio->Nombre_Beneficiario }}</td>
            <td>{{ $socio->DNI }}</td>
            <td>{{ $socio->Telefono }}</td>
            <td>{{ $socio->genero }}</td>
            <td>{{ $socio->estado_civil }}</td>
            <td>{{ $socio->nivel_educativo }}</td>
            <td>{{ $socio->medio_comunicacion }}</td>
            <td>{{ $socio->departamento }}</td>
            <td>{{ $socio->municipio }}</td>
            <td>{{ $socio->comunidad }}</td>
            <td>{{ $socio->Tipo_Cargo }}</td>
            <td>{{ $socio->Tipo_De_Socio }}</td>
            <td>{{ $socio->categoria }}</td>
            <td>{{ $socio->estado == 1 ? 'Activo' : 'Inactivo' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
