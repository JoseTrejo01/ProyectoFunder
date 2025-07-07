@extends('adminlte::page')

@section('content_header')
    <h1>Listado de Socios</h1>
@stop

@section('content')
    {{-- BOTONES --}}
    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('socios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Socio
        </a>
        <div>
            <a href="{{ route('socios.export') }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
            <a href="{{ route('socios.export-pdf', request()->query()) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
        </div>
    </div>
    {{-- FIN BOTONES --}}

    {{-- MENSAJES DE ÉXITO --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- BUSCADOR --}}
    <form method="GET" action="{{ route('socios.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-2">
                <input type="text" name="search" class="form-control"
                       placeholder="Buscar nombre, DNI, teléfono"
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="genero" class="form-control">
                    <option value="">Género</option>
                    <option value="M" {{ request('genero')=='M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ request('genero')=='F' ? 'selected' : '' }}>Femenino</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="localidad" class="form-control"
                       placeholder="Localidad" value="{{ request('localidad') }}">
            </div>
            <div class="col-md-2">
                <input type="text" name="tipo" class="form-control"
                       placeholder="Tipo de socio" value="{{ request('tipo') }}">
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-control">
                    <option value="">Todos</option>
                    <option value="1" {{ request('estado')=='1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ request('estado')=='0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
                <div class="col-md-2 mt-2">
    <select name="departamento" class="form-control">
        <option value="">Departamento</option>
        @foreach(["Atlántida","Choluteca","Colón","Comayagua","Copán","Cortés","El Paraíso","Francisco Morazán","Gracias a Dios","Intibucá","Islas de la Bahía","La Paz","Lempira","Ocotepeque","Olancho","Santa Bárbara","Valle","Yoro"] as $dep)
            <option value="{{ $dep }}" {{ request('departamento') == $dep ? 'selected' : '' }}>{{ $dep }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-2 mt-2">
    <select name="estado_civil" class="form-control">
        <option value="">Estado Civil</option>
        @foreach(["Soltero(a)","Casado(a)","Unión Libre","Viudo(a)"] as $estado)
            <option value="{{ $estado }}" {{ request('estado_civil') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-2 mt-2">
    <select name="nivel_educativo" class="form-control">
        <option value="">Nivel Educativo</option>
        @foreach(["Sin estudios","Educación básica","Educación media","Educación superior"] as $nivel)
            <option value="{{ $nivel }}" {{ request('nivel_educativo') == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-2 mt-2">
    <input type="number" name="edad" class="form-control"
           placeholder="Edad" value="{{ request('edad') }}">
</div>


        </div>
    </form>
    {{-- FIN BUSCADOR --}}

    {{-- TABLA --}}
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
            @forelse($socios as $socio)
                <tr>
                    <td>{{ $socio->Nombre_Beneficiario }}</td>
                    <td>{{ $socio->DNI }}</td>
                    <td>{{ $socio->Telefono }}</td>
                    <td>
                        <a href="{{ route('socios.edit', $socio->Id_Beneficiario) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('socios.ficha', $socio->Id_Beneficiario) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ficha
                        </a>

                        @if($socio->estado == 1)
                            {{-- Botón inactivar --}}
                            <form action="{{ route('socios.destroy', $socio->Id_Beneficiario) }}"
                                  method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Seguro de inactivar este socio?')">
                                    <i class="fas fa-trash"></i> Inactivar
                                </button>
                            </form>
                        @else
                            {{-- Botón reactivar --}}
                            <form action="{{ route('socios.reactivar', $socio->Id_Beneficiario) }}"
                                  method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-success btn-sm"
                                        onclick="return confirm('¿Seguro de reactivar este socio?')">
                                    <i class="fas fa-check"></i> Activar
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay socios registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PAGINACIÓN --}}
    {{ $socios->withQueryString()->links() }}

@stop
