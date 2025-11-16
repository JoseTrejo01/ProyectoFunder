@extends('adminlte::page')

@section('title', 'Listado de Ahorros')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Gestión de Ahorros</h1>
    <div>
        {{-- Nuevo botón para ir al resumen --}}
        <a href="{{ route('ahorros.resumen') }}" class="btn btn-info mr-2">
            <i class="fas fa-chart-bar"></i> Ver Resumen
        </a>
        
        <a href="{{ route('ahorros.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Nuevo Ahorro
        </a>
        <a href="{{ route('ahorros.reportePDF', request()->query()) }}" class="btn btn-danger ml-2" target="_blank">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </div>
</div>
@stop

@section('content')
{{-- Filtros --}}
<div class="mb-3 d-flex justify-content-between flex-wrap">
    <form method="GET" action="{{ route('ahorros.index') }}" class="form-inline mt-2 mt-md-0">
        <input type="text" name="organizacion" class="form-control mr-2" placeholder="Organización" value="{{ request('organizacion') }}">
        <input type="text" name="beneficiario" class="form-control mr-2" placeholder="Beneficiario" value="{{ request('beneficiario') }}">
        <select name="tipo" class="form-control mr-2">
            <option value="">Todos los tipos</option>
            <option value="Socio" {{ request('tipo') == 'Socio' ? 'selected' : '' }}>Socio</option>
            <option value="Cliente" {{ request('tipo') == 'Cliente' ? 'selected' : '' }}>Cliente</option>
        </select>

        <button type="submit" class="btn btn-outline-secondary mr-2">Buscar</button>
        <a href="{{ route('ahorros.index') }}" class="btn btn-outline-danger">Limpiar</a>
    </form>
</div>

{{-- Tabla --}}
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover text-center align-middle mb-0">
        <thead class="thead-dark">
            <tr>
                <th>Organización</th>
                <th>Beneficiario</th>
                <th>Tipo</th>
                <th>Monto Total (Lps)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ahorros as $ahorro)
                <tr>
                    <td>{{ $ahorro->organizacion->Nombre_Organizacion ?? 'N/D' }}</td>
                    <td>{{ $ahorro->beneficiario->Nombre_Beneficiario ?? 'N/D' }}</td>
                    <td>{{ $ahorro->beneficiario->Tipo_De_Socio ?? 'N/D' }}</td>
                    <td class="text-success font-weight-bold">
                        L. {{ number_format($ahorro->Monto, 2, '.', ',') }}
                    </td>
                    <td>
                        <a href="{{ route('ahorros.edit', $ahorro->id) }}" class="btn btn-sm btn-primary" title="Editar">
                        <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('ahorros.ficha', $ahorro->id) }}" class="btn btn-sm btn-dark mx-1" title="Ficha">
                        <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('ahorros.destroy', $ahorro->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Confirma eliminar este ahorro?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                            </button>
                            </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No hay ahorros registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Paginación --}}
<div class="mt-3">
    {{ $ahorros->appends(request()->query())->links() }}
</div>
@stop