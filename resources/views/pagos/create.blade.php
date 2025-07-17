@extends('adminlte::page')

@section('title', 'Registrar Pago')

@section('content_header')
    <h1 class="text-center">Registrar Pago para Préstamo #{{ $prestamo->id }}</h1>
@stop

@section('content')
<div class="container d-flex justify-content-center">
    <div class="w-100" style="max-width: 600px;">

        {{-- Mensaje de éxito --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Errores de validación --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Corrige los errores:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="pagoForm" action="{{ route('pagos.store', $prestamo->id) }}" method="POST" novalidate>
            @csrf

            <div class="mb-3">
                <label for="fecha_pago" class="form-label">Fecha del Pago</label>
                <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" value="{{ old('fecha_pago', now()->format('Y-m-d')) }}" required>
                <div class="invalid-feedback">Por favor, ingrese una fecha válida.</div>
            </div>

            <div class="mb-3">
                <label for="monto_pagado" class="form-label">Monto Pagado (Lps)</label>
                <input type="number" name="monto_pagado" id="monto_pagado" class="form-control" step="0.01" min="0.01" required>
                <div class="invalid-feedback">Debe ingresar un monto mayor a 0.</div>
            </div>

            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea name="observaciones" id="observaciones" class="form-control" rows="3" maxlength="255" placeholder="Opcional...">{{ old('observaciones') }}</textarea>
                <div class="form-text">Máximo 255 caracteres.</div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success">Registrar Pago</button>
                <a href="{{ route('pagos.index', $prestamo->id) }}" class="btn btn-secondary ms-2">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
    // Bootstrap 5 validación personalizada
    (() => {
        'use strict';
        const form = document.getElementById('pagoForm');
        form.addEventListener('submit', function (e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    })();
</script>
@stop
