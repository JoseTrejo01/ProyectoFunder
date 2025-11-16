@extends('adminlte::page')

@section('title', 'Listado de Emprendimientos')

@section('content_header')
<div class="d-flex justify-content-between align-items-center flex-wrap">
    <h1 class="mb-3 text-dark font-weight-bold">Gestión de Emprendimientos</h1>
</div>
@stop

@section('content')

{{-- =======================================================
     ACCIONES Y FILTROS
======================================================= --}}
<div class="mb-3 d-flex justify-content-between flex-wrap">

    {{-- Botones de acción --}}
    <div class="mb-2">
        <a href="{{ route('emprendimientos.create') }}" 
           class="btn btn-success font-weight-bold mr-2">
            <i class="fas fa-plus-circle"></i> Nuevo Emprendimiento
        </a>

        <a href="{{ route('emprendimientos.export.pdf', request()->query()) }}" 
           class="btn btn-danger font-weight-bold">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('emprendimientos.index') }}" 
          class="form-inline d-flex flex-wrap justify-content-end">

        <input type="text" name="nombre"
               class="form-control mb-2 mr-2"
               placeholder="Nombre del Emprendimiento"
               aria-label="Buscar por nombre"
               value="{{ request('nombre') }}">

        <input type="text" name="tecnico"
               class="form-control mb-2 mr-2"
               placeholder="Técnico"
               aria-label="Buscar por técnico"
               value="{{ request('tecnico') }}">

        <input type="date" name="fecha"
               class="form-control mb-2 mr-2"
               aria-label="Filtrar por fecha"
               value="{{ request('fecha') }}">

        <select name="municipio" class="form-control mb-2 mr-2"
                aria-label="Filtrar por municipio">
            <option value="">Todos los municipios</option>
            @foreach($municipios as $municipio)
                <option value="{{ $municipio->Id_Municipio }}"
                    {{ request('municipio') == $municipio->Id_Municipio ? 'selected' : '' }}>
                    {{ $municipio->Nombre_Municipio }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-outline-secondary mb-2 mr-2 font-weight-bold">
            <i class="fas fa-search"></i> Buscar
        </button>

        <a href="{{ route('emprendimientos.index') }}" 
           class="btn btn-outline-danger mb-2 font-weight-bold">
            <i class="fas fa-times"></i> Limpiar
        </a>
    </form>
</div>

{{-- =======================================================
     TABLA
======================================================= --}}
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover text-center shadow-sm">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Municipio</th>
                <th>Aldea</th>
                <th>Tipo de Negocio</th>
                <th>Total Socios</th>
                <th>Total Empleos</th>
                <th>Ventas Trimestrales</th>
                <th>Fecha Levantamiento</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse($emprendimientos as $emp)
                <tr>
                    <td>{{ $emp->Id_Emprendimiento }}</td>

                    <td>
                        <strong>{{ $emp->Caja_Rural }}</strong><br>
                        <small class="text-muted">
                            {{ $emp->organizacion->Nombre_Organizacion ?? 'Sin organización' }}
                        </small>
                    </td>

                    <td>{{ $emp->municipio->Nombre_Municipio ?? 'N/D' }}</td>
                    <td>{{ $emp->aldea->Nombre_Aldea ?? 'Sin aldea' }}</td>

                    <td>{{ $emp->Tipo_Negocio }}</td>

                    {{-- Socios --}}
                    <td class="detalle-hover text-primary">
                        {{ $emp->Socios_Hombres + $emp->Socios_Mujeres }}

                        <div class="detalle-popup">
                            <strong>Detalle Socios</strong><br>
                            🔹 Hombres: {{ $emp->Socios_Hombres }}<br>
                            🔹 Mujeres: {{ $emp->Socios_Mujeres }}
                        </div>
                    </td>

                    {{-- Empleos --}}
                    <td class="detalle-hover text-primary">
                        {{ $emp->Empleos_Hombres + $emp->Empleos_Mujeres }}

                        <div class="detalle-popup">
                            <strong>Detalle Empleos</strong><br>
                            🔹 Hombres: {{ $emp->Empleos_Hombres }}<br>
                            🔹 Mujeres: {{ $emp->Empleos_Mujeres }}
                        </div>
                    </td>

                    <td class="text-success font-weight-bold">
                        L {{ number_format($emp->Ventas_Trimestrales, 2, '.', ',') }}
                    </td>

                    <td>{{ \Carbon\Carbon::parse($emp->Fecha_Levantamiento)->format('d/m/Y') }}</td>

                    {{-- ACCIONES --}}
                    <td>
                        <a href="{{ route('emprendimientos.edit', $emp->Id_Emprendimiento) }}"
                           class="btn btn-primary btn-sm" aria-label="Editar emprendimiento">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('emprendimientos.destroy', $emp->Id_Emprendimiento) }}"
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm"
                                aria-label="Eliminar emprendimiento"
                                onclick="return confirm('¿Eliminar este registro?')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted">
                        No se encontraron resultados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- =======================================================
     PAGINACIÓN
======================================================= --}}
<div class="mt-3">
    {{ $emprendimientos->appends(request()->query())->links() }}
</div>

@stop

{{-- =======================================================
     JS
======================================================= --}}
@section('js')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const popups = document.querySelectorAll('.detalle-popup');

    document.querySelectorAll('.detalle-hover').forEach(cell => {

        cell.addEventListener('click', e => {
            e.stopPropagation();
            popups.forEach(p => p.style.display = 'none');

            const popup = cell.querySelector('.detalle-popup');
            const rect = cell.getBoundingClientRect();

            popup.style.display = 'block';
            popup.style.top = `${rect.bottom + window.scrollY + 8}px`;
            popup.style.left = `${rect.left + rect.width/2 - popup.offsetWidth/2}px`;
        });
    });

    document.addEventListener('click', () => {
        popups.forEach(p => p.style.display = 'none');
    });
});
</script>
@stop

{{-- =======================================================
     CSS
======================================================= --}}
@section('css')
<style>
.detalle-hover {
    cursor: pointer;
    font-weight: bold;
    position: relative;
}

.detalle-popup {
    display: none;
    position: fixed;
    background: #ffffff;
    border: 1px solid #ddd;
    padding: 10px 15px;
    border-radius: 6px;
    z-index: 9999;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style>
@stop
