@extends('adminlte::page')

@section('content')
<div class="container">
    <h2>Gestión De Roles y Permisos</h2>
    <div class="mb-3">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevoRol">Nuevo Rol</button>
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalNuevoObjeto">Nuevo Objeto</button>
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalNuevoPermiso">Nuevo Permiso</button>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('asignar.permisos') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="Id_Rol" class="form-label">Rol</label>
            <select name="Id_Rol" id="Id_Rol" class="form-control" required>
                <option value="">Seleccione un rol</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->Id_Rol }}">{{ $rol->Rol }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="Id_Objeto" class="form-label">Objeto</label>
            <select name="Id_Objeto" id="Id_Objeto" class="form-control" required>
                <option value="">Seleccione un objeto</option>
                @foreach($objetos as $objeto)
                    <option value="{{ $objeto->Id_Objeto }}">{{ $objeto->Objeto }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Permisos</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="Permiso_Insercion" value="1" id="insercion">
                <label class="form-check-label" for="insercion">Inserción</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="Permiso_Eliminacion" value="1" id="eliminacion">
                <label class="form-check-label" for="eliminacion">Eliminación</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="Permiso_Actualizacion" value="1" id="actualizacion">
                <label class="form-check-label" for="actualizacion">Actualización</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="Permiso_Consultar" value="1" id="consultar">
                <label class="form-check-label" for="consultar">Consultar</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Permisos</button>
    </form>

    <!-- Modal Nuevo Rol -->
    <div class="modal fade" id="modalNuevoRol" tabindex="-1" aria-labelledby="modalNuevoRolLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="{{ route('roles.store') }}">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="modalNuevoRolLabel">Agregar Nuevo Rol</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="nombre_rol" class="form-label">Nombre del Rol</label>
                <input type="text" class="form-control" id="nombre_rol" name="Rol" required>
              </div>
              <div class="mb-3">
                <label for="descripcion_rol" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="descripcion_rol" name="Descripcion">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Guardar Rol</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Nuevo Objeto -->
    <div class="modal fade" id="modalNuevoObjeto" tabindex="-1" aria-labelledby="modalNuevoObjetoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="{{ route('objetos.store') }}">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="modalNuevoObjetoLabel">Agregar Nuevo Objeto</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="nombre_objeto" class="form-label">Nombre del Objeto</label>
                <input type="text" class="form-control" id="nombre_objeto" name="Objeto" required>
              </div>
              <div class="mb-3">
                <label for="descripcion_objeto" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="descripcion_objeto" name="Descripcion">
              </div>
              <div class="mb-3">
                <label for="tipo_objeto" class="form-label">Tipo de Objeto</label>
                <input type="text" class="form-control" id="tipo_objeto" name="Tipo_Objeto">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-info">Guardar Objeto</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Nuevo Permiso (solo informativo, ya que los permisos se asignan con los checkboxes) -->
    <div class="modal fade" id="modalNuevoPermiso" tabindex="-1" aria-labelledby="modalNuevoPermisoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalNuevoPermisoLabel">Permisos disponibles</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <ul>
              <li>Permiso de Inserción</li>
              <li>Permiso de Eliminación</li>
              <li>Permiso de Actualización</li>
              <li>Permiso de Consulta</li>
            </ul>
            <p>Los permisos se asignan usando los checkboxes del formulario principal.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection