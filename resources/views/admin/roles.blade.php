@extends('adminlte::page')
@section('content')
<div class="container">
    <h2 class="text-center my-4 font-weight-bold">Gestión de Roles</h2>
    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalNuevoRol">Nuevo Rol</button>
    <a href="{{ route('roles.exportar.pdf') }}" class="btn btn-danger mb-3">
    <i class="fas fa-file-pdf"></i> Exportar a PDF
</a>

    <div class="table-responsive">
        <table id="tabla-roles" class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $rol)
                <tr>
                    <td>{{ $rol->Id_Rol }}</td>
                    <td>{{ $rol->Rol }}</td>
                    <td>{{ $rol->Descripcion }}</td>
                    <td>
                        <!-- Botón de editar existente -->
                        <button class="btn btn-xs btn-primary p-1" data-bs-toggle="modal" data-bs-target="#modalEditarRol{{ $rol->Id_Rol }}" title="Editar" style="font-size: 0.85rem;">
                            <i class="fas fa-edit"></i>
                        </button>
                        <!-- Botón de eliminar -->
                        <form action="{{ route('roles.destroy', $rol->Id_Rol) }}" method="POST" style="display:inline-block;" onsubmit="return confirmarEliminacionRol(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Borrar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <!-- Modal Editar Rol -->
                <div class="modal fade" id="modalEditarRol{{ $rol->Id_Rol }}" tabindex="-1" aria-labelledby="modalEditarRolLabel{{ $rol->Id_Rol }}" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form method="POST" action="{{ route('roles.update', $rol->Id_Rol) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalEditarRolLabel{{ $rol->Id_Rol }}">Editar Rol</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label for="Rol{{ $rol->Id_Rol }}" class="form-label">Nombre</label>
                            <input type="text" class="form-control validacion-texto" id="Rol{{ $rol->Id_Rol }}" name="Rol" value="{{ $rol->Rol }}" required maxlength="40" pattern="[A-ZÁÉÍÓÚÑa-záéíóúñ\s]+" title="Solo se permiten letras y espacios">
                          </div>
                          <div class="mb-3">
                            <label for="Descripcion{{ $rol->Id_Rol }}" class="form-label">Descripción</label>
                            <input type="text" class="form-control validacion-texto" id="Descripcion{{ $rol->Id_Rol }}" name="Descripcion" value="{{ $rol->Descripcion }}" maxlength="40" pattern="[A-ZÁÉÍÓÚÑa-záéíóúñ\s]+" title="Solo se permiten letras y espacios">
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
                <label for="Rol" class="form-label">Nombre</label>
                <input type="text" class="form-control validacion-texto" id="Rol" name="Rol" required maxlength="40" pattern="[A-ZÁÉÍÓÚÑa-záéíóúñ\s]+" title="Solo se permiten letras y espacios">
              </div>
              <div class="mb-3">
                <label for="Descripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control validacion-texto" id="Descripcion" name="Descripcion" maxlength="40" pattern="[A-ZÁÉÍÓÚÑa-záéíóúñ\s]+" title="Solo se permiten letras y espacios">
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
@parent
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            title: 'Error',
            text: @json(session('error')),
            confirmButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
        });
    });
</script>
@endif
<script>
function confirmarEliminacionRol(e) {
    e.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¡Esta acción eliminará el rol!',
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
    $('#tabla-roles').DataTable({
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

    // Validación para campos de texto - solo letras y mayúsculas
    $('.validacion-texto').on('input', function() {
        let valor = $(this).val();
        
        // Eliminar números y caracteres especiales, mantener solo letras, espacios y acentos
        let valorLimpio = valor.replace(/[^A-ZÁÉÍÓÚÑa-záéíóúñ\s]/g, '');
        
        // Convertir a mayúsculas
        valorLimpio = valorLimpio.toUpperCase();
        
        // Actualizar el valor del campo
        $(this).val(valorLimpio);
    });

    // Prevenir pegado de contenido inválido
    $('.validacion-texto').on('paste', function(e) {
        setTimeout(() => {
            $(this).trigger('input');
        }, 10);
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
