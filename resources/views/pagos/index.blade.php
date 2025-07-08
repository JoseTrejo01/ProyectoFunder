@extends('adminlte::page')

@section('title', 'Pagos del Préstamo')

@section('content_header')
    <h1>Pagos del Préstamo #{{ $prestamo->id }}</h1>
@stop

@section('content')
    <a href="{{ route('pagos.create', $prestamo->id) }}" class="btn btn-primary mb-3">Registrar Pago</a>

    @if($prestamo->pagos->count())
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Pago</th>
                    <th>Fecha</th>
                    <th>Monto Pagado</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prestamo->pagos as $pago)
                    <tr>
                        <td>{{ $pago->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                        <td>{{ number_format($pago->monto_pagado, 2) }}</td>
                        <td>{{ $pago->observaciones }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay pagos registrados para este préstamo.</p>
    @endif
@stop
