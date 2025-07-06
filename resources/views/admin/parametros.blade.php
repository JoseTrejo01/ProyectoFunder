@extends('adminlte::page')
@section('content')
<div class="container">
    <h1 class="text-center my-4 font-weight-bold">Parámetros del Sistema</h1>
    <div class="table-responsive">
        <table id="tabla-parametros" class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Parámetro</th>
                    <th>Valor</th>
                    <th>Fecha de Creación</th>
                    <th>Fecha de Modificación</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($parametros as $parametro)
                <tr>
                    <td>{{ $parametro->Id_Parametro }}</td>
                    <td>{{ $parametro->Nombre_Parametro }}</td>
                    <td>{{ $parametro->Valor }}</td>
                    <td>{{ $parametro->Fecha_Creacion }}</td>
                    <td>{{ $parametro->Fecha_Modificacion }}</td>
                    <td>{{ $parametro->usuario->Nombre_Usuario ?? '' }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalEditarParametro{{ $parametro->Id_Parametro }}">
                            <i class="fas fa-cog"></i> Configuración
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="modalEditarParametro{{ $parametro->Id_Parametro }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $parametro->Id_Parametro }}" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <form action="{{ route('parametros.update', $parametro->Id_Parametro) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header bg-primary text-white">
                                  <h5 class="modal-title" id="modalLabel{{ $parametro->Id_Parametro }}">Editar Parámetro</h5>
                                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group">
                                    <label>Nombre del Parámetro</label>
                                    <input type="text" class="form-control" value="{{ $parametro->Nombre_Parametro }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label>Valor</label>
                                    <input type="text" class="form-control" name="Valor" value="{{ $parametro->Valor }}" required>
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
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('js')
@parent
<script>
$(document).ready(function() {
    $('#tabla-parametros').DataTable({
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
