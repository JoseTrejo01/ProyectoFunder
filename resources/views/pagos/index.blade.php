@extends('adminlte::page')

@section('title', 'Pagos del Préstamo')

@section('content_header')
    <h1>Pagos del Préstamo #{{ $prestamo->id }}</h1>
@stop

@section('content')
<a href="{{ route('prestamos.pagos.pdf', $prestamo->id) }}" target="_blank" class="btn btn-danger mb-3">
    Ver Pagos (PDF)
</a>

    @if($prestamo->pagos->count())
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Pago</th>
                    <th>Fecha Programada</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prestamo->pagos as $pago)
                    <tr>
                        <td>{{ $pago->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                        <td>{{ number_format($pago->monto_pagado, 2) }}</td>
                        <td>
                            @if ($pago->estado === 'pagado')
                                <span class="badge bg-success">Pagado</span>
                            @elseif ($pago->estado === 'pendiente')
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @elseif ($pago->estado === 'atrasado')
                                <span class="badge bg-danger">Atrasado</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($pago->estado) }}</span>
                            @endif
                        </td>
                        <td>{{ $pago->observaciones }}</td>
                        <td>
                           @if ($pago->estado !== 'pagado')
                                @if ($pago->prestamo->estado === 'aprobado')
                                    <form action="{{ route('pagos.marcarPagado', $pago->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">Marcar como pagado</button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-warning" disabled title="El préstamo no está aprobado. No se puede marcar como pagado.">
                                        Marcar como pagado
                                    </button>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay pagos registrados para este préstamo.</p>
    @endif
@stop
