@extends('adminlte::page')

@section('title', 'Listado de Emprendimientos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Gestión de Emprendimientos</h1>
        <a href="{{ route('emprendimientos.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo Emprendimiento
        </a>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success shadow-sm">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- FILTROS --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-filter"></i> Filtros de Búsqueda
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('emprendimientos.index') }}">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="municipio">Municipio</label>
                        <select name="municipio" id="municipio" class="form-control">
                            <option value="">-- Todos --</option>
                            @foreach($municipios as $municipio)
                                <option value="{{ $municipio->Id_Municipio }}"
                                    {{ request('municipio') == $municipio->Id_Municipio ? 'selected' : '' }}>
                                    {{ $municipio->Nombre_Municipio }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="tecnico">Técnico</label>
                        <input type="text" name="tecnico" id="tecnico" class="form-control" value="{{ request('tecnico') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="fecha">Fecha de Levantamiento</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" value="{{ request('fecha') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="nombre">Nombre del Emprendimiento</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{ request('nombre') }}">
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="{{ route('emprendimientos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-broom"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <i class="fas fa-list"></i> Listado de Emprendimientos
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Municipio</th>
                        <th>Aldea</th>
                        <th>Tipo de Negocio</th>
                        <th>Total Socios</th>
                        <th>Total Empleos</th>
                        <th>Ventas Trimestrales</th>
                        <th>Técnico</th>
                        <th>Fecha de Levantamiento</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($emprendimientos as $emp)
                        <tr>
                            <td>
                                <strong>{{ $emp->Caja_Rural }}</strong><br>
                                <small class="text-muted">
                                    ({{ $emp->organizacion->Nombre_Organizacion ?? 'Sin organización' }})
                                </small>
                            </td>
                            <td>{{ $emp->municipio->Nombre_Municipio ?? 'N/D' }}</td>
                            <td>{{ $emp->aldea->Nombre_Aldea ?? 'Sin aldea' }}</td>
                            <td>{{ $emp->Tipo_Negocio }}</td>

                            {{-- Socios con detalle --}}
                            <td class="detalle-hover text-primary">
                                {{ $emp->Socios_Hombres + $emp->Socios_Mujeres }}
                                <div class="detalle-popup">
                                    <strong>Detalle Socios:</strong><br>
                                    Hombres: {{ $emp->Socios_Hombres }}<br>
                                    Mujeres: {{ $emp->Socios_Mujeres }}
                                </div>
                            </td>

                            {{-- Empleos con detalle --}}
                            <td class="detalle-hover text-primary">
                                {{ $emp->Empleos_Hombres + $emp->Empleos_Mujeres }}
                                <div class="detalle-popup">
                                    <strong>Detalle Empleos:</strong><br>
                                    Hombres: {{ $emp->Empleos_Hombres }}<br>
                                    Mujeres: {{ $emp->Empleos_Mujeres }}
                                </div>
                            </td>

                            {{-- Ventas formateadas --}}
                            <td class="text-success font-weight-bold">
                                L {{ number_format($emp->Ventas_Trimestrales, 2, '.', ',') }}
                            </td>

                            <td>{{ $emp->tecnico->Nombre_Usuario ?? 'Sin técnico' }}</td>
                            <td>{{ \Carbon\Carbon::parse($emp->Fecha_Levantamiento)->format('d/m/Y') }}</td>

                            <td class="text-center">
                                <a href="{{ route('emprendimientos.edit', $emp->Id_Emprendimiento) }}" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('emprendimientos.destroy', $emp->Id_Emprendimiento) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('¿Eliminar este registro?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center">No se encontraron resultados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINACIÓN --}}
    <div class="mt-3">
        {{ $emprendimientos->appends(request()->query())->links() }}
    </div>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('td[style*="cursor:pointer"]').forEach(cell => {
            cell.addEventListener('click', () => {
                const popup = cell.querySelector('.detalle-popup');
                if (popup.style.display === 'block') {
                    popup.style.display = 'none';
                } else {
                    // Ocultar todos los demás popups antes de mostrar este
                    document.querySelectorAll('.detalle-popup').forEach(p => p.style.display = 'none');
                    popup.style.display = 'block';
                }
            });
        });

        // Cerrar popup si se clickea fuera
      
    });
</script>
@stop
@section('css')
<style>
    .detalle-hover { 
        cursor: pointer; 
        position: relative; 
        color: #007bff;
        font-weight: bold;
    }
    .detalle-popup {
        display: none;
        position: absolute;
        top: 120%;
        left: 50%;
        transform: translateX(-50%);
        background: #ffffff;
        border: 1px solid #ccc;
        padding: 8px 12px;
        border-radius: 6px;
        z-index: 1000;
        white-space: nowrap;
        box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    }
    .detalle-hover:hover .detalle-popup {
        display: block;
    }
</style>
@stop