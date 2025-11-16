@extends('adminlte::page')

@section('title', 'Gestión de Roles')

@section('content')
<div class="container">

    <h2 class="text-center my-4 font-weight-bold">Gestión de Roles</h2>

    {{-- ================= BOTONES SUPERIORES ================= --}}
    <div class="d-flex gap-2 mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevoRol">
            <i class="fas fa-plus"></i> Nuevo Rol
        </button>

        <a href="{{ route('roles.exportar.pdf') }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </div>

    {{-- ================= TABLA ================= --}}
    <div class="table-responsive">
        <table id="tabla-roles" class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Rol</th>
                    <th>Descripción</th>
                    <th style="width: 120px;">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($roles as $rol)
                <tr>
                    <td>{{ $rol->Id_Rol }}</td>
                    <td>{{ $rol->Rol }}</td>
                    <td>{{ $rol->Descripcion }}</td>

                    <td class="text-center">

                        {{-- Editar --}}
                        <button class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarRol{{ $rol->Id_Rol }}"
                                title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>

                        {{-- Eliminar --}}
                        <form action="{{ route('roles.destroy', $rol->Id_Rol) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirmarEliminacionRol(event)">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger btn-sm"
                                    title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>

                    </td>
                </tr>

                {{-- ================= MODAL EDITAR ROL ================= --}}
                <div class="modal fade"
                     id="modalEditarRol{{ $rol->Id_Rol }}"
                     tabindex="-1"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <form method="POST" action="{{ route('roles.update', $rol->Id_Rol) }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Editar Rol</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label class="form-label">Nombre</label>
                                        <input type="text"
                                               name="Rol"
                                               value="{{ $rol->Rol }}"
                                               class="form-control validacion-texto"
                                               maxlength="40"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Descripción</label>
                                        <input type="text"
                                               name="Descripcion"
                                               value="{{ $rol->Descripcion }}"
                                               class="form-control validacion-texto"
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
                {{-- ================= FIN MODAL EDITAR ================= --}}

                @endforeach
            </tbody>
        </table>
    </div>


    {{-- ================= MODAL NUEVO ROL ================= --}}
    <div class="modal fade" id="modalNuevoRol" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf

                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Agregar Nuevo Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text"
                                   name="Rol"
                                   class="form-control validacion-texto"
                                   required maxlength="40">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <input type="text"
                                   name="Descripcion"
                                   class="form-control validacion-texto"
                                   maxlength="40">
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
{{-- Bootstrap --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ================= ALERTAS FLASH ================= --}}
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: '¡Éxito!',
    text: @json(session('success')),
    showConfirmButton: false,
    timer: 2000
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: @json(session('error')),
});
</script>
@endif


{{-- ================= CONFIRMAR ELIMINACIÓN ================= --}}
<script>
function confirmarEliminacionRol(event) {
    event.preventDefault();

    Swal.fire({
        title: '¿Eliminar rol?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            event.target.submit();
        }
    });
}
</script>


{{-- ================= VALIDACIÓN CAMPOS ================= --}}
<script>
$(document).ready(function() {

    $('#tabla-roles').DataTable({
        language: {
            lengthMenu: 'Mostrar _MENU_ registros',
            search: 'Buscar:',
            zeroRecords: 'No se encontraron resultados',
            paginate: { next: 'Siguiente', previous: 'Anterior' }
        },
        order: [[0, 'desc']]
    });

    // Validación dinámica
    $('.validacion-texto').on('input', function() {
        this.value = this.value
            .replace(/[^A-Za-zÁÉÍÓÚÑáéíóúñ ]/g, '')  // Solo letras y espacios
            .toUpperCase();
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
