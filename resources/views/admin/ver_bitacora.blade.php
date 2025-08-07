@extends('adminlte::page')

@section('content')
<div class="container">
    <h2 class="text-center my-4 font-weight-bold">Bitácora del Sistema</h2>
    @if(session('success'))
        {{-- <div class="alert alert-success">{{ session('success') }}</div> --}}
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
            <form method="POST" action="{{ route('bitacora.borrar') }}" onsubmit="return confirmarEliminacionBitacora(event);" class="w-100">
                @csrf
                <input type="hidden" name="fecha_desde" value="{{ request('fecha_desde') }}">
                <input type="hidden" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                <button type="submit" class="btn btn-danger btn-sm w-100">Borrar registros filtrados</button>
                
            </form>
            
        </div>
        
    </div>
    <a href="{{ route('bitacora.exportar.pdf', request()->query()) }}" class="btn btn-danger mb-3">
    <i class="fas fa-file-pdf"></i> Exportar a PDF
</a>

    <div class="table-responsive">
        <table id="tabla-bitacora" class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="thead-dark">
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
</div>
@endsection

@section('css')
    {{-- Si quieres agregar estilos personalizados, hazlo aquí --}}
@endsection

@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script>
function confirmarEliminacionBitacora(e) {
    e.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¡Esta acción eliminará los registros filtrados de la bitácora!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            e.target.submit();
        }
    });
    return false;
}
$(document).ready(function() {
    $('#tabla-bitacora').DataTable({
        language: {
            lengthMenu: 'Mostrar _MENU_ registros',
            zeroRecords: 'No se encontraron resultados',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
            infoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
            infoFiltered: '(filtrado de un total de _MAX_ registros)',
            search: 'Buscar:',
            paginate: {
                first: 'Primero',
                last: 'Último',
                next: 'Siguiente',
                previous: 'Anterior'
            },
            processing: 'Procesando...'
        },
        order: [[0, 'desc']],
        searching: false // Desactiva el buscador
    });
});
</script>
@endsection
