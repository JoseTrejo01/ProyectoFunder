@extends('adminlte::page')

@section('title', 'Listado de Emprendimientos')

@section('content_header')
    <h1>Gestión de Emprendimientos</h1>
    <a href="{{ route('emprendimientos.create') }}" class="btn btn-success">Nuevo Emprendimiento</a>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('emprendimientos.index') }}" class="mb-4">
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

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('emprendimientos.index') }}" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Municipio</th>
            <th>Tipo de Negocio</th>
            <th>Total Socios</th>
            <th>Total Empleos</th>
            <th>Técnico</th>
            <th>Fecha de Inicio</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($emprendimientos as $emp)
            <tr>
                <td>
                    {{ $emp->Caja_Rural }}<br>
                    <small class="text-muted">({{ $emp->organizacion->Nombre_Organizacion ?? 'Sin organización' }})</small>
                </td>
                <td>{{ $emp->municipio->Nombre_Municipio ?? 'N/D' }}</td>
                <td>{{ $emp->Tipo_Negocio }}</td>

                <td style="cursor:pointer; color:blue; text-decoration:underline; position:relative;">
                    {{ $emp->Socios_Hombres + $emp->Socios_Mujeres }}

                    <div class="detalle-popup" style="
                        display:none;
                        position:absolute;
                        top: 100%;
                        left: 50%;
                        transform: translateX(-50%);
                        background: #f8f9fa;
                        border: 1px solid #ccc;
                        padding: 8px 12px;
                        z-index: 1000;
                        white-space: nowrap;
                        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
                        ">
                        <strong>Detalle Socios:</strong><br>
                        Hombres: {{ $emp->Socios_Hombres }}<br>
                        Mujeres: {{ $emp->Socios_Mujeres }}
                    </div>
                </td>

                <td style="cursor:pointer; color:blue; text-decoration:underline; position:relative;">
                    {{ $emp->Empleos_Hombres + $emp->Empleos_Mujeres }}

                    <div class="detalle-popup" style="
                        display:none;
                        position:absolute;
                        top: 100%;
                        left: 50%;
                        transform: translateX(-50%);
                        background: #f8f9fa;
                        border: 1px solid #ccc;
                        padding: 8px 12px;
                        z-index: 1000;
                        white-space: nowrap;
                        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
                        ">
                        <strong>Detalle Empleos:</strong><br>
                        Hombres: {{ $emp->Empleos_Hombres }}<br>
                        Mujeres: {{ $emp->Empleos_Mujeres }}
                    </div>
                </td>

                <td>{{ $emp->tecnico->Nombre_Usuario ?? 'Sin técnico' }}</td>
                <td>{{ \Carbon\Carbon::parse($emp->Fecha_Levantamiento)->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('emprendimientos.edit', ['emprendimiento' => $emp->Id_Emprendimiento]) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('emprendimientos.destroy', ['emprendimiento' => $emp->Id_Emprendimiento]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este registro?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8">No se encontraron resultados.</td></tr>
        @endforelse
    </tbody>
</table>

    {{ $emprendimientos->appends(request()->query())->links() }}

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
        document.addEventListener('click', function(e) {
            if (![...document.querySelectorAll('td[style*="cursor:pointer"]')].some(td => td.contains(e.target))) {
                document.querySelectorAll('.detalle-popup').forEach(p => p.style.display = 'none');
            }
        });
    });
</script>
@stop