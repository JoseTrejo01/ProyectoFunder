@extends('adminlte::page')

@section('title', 'Reportes y Métricas')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Reportes y Métricas</h4>
    </div>
    <div class="card-body">

        <h5>Total de préstamos por mes</h5>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Mes</th>
                    <th>Total Préstamos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prestamosPorMes as $mes)
                    <tr>
                        <td>{{ $mes->anio }}-{{ str_pad($mes->mes, 2, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $mes->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>

        <h5>Total desembolsado</h5>
        <p><strong>Lps {{ number_format($totalDesembolsado, 2) }}</strong></p>

        <hr>

        <h5>Porcentaje de aprobación</h5>
        <p><strong>{{ $porcentajeAprobado }}%</strong> de préstamos aprobados ({{ $aprobados }} de {{ $total }})</p>

        <hr>

        <h5>Top 5 Organizaciones que más solicitan</h5>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Organización</th>
                    <th>Total Préstamos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topOrganizaciones as $org)
                    <tr>
                        <td>{{ $org->organizacion->Nombre_Organizacion ?? 'N/A' }}</td>
                        <td>{{ $org->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
