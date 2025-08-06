@extends('adminlte::page')

@section('title', 'Evaluaciones')

@section('content_header')
    <h1>Evaluaciones</h1>
@endsection

@section('content')
<div class="mb-3 d-flex justify-content-between flex-wrap">
    <div>
        <a href="{{ route('evaluacion.create') }}" class="btn btn-success">Nueva Evaluación</a>
        <a href="{{ route('evaluacion.exportarPDF') }}" class="btn btn-danger ml-2" target="_blank">Exportar PDF</a>
    </div>
    <form method="GET" action="{{ route('evaluacion.index') }}" class="form-inline mt-2 mt-md-0">
        <input type="text" name="search" class="form-control mr-2" placeholder="Buscar organización..." value="{{ request('search') }}">
        <button class="btn btn-outline-secondary mr-2" type="submit">Buscar</button>
        <a href="{{ route('evaluacion.index') }}" class="btn btn-outline-danger">Limpiar</a>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped text-center">
        <thead class="thead-dark">
            <tr>
                <th>No.</th>
                <th>Organización</th>
                <th>Departamento</th>

                <th>Eval. Inicial - Inst.</th>
                <th>Eval. Inicial - Finan.</th>
                <th>Eval. Inicial - Total</th>
                <th>Eval. Inicial - Cat.</th>

                <th>Eval. Actual - Inst.</th>
                <th>Eval. Actual - Finan.</th>
                <th>Eval. Actual - Total</th>
                <th>Eval. Actual - Cat.</th>

                <th>% de Crecimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($actualizadas as $index => $evaAct)
                @php
                    $evaIni = $evaluaciones->firstWhere('organizacion_id', $evaAct->organizacion_id);
                    $esActualizada = $evaIni && $evaIni->updated_at > $evaIni->created_at;

                    $calcPorc = fn($inst, $fin) => round((($inst + $fin) / (315 + 400)) * 100, 2);
                    $calcCat = fn($total) => $total >= 90 ? 'A' : ($total >= 71 ? 'B' : ($total >= 50 ? 'C' : 'D'));

                    // Inicial
                    $instIni = $evaAct->desempeno_institucional ?? 0;
                    $finanIni = $evaAct->total_financiero ?? 0;
                    $totalIni = $calcPorc($instIni, $finanIni);
                    $catIni = $calcCat($totalIni);

                    // Actualizada
                    $instAct = $evaIni?->desempeno_institucional ?? null;
                    $finanAct = $evaIni?->total_financiero ?? null;
                    $totalAct = $instAct && $finanAct ? $calcPorc($instAct, $finanAct) : null;
                    $catAct = $totalAct !== null ? $calcCat($totalAct) : null;

                    $crecimiento = ($totalAct !== null) ? round($totalAct - $totalIni, 2) : 0;
                @endphp

                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $evaAct->organizacion->Nombre_Organizacion ?? 'Sin nombre' }}</td>
                    <td>{{ $evaAct->organizacion->aldea->municipio->departamento->Nombre_Departamento ?? 'No definido' }}</td>

                    {{-- Evaluación Inicial --}}
                    <td>{{ round(($instIni / 315) * 100, 2) }}%</td>
                    <td>{{ round(($finanIni / 400) * 100, 2) }}%</td>
                    <td>{{ $totalIni }}%</td>
                    <td>{{ $catIni }}</td>

                    {{-- Evaluación Actualizada --}}
                    @if ($evaIni && $esActualizada)
                        <td>{{ round(($instAct / 315) * 100, 2) }}%</td>
                        <td>{{ round(($finanAct / 400) * 100, 2) }}%</td>
                        <td>{{ $totalAct }}%</td>
                        <td>{{ $catAct }}</td>
                    @else
                        <td colspan="4" class="text-muted">Sin datos</td>
                    @endif

                    <td>{{ $crecimiento }}%</td>

                    {{-- Acciones --}}
                    <td>
                        <a href="{{ route('evaluacion.edit', $evaAct->Id_Evaluacion ?? ($evaIni->Id_Evaluacion ?? 0)) }}" class="btn btn-sm btn-primary">Actualizar</a>
                        <form action="{{ route('evaluacion.destroy', $evaAct->Id_Evaluacion ?? ($evaIni->Id_Evaluacion ?? 0)) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta evaluación?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Borrar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
