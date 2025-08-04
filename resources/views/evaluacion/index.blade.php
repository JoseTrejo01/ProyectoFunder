@extends('adminlte::page')

@section('title', 'Evaluaciones')

@section('content_header')
    <h1>Evaluaciones</h1>
@endsection

@section('content')
<a href="{{ route('evaluacion.create') }}" class="btn btn-success mb-3">Nueva Evaluación</a>
<form method="GET" action="{{ route('evaluacion.index') }}" class="mb-3">
    <div class="input-group" style="max-width: 400px;">
        <input type="text" name="search" class="form-control" placeholder="Buscar organización..." value="{{ request('search') }}">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            <a href="{{ route('evaluacion.index') }}" class="btn btn-outline-danger ml-2">Limpiar</a>

        </div>
    </div>
</form>

<table class="table table-bordered table-striped text-center">
    <thead class="bg-success text-white align-middle">
        <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2">Nombre de la Organización</th>
            <th rowspan="2">Departamento</th>
            <th colspan="4">Evaluación Inicial </th>
            <th colspan="4">Evaluación Actualizada </th>
            <th rowspan="2">% de crecimiento</th>
            <th rowspan="2">Acciones</th>
        </tr>
        <tr>
            <th>Desempeño Institucional</th>
            <th>Desempeño Financiero</th>
            <th>Calificación Total</th>
            <th>Categoría</th>

            <th>Desempeño Institucional</th>
            <th>Desempeño Financiero</th>
            <th>Calificación Total</th>
            <th>Categoría</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($actualizadas as $index => $evaAct)
        @php
            // Buscar la evaluación inicial correspondiente
            $evaIni = $evaluaciones->firstWhere('organizacion_id', $evaAct->organizacion_id);
            $esActualizada = $evaIni && $evaIni->updated_at > $evaIni->created_at;
        @endphp
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $evaAct->organizacion->Nombre_Organizacion ?? 'Sin nombre' }}</td>
            <td>{{ $evaAct->organizacion->aldea->municipio->departamento->Nombre_Departamento ?? 'No definido' }}</td>

            {{-- Evaluación Inicial --}}
            <td>{{ round(($evaAct->desempeno_institucional / 315) * 100, 2) }}%</td>
            <td>{{ round(($evaAct->total_financiero / 400) * 100, 2) }}%</td>
            <td>{{ round((($evaAct->desempeno_institucional + $evaAct->total_financiero) / (315 + 400)) * 100, 2) }}%</td>
           @php
    $totalAct = (($evaAct->desempeno_institucional + $evaAct->total_financiero) / (315 + 400)) * 100;
    $categoriaAct = match(true) {
        $totalAct >= 90 => 'A',
        $totalAct >= 71 => 'B',
        $totalAct >= 50 => 'C',
        default => 'D',
    };
@endphp
<td>{{ $categoriaAct }}</td>

            {{-- Evaluación Actualizada --}}
            @if ($evaIni && $esActualizada)
                <td>{{ round(($evaIni->desempeno_institucional / 315) * 100, 2) }}%</td>
                <td>{{ round(($evaIni->total_financiero / 400) * 100, 2) }}%</td>
                <td>{{ round((($evaIni->desempeno_institucional + $evaIni->total_financiero) / (315 + 400)) * 100, 2) }}%</td>
           @php
    $totalIni = (($evaIni->desempeno_institucional + $evaIni->total_financiero) / (315 + 400)) * 100;
    $categoriaIni = match(true) {
        $totalIni >= 90 => 'A',
        $totalIni >= 71 => 'B',
        $totalIni >= 50 => 'C',
        default => 'D',
    };
@endphp
<td>{{ $categoriaIni }}</td>

            @else
                <td colspan="4" class="text-muted">Sin datos</td>
            @endif

            {{-- % de crecimiento --}}
            <td>
                @if ($evaIni && $esActualizada)
                    @php
                        $crecimiento = (
                            ((($evaIni->desempeno_institucional / 315) * 100) + (($evaIni->total_financiero / 400) * 100))
                            -
                            ((($evaAct->desempeno_institucional / 315) * 100) + (($evaAct->total_financiero / 400) * 100))
                        );
                    @endphp
                    {{ round($crecimiento, 2) }}%
                @else
                    0%
                @endif
            </td>

            {{-- Acciones --}}
           {{-- Acciones --}}
<td>
    <a href="{{ route('evaluacion.edit', $evaAct->Id_Evaluacion ?? ($evaIni->Id_Evaluacion ?? 0)) }}" class="btn btn-sm btn-primary">Actualizar</a>

    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmDeleteModal"
        data-id="{{ $evaAct->Id_Evaluacion ?? ($evaIni->Id_Evaluacion ?? 0) }}">
        Borrar
    </button>
</td>

        </tr>
        @endforeach
    </tbody>
</table>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="deleteForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmDeleteLabel">Confirmar Eliminación</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar esta evaluación?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Sí, eliminar</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- Script para configurar el formulario del modal -->
<script>
    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var action = '{{ route("evaluacion.destroy", ":id") }}'.replace(':id', id)
        $('#deleteForm').attr('action', action)
    })
</script>
@endsection
