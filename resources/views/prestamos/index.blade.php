@extends('adminlte::page')

@section('title', 'Créditos')

@section('content_header')
    <h1>Listado de Préstamos</h1>
@stop

@section('content')
<div class="tab-content mt-3" id="creditosTabsContent">
    {{-- TAB LISTADO --}}
    <div class="tab-pane fade show active" id="listado" role="tabpanel" aria-labelledby="listado-tab">
        <a href="{{ route('prestamos.create') }}" class="btn btn-primary mb-3">Nueva Solicitud</a>
        <a href="{{ route('creditos.reportes') }}" class="btn btn-info mb-3 ms-2">Reportes y Métricas</a>
        @if($prestamos->count())
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Socio</th>
                        <th>Monto</th>
                        <th>Destino</th>
                        <th>Estado</th>
                        <th>Fecha de la solicitud</th>
                        <th>Acciones</th>
                        <th>Pagos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prestamos as $prestamo)
                        <tr>
                            <td>{{ $prestamo->id }}</td>
                            <td>{{ $prestamo->organizacion->Nombre_Organizacion ?? 'N/A' }}</td>
                            <td>{{ number_format($prestamo->monto_solicitado, 2) }}</td>
                            <td>{{ $prestamo->destino }}</td>
                            <td>{{ ucfirst($prestamo->estado) }}</td>
                            <td>{{ \Carbon\Carbon::parse($prestamo->fecha_solicitud)->format('d/m/Y') }}</td>
                            <td>
                                @if ($prestamo->estado === 'pendiente')
                                    <form action="{{ route('prestamos.aprobar', $prestamo->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-success btn-sm">Aprobar</button>
                                    </form>
                                    <form action="{{ route('prestamos.rechazar', $prestamo->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-danger btn-sm">Rechazar</button>
                                    </form>
                                @elseif ($prestamo->estado === 'aprobado')
                                    <form action="{{ route('prestamos.desembolsar', $prestamo->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button class="btn btn-warning btn-sm">Desembolsar</button>
                                    </form>
                                @else
                                    <span class="text-muted">Sin acciones</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pagos.index', $prestamo->id) }}" class="btn btn-secondary btn-sm">Ver Pagos</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay préstamos registrados.</p>
        @endif
    </div>

    {{-- TAB REPORTES --}}
    <div class="tab-pane fade" id="reportes" role="tabpanel" aria-labelledby="reportes-tab">
        <h3>Reportes y Métricas</h3>
        <ul>
            <li><strong>Total de préstamos por mes:</strong></li>
            <ul>
                @foreach($prestamosPorMes as $item)
                    <li>{{ $item->anio }}-{{ str_pad($item->mes, 2, '0', STR_PAD_LEFT) }} : {{ $item->total }} préstamos</li>
                @endforeach
            </ul>
            <li><strong>Total desembolsado:</strong> Lps {{ number_format($totalDesembolsado, 2) }}</li>
            <li><strong>Porcentaje de aprobación:</strong> {{ $porcentajeAprobado }}%</li>
            <li><strong>Top 5 organizaciones que más solicitan:</strong>
                <ul>
                    @foreach($topOrganizaciones as $org)
                        <li>{{ $org->organizacion->Nombre_Organizacion ?? 'N/A' }} - {{ $org->total }} solicitudes</li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </div>
</div>

@stop
