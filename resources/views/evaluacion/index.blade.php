@extends('adminlte::page')

@section('title', 'Evaluaciones')

@section('content_header')
    <h1>Evaluaciones</h1>
@endsection

@section('content')
<a href="{{ route('evaluacion.create') }}" class="btn btn-success mb-3">Nueva Evaluación</a>

<table class="table table-bordered table-striped text-center">
    <thead class="bg-success text-white align-middle">
        <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2">Nombre de la Organización</th>
            <th rowspan="2">Departamento</th>
            <th colspan="4">Evaluación Inicial</th>
            <th colspan="4">Evaluación Actualizada</th>
            <th rowspan="2">% de crecimiento</th>
            <th rowspan="2">Acciones</th>
        </tr>
        <tr>
            <th>Desempeño Institucional</th>
            <th>Desempeño Financiero</th>
            <th>Calificación Total</th>
            <th>Categoría</th>

            <th>Desempeño Institucional</th>
            <th>Desempeño Financiero</th>
            <th>Calificación Total</th>
            <th>Categoría</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($evaluaciones as $index => $eva)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $eva->organizacion->Nombre_Organizacion }}</td>
            <td>{{ $eva->organizacion->aldea->municipio->departamento->Nombre_Departamento ?? 'No definido' }}</td>


        {{-- Evaluación Inicial --}}
<td>{{ $eva->porcentaje_institucional }}%</td>
<td>{{ $eva->porcentaje_financiero }}%</td>
<td>{{ $eva->calificacion_total_pct }}%</td>
<td>{{ $eva->categoria_calculada }}</td>

          {{-- Evaluación Actualizada --}}
@if ($eva->actualizada)
    <td>{{ $eva->actualizada->porcentaje_institucional }}%</td>
    <td>{{ $eva->actualizada->porcentaje_financiero }}%</td>
    <td>{{ $eva->actualizada->calificacion_total_pct }}%</td>
    <td>{{ $eva->actualizada->categoria_calculada }}</td>
@else
    <td colspan="4" class="text-muted">Sin actualizar</td>
@endif

            {{-- % de crecimiento (esto puedes ajustar si comparas entre dos evaluaciones) --}}
            <td>0%</td>

            <td>
                <a href="{{ route('evaluacion.edit', $eva->Id_Evaluacion) }}" class="btn btn-sm btn-primary">Editar</a>
                <form action="{{ route('evaluacion.destroy', $eva->Id_Evaluacion) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta evaluación?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">Borrar</button>
    </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
