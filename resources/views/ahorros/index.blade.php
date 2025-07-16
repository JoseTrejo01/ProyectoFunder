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

    {{-- TABLA DE AHORROS --}}
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-primary">
            <tr>
                <th>No.</th>
                <th>Nombre Caja Rural</th>
                <th>Socios<br><small>(No. / Ahorros / Prom.)</small></th>
                <th>Adultos<br><small>(No. / Ahorros / Prom.)</small></th>
                <th>Niños<br><small>(No. / Ahorros / Prom.)</small></th>
                <th>SubTotal No Socios<br><small>(No. / Ahorros / Prom.)</small></th>
                <th>Total<br><small>(No. / Ahorros / Prom.)</small></th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ahorros as $index => $ahorro)
                <tr>
                    <td>{{ $loop->iteration + ($ahorros->currentPage() - 1) * $ahorros->perPage() }}</td>
                    <td>{{ $ahorro->nombre_caja_rural }}</td>
                    <td>{{ $ahorro->socios_no }} / {{ number_format($ahorro->socios_ahorros, 2) }} / {{ number_format($ahorro->socios_promedio, 2) }}</td>
                    <td>{{ $ahorro->adultos_no }} / {{ number_format($ahorro->adultos_ahorros, 2) }} / {{ number_format($ahorro->adultos_promedio, 2) }}</td>
                    <td>{{ $ahorro->ninos_no }} / {{ number_format($ahorro->ninos_ahorros, 2) }} / {{ number_format($ahorro->ninos_promedio, 2) }}</td>
                    <td>{{ $ahorro->subtotal_no_socios_no }} / {{ number_format($ahorro->subtotal_no_socios_ahorros, 2) }} / {{ number_format($ahorro->subtotal_no_socios_promedio, 2) }}</td>
                    <td>{{ $ahorro->total_no }} / {{ number_format($ahorro->total_ahorros, 2) }} / {{ number_format($ahorro->total_promedio, 2) }}</td>
                    <td>
                        <a href="{{ route('ahorros.edit', $ahorro->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('ahorros.ficha', $ahorro->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ficha
                        </a>
                        <form action="{{ route('ahorros.destroy', $ahorro->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar este registro?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No hay registros de ahorros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PAGINACIÓN --}}
    <div class="d-flex justify-content-center">
        {{ $ahorros->withQueryString()->links() }}
    </div>
@stop
