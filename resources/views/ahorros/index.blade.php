@extends('adminlte::page') 

@section('title', 'Listado de Ahorros')

@section('content_header')
<div class="d-flex justify-content-between align-items-center flex-wrap">
    <h1 class="mb-0 fw-bold text-dark">Gestión de Ahorros</h1>

    <div class="d-flex gap-2 mt-2 mt-md-0">

        <a href="{{ route('ahorros.resumen') }}" class="btn btn-info shadow-sm">
            <i class="fas fa-chart-bar"></i> Resumen
        </a>

        <a href="{{ route('ahorros.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus-circle"></i> Nuevo Ahorro
        </a>
<<<<<<< HEAD

        <a href="{{ route('ahorros.reportePDF', request()->query()) }}" 
           class="btn btn-danger shadow-sm" 
           target="_blank">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
=======
      
>>>>>>> origin/cambios-seguridad
    </div>
</div>
@stop

@section('content')

{{-- FILTROS --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">

        <form method="GET" action="{{ route('ahorros.index') }}" class="row g-3">

            <div class="col-md-3">
                <label class="form-label fw-semibold text-dark">Organización</label>
                <input type="text" name="organizacion" class="form-control border-dark"
                       placeholder="Nombre de organización" value="{{ request('organizacion') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold text-dark">Beneficiario</label>
                <input type="text" name="beneficiario" class="form-control border-dark"
                       placeholder="Nombre de beneficiario" value="{{ request('beneficiario') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold text-dark">Tipo</label>
                <select name="tipo" class="form-control border-dark">
                    <option value="">Todos los tipos</option>
                    <option value="Socio" {{ request('tipo')=='Socio'?'selected':'' }}>Socio</option>
                    <option value="Cliente" {{ request('tipo')=='Cliente'?'selected':'' }}>Cliente</option>
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-50 shadow-sm">
                    <i class="fas fa-search"></i> Buscar
                </button>

                <a href="{{ route('ahorros.index') }}" class="btn btn-secondary w-50 shadow-sm">
                    <i class="fas fa-eraser"></i> Limpiar
                </a>
            </div>

        </form>

    </div>
</div>

{{-- TABLA --}}
<div class="card shadow-sm border-0">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">

                <thead class="thead-dark text-white" style="background:#1b263b;">
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

                            {{-- ORGANIZACIÓN --}}
                            <td class="fw-semibold text-dark">
                                {{ $ahorro->organizacion?->Nombre_Organizacion ?? 'N/D' }}
                            </td>

                            {{-- BENEFICIARIO --}}
                            <td class="text-dark">
                                {{ $ahorro->beneficiario?->Nombre_Beneficiario ?? 'N/D' }}
                            </td>

                            {{-- TIPO DE SOCIO --}}
                            <td>
                                @php
                                    $tipo = $ahorro->beneficiario?->Tipo_De_Socio ?? 'N/D';
                                    $badgeClass = ($tipo === 'Socio')
                                        ? 'bg-primary'
                                        : 'bg-warning text-dark';
                                @endphp

                                <span class="badge {{ $badgeClass }}">
                                    {{ $tipo }}
                                </span>
                            </td>

                            {{-- MONTO --}}
                            <td class="fw-bold text-success">
                                L. {{ number_format($ahorro->Monto, 2, '.', ',') }}
                            </td>

                            {{-- ACCIONES --}}
                            <td>
                                {{-- BOTÓN EDITAR - REPARADO --}}
                                <a href="{{ route('ahorros.edit', $ahorro->id_Ahorro) }}" 
                                   class="btn btn-sm btn-primary shadow-sm" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- BOTÓN FICHA - REPARADO --}}
                                <a href="{{ route('ahorros.ficha', $ahorro->id_Ahorro) }}" 
                                   class="btn btn-sm btn-dark shadow-sm mx-1" 
                                   title="Ficha">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- BOTÓN ELIMINAR - REPARADO --}}
                                <form action="{{ route('ahorros.destroy', $ahorro->id_Ahorro) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirmarEliminacion(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger shadow-sm" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No hay ahorros registrados.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>
</div>

{{-- PAGINACIÓN --}}
<div class="mt-3">
    {{ $ahorros->appends(request()->query())->links() }}
</div>

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmarEliminacion(e) {
    e.preventDefault();
    Swal.fire({
        title: "¿Eliminar ahorro?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((r) => {
        if (r.isConfirmed) {
            e.target.submit();
        }
    });

    return false;
}
</script>
@stop
