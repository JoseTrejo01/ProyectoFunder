@extends('adminlte::page')

@section('title', 'Créditos')

@section('content_header')
    <h1>Listado de Préstamos</h1>
@stop

@section('content')
    <a href="{{ route('prestamos.create') }}" class="btn btn-primary mb-3">Nueva Solicitud</a>

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
                            <a href="{{ route('pagos.create', $prestamo->id) }}" class="btn btn-primary btn-sm mb-1">Registrar Pago</a><br>
                            <a href="{{ route('pagos.index', $prestamo->id) }}" class="btn btn-secondary btn-sm">Ver Pagos</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay préstamos registrados.</p>
    @endif
@stop
