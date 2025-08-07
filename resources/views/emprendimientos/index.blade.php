@extends('adminlte::page')

@section('title', 'Listado de Emprendimientos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Gestión de Emprendimientos</h1>
        
@stop

@section('content')

    {{-- Filtros --}}
<div class="mb-3 d-flex justify-content-between flex-wrap">
    <div>
        <a href="{{ route('emprendimientos.create') }}" class="btn btn-success">Nuevo Emprendimiento</a>
        <a href="{{ route('emprendimientos.export.pdf', request()->query()) }}" class="btn btn-danger ml-2">Exportar PDF</a>
    </div>

    <form method="GET" action="{{ route('emprendimientos.index') }}" class="form-inline mt-2 mt-md-0">
        <input type="text" name="nombre" class="form-control mr-2" placeholder="Nombre del Emprendimiento" value="{{ request('nombre') }}">
        <input type="text" name="tecnico" class="form-control mr-2" placeholder="Técnico" value="{{ request('tecnico') }}">
        <input type="date" name="fecha" class="form-control mr-2" value="{{ request('fecha') }}">

        <select name="municipio" class="form-control mr-2">
            <option value="">Todos los municipios</option>
            @foreach($municipios as $municipio)
                <option value="{{ $municipio->Id_Municipio }}" {{ request('municipio') == $municipio->Id_Municipio ? 'selected' : '' }}>
                    {{ $municipio->Nombre_Municipio }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-outline-secondary mr-2">Buscar</button>
        <a href="{{ route('emprendimientos.index') }}" class="btn btn-outline-danger">Limpiar</a>
    </form>
</div>


    {{-- Tabla --}}
            <table class="table table-bordered table-striped table-hover text-center align-middle mb-0">
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
                                <small class="text-muted">{{ $emp->organizacion->Nombre_Organizacion ?? 'Sin organización' }}</small>
                            </td>
                            <td>{{ $emp->municipio->Nombre_Municipio ?? 'N/D' }}</td>
                            <td>{{ $emp->aldea->Nombre_Aldea ?? 'Sin aldea' }}</td>
                            <td>{{ $emp->Tipo_Negocio }}</td>

                            {{-- Socios --}}
                            <td class="detalle-hover text-primary">
                                {{ $emp->Socios_Hombres + $emp->Socios_Mujeres }}
                                <div class="detalle-popup">
                                    <strong>Detalle Socios:</strong><br>
                                    Hombres: {{ $emp->Socios_Hombres }}<br>
                                    Mujeres: {{ $emp->Socios_Mujeres }}
                                </div>
                            </td>

                            {{-- Empleos --}}
                            <td class="detalle-hover text-primary">
                                {{ $emp->Empleos_Hombres + $emp->Empleos_Mujeres }}
                                <div class="detalle-popup">
                                    <strong>Detalle Empleos:</strong><br>
                                    Hombres: {{ $emp->Empleos_Hombres }}<br>
                                    Mujeres: {{ $emp->Empleos_Mujeres }}
                                </div>
                            </td>

                            <td class="text-success font-weight-bold">
                                L {{ number_format($emp->Ventas_Trimestrales, 2, '.', ',') }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($emp->Fecha_Levantamiento)->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('emprendimientos.edit', $emp->Id_Emprendimiento) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('emprendimientos.destroy', $emp->Id_Emprendimiento) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este registro?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10">No se encontraron resultados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación --}}
    <div class="mt-3">
        {{ $emprendimientos->appends(request()->query())->links() }}
    </div>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const popups = document.querySelectorAll('.detalle-popup');

        document.querySelectorAll('.detalle-hover').forEach(cell => {
            cell.addEventListener('click', function (e) {
                e.stopPropagation();
                popups.forEach(p => p.style.display = 'none');
                const popup = cell.querySelector('.detalle-popup');
                const rect = cell.getBoundingClientRect();
                popup.style.display = 'block';
                popup.style.top = `${rect.bottom + window.scrollY + 5}px`;
                popup.style.left = `${rect.left + rect.width / 2 - popup.offsetWidth / 2}px`;
            });
        });

        document.addEventListener('click', function () {
            popups.forEach(p => p.style.display = 'none');
        });
    });
</script>
@endsection

@section('css')
<style>
    .detalle-hover {
        cursor: pointer;
        color: #007bff;
        font-weight: bold;
        position: relative;
    }

    .detalle-popup {
        display: none;
        position: fixed;
        background: #fff;
        border: 1px solid #ccc;
        padding: 8px 12px;
        border-radius: 6px;
        z-index: 9999;
        white-space: nowrap;
        box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    }
</style>
@endsection
