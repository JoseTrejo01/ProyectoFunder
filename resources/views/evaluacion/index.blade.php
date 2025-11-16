@extends('adminlte::page')

@section('title', 'Evaluaciones')

@section('content_header')
    <h1 class="mb-0">Evaluaciones</h1>
@endsection

@section('content')

{{-- Barra superior: acciones y filtros --}}
<div class="mb-3 d-flex justify-content-between flex-wrap align-items-center">

    <div class="mb-2">
        <a href="{{ route('evaluacion.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nueva Evaluación
        </a>

        <a href="{{ route('evaluacion.exportarPDF') }}" target="_blank" class="btn btn-danger ml-2">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </div>

    {{-- Filtro --}}
    <form method="GET" action="{{ route('evaluacion.index') }}" class="form-inline">
        <input type="text" name="search" class="form-control mr-2" placeholder="Buscar organización..."
            value="{{ request('search') }}">

        <button class="btn btn-outline-secondary mr-2" type="submit">
            <i class="fas fa-search"></i> Buscar
        </button>

        <a href="{{ route('evaluacion.index') }}" class="btn btn-outline-danger">
            <i class="fas fa-eraser"></i> Limpiar
        </a>
    </form>
</div>

{{-- Tabla --}}
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover text-center align-middle">
        <thead class="thead-dark">
            <tr>
                <th>No.</th>
                <th>Organización</th>
                <th>Departamento</th>

                <th colspan="4">Evaluación Inicial</th>
                <th colspan="4">Evaluación Actual</th>

                <th>% Crecimiento</th>
                <th>Acciones</th>
            </tr>

            <tr>
                <th></th>
                <th></th>
                <th></th>

                <th>Inst.</th>
                <th>Finan.</th>
                <th>Total</th>
                <th>Cat.</th>

                <th>Inst.</th>
                <th>Finan.</th>
                <th>Total</th>
                <th>Cat.</th>

                <th></th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($actualizadas as $index => $evaAct)

                @php
                    // Buscar evaluación inicial
                    $evaIni = $evaluaciones->firstWhere('id_organizacion', $evaAct->id_organizacion);

                    $esActualizada = $evaIni && $evaIni->updated_at > $evaIni->created_at;

                    // Helpers
                    $calcPorc = fn($inst, $fin) =>
                        round((($inst + $fin) / (315 + 400)) * 100, 2);

                    $calcCat = fn($t) =>
                        $t >= 90 ? 'A' : ($t >= 71 ? 'B' : ($t >= 50 ? 'C' : 'D'));

                    // Inicial
                    $instIni = $evaAct->desempeno_institucional ?? 0;
                    $finIni = $evaAct->total_financiero ?? 0;
                    $totalIni = $calcPorc($instIni, $finIni);
                    $catIni = $calcCat($totalIni);

                    // Actual
                    $instAct = $evaIni->desempeno_institucional ?? null;
                    $finAct = $evaIni->total_financiero ?? null;

                    $hayActual = $instAct !== null && $finAct !== null;

                    $totalAct = $hayActual ? $calcPorc($instAct, $finAct) : null;
                    $catAct = $hayActual ? $calcCat($totalAct) : null;

                    $crecimiento = $totalAct ? round($totalAct - $totalIni, 2) : 0;
                @endphp

                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>
                        <strong>{{ $evaAct->organizacion->Nombre_Organizacion ?? 'Sin nombre' }}</strong>
                    </td>

                    <td>
                        {{ $evaAct->organizacion->aldea->municipio->departamento->Nombre_Departamento ?? 'No definido' }}
                    </td>

                    {{-- Inicial --}}
                    <td>{{ round(($instIni / 315) * 100, 2) }}%</td>
                    <td>{{ round(($finIni / 400) * 100, 2) }}%</td>
                    <td>{{ $totalIni }}%</td>
                    <td>{{ $catIni }}</td>

                    {{-- Actual --}}
                    @if ($hayActual)
                        <td>{{ round(($instAct / 315) * 100, 2) }}%</td>
                        <td>{{ round(($finAct / 400) * 100, 2) }}%</td>
                        <td>{{ $totalAct }}%</td>
                        <td>{{ $catAct }}</td>
                    @else
                        <td colspan="4" class="text-muted">
                            Sin datos
                        </td>
                    @endif

                    {{-- Crecimiento --}}
                    <td class="{{ $crecimiento > 0 ? 'text-success' : ($crecimiento < 0 ? 'text-danger' : 'text-muted') }}">
                        {{ $crecimiento }}%
                    </td>

                    {{-- Acciones --}}
                    <td>
                        <a href="{{ route('evaluacion.edit', $evaAct->Id_Evaluacion ?? $evaIni->Id_Evaluacion) }}"
                           class="btn btn-sm btn-primary mb-1">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('evaluacion.destroy', $evaAct->Id_Evaluacion ?? $evaIni->Id_Evaluacion) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('¿Está seguro de eliminar esta evaluación?');">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
