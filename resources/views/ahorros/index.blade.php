@extends('adminlte::page')

@section('content_header')
    <h1>Listado de Ahorros</h1>
@stop

@section('content')
    {{-- BOTONES --}}
    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('ahorros.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Registro
        </a>
        <div>
            <a href="{{ route('ahorros.export-pdf') }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
        </div>
    </div>

    {{-- MENSAJES DE ÉXITO --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- TABLA RESUMEN AGRUPADO --}}
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-primary">
            <tr>
                <th>Caja Rural</th>
                <th>Socios<br><small>(Cantidad / Total / Promedio)</small></th>
                <th>No Socios Adultos<br><small>(Cantidad / Total / Promedio)</small></th>
                <th>No Socios Jóvenes<br><small>(Cantidad / Total / Promedio)</small></th>
            </tr>
        </thead>
        <tbody>
            @forelse($agrupados as $caja => $datos)
                <tr>
                    <td>{{ $caja }}</td>
                    <td>
                        {{ $datos['socios']['cantidad'] }}<br>
                        L {{ number_format($datos['socios']['total'], 2) }}<br>
                        L {{ number_format($datos['socios']['promedio'], 2) }}
                    </td>
                    <td>
                        {{ $datos['no_socios_adultos']['cantidad'] }}<br>
                        L {{ number_format($datos['no_socios_adultos']['total'], 2) }}<br>
                        L {{ number_format($datos['no_socios_adultos']['promedio'], 2) }}
                    </td>
                    <td>
                        {{ $datos['no_socios_jovenes']['cantidad'] }}<br>
                        L {{ number_format($datos['no_socios_jovenes']['total'], 2) }}<br>
                        L {{ number_format($datos['no_socios_jovenes']['promedio'], 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay registros de ahorros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@stop
