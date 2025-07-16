@extends('adminlte::page')

@section('content_header')
    <h1>Ficha de Ahorro</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            <strong>{{ $ahorro->nombre_caja_rural }}</strong>
        </div>
        <div class="card-body">
            <h5 class="mb-3">Socios</h5>
            <ul>
                <li>No.: {{ $ahorro->socios_no }}</li>
                <li>Ahorros: L {{ number_format($ahorro->socios_ahorros, 2) }}</li>
                <li>Promedio: L {{ number_format($ahorro->socios_promedio, 2) }}</li>
            </ul>

            <h5 class="mt-4">No Socios - Adultos</h5>
            <ul>
                <li>No.: {{ $ahorro->adultos_no }}</li>
                <li>Ahorros: L {{ number_format($ahorro->adultos_ahorros, 2) }}</li>
                <li>Promedio: L {{ number_format($ahorro->adultos_promedio, 2) }}</li>
            </ul>

            <h5 class="mt-4">No Socios - Niños</h5>
            <ul>
                <li>No.: {{ $ahorro->ninos_no }}</li>
                <li>Ahorros: L {{ number_format($ahorro->ninos_ahorros, 2) }}</li>
                <li>Promedio: L {{ number_format($ahorro->ninos_promedio, 2) }}</li>
            </ul>

            <h5 class="mt-4">Sub Total No Socios</h5>
            <ul>
                <li>No.: {{ $ahorro->subtotal_no_socios_no }}</li>
                <li>Ahorros: L {{ number_format($ahorro->subtotal_no_socios_ahorros, 2) }}</li>
                <li>Promedio: L {{ number_format($ahorro->subtotal_no_socios_promedio, 2) }}</li>
            </ul>

            <h5 class="mt-4">Total</h5>
            <ul>
                <li>No.: {{ $ahorro->total_no }}</li>
                <li>Ahorros: L {{ number_format($ahorro->total_ahorros, 2) }}</li>
                <li>Promedio: L {{ number_format($ahorro->total_promedio, 2) }}</li>
            </ul>
        </div>
    </div>
@stop
