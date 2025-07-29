@extends('adminlte::page')

@section('title', 'Resumen de Ahorros')

@section('content_header')
    <h1>Resumen de Ahorros por Caja Rural</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="bg-primary text-white text-center">
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Nombre de la Caja Rural</th>
                    
                    <th colspan="3">Socios Adultos</th>
                    <th colspan="3">Socios Niños</th>
                    <th colspan="3">No Socios Adultos</th>
                    <th colspan="3">No Socios Niños</th>
                    
                    <th colspan="3">Totales</th>
                </tr>
                <tr>
                    @for ($i = 0; $i < 5; $i++)
                        <th>No.</th>
                        <th>Ahorros</th>
                        <th>Promedio</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $i => $dato)
                @php
                    $total_num = $dato->socios_adultos + $dato->socios_ninos + $dato->no_socios_adultos + $dato->no_socios_ninos;
                    $total_ahorros = $dato->ahorros_socios_adultos + $dato->ahorros_socios_ninos + $dato->ahorros_no_socios_adultos + $dato->ahorros_no_socios_ninos;
                    $total_promedio = $total_num > 0 ? round($total_ahorros / $total_num, 2) : 0;
                @endphp
                <tr class="text-center">
                    <td>{{ $i + 1 }}</td>
                    <td class="text-left">{{ $dato->Nombre_Organizacion }}</td>

                    <td>{{ $dato->socios_adultos }}</td>
                    <td>L {{ number_format($dato->ahorros_socios_adultos, 2) }}</td>
                    <td>{{ $dato->socios_adultos > 0 ? round($dato->ahorros_socios_adultos / $dato->socios_adultos, 2) : 0 }}</td>

                    <td>{{ $dato->socios_ninos }}</td>
                    <td>L {{ number_format($dato->ahorros_socios_ninos, 2) }}</td>
                    <td>{{ $dato->socios_ninos > 0 ? round($dato->ahorros_socios_ninos / $dato->socios_ninos, 2) : 0 }}</td>

                    <td>{{ $dato->no_socios_adultos }}</td>
                    <td>L {{ number_format($dato->ahorros_no_socios_adultos, 2) }}</td>
                    <td>{{ $dato->no_socios_adultos > 0 ? round($dato->ahorros_no_socios_adultos / $dato->no_socios_adultos, 2) : 0 }}</td_
