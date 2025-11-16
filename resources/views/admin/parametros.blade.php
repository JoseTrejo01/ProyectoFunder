@extends('adminlte::page')

@section('title', 'Parámetros del Sistema')

@section('content')
<div class="container">
    <h1 class="text-center my-4 font-weight-bold">Parámetros del Sistema</h1>

    <div class="table-responsive">
        <table id="tabla-parametros" class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="table-dark">
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
                    <td class="text-start">{{ $parametro->Nombre_Parametro }}</td>
                    <td class="text-start">{{ $parametro->Valor }}</td>
                    <td>{{ $parametro->Fecha_Creacion }}</td>
                    <td>{{ $parametro->Fecha_Modificacion }}</td>
                    <td>{{ $parametro->usuario->Nombre_Usuario ?? '' }}</td>

                    <td>
                        <button class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarParametro{{ $parametro->Id_Parametro }}">
                            <i class="fas fa-cog"></i> Configurar
                        </button>
                    </td>
                </tr>

                {{-- ====================== MODAL EDITAR ====================== --}}
                <div class="modal fade"
                     id="modalEditarParametro{{ $parametro->Id_Parametro }}"
                     tabindex="-1"
                     aria-hidden="true">

                    <div class="modal-dialog">
                        <div class="modal-content">

                            <form action="{{ route('parametros.update', $parametro->Id_Parametro) }}"
                                  method="POST">
                                @csrf
                                @method('PUT')

                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Editar Parámetro</h5>
                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                            aria-label="Cerrar"></button>
                                </div>

                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label class="form-label">Nombre del Parámetro</label>
                                        <input type="text"
                                               class="form-control"
                                               value="{{ $parametro->Nombre_Parametro }}"
                                               disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Valor</label>
                                        <input type="text"
                                               class="form-control"
                                               name="Valor"
                                               value="{{ $parametro->Valor }}"
                                               required
                                               maxlength="150">
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                                        Cancelar
                                    </button>
                                    <button class="btn btn-success">
                                        Guardar Cambios
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
                {{-- ====================== FIN MODAL ====================== --}}

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
            infoEmpty: 'Mostrando 0 registros',
            infoFiltered: '(filtrado de _MAX_ registros totales)',
            search: 'Buscar:',
            paginate: {
                next: 'Siguiente',
                previous: 'Anterior'
            }
        },
        order: [[0, 'desc']]
    });

});
</script>
@endsection
