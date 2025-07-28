@extends('adminlte::page')

@section('title', 'Evaluaciones')

@section('content_header')
    <h1>Evaluaciones</h1>
@endsection

@section('content')
    <a href="{{ route('evaluacion.create') }}" class="btn btn-success mb-3">Nueva Evaluación</a>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Organización</th>
                <th>Criterio</th>
                <th>Inicial</th>
                <th>Actualizada</th>
                <th>Ponderación Inicial</th>
                <th>Ponderación Actual</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($evaluaciones as $eva)
            <tr>
                <td>{{ $eva->id }}</td>
                <td>{{ optional($eva->organizacion)->Nombre_Organizacion }}</td>
                     <td>
                    @php
                        echo match($eva->criterio_id) {
                            1 => 'Excelente',
                            2 => 'Bueno',
                            3 => 'Malo',
                            default => 'No definido',
                        };
                    @endphp
                </td>
                <td>{{ $eva->puntuacion_inicial }}</td>
                <td>{{ $eva->puntuacion_actualizada }}</td>
                <td>{{ $eva->ponderacion_inicial }}</td>
                <td>{{ $eva->ponderacion_actual }}</td>
                <td>
                    <a href="{{ route('evaluacion.edit', $eva) }}" class="btn btn-sm btn-primary">Editar</a>
                    <form action="{{ route('evaluacion.destroy', $eva) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta evaluación?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
