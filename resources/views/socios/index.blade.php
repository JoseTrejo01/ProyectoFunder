@extends('adminlte::page')

@section('content_header')
    <h1>Listado de Socios</h1>
@stop

@section('content')
    <a href="{{ route('socios.create') }}" class="btn btn-primary mb-3">Nuevo Socio</a>

    {{-- mensajes de éxito --}}
    @if(session('success'))
         <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    {{-- BUSCADOR: colocar aquí --}}
 <form method="GET" action="{{ route('socios.index') }}" class="mb-3">
    <div class="row">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Buscar nombre, DNI, teléfono" value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="genero" class="form-control">
                <option value="">Género</option>
                <option value="M" {{ request('genero')=='M' ? 'selected' : '' }}>Masculino</option>
                <option value="F" {{ request('genero')=='F' ? 'selected' : '' }}>Femenino</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" name="localidad" class="form-control" placeholder="Localidad" value="{{ request('localidad') }}">
        </div>
        <div class="col-md-2">
            <input type="text" name="tipo" class="form-control" placeholder="Tipo de socio" value="{{ request('tipo') }}">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary">
                <i class="fas fa-search"></i> Buscar
            </button>
        </div>
    </div>
</form>
    {{-- FIN BUSCADOR --}}

   <table class="table table-striped table-hover table-bordered">
    <thead class="table-primary">
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
                    <a href="{{ route('socios.edit', $socio->Id_Beneficiario) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <form action="{{ route('socios.destroy', $socio->Id_Beneficiario) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Seguro de inactivar este socio?')">
                            <i class="fas fa-trash"></i> Inactivar
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


    {{-- PAGINACIÓN --}}
    {{ $socios->withQueryString()->links() }}

@stop
