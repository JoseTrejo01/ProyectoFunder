@extends('adminlte::page')

@section('content')
<div class="container">

  <div class="container">
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: @json(session('success')),
                    confirmButtonColor: '#5B8E3E',
                    timer: 2500,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    <h2 class="text-center my-4 font-weight-bold">Gestión de Usuarios</h2>
    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">Nuevo Usuario</button>
    <div class="table-responsive">
        <table id="tabla-usuarios" class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="thead-dark">
                <tr>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Registro</th>
                    <th>Vencimiento</th>
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
                            <form action="{{ route('usuarios.destroy', $usuario->Id_Usuario) }}" method="POST" style="display:inline-block" onsubmit="return confirmarEliminacion(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger p-1" title="Borrar" style="font-size: 0.85rem;">
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
                            <input
                            type="text"
                            class="form-control"
                            id="Usuario{{ $usuario->Id_Usuario }}"
                            value="{{ $usuario->Usuario }}"
                            disabled
                        >
                        <input type="hidden" name="Usuario" value="{{ $usuario->Usuario }}">

                          </div>
                          <div class="mb-3">
                            <label for="Nombre_Usuario{{ $usuario->Id_Usuario }}" class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="Nombre_Usuario{{ $usuario->Id_Usuario }}" name="Nombre_Usuario" value="{{ $usuario->Nombre_Usuario }}" required maxlength="40>
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
                              <option value="BLOQUEADO" @if($usuario->Estado_Usuario == 'BLOQUEADO') selected @endif>BLOQUEADO</option>
                              <option value="VACACIONES" @if($usuario->Estado_Usuario == 'VACACIONES') selected @endif>VACACIONES</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label for="Fecha_Vencimiento{{ $usuario->Id_Usuario }}" class="form-label">Fecha de Vencimiento</label>
                            <input
                                type="date"
                                class="form-control"
                                id="Fecha_Vencimiento{{ $usuario->Id_Usuario }}"
                                name="Fecha_Vencimiento"
                                value="{{ old('Fecha_Vencimiento', $usuario->Fecha_Vencimiento ? \Carbon\Carbon::parse($usuario->Fecha_Vencimiento)->format('Y-m-d') : '') }}"
                            >
                        </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
            @endforeach
        </tbody>
    </table>
</div>

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
                <input
                type="text"
                name="Usuario"
                id="Usuario"
                maxlength="30"
                class="form-control @error('Usuario') is-invalid @enderror"
                value="{{ old('Usuario') }}"
                required
                maxlength="60"
                pattern="[A-Z0-9]+"
                title="Solo letras mayúsculas y números"
            />

            @error('Usuario')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
              </div>
              <div class="mb-3">
                <label for="Nombre_Usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="Nombre_Usuario" name="Nombre_Usuario" required maxlength="40>
              </div>
              <div class="mb-3">
                <label for="Correo_Electronico" class="form-label">Correo</label>
               <input 
                type="email" 
                name="Correo_Electronico" 
                class="form-control @error('Correo_Electronico') is-invalid @enderror" 
                id="Correo_Electronico" 
                value="{{ old('Correo_Electronico') }}" 
                required
              >

              @error('Correo_Electronico')
                  <div class="invalid-feedback">
                      {{ $message }}
                  </div>
              @enderror
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
              <button type="submit" class="btn btn-success">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($errors->any())
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var myModal = new bootstrap.Modal(document.getElementById('modalNuevoUsuario'));
      myModal.show();
    });
  </script>
@endif
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Acceso denegado',
                text: '{{ session('error') }}',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
    <script>
    function confirmarEliminacion(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Estás seguro?',
            text: '¡Esta acción inactivará al usuario!',
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
    </script>

    <script>
    $(document).ready(function() {
        $('#tabla-usuarios').DataTable({
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
            order: [[5, 'desc']], // Cambiado: 5 es la columna 'Fecha de Registro'
            searching: false
        });
    });
    </script>

    <script>
    // Aplica validaciones cada vez que se abre el modal Nuevo Usuario
    document.addEventListener('DOMContentLoaded', function () {
        var modalNuevoUsuario = document.getElementById('modalNuevoUsuario');
        if (modalNuevoUsuario) {
            modalNuevoUsuario.addEventListener('shown.bs.modal', function () {
                // Forzar mayúsculas en Usuario y Nombre_Usuario
                var usuarioInput = document.getElementById('Usuario');
                var nombreUsuarioInput = document.getElementById('Nombre_Usuario');
                if (usuarioInput) {
                    usuarioInput.addEventListener('input', function () {
                        this.value = this.value.toUpperCase();
                    });
                }
                if (nombreUsuarioInput) {
                    nombreUsuarioInput.setAttribute('maxlength', '40');
                    nombreUsuarioInput.addEventListener('input', function () {
                        this.value = this.value.toUpperCase();
                        if (this.value.length > 40) {
                            this.value = this.value.slice(0, 40);
                        }
                    });
                }
                // Bloquear caracteres especiales en Usuario y Nombre_Usuario
                var campos = [usuarioInput, nombreUsuarioInput];
                campos.forEach(function(campo) {
                    if (campo) {
                        campo.addEventListener('keypress', function(e) {
                            const regex = /^[A-Za-z0-9 ]+$/;
                            if (!regex.test(e.key)) {
                                e.preventDefault();
                            }
                        });
                    }
                });
            });
        }
    });
    </script>

@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
@endsection
