@extends('adminlte::page')

@section('title', 'Lista de Criterios')

@section('content_header')
    <h1>Lista de Criterios</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('criterio.create') }}" class="btn btn-primary mb-3">Nuevo Criterio</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Variable</th>
                <th>Descripción</th>
                <th>Subíndice</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($criterios as $criterio)
                <tr>
                    <td>{{ $criterio->variable }}</td>
                    <td>{{ $criterio->descripcion }}</td>
                    <td>{{ $criterio->subindice }}</td>
                    <td>
                        <a href="{{ route('criterio.edit', ['criterio' => $criterio->id]) }}" class="btn btn-warning btn-sm">Editar</a>
                       <form action="{{ route('criterio.destroy', ['criterio' => $criterio->id]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar este criterio?');">
    @csrf
    @method('DELETE')
    <button class="btn btn-danger btn-sm">Eliminar</button>
</form>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
