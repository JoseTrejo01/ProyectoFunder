@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container">

  {{-- Alert de éxito (SweetAlert) --}}
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

  <div class="d-flex gap-2 mb-3">
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
      <i class="fas fa-user-plus me-1"></i> Nuevo Usuario
    </button>
    <a href="{{ route('usuarios.exportar.pdf') }}" class="btn btn-danger">
      <i class="fas fa-file-pdf me-1"></i> Exportar a PDF
    </a>
  </div>

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
          <th style="width:110px;">Acción</th>
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
          <td>{{ $usuario->Fecha_Vencimiento ? \Carbon\Carbon::parse($usuario->Fecha_Vencimiento)->format('Y-m-d') : '-' }}</td>
          <td>
            <div class="d-flex align-items-center gap-1">
              <button class="btn btn-xs btn-primary p-1" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario{{ $usuario->Id_Usuario }}" title="Editar" style="font-size: .85rem;">
                <i class="fas fa-edit"></i>
              </button>
              <form action="{{ route('usuarios.destroy', $usuario->Id_Usuario) }}" method="POST" style="display:inline-block" onsubmit="return confirmarEliminacion(event)">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-xs btn-danger p-1" title="Borrar" style="font-size: .85rem;">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>

        {{-- Modal Editar Usuario --}}
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
                    <input type="text"
                      class="form-control"
                      id="Usuario{{ $usuario->Id_Usuario }}"
                      value="{{ $usuario->Usuario }}"
                      disabled>
                    <input type="hidden" name="Usuario" value="{{ $usuario->Usuario }}">
                  </div>

                  <div class="mb-3">
                    <label for="Nombre_Usuario{{ $usuario->Id_Usuario }}" class="form-label">Nombre de Usuario</label>
                    <input type="text"
                      class="form-control validacion-texto @error('Nombre_Usuario') is-invalid @enderror"
                      id="Nombre_Usuario{{ $usuario->Id_Usuario }}"
                      name="Nombre_Usuario"
                      value="{{ old('Nombre_Usuario', $usuario->Nombre_Usuario) }}"
                      required
                      maxlength="40"
                      pattern="[A-ZÁÉÍÓÚÑ ]{1,40}"
                      title="Solo letras mayúsculas y espacios, máximo 40 caracteres">
                    @error('Nombre_Usuario')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label for="Correo_Electronico{{ $usuario->Id_Usuario }}" class="form-label">Correo</label>
                    <input type="email"
                      class="form-control @error('Correo_Electronico') is-invalid @enderror"
                      id="Correo_Electronico{{ $usuario->Id_Usuario }}"
                      name="Correo_Electronico"
                      value="{{ old('Correo_Electronico', $usuario->Correo_Electronico) }}"
                      maxlength="120"
                      required>
                    @error('Correo_Electronico')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label for="Id_Rol{{ $usuario->Id_Usuario }}" class="form-label">Rol</label>
                    <select class="form-control" id="Id_Rol{{ $usuario->Id_Usuario }}" name="Id_Rol" required>
                      @foreach($roles as $rol)
                        <option value="{{ $rol->Id_Rol }}" @selected($usuario->Id_Rol == $rol->Id_Rol)>{{ $rol->Rol }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-3">
                    <label for="Estado_Usuario{{ $usuario->Id_Usuario }}" class="form-label">Estado</label>
                    <select class="form-control" id="Estado_Usuario{{ $usuario->Id_Usuario }}" name="Estado_Usuario" required>
                      <option value="NUEVO"      @selected($usuario->Estado_Usuario=='NUEVO')>NUEVO</option>
                      <option value="ACTIVO"     @selected($usuario->Estado_Usuario=='ACTIVO')>ACTIVO</option>
                      <option value="BLOQUEADO"  @selected($usuario->Estado_Usuario=='BLOQUEADO')>BLOQUEADO</option>
                      <option value="INACTIVO"   @selected($usuario->Estado_Usuario=='INACTIVO')>INACTIVO</option>
                      <option value="VACACIONES" @selected($usuario->Estado_Usuario=='VACACIONES')>VACACIONES</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label for="Fecha_Vencimiento{{ $usuario->Id_Usuario }}" class="form-label">Fecha de Vencimiento</label>
                    <input type="date"
                      class="form-control"
                      id="Fecha_Vencimiento{{ $usuario->Id_Usuario }}"
                      name="Fecha_Vencimiento"
                      value="{{ old('Fecha_Vencimiento', $usuario->Fecha_Vencimiento ? \Carbon\Carbon::parse($usuario->Fecha_Vencimiento)->format('Y-m-d') : '') }}">
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

  {{-- Modal Nuevo Usuario --}}
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
              <input type="text"
                name="Usuario"
                id="Usuario"
                class="form-control validacion-usuario @error('Usuario') is-invalid @enderror"
                value="{{ old('Usuario') }}"
                required
                maxlength="20"
                pattern="[A-Z0-9]{1,20}"
                title="Solo letras mayúsculas y números (máx. 20)">
              @error('Usuario')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="Nombre_Usuario" class="form-label">Nombre de Usuario</label>
              <input type="text"
                name="Nombre_Usuario"
                id="Nombre_Usuario"
                class="form-control validacion-texto @error('Nombre_Usuario') is-invalid @enderror"
                value="{{ old('Nombre_Usuario') }}"
                required
                maxlength="40"
                pattern="[A-ZÁÉÍÓÚÑ ]{1,40}"
                title="Solo letras mayúsculas y espacios, máximo 40 caracteres">
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
                maxlength="120"
                required
                title="Ingresa un correo válido">
              @error('Correo_Electronico')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="Id_Rol" class="form-label">Rol</label>
              <select class="form-control" id="Id_Rol" name="Id_Rol" required>
                <option value="">Seleccione un rol</option>
                @foreach($roles as $rol)
                  <option value="{{ $rol->Id_Rol }}" @selected(old('Id_Rol')==$rol->Id_Rol)>{{ $rol->Rol }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label for="Estado_Usuario" class="form-label">Estado</label>
              <select class="form-control" id="Estado_Usuario" name="Estado_Usuario" required>
                <option value="NUEVO" @selected(old('Estado_Usuario')=='NUEVO')>NUEVO</option>
                <option value="ACTIVO" @selected(old('Estado_Usuario')=='ACTIVO')>ACTIVO</option>
                <option value="BLOQUEADO" @selected(old('Estado_Usuario')=='BLOQUEADO')>BLOQUEADO</option>
                <option value="INACTIVO" @selected(old('Estado_Usuario')=='INACTIVO')>INACTIVO</option>
                <option value="VACACIONES" @selected(old('Estado_Usuario')=='VACACIONES')>VACACIONES</option>
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

@section('css')
  {{-- Font Awesome (íconos) --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  {{-- DataTables Bootstrap 5 --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css" />
  <style>
    .validacion-texto, .validacion-usuario { text-transform: uppercase; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { padding: .25rem .6rem; }
  </style>
@endsection

@section('js')
  {{-- Bootstrap 5 (bundle) y SweetAlert2 --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  {{-- jQuery (requerido por DataTables 2 si usas estilo jQuery), y DataTables + Bootstrap5 --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

  {{-- Abrir modal de creación si hay errores de validación --}}
  @if ($errors->any())
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('modalNuevoUsuario'));
        myModal.show();
      });
    </script>
  @endif

  {{-- Alert de error de permisos --}}
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

  <script>
    // Confirmación al eliminar
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

    // Forzar mayúsculas y filtrar caracteres
    function aplicarValidacionesMayusculas(selector, soloLetras = true) {
      document.querySelectorAll(selector).forEach(function (campo) {
        campo.addEventListener('input', function () {
          this.value = this.value.toUpperCase();
          // Limpieza extra (solo letras/espacios si soloLetras, si no letras y números)
          const regex = soloLetras ? /[^A-ZÁÉÍÓÚÑ ]/g : /[^A-Z0-9]/g;
          this.value = this.value.replace(regex, '');
        });
        campo.addEventListener('keypress', function (e) {
          const allow = soloLetras ? /^[A-ZÁÉÍÓÚÑ ]$/i : /^[A-Z0-9]$/i;
          if (!allow.test(e.key)) e.preventDefault();
        });
        campo.addEventListener('paste', function () {
          setTimeout(() => { this.dispatchEvent(new Event('input')); }, 10);
        });
      });
    }

    // DataTable
    $(document).ready(function () {
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
        order: [[5, 'desc']], // Fecha de Registro
        searching: false
      });
    });

    // Aplicar validaciones a campos de creación
    document.addEventListener('DOMContentLoaded', function () {
      aplicarValidacionesMayusculas('input#Usuario', false);           // letras y números
      aplicarValidacionesMayusculas('input#Nombre_Usuario', true);     // solo letras y espacios

      // Aplicar a campos de edición (por cada usuario)
      @foreach($usuarios as $usuario)
        aplicarValidacionesMayusculas('#Nombre_Usuario{{ $usuario->Id_Usuario }}', true);
      @endforeach
    });
  </script>
@endsection
