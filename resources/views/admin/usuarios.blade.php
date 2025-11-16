@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h2 class="mb-0 fw-bold text-gradient">Gestión de Usuarios</h2>
            <small class="text-muted">Administra cuentas, estados y roles del sistema.</small>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                <i class="fas fa-user-plus me-1"></i> Nuevo Usuario
            </button>
            <a href="{{ route('usuarios.exportar.pdf') }}" class="btn btn-danger btn-modern">
                <i class="fas fa-file-pdf me-1"></i> Exportar PDF
            </a>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">

            <div class="table-responsive">
                <table id="tabla-usuarios" class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            <th>Vencimiento</th>
                            <th style="width: 110px;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->Usuario }}</td>
                            <td class="text-start">{{ $usuario->Nombre_Usuario }}</td>
                            <td class="text-start">{{ $usuario->Correo_Electronico }}</td>
                            <td>{{ $usuario->rol->Rol ?? '-' }}</td>
                            <td>
                                @php
                                    $estado = $usuario->Estado_Usuario;
                                    $badgeClass = match($estado) {
                                        'ACTIVO'     => 'bg-success',
                                        'INACTIVO'   => 'bg-secondary',
                                        'BLOQUEADO'  => 'bg-danger',
                                        'VACACIONES' => 'bg-info',
                                        'NUEVO'      => 'bg-warning text-dark',
                                        default      => 'bg-light text-dark'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">
                                    {{ $estado }}
                                </span>
                            </td>
                            <td>{{ $usuario->Fecha_Creacion }}</td>
                            <td>{{ $usuario->Fecha_Vencimiento ?? '-' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Editar --}}
                                    <button class="btn btn-sm btn-primary btn-icon"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditarUsuario{{ $usuario->Id_Usuario }}"
                                            title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- Eliminar --}}
                                    <form action="{{ route('usuarios.destroy', $usuario->Id_Usuario) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirmarEliminacion(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger btn-icon"
                                                title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL EDITAR USUARIO --}}
                        <div class="modal fade" id="modalEditarUsuario{{ $usuario->Id_Usuario }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4">

                                    <form method="POST" action="{{ route('usuarios.update', $usuario->Id_Usuario) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-semibold">
                                                Editar Usuario
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>

                                        <div class="modal-body">

                                            <div class="mb-3">
                                                <label class="form-label">Usuario</label>
                                                <input type="text"
                                                       class="form-control"
                                                       value="{{ $usuario->Usuario }}"
                                                       disabled>
                                                <input type="hidden" name="Usuario" value="{{ $usuario->Usuario }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="Nombre_Usuario{{ $usuario->Id_Usuario }}" class="form-label">
                                                    Nombre de Usuario
                                                </label>
                                                <input type="text"
                                                       class="form-control texto-mayusculas @error('Nombre_Usuario') is-invalid @enderror"
                                                       id="Nombre_Usuario{{ $usuario->Id_Usuario }}"
                                                       name="Nombre_Usuario"
                                                       value="{{ old('Nombre_Usuario', $usuario->Nombre_Usuario) }}"
                                                       required
                                                       maxlength="40">
                                                @error('Nombre_Usuario')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="Correo_Electronico{{ $usuario->Id_Usuario }}" class="form-label">
                                                    Correo
                                                </label>
                                                <input type="email"
                                                       class="form-control @error('Correo_Electronico') is-invalid @enderror"
                                                       id="Correo_Electronico{{ $usuario->Id_Usuario }}"
                                                       name="Correo_Electronico"
                                                       value="{{ old('Correo_Electronico', $usuario->Correo_Electronico) }}"
                                                       maxlength="40"
                                                       required>
                                                @error('Correo_Electronico')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="Id_Rol{{ $usuario->Id_Usuario }}" class="form-label">Rol</label>
                                                <select class="form-control" id="Id_Rol{{ $usuario->Id_Usuario }}" name="Id_Rol" required>
                                                    @foreach($roles as $rol)
                                                        <option value="{{ $rol->Id_Rol }}"
                                                            @selected($usuario->Id_Rol == $rol->Id_Rol)>
                                                            {{ $rol->Rol }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="Estado_Usuario{{ $usuario->Id_Usuario }}" class="form-label">Estado</label>
                                                <select class="form-control" id="Estado_Usuario{{ $usuario->Id_Usuario }}" name="Estado_Usuario" required>
                                                    <option value="ACTIVO"     @selected($usuario->Estado_Usuario=='ACTIVO')>ACTIVO</option>
                                                    <option value="INACTIVO"   @selected($usuario->Estado_Usuario=='INACTIVO')>INACTIVO</option>
                                                    <option value="NUEVO"      @selected($usuario->Estado_Usuario=='NUEVO')>NUEVO</option>
                                                    <option value="BLOQUEADO"  @selected($usuario->Estado_Usuario=='BLOQUEADO')>BLOQUEADO</option>
                                                    <option value="VACACIONES" @selected($usuario->Estado_Usuario=='VACACIONES')>VACACIONES</option>
                                                </select>
                                            </div>

                                            <div class="mb-0">
                                                <label for="Fecha_Vencimiento{{ $usuario->Id_Usuario }}" class="form-label">
                                                    Fecha de Vencimiento
                                                </label>
                                                <input type="date"
                                                       class="form-control"
                                                       id="Fecha_Vencimiento{{ $usuario->Id_Usuario }}"
                                                       name="Fecha_Vencimiento"
                                                       value="{{ old('Fecha_Vencimiento', $usuario->Fecha_Vencimiento ? \Carbon\Carbon::parse($usuario->Fecha_Vencimiento)->format('Y-m-d') : '') }}">
                                            </div>

                                        </div>

                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                Guardar cambios
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- MODAL NUEVO USUARIO --}}
    <div class="modal fade" id="modalNuevoUsuario" tabindex="-1" aria-labelledby="modalNuevoUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">

                <form method="POST" action="{{ route('usuarios.store') }}">
                    @csrf

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold" id="modalNuevoUsuarioLabel">
                            Agregar Nuevo Usuario
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="Usuario" class="form-label">Usuario</label>
                            <input type="text"
                                   name="Usuario"
                                   id="Usuario"
                                   class="form-control texto-mayusculas @error('Usuario') is-invalid @enderror"
                                   value="{{ old('Usuario') }}"
                                   required
                                   maxlength="10">
                            @error('Usuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Nombre_Usuario" class="form-label">Nombre de Usuario</label>
                            <input type="text"
                                   name="Nombre_Usuario"
                                   id="Nombre_Usuario"
                                   class="form-control texto-mayusculas @error('Nombre_Usuario') is-invalid @enderror"
                                   value="{{ old('Nombre_Usuario') }}"
                                   required
                                   maxlength="40">
                            @error('Nombre_Usuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Correo_Electronico" class="form-label">Correo</label>
                            <input type="email"
                                   name="Correo_Electronico"
                                   id="Correo_Electronico"
                                   class="form-control @error('Correo_Electronico') is-invalid @enderror"
                                   value="{{ old('Correo_Electronico') }}"
                                   maxlength="40"
                                   required>
                            @error('Correo_Electronico')
                                <div class="invalid-feedback">{{ $message }}</div>
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

                        <div class="mb-0">
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

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            Guardar
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</div>
@endsection


@section('js')
  {{-- Bootstrap y SweetAlert2 --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  {{-- Mostrar modal de creación si hubo errores de validación --}}
  @if ($errors->any())
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          const modal = document.getElementById('modalNuevoUsuario');
          if (modal) {
              const instancia = new bootstrap.Modal(modal);
              instancia.show();
          }
      });
    </script>
  @endif

  {{-- Alertas flash --}}
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

  @if(session('error'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          Swal.fire({
              icon: 'error',
              title: 'Acceso denegado',
              text: @json(session('error')),
              confirmButtonText: 'Aceptar'
          });
      });
    </script>
  @endif

  {{-- Confirmación eliminación --}}
  <script>
    function confirmarEliminacion(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción inactivará al usuario.',
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
        return false;
    }
  </script>

  {{-- DataTables + validaciones frontend --}}
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
            order: [[5, 'desc']],
            searching: false
        });

        // Forzar mayúsculas en campos con clase texto-mayusculas
        document.querySelectorAll('.texto-mayusculas').forEach(campo => {
            campo.addEventListener('input', function () {
                this.value = this.value.toUpperCase();
            });
        });
    });
  </script>
@endsection

@section('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

  <style>
    body {
        background: #f5f7fb;
    }

    .text-gradient {
        background: linear-gradient(90deg, #0d6efd, #20c997);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .btn-modern {
        border-radius: 999px;
        padding-inline: 1.4rem;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
    }

    .card {
        border-radius: 1.5rem;
    }

    .modal-content {
        border-radius: 1.2rem;
    }
  </style>
@endsection
