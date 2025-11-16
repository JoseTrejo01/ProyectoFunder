@extends('adminlte::page')

@section('title', 'Registrar Pago')

@section('content_header')
    <h1 id="titulo-registrar-pago" class="text-center fw-bold">
        Registrar Pago para Préstamo #{{ $prestamo->id }}
    </h1>
@stop

@section('content')
<div class="container d-flex justify-content-center" role="main" aria-labelledby="titulo-registrar-pago">
    <div class="w-100" style="max-width: 620px;">

        {{-- Mensaje de éxito --}}
        @if(session('success'))
            <div class="alert alert-success"
                 role="alert"
                 aria-live="polite"
                 tabindex="0">
                {{ session('success') }}
            </div>
        @endif

        {{-- Errores --}}
        @if($errors->any())
            <div class="alert alert-danger"
                 role="alert"
                 aria-live="assertive"
                 tabindex="0">
                <strong>Corrige los errores:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="pagoForm"
              action="{{ route('pagos.store', $prestamo->id) }}"
              method="POST"
              novalidate
              aria-describedby="ayuda-formulario"
              role="form"
              aria-labelledby="titulo-registrar-pago">

            @csrf

            <p id="ayuda-formulario" class="visually-hidden">
                Formulario para registrar un pago al préstamo seleccionado. Todos los campos obligatorios están marcados.
            </p>

            <fieldset class="border rounded p-3" aria-describedby="ayuda-datos" role="group">
                <legend class="float-none w-auto px-2 fw-semibold">
                    Datos del Pago
                </legend>

                <p id="ayuda-datos" class="visually-hidden">
                    Complete la fecha, monto y observaciones del pago.
                </p>

                {{-- FECHA --}}
                <div class="mb-3">
                    <label for="fecha_pago" class="form-label fw-semibold">
                        Fecha del Pago <span class="text-danger">*</span>
                    </label>
                    <input
                        type="date"
                        name="fecha_pago"
                        id="fecha_pago"
                        class="form-control"
                        value="{{ old('fecha_pago', now()->format('Y-m-d')) }}"
                        required
                        aria-required="true"
                        aria-describedby="fecha-descripcion"
                    >
                    <div id="fecha-descripcion" class="form-text">
                        Seleccione la fecha exacta en la que se efectuó el pago.
                    </div>
                    <div class="invalid-feedback">Debe seleccionar una fecha válida.</div>
                </div>

                {{-- MONTO --}}
                <div class="mb-3">
                    <label for="monto_pagado" class="form-label fw-semibold">
                        Monto Pagado (Lps) <span class="text-danger">*</span>
                    </label>
                    <input
                        type="number"
                        name="monto_pagado"
                        id="monto_pagado"
                        class="form-control"
                        step="0.01"
                        min="0.01"
                        required
                        aria-required="true"
                        aria-describedby="monto-descripcion"
                    >
                    <div id="monto-descripcion" class="form-text">
                        Ingrese el monto abonado. Debe ser mayor a cero.
                    </div>
                    <div class="invalid-feedback">Ingrese un monto válido mayor a 0.</div>
                </div>

                {{-- OBSERVACIONES --}}
                <div class="mb-3">
                    <label for="observaciones" class="form-label fw-semibold">
                        Observaciones
                    </label>
                    <textarea
                        name="observaciones"
                        id="observaciones"
                        class="form-control"
                        rows="3"
                        maxlength="255"
                        aria-describedby="obs-descripcion"
                        placeholder="Opcional..."
                    >{{ old('observaciones') }}</textarea>
                    <div id="obs-descripcion" class="form-text">
                        Puede agregar una nota breve (máximo 255 caracteres).
                    </div>
                </div>

            </fieldset>

            <div class="text-end mt-3 d-flex justify-content-end gap-2">

                {{-- COLORES DE CONTRASTE AA --}}
                <button type="submit"
                        class="btn fw-semibold px-3"
                        style="background-color:#1B5E20; color:white;">
                    Registrar Pago
                </button>

                <a href="{{ route('pagos.index', $prestamo->id) }}"
                   class="btn fw-semibold px-3"
                   style="background-color:#424242; color:white;"
                   role="button">
                    Cancelar
                </a>
            </div>

        </form>

    </div>
</div>
@stop

@section('js')
<script>
    // Bootstrap 5 validación accesible
    (() => {
        'use strict';
        const form = document.getElementById('pagoForm');

        form.addEventListener('submit', function (e) {

            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();

                // Enviar foco al primer error para accesibilidad
                const primerError = form.querySelector(':invalid');
                if (primerError) {
                    primerError.focus();
                }
            }

            form.classList.add('was-validated');
        }, false);

    })();
</script>
@stop
