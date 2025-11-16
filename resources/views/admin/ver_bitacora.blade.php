@extends('adminlte::page')

@section('title', 'Bitácora del Sistema')

@section('content')
<div class="container my-4">

    {{-- Título --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <div>
            <h2 class="fw-bold text-gradient mb-0">Bitácora del Sistema</h2>
            <small class="text-muted">Historial de acciones registradas en el sistema.</small>
        </div>
        <a href="{{ route('bitacora.exportar.pdf', request()->query()) }}" class="btn btn-danger btn-modern">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </div>

    {{-- FILTROS --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">

                <div class="col-md-2">
                    <label class="form-label small">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small">Usuario</label>
                    <input type="text" name="usuario" class="form-control" placeholder="Nombre Usuario" value="{{ request('usuario') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small">Objeto</label>
                    <input type="text" name="objeto" class="form-control" placeholder="Objeto" value="{{ request('objeto') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small">Acción</label>
                    <select name="accion" class="form-select">
                        <option value="">Todas</option>
                        @foreach(['Ingreso','Salida','Fallo','Lockout','Actualización','Bloqueo','Desbloqueo','Nuevo','Update','Delete'] as $a)
                            <option value="{{ $a }}" {{ request('accion')===$a ? 'selected':'' }}>
                                {{ $a }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small">Descripción</label>
                    <input type="text" name="descripcion" class="form-control" placeholder="Coincidencia" value="{{ request('descripcion') }}">
                </div>

                <div class="col-md-12 d-flex justify-content-between mt-3">

                    <button type="submit" class="btn btn-primary btn-modern">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>

                    {{-- BORRAR --}}
                    <form method="POST" action="{{ route('bitacora.borrar') }}"
                          onsubmit="return confirmarEliminacionBitacora(event);">
                        @csrf

                        {{-- Pasar filtros --}}
                        @foreach(request()->all() as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach

                        <button type="submit" class="btn btn-danger btn-modern">
                            <i class="fas fa-trash"></i> Borrar registros filtrados
                        </button>
                    </form>

                </div>

            </form>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="card shadow-lg border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla-bitacora" class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Objeto</th>
                            <th>Acción</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registros as $r)
                            <tr>
                                <td>{{ $r->Fecha }}</td>
                                <td>{{ $r->usuario->Nombre_Usuario ?? 'N/A' }}</td>
                                <td>{{ $r->objeto->Objeto ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-secondary px-3 py-2">
                                        {{ $r->Accion }}
                                    </span>
                                </td>
                                <td class="text-start">{{ $r->Descripcion }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">
                                    No hay registros para los filtros seleccionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection


@section('js')
{{-- SweetAlert2 moderno --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Confirmación de borrado --}}
<script>
function confirmarEliminacionBitacora(e) {
    e.preventDefault();
    Swal.fire({
        title: '¿Eliminar registros?',
        text: 'Se eliminarán todos los registros filtrados.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            e.target.submit();
        }
    });
}
</script>

{{-- DataTable --}}
<script>
$(document).ready(function() {
    $('#tabla-bitacora').DataTable({
        order: [[0, 'desc']],
        searching: false,
        language: {
            lengthMenu: 'Mostrar _MENU_ registros',
            zeroRecords: 'No se encontraron resultados',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
            infoEmpty: 'Mostrando 0 registros',
            infoFiltered: '(filtrado de _MAX_)',
            search: 'Buscar:',
            paginate: {
                first: 'Primero',
                last: 'Último',
                next: 'Siguiente',
                previous: 'Anterior'
            }
        }
    });
});
</script>
@endsection


@section('css')
<style>
    .text-gradient {
        background: linear-gradient(90deg, #0d6efd, #20c997);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .btn-modern {
        border-radius: 100px;
        padding-inline: 1.3rem;
    }
    .card {
        border-radius: 1.2rem;
    }
</style>
@endsection
