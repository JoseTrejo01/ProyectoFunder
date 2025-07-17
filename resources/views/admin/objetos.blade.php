@extends('adminlte::page')
@section('content')
<div class="container">
    <h2 class="text-center my-4 font-weight-bold">Gestión de Objetos</h2>
    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalNuevoObjeto">Nuevo Objeto</button>
    <div class="table-responsive">
        <table id="tabla-objetos" class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($objetos as $objeto)
                <tr>
                    <td>{{ $objeto->Id_Objeto }}</td>
                    <td>{{ $objeto->Objeto }}</td>
                    <td>{{ $objeto->Descripcion }}</td>
                    <td>{{ $objeto->Tipo_Objeto }}</td>
                    <td>
                        <!-- Botón de editar existente -->
                        <button class="btn btn-xs btn-primary p-1" data-bs-toggle="modal" data-bs-target="#modalEditarObjeto{{ $objeto->Id_Objeto }}" title="Editar" style="font-size: 0.85rem;">
                            <i class="fas fa-edit"></i>
                        </button>
                        <!-- Botón de eliminar -->
                        <form action="{{ route('objetos.destroy', $objeto->Id_Objeto) }}" method="POST" style="display:inline-block;" onsubmit="return confirmarEliminacionObjeto(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Borrar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <!-- Modal Editar Objeto -->
                <div class="modal fade" id="modalEditarObjeto{{ $objeto->Id_Objeto }}" tabindex="-1" aria-labelledby="modalEditarObjetoLabel{{ $objeto->Id_Objeto }}" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form method="POST" action="{{ route('objetos.update', $objeto->Id_Objeto) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalEditarObjetoLabel{{ $objeto->Id_Objeto }}">Editar Objeto</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label for="Objeto{{ $objeto->Id_Objeto }}" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="Objeto{{ $objeto->Id_Objeto }}" name="Objeto" value="{{ $objeto->Objeto }}" required>
                          </div>
                          <div class="mb-3">
                            <label for="Descripcion{{ $objeto->Id_Objeto }}" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="Descripcion{{ $objeto->Id_Objeto }}" name="Descripcion" value="{{ $objeto->Descripcion }}">
                          </div>
                          <div class="mb-3">
                            <label for="Tipo_Objeto{{ $objeto->Id_Objeto }}" class="form-label">Tipo</label>
                            <input type="text" class="form-control" id="Tipo_Objeto{{ $objeto->Id_Objeto }}" name="Tipo_Objeto" value="{{ $objeto->Tipo_Objeto }}">
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
                <label for="Objeto" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="Objeto" name="Objeto" required>
              </div>
              <div class="mb-3">
                <label for="Descripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="Descripcion" name="Descripcion">
              </div>
              <div class="mb-3">
                <label for="Tipo_Objeto" class="form-label">Tipo</label>
                <input type="text" class="form-control" id="Tipo_Objeto" name="Tipo_Objeto">
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
<script>
function confirmarEliminacionObjeto(e) {
    e.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¡Esta acción eliminará el objeto!',
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
    $('#tabla-objetos').DataTable({
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
