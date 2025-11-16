@extends('adminlte::page')

@section('content')
<div class="container" role="main" aria-labelledby="titulo-cargos">

    <h2 id="titulo-cargos" class="text-center my-4 fw-bold">
        Distribución de Cargos por Caja Rural
    </h2>

    <div class="table-responsive" role="region" aria-labelledby="tabla-cargos-titulo">

        <table id="tabla-cargos"
               class="table table-bordered table-striped table-hover shadow-sm align-middle"
               aria-describedby="descripcion-tabla-cargos">

            {{-- Texto accesible para lectores de pantalla --}}
            <caption id="tabla-cargos-titulo" class="visually-hidden">
                Tabla que muestra los cargos distribuidos por Caja Rural indicando sexo y vocalías.
            </caption>

            <thead class="table-dark">
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Nombre de la Caja Rural</th>
                    <th scope="col">Presidente(a)</th>
                    <th scope="col">Vicepresidente(a)</th>
                    <th scope="col">Secretario(a)</th>
                    <th scope="col">Tesorero(a)</th>
                    <th scope="col">Vocal I</th>
                    <th scope="col">Vocal II</th>
                    <th scope="col">Vocal III</th>
                </tr>
            </thead>

            <tbody>
                @foreach($cajas as $i => $caja)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $caja->nombre_organizacion }}</td>

                        {{-- Presidente --}}
                        <td aria-label="Presidencia">
                            @if($caja->presidente_h && $caja->presidente_m)
                                H/M
                            @elseif($caja->presidente_h)
                                H
                            @elseif($caja->presidente_m)
                                M
                            @else
                                -
                            @endif
                        </td>

                        {{-- Vicepresidente --}}
                        <td aria-label="Vicepresidencia">
                            @if($caja->vicepresidente_h && $caja->vicepresidente_m)
                                H/M
                            @elseif($caja->vicepresidente_h)
                                H
                            @elseif($caja->vicepresidente_m)
                                M
                            @else
                                -
                            @endif
                        </td>

                        {{-- Secretario --}}
                        <td aria-label="Secretaría">
                            @if($caja->secretario_h && $caja->secretario_m)
                                H/M
                            @elseif($caja->secretario_h)
                                H
                            @elseif($caja->secretario_m)
                                M
                            @else
                                -
                            @endif
                        </td>

                        {{-- Tesorero --}}
                        <td aria-label="Tesorería">
                            @if($caja->tesorero_h && $caja->tesorero_m)
                                H/M
                            @elseif($caja->tesorero_h)
                                H
                            @elseif($caja->tesorero_m)
                                M
                            @else
                                -
                            @endif
                        </td>

                        {{-- Vocalías --}}
                        <td>{{ $caja->vocal1 ?? '-' }}</td>
                        <td>{{ $caja->vocal2 ?? '-' }}</td>
                        <td>{{ $caja->vocal3 ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <p id="descripcion-tabla-cargos" class="visually-hidden">
            La tabla muestra la distribución de cargos de cada Caja Rural indicando género en presidencia, vicepresidencia, secretaría y tesorería.
        </p>

    </div>
</div>
@endsection

@section('css')
{{-- Estilos Funder opcionales (si quieres usar paleta) --}}
<style>
    table thead th {
        background-color: #0D47A1 !important;
        color: white !important;
    }
</style>
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
        searching: false
    });
});
</script>
@endsection
