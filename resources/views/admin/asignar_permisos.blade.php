@extends('adminlte::page')

@section('title', 'Gestión de Roles y Permisos')

@section('content')
<div class="container">

    <h2 class="text-center my-4 font-weight-bold">Gestión de Roles y Permisos</h2>

    <a href="{{ route('roles.exportar.pdf') }}" class="btn btn-danger mb-3">
        <i class="fas fa-file-pdf"></i> Exportar a PDF
    </a>

    {{-- FORMULARIO SUPERIOR --}}
    <form>
        <div class="row mb-3 align-items-end">

            {{-- SELECCIÓN DE ROL --}}
            <div class="col-md-4">
                <label for="Id_Rol" class="form-label">Rol</label>
                <select id="Id_Rol" class="form-control form-control-sm" required>
                    <option value="">Seleccione un rol</option>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->Id_Rol }}">{{ $rol->Rol }}</option>
                    @endforeach
                </select>
            </div>

            {{-- SELECCIÓN DE OBJETO --}}
            <div class="col-md-4">
                <label for="Id_Objeto" class="form-label">Objeto</label>
                <select id="Id_Objeto" class="form-control form-control-sm" required>
                    <option value="">Seleccione un objeto</option>
                    @foreach ($objetos as $objeto)
                        <option value="{{ $objeto->Id_Objeto }}">{{ $objeto->Objeto }}</option>
                    @endforeach
                </select>
            </div>

            {{-- BOTÓN ABRIR MODAL --}}
            <div class="col-md-4 d-flex justify-content-end mt-3 mt-md-0">
                <button type="button"
                        id="btnAsignar"
                        class="btn btn-secondary btn-sm"
                        data-toggle="modal"
                        data-target="#modalAsignarPermisos">

                    Asignar Permisos
                </button>
            </div>

        </div>
    </form>

    {{-- ===========================
            MODAL
    ============================ --}}
    <div class="modal fade" id="modalAsignarPermisos" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="{{ route('permisos.asignar') }}" method="POST">
                    @csrf
                    
                    <input type="hidden" name="Id_Rol" id="modal_Id_Rol">
                    <input type="hidden" name="Id_Objeto" id="modal_Id_Objeto">

                    <div class="modal-header">
                        <h5 class="modal-title">Asignar Permisos</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <label class="form-label">Permisos</label><br>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="Permiso_Insercion" value="1" id="modal_insercion">
                            <label class="form-check-label" for="modal_insercion">Inserción</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="Permiso_Eliminacion" value="1" id="modal_eliminacion">
                            <label class="form-check-label" for="modal_eliminacion">Eliminación</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="Permiso_Actualizacion" value="1" id="modal_actualizacion">
                            <label class="form-check-label" for="modal_actualizacion">Actualización</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="Permiso_Consultar" value="1" id="modal_consultar">
                            <label class="form-check-label" for="modal_consultar">Consultar</label>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- ===========================
            TABLA
    ============================ --}}
    <div class="table-responsive mt-4">
        <table id="tabla-permisos" class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="thead-dark">
                <tr>
                    <th>Rol</th>
                    <th>Objeto</th>
                    <th>Inserción</th>
                    <th>Eliminación</th>
                    <th>Actualización</th>
                    <th>Consulta</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rolesObjetos as $ro)
                    <tr>
                        <td>{{ $ro->rol->Rol ?? '-' }}</td>
                        <td>{{ $ro->objeto->Objeto ?? '-' }}</td>

                        <td class="text-center">
                            {!! $ro->Permiso_Insercion
                                ? '<i class="fas fa-check text-success"></i>'
                                : '<i class="fas fa-times text-danger"></i>' !!}
                        </td>

                        <td class="text-center">
                            {!! $ro->Permiso_Eliminacion
                                ? '<i class="fas fa-check text-success"></i>'
                                : '<i class="fas fa-times text-danger"></i>' !!}
                        </td>

                        <td class="text-center">
                            {!! $ro->Permiso_Actualizacion
                                ? '<i class="fas fa-check text-success"></i>'
                                : '<i class="fas fa-times text-danger"></i>' !!}
                        </td>

                        <td class="text-center">
                            {!! $ro->Permiso_Consultar
                                ? '<i class="fas fa-check text-success"></i>'
                                : '<i class="fas fa-times text-danger"></i>' !!}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay permisos asignados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

@section('js')

{{-- DataTables --}}
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {

    // VALIDACIÓN ANTES DE ABRIR EL MODAL
    $('#btnAsignar').on('click', function () {
        const rol = $('#Id_Rol').val();
        const obj = $('#Id_Objeto').val();

        if (!rol || !obj) {
            Swal.fire({
                icon: 'warning',
                text: 'Debe seleccionar un Rol y un Objeto antes de asignar permisos.',
            });
            return false;
        }

        $('#modal_Id_Rol').val(rol);
        $('#modal_Id_Objeto').val(obj);
    });

    // DATATABLES
    $('#tabla-permisos').DataTable({
        language: {
            lengthMenu: 'Mostrar _MENU_ registros',
            zeroRecords: 'No se encontraron resultados',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
            infoEmpty: 'No hay registros disponibles',
            infoFiltered: '(filtrado de _MAX_ registros totales)',
            search: 'Buscar:',
            paginate: {
                first: 'Primero',
                last: 'Último',
                next: 'Siguiente',
                previous: 'Anterior'
            }
        },
        order: [[0, 'asc']]
    });

    // ALERTAS SWEETALERT
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}'
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}'
        });
    @endif

});
</script>
@endsection
