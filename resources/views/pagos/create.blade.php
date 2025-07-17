@extends('adminlte::page')

@section('title', 'Registrar Pago')

@section('content_header')
    <h1>Registrar Pago para Préstamo #{{ $prestamo->id }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        {{-- Mensaje de éxito --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Errores de validación --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pagos.store', $prestamo->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="fecha_pago" class="form-label">Fecha del Pago</label>
                <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" value="{{ old('fecha_pago') }}" required>
            </div>

            <div class="mb-3">
                <label for="monto_pagado" class="form-label">Monto Pagado (Lps)</label>
                <input type="number" name="monto_pagado" id="monto_pagado" class="form-control" step="0.01" value="{{ old('monto_pagado') }}" required>
            </div>

            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea name="observaciones" id="observaciones" class="form-control" rows="3">{{ old('observaciones') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Registrar Pago</button>
            <a href="{{ route('pagos.index', $prestamo->id) }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@stop
