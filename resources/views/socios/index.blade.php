@extends('adminlte::page')

@section('title', 'Socio/Clientes')

@section('content_header')
<h1 id="titulo-listado-socios" class="fw-bold text-dark">Listado de Socios</h1>
@stop

@section('content')

{{-- BOTONES SUPERIORES --}}
<div class="mb-3 d-flex justify-content-between flex-wrap" role="navigation" aria-label="Acciones principales">

    <a href="{{ route('socios.create') }}"
       class="btn text-white fw-semibold"
       style="background-color:#1b263b;"
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

{{-- FORMULARIO DE BÚSQUEDA --}}
<form method="GET"
      action="{{ route('socios.index') }}"
      class="mb-3 p-3 rounded shadow-sm border"
      style="background:#f8f9fa;"
      aria-label="Formulario de filtros de búsqueda de socios">

    <div class="row g-2">

        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Buscar</label>
            <input type="text"
                   name="search"
                   class="form-control border-dark"
                   placeholder="Nombre, DNI, teléfono"
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Género</label>
            <select name="genero" class="form-control border-dark">
                <option value="">Todos</option>
                <option value="M" {{ request('genero')=='M' ? 'selected' : '' }}>Masculino</option>
                <option value="F" {{ request('genero')=='F' ? 'selected' : '' }}>Femenino</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Localidad</label>
            <input type="text"
                   name="localidad"
                   class="form-control border-dark"
                   placeholder="Localidad"
                   value="{{ request('localidad') }}">
        </div>

        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Tipo</label>
            <input type="text"
                   name="tipo"
                   class="form-control border-dark"
                   placeholder="Tipo de socio"
                   value="{{ request('tipo') }}">
        </div>

        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Estado</label>
            <select name="estado" class="form-control border-dark">
                <option value="">Todos</option>
                <option value="1" {{ request('estado')=='1' ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ request('estado')=='0' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        {{-- FILTROS EXTENDIDOS --}}
        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Departamento</label>
            <select name="departamento" class="form-control border-dark">
                <option value="">Todos</option>
                @foreach(["Atlántida","Choluteca","Colón","Comayagua","Copán","Cortés","El Paraíso",
                          "Francisco Morazán","Gracias a Dios","Intibucá","Islas de la Bahía",
                          "La Paz","Lempira","Ocotepeque","Olancho","Santa Bárbara","Valle","Yoro"] as $dep)
                    <option value="{{ $dep }}" {{ request('departamento') == $dep ? 'selected' : '' }}>
                        {{ $dep }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Estado Civil</label>
            <select name="estado_civil" class="form-control border-dark">
                <option value="">Todos</option>
                @foreach(["Soltero(a)","Casado(a)","Unión Libre","Viudo(a)"] as $ec)
                    <option value="{{ $ec }}" {{ request('estado_civil') == $ec ? 'selected' : '' }}>
                        {{ $ec }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Educación</label>
            <select name="nivel_educativo" class="form-control border-dark">
                <option value="">Todos</option>
                @foreach(["Sin estudios","Educación básica","Educación media","Educación superior"] as $nivel)
                    <option value="{{ $nivel }}" {{ request('nivel_educativo') == $nivel ? 'selected' : '' }}>
                        {{ $nivel }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Edad</label>
            <input type="number"
                   name="edad"
                   class="form-control border-dark"
                   placeholder="Edad"
                   value="{{ request('edad') }}">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn text-white w-100 fw-semibold"
                    style="background-color:#1b263b;">
                <i class="fas fa-search"></i> Buscar
            </button>
        </div>

    </div>
</form>

{{-- TABLA --}}
<div class="table-responsive">
    <table id="tabla-socios"
           class="table table-bordered table-striped table-hover shadow-sm">

        <thead class="text-white" style="background-color:#1b263b;">
            <tr>
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

                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1 flex-wrap">

                        <button class="btn btn-sm text-white"
                                style="background-color:#1b263b;"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarSocio{{ $socio->Id_Beneficiario }}">
                            <i class="fas fa-edit"></i>
                        </button>

                        <a href="{{ route('socios.ficha', $socio->Id_Beneficiario) }}"
                           class="btn btn-sm text-white"
                           style="background-color:#424242;">
                            <i class="fas fa-eye"></i>
                        </a>

                        @if($socio->estado == 1)
                        <form method="POST"
                              action="{{ route('socios.destroy', $socio->Id_Beneficiario) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm text-white"
                                    style="background-color:#B71C1C;"
                                    onclick="return confirmarEliminacion(event)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @else
                        <form method="POST"
                              action="{{ route('socios.reactivar', $socio->Id_Beneficiario) }}">
                            @csrf
                            <button class="btn btn-sm text-white"
                                    style="background-color:#1B5E20;"
                                    onclick="return confirm('¿Seguro de reactivar este socio?')">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif

                    </div>
                </td>
            </tr>
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

@section('js')
@parent
<script>
// JS sin cambios
</script>
@endsection
