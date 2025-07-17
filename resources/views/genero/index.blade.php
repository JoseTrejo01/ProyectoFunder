@extends('adminlte::page')

@section('title', 'Indicadores de Género')

@section('content_header')
    <h1>Listado de Indicadores de Género</h1>
@stop

@section('content')
    <a href="{{ route('genero.create') }}" class="btn btn-success mb-3">➕ Nuevo Registro</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>No.</th>
                    <th>Nombre de Caja Rural</th>
                    <th>Departamento</th>
                    <th>Municipio</th>
                    <th>Comunidad</th>
                    <th>Nombre del Socio(a)</th>
                    <th>Identidad</th>
                    <th>Sexo</th>
                    <th>Edad</th>
                    <th>Cargo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
           <tbody>
    @forelse($indicadores as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->nombre_caja_rural }}</td>
            <td>{{ $item->departamento }}</td>
            <td>{{ $item->municipio }}</td>
            <td>{{ $item->comunidad }}</td>
            <td>{{ $item->nombre_apellidos }}</td>
            <td>{{ $item->identidad }}</td>
            <td>{{ $item->sexo }}</td>
            <td>{{ $item->edad }}</td>
            <td>{{ $item->cargo }}</td>
            <td>
                <a href="{{ route('genero.edit', $item->id) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                <form action="{{ route('genero.destroy', $item->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="10">Sin datos</td>
        </tr>
    @endforelse
</tbody>

        </table>
    </div>
@stop
