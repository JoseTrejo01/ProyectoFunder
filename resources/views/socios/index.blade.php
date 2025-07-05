@extends('adminlte::page')

@section('content_header')
    <h1>Listado de Socios</h1>
@stop

@section('content')
    <a href="{{ route('socios.create') }}" class="btn btn-primary mb-3">Nuevo Socio</a>

    {{-- mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- BUSCADOR: colocar aquí --}}
    <form method="GET" action="{{ route('socios.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, DNI o teléfono" value="{{ request('search') }}">
            <button class="btn btn-primary">Buscar</button>
        </div>
    </form>
    {{-- FIN BUSCADOR --}}

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($socios as $socio)
                <tr>
                    <td>{{ $socio->Nombre_Beneficiario }}</td>
                    <td>{{ $socio->DNI }}</td>
                    <td>{{ $socio->Telefono }}</td>
                    <td>
                        <a href="{{ route('socios.edit', $socio->Id_Beneficiario) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('socios.destroy', $socio->Id_Beneficiario) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Seguro de inactivar este socio?')">Inactivar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- PAGINACIÓN --}}
    {{ $socios->withQueryString()->links() }}

@stop
