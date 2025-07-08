@extends('adminlte::page')

@section('content')
<div class="container">
    <h2 class="text-center my-4 font-weight-bold">Gestión De Roles y Permisos</h2>
    <form>
        <div class="row mb-3 align-items-end">
            <div class="col">
                <label for="Id_Rol" class="form-label">Rol</label>
                <select id="Id_Rol" class="form-control form-control-sm" required>
                    <option value="">Seleccione un rol</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->Id_Rol }}">{{ $rol->Rol }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="Id_Objeto" class="form-label">Objeto</label>
                <select id="Id_Objeto" class="form-control form-control-sm" required>
                    <option value="">Seleccione un objeto</option>
                    @foreach($objetos as $objeto)
                        <option value="{{ $objeto->Id_Objeto }}">{{ $objeto->Objeto }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto d-flex justify-content-end">
                <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalAsignarPermisos">Asignar Permisos</button>
            </div>
        </div>
    </form>


<!-- Modal para asignar permisos -->
<div class="modal fade" id="modalAsignarPermisos" tabindex="-1" aria-labelledby="modalAsignarPermisosLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('asignar.permisos') }}" method="POST">
        @csrf
        <input type="hidden" name="Id_Rol" id="modal_Id_Rol">
<input type="hidden" name="Id_Objeto" id="modal_Id_Objeto">
        <div class="modal-header">
          <h5 class="modal-title" id="modalAsignarPermisosLabel">Asignar Permisos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
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
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
    <!-- Tabla de roles-objetos-permisos -->
<div class="mt-5">
    <h4 class="text-center my-4 font-weight-bold"></h4>
    <div class="table-responsive">
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
                @forelse($rolesObjetos as $ro)
                    <tr>
                        <td>{{ $ro->rol->Rol ?? '-' }}</td>
                        <td>{{ $ro->objeto->Objeto ?? '-' }}</td>
                        <td class="text-center">{!! $ro->Permiso_Insercion ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' !!}</td>
                        <td class="text-center">{!! $ro->Permiso_Eliminacion ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' !!}</td>
                        <td class="text-center">{!! $ro->Permiso_Actualizacion ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' !!}</td>
                        <td class="text-center">{!! $ro->Permiso_Consultar ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' !!}</td>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const selectRol = document.getElementById('Id_Rol');
const selectObjeto = document.getElementById('Id_Objeto');
const modalRol = document.getElementById('modal_Id_Rol');
const modalObjeto = document.getElementById('modal_Id_Objeto');
const modalAsignarPermisos = document.getElementById('modalAsignarPermisos');
modalAsignarPermisos.addEventListener('show.bs.modal', function () {
    modalRol.value = selectRol.value;
    modalObjeto.value = selectObjeto.value;
});
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6',
    });
@endif
@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        confirmButtonColor: '#d33',
    });
@endif
</script>
<script>
$(document).ready(function() {
    $('#tabla-permisos').DataTable({
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
        order: [[0, 'desc']]
    });
});
</script>
@endsection