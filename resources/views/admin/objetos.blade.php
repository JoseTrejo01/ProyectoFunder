@extends('adminlte::page')

@section('title', 'Gestión de Objetos')

@section('content')
<div class="container">

    <h2 class="text-center my-4 font-weight-bold">Gestión de Objetos</h2>

    {{-- ================= BOTONES SUPERIORES ================= --}}
    <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevoObjeto">
            <i class="fas fa-plus"></i> Nuevo Objeto
        </button>

        <a href="{{ route('objetos.exportar-pdf') }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </div>

    {{-- ================= TABLA ================= --}}
    <div class="table-responsive">
        <table id="tabla-objetos" class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Tipo</th>
                    <th style="width: 110px;">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($objetos as $objeto)
                <tr>
                    <td>{{ $objeto->Id_Objeto }}</td>
                    <td class="text-start">{{ $objeto->Objeto }}</td>
                    <td class="text-start">{{ $objeto->Descripcion }}</td>
                    <td>{{ $objeto->Tipo_Objeto }}</td>
                    <td class="text-center">

                        {{-- Editar --}}
                        <button class="btn btn-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEditarObjeto{{ $objeto->Id_Objeto }}"
                            title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>

                        {{-- Eliminar --}}
                        <form action="{{ route('objetos.destroy', $objeto->Id_Objeto) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirmarEliminacionObjeto(event)">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>

                    </td>
                </tr>

                {{-- ================= MODAL EDITAR ================= --}}
                <div class="modal fade" id="modalEditarObjeto{{ $objeto->Id_Objeto }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <form method="POST" action="{{ route('objetos.update', $objeto->Id_Objeto) }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Objeto</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" class="form-control validacion-texto"
                                               name="Objeto"
                                               value="{{ $objeto->Objeto }}"
                                               maxlength="40"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Descripción</label>
                                        <input type="text" class="form-control validacion-texto"
                                               name="Descripcion"
                                               value="{{ $objeto->Descripcion }}"
                                               maxlength="40">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tipo</label>
                                        <input type="text" class="form-control validacion-texto"
                                               name="Tipo_Objeto"
                                               value="{{ $objeto->Tipo_Objeto }}"
                                               maxlength="40">
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button class="btn btn-primary">Guardar</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ================= MODAL NUEVO OBJETO ================= --}}
    <div class="modal fade" id="modalNuevoObjeto" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST" action="{{ route('objetos.store') }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Nuevo Objeto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control validacion-texto"
                                   name="Objeto" required maxlength="40">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <input type="text" class="form-control validacion-texto"
                                   name="Descripcion" maxlength="40">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <input type="text" class="form-control validacion-texto"
                                   name="Tipo_Objeto" maxlength="40">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-success">Guardar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</div>
@endsection


@section('js')
{{-- BOOTSTRAP --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- SWEETALERT2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ALERTAS FLASH --}}
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: '¡Éxito!',
    text: @json(session('success')),
    timer: 2200,
    showConfirmButton: false
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: @json(session('error')),
    confirmButtonColor: "#d33"
});
</script>
@endif


{{-- CONFIRMACIÓN ELIMINAR --}}
<script>
function confirmarEliminacionObjeto(event) {
    event.preventDefault();

    Swal.fire({
        title: "¿Eliminar objeto?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#d33"
    }).then(result => {
        if (result.isConfirmed) {
            event.target.submit();
        }
    });
}
</script>


{{-- VALIDACIONES --}}
<script>
$(document).ready(function() {

    $('#tabla-objetos').DataTable({
        language: {
            lengthMenu: 'Mostrar _MENU_ registros',
            search: 'Buscar:',
            zeroRecords: 'No se encontraron resultados',
            paginate: { previous: 'Anterior', next: 'Siguiente' }
        },
        order: [[0, 'desc']]
    });

    // Validación clean
    $('.validacion-texto').on('input', function() {
        let limpio = this.value.replace(/[^A-Za-zÁÉÍÓÚÑáéíóúñ ]+/g, "").toUpperCase();
        this.value = limpio;
    });

});
</script>
@endsection


@section('css')
<style>
    .validacion-texto {
        text-transform: uppercase;
    }
</style>
@endsection
