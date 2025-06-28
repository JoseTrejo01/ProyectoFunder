@extends('adminlte::page')

@section('content')
<div class="container">
    <h2>Gestión de Usuarios</h2>
    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">Nuevo Usuario</button>
    <table id="tabla-usuarios" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Nombre de Usuario</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Fecha de Registro</th>
                <th>Fecha de Vencimiento</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->Usuario }}</td>
                    <td>{{ $usuario->Nombre_Usuario }}</td>
                    <td>{{ $usuario->Correo_Electronico }}</td>
                    <td>{{ $usuario->rol->Rol ?? '-' }}</td>
                    <td>{{ $usuario->Estado_Usuario }}</td>
                    <td>{{ $usuario->Fecha_Creacion }}</td>
                    <td>{{ $usuario->Fecha_Vencimiento ?? '-' }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            <button class="btn btn-xs btn-primary p-1" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario{{ $usuario->Id_Usuario }}" title="Editar" style="font-size: 0.85rem;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('usuarios.destroy', $usuario->Id_Usuario) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger p-1" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')" title="Borrar" style="font-size: 0.85rem;">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <!-- Modal Editar Usuario -->
                <div class="modal fade" id="modalEditarUsuario{{ $usuario->Id_Usuario }}" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel{{ $usuario->Id_Usuario }}" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form method="POST" action="{{ route('usuarios.update', $usuario->Id_Usuario) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalEditarUsuarioLabel{{ $usuario->Id_Usuario }}">Editar Usuario</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label for="Usuario{{ $usuario->Id_Usuario }}" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="Usuario{{ $usuario->Id_Usuario }}" name="Usuario" value="{{ $usuario->Usuario }}" required>
                          </div>
                          <div class="mb-3">
                            <label for="Nombre_Usuario{{ $usuario->Id_Usuario }}" class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="Nombre_Usuario{{ $usuario->Id_Usuario }}" name="Nombre_Usuario" value="{{ $usuario->Nombre_Usuario }}" required>
                          </div>
                          <div class="mb-3">
                            <label for="Correo_Electronico{{ $usuario->Id_Usuario }}" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="Correo_Electronico{{ $usuario->Id_Usuario }}" name="Correo_Electronico" value="{{ $usuario->Correo_Electronico }}" required>
                          </div>
                          <div class="mb-3">
                            <label for="Id_Rol{{ $usuario->Id_Usuario }}" class="form-label">Rol</label>
                            <select class="form-control" id="Id_Rol{{ $usuario->Id_Usuario }}" name="Id_Rol" required>
                              @foreach($roles as $rol)
                                <option value="{{ $rol->Id_Rol }}" @if($usuario->Id_Rol == $rol->Id_Rol) selected @endif>{{ $rol->Rol }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="mb-3">
                            <label for="Estado_Usuario{{ $usuario->Id_Usuario }}" class="form-label">Estado</label>
                            <select class="form-control" id="Estado_Usuario{{ $usuario->Id_Usuario }}" name="Estado_Usuario" required>
                              <option value="ACTIVO" @if($usuario->Estado_Usuario == 'ACTIVO') selected @endif>ACTIVO</option>
                              <option value="INACTIVO" @if($usuario->Estado_Usuario == 'INACTIVO') selected @endif>INACTIVO</option>
                              <option value="NUEVO" @if($usuario->Estado_Usuario == 'NUEVO') selected @endif>NUEVO</option>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Nuevo Usuario -->
    <div class="modal fade" id="modalNuevoUsuario" tabindex="-1" aria-labelledby="modalNuevoUsuarioLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="{{ route('usuarios.store') }}">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="modalNuevoUsuarioLabel">Agregar Nuevo Usuario</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="Usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="Usuario" name="Usuario" required>
              </div>
              <div class="mb-3">
                <label for="Nombre_Usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="Nombre_Usuario" name="Nombre_Usuario" required>
              </div>
              <div class="mb-3">
                <label for="Correo_Electronico" class="form-label">Correo</label>
                <input type="email" class="form-control" id="Correo_Electronico" name="Correo_Electronico" required>
              </div>
              <div class="mb-3">
                <label for="Id_Rol" class="form-label">Rol</label>
                <select class="form-control" id="Id_Rol" name="Id_Rol" required>
                  <option value="">Seleccione un rol</option>
                  @foreach($roles as $rol)
                    <option value="{{ $rol->Id_Rol }}">{{ $rol->Rol }}</option>
                  @endforeach
                </select>
              </div>
              {{-- Campo de contraseña eliminado, será autogenerada --}}
              <div class="mb-3">
                <label for="Estado_Usuario" class="form-label">Estado</label>
                <select class="form-control" id="Estado_Usuario" name="Estado_Usuario" required>
                  <option value="NUEVO">NUEVO</option>
                  <option value="ACTIVO">ACTIVO</option>
                  <option value="BLOQUEADO">BLOQUEADO</option>
                  <option value="INACTIVO">INACTIVO</option>
                  <option value="VACACIONES">VACACIONES</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Guardar Usuario</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#tabla-usuarios').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [[5, 'desc']], // Cambiado: 5 es la columna 'Fecha de Registro'
            searching: false
        });
    });
    </script>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
@endsection
