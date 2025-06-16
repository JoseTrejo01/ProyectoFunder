@extends('adminlte::page')

@section('content')
<div class="container">
    <h2>Bitácora del Sistema</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-2">
                <input type="text" name="usuario" class="form-control" placeholder="Usuario" value="{{ request('usuario') }}">
            </div>
            <div class="col-md-2">
                <input type="text" name="objeto" class="form-control" placeholder="Objeto" value="{{ request('objeto') }}">
            </div>
            <div class="col-md-2">
                <input type="text" name="accion" class="form-control" placeholder="Acción" value="{{ request('accion') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>
    <form method="POST" action="{{ route('bitacora.borrar') }}" onsubmit="return confirm('¿Seguro que deseas borrar los registros filtrados?');">
        @csrf
        <input type="hidden" name="usuario" value="{{ request('usuario') }}">
        <input type="hidden" name="objeto" value="{{ request('objeto') }}">
        <input type="hidden" name="accion" value="{{ request('accion') }}">
        <input type="hidden" name="fecha_desde" value="{{ request('fecha_desde') }}">
        <input type="hidden" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
        <button type="submit" class="btn btn-danger mb-3">Borrar registros filtrados</button>
    </form>
    <table class="table table-bordered">
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
