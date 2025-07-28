
@extends('adminlte::page')

@section('content')
<div class="container">
    <h2 class="text-center my-4 font-weight-bold">Distribución de Cargos por Caja Rural</h2>
    <div class="table-responsive">
        <table id="tabla-cargos" class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="thead-dark">
                <tr>
                    <th>No.</th>
                    <th>Nombre de la Caja Rural</th>
                    <th>Presidente(a)</th>
                    <th>Vicepresidente(a)</th>
                    <th>Secretario(a)</th>
                    <th>Tesorero(a)</th>
                    <th>Vocal I</th>
                    <th>Vocal II</th>
                    <th>Vocal III</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cajas as $i => $caja)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $caja->nombre_organizacion }}</td>
                        <td>
                            @if($caja->presidente_h && $caja->presidente_m)
                                H/M
                            @elseif($caja->presidente_h)
                                H
                            @elseif($caja->presidente_m)
                                M
                            @endif
                        </td>
                        <td>
                            @if($caja->vicepresidente_h && $caja->vicepresidente_m)
                                H/M
                            @elseif($caja->vicepresidente_h)
                                H
                            @elseif($caja->vicepresidente_m)
                                M
                            @endif
                        </td>
                        <td>
                            @if($caja->secretario_h && $caja->secretario_m)
                                H/M
                            @elseif($caja->secretario_h)
                                H
                            @elseif($caja->secretario_m)
                                M
                            @endif
                        </td>
                        <td>
                            @if($caja->tesorero_h && $caja->tesorero_m)
                                H/M
                            @elseif($caja->tesorero_h)
                                H
                            @elseif($caja->tesorero_m)
                                M
                            @endif
                        </td>
                        <td>{{ $caja->vocal1 }}</td>
                        <td>{{ $caja->vocal2 }}</td>
                        <td>{{ $caja->vocal3 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('css')
    {{-- Si quieres agregar estilos personalizados, hazlo aquí --}}
@endsection

@section('js')
<script>
$(document).ready(function() {
    $('#tabla-cargos').DataTable({
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
        order: [[0, 'desc']],
        searching: false // Desactiva el buscador
    });
});
</script>
@endsection
