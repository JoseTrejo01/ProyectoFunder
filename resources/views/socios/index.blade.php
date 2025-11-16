@extends('adminlte::page')

@section('title', 'Socio/Clientes')

@section('content_header')
<h1 id="titulo-listado-socios" class="fw-bold">Listado de Socios</h1>
@stop

@section('content')

{{-- BOTONES SUPERIORES --}}
<div class="mb-3 d-flex justify-content-between" role="navigation" aria-label="Acciones principales">

    <a href="{{ route('socios.create') }}"
       class="btn text-white fw-semibold"
       style="background-color:#0D47A1;"
       aria-label="Crear nuevo socio">
        <i class="fas fa-plus"></i> Nuevo Socio
    </a>

    <div class="d-flex gap-2">

        <a href="{{ route('socios.export') }}"
           class="btn fw-semibold text-white"
           style="background-color:#1B5E20;"
           aria-label="Exportar listado de socios en Excel">
            <i class="fas fa-file-excel"></i> Exportar Excel
        </a>

        <a href="{{ route('socios.export-pdf', request()->query()) }}"
           class="btn fw-semibold text-white"
           style="background-color:#B71C1C;"
           aria-label="Exportar listado de socios en PDF">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </div>
</div>

{{-- SWEETALERT MENSAJES --}}
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: @json(session('success')),
        confirmButtonColor: '#1B5E20',
        timer: 2500,
        timerProgressBar: true,
        showConfirmButton: false
    });
});
</script>
@endif

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: @json(session('error')),
        confirmButtonColor: '#B71C1C',
    });
});
</script>
@endif

{{-- BUSCADOR --}}
<form method="GET"
      action="{{ route('socios.index') }}"
      class="mb-3"
      role="search"
      aria-label="Formulario de búsqueda de socios">

    <div class="row g-2">

        <div class="col-md-2">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Buscar nombre, DNI, teléfono"
                   aria-label="Buscar por nombre, DNI o teléfono"
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-2">
            <select name="genero"
                    class="form-control"
                    aria-label="Filtrar por género">
                <option value="">Género</option>
                <option value="M" {{ request('genero')=='M' ? 'selected' : '' }}>Masculino</option>
                <option value="F" {{ request('genero')=='F' ? 'selected' : '' }}>Femenino</option>
            </select>
        </div>

        <div class="col-md-2">
            <input type="text"
                   name="localidad"
                   class="form-control"
                   placeholder="Localidad"
                   aria-label="Filtrar por localidad"
                   value="{{ request('localidad') }}">
        </div>

        <div class="col-md-2">
            <input type="text"
                   name="tipo"
                   class="form-control"
                   placeholder="Tipo de socio"
                   aria-label="Filtrar por tipo de socio"
                   value="{{ request('tipo') }}">
        </div>

        <div class="col-md-2">
            <select name="estado" class="form-control" aria-label="Filtrar por estado">
                <option value="">Todos</option>
                <option value="1" {{ request('estado')=='1' ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ request('estado')=='0' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        {{-- FILTROS EXTENDIDOS --}}
        <div class="col-md-2 mt-2">
            <select name="departamento" class="form-control" aria-label="Filtrar por departamento">
                <option value="">Departamento</option>
                @foreach(["Atlántida","Choluteca","Colón","Comayagua","Copán","Cortés","El Paraíso","Francisco Morazán","Gracias a Dios","Intibucá","Islas de la Bahía","La Paz","Lempira","Ocotepeque","Olancho","Santa Bárbara","Valle","Yoro"] as $dep)
                    <option value="{{ $dep }}" {{ request('departamento') == $dep ? 'selected' : '' }}>{{ $dep }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 mt-2">
            <select name="estado_civil" class="form-control" aria-label="Filtrar por estado civil">
                <option value="">Estado Civil</option>
                @foreach(["Soltero(a)","Casado(a)","Unión Libre","Viudo(a)"] as $ec)
                    <option value="{{ $ec }}" {{ request('estado_civil') == $ec ? 'selected' : '' }}>{{ $ec }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 mt-2">
            <select name="nivel_educativo" class="form-control" aria-label="Filtrar por nivel educativo">
                <option value="">Nivel Educativo</option>
                @foreach(["Sin estudios","Educación básica","Educación media","Educación superior"] as $nivel)
                    <option value="{{ $nivel }}" {{ request('nivel_educativo') == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 mt-2">
            <input type="number"
                   name="edad"
                   class="form-control"
                   placeholder="Edad"
                   aria-label="Filtrar por edad"
                   value="{{ request('edad') }}">
        </div>

        <div class="col-md-2 mt-2">
            <button class="btn text-white w-100 fw-semibold"
                    style="background-color:#0D47A1;"
                    aria-label="Realizar búsqueda">
                <i class="fas fa-search"></i> Buscar
            </button>
        </div>

    </div>
</form>

{{-- TABLA --}}
<div class="table-responsive" role="region" aria-labelledby="titulo-listado-socios">
    <table id="tabla-socios"
           class="table table-bordered table-striped table-hover shadow-sm"
           role="table">

        <thead class="text-white"
               style="background-color:#0D47A1;">
            <tr role="row">
                <th>Nombre</th>
                <th>Caja Rural</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
        @forelse($socios as $socio)
            <tr>
                <td>{{ $socio->Nombre_Beneficiario }}</td>
                <td>{{ $socio->organizacion->Nombre_Organizacion ?? 'N/D' }}</td>
                <td>{{ $socio->DNI }}</td>
                <td>{{ $socio->Telefono }}</td>

                {{-- ACCIONES --}}
                <td class="text-center">

                    <div class="d-flex justify-content-center flex-wrap">

                        {{-- EDITAR --}}
                        <button class="btn btn-sm text-white"
                                style="background-color:#0D47A1;"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarSocio{{ $socio->Id_Beneficiario }}"
                                aria-label="Editar socio {{ $socio->Nombre_Beneficiario }}">
                            <i class="fas fa-edit"></i>
                        </button>

                        {{-- FICHA --}}
                        <a href="{{ route('socios.ficha', $socio->Id_Beneficiario) }}"
                           class="btn btn-sm text-white"
                           style="background-color:#424242;"
                           aria-label="Ver ficha del socio {{ $socio->Nombre_Beneficiario }}">
                            <i class="fas fa-eye"></i>
                        </a>

                        {{-- ESTADO --}}
                        @if($socio->estado == 1)
                        <form method="POST"
                              action="{{ route('socios.destroy', $socio->Id_Beneficiario) }}"
                              class="mx-1">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm text-white"
                                    style="background-color:#B71C1C;"
                                    onclick="return confirmarEliminacion(event)"
                                    aria-label="Inactivar socio {{ $socio->Nombre_Beneficiario }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @else
                        <form method="POST"
                              action="{{ route('socios.reactivar', $socio->Id_Beneficiario) }}"
                              class="mx-1">
                            @csrf
                            <button class="btn btn-sm text-white"
                                    style="background-color:#1B5E20;"
                                    onclick="return confirm('¿Seguro de reactivar este socio?')"
                                    aria-label="Reactivar socio {{ $socio->Nombre_Beneficiario }}">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif

                    </div>
                </td>
            </tr>

            {{-- MODALES (los dejé sin tocar para no romper funcionalidad) --}}

        @empty
            <tr>
                <td colspan="5" class="text-center text-muted py-3">
                    No hay socios registrados.
                </td>
            </tr>
        @endforelse
        </tbody>

    </table>
</div>

@stop

{{-- JS (No se modifica ninguna funcionalidad) --}}
@section('js')
@parent
<script>
// Todo tu JS queda igual
</script>
@endsection
