@extends('adminlte::page')

@section('content')
<div class="container">
    <h2>Bitácora del Sistema</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="row mb-3">
        <form method="GET" class="col-md-10 d-flex gap-2 align-items-end">
            <div class="col">
                <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col">
                <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
            </div>
            <div>
                <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
            </div>
        </form>
        <div class="col-md-2 d-flex align-items-end">
            <form method="POST" action="{{ route('bitacora.borrar') }}" onsubmit="return confirm('¿Seguro que deseas borrar los registros filtrados?');" class="w-100">
                @csrf
                <input type="hidden" name="fecha_desde" value="{{ request('fecha_desde') }}">
                <input type="hidden" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                <button type="submit" class="btn btn-danger btn-sm w-100">Borrar registros filtrados</button>
            </form>
        </div>
    </div>
    <table id="tabla-bitacora" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Objeto</th>
                <th>Acción</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registros as $registro)
                <tr>
                    <td>{{ $registro->Fecha }}</td>
                    <td>{{ $registro->usuario->Nombre_Usuario ?? 'N/A' }}</td>
                    <td>{{ $registro->objeto->Objeto ?? 'N/A' }}</td>
                    <td>{{ $registro->Accion }}</td>
                    <td>{{ $registro->Descripcion }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay registros para los filtros seleccionados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('css')
    {{-- Si quieres agregar estilos personalizados, hazlo aquí --}}
@endsection

@section('js')
<script>
$(document).ready(function() {
    $('#tabla-bitacora').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[0, 'desc']],
        searching: false // Desactiva el buscador
    });
});
</script>
@endsection
