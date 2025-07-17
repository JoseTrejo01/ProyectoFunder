@extends('adminlte::page')

@section('title', 'Registrar Solicitud de Préstamo')

@section('content_header')
    <h1>Registrar Solicitud de Préstamo</h1>
@stop

@section('content')
<div class="container-fluid">

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Corrige los siguientes errores!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('prestamos.store') }}" method="POST">
        @csrf

        {{-- Organización (Socio) --}}
        <div class="mb-3">
            <label for="socio_id" class="form-label">Organización (Socio)</label>
            <select name="socio_id" id="socio_id" class="form-select" required>
                <option value="">Seleccione una organización</option>
                @foreach($organizaciones as $org)
                    <option value="{{ $org->Id_Organizacion }}" {{ old('socio_id') == $org->Id_Organizacion ? 'selected' : '' }}>
                        {{ $org->Nombre_Organizacion }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Nombre de la Caja Rural --}}
        <div class="mb-3">
            <label for="nombre_caja_rural" class="form-label">Nombre de la Caja Rural</label>
            <input type="text" name="nombre_caja_rural" class="form-control" value="{{ old('nombre_caja_rural') }}" required>
        </div>

        {{-- Monto solicitado --}}
        <div class="mb-3">
            <label for="monto_solicitado" class="form-label">Monto Solicitado (Lps)</label>
            <input type="number" name="monto_solicitado" class="form-control" step="0.01" value="{{ old('monto_solicitado') }}" required>
        </div>

        {{-- Plazo en meses --}}
        <div class="mb-3">
            <label for="plazo_meses" class="form-label">Plazo en Meses</label>
            <input type="number" name="plazo_meses" class="form-control" value="{{ old('plazo_meses') }}" required>
        </div>

        {{-- Destino --}}
        <div class="mb-3">
            <label for="destino" class="form-label">Destino del Préstamo</label>
            <input type="text" name="destino" class="form-control" value="{{ old('destino') }}" required>
        </div>

        {{-- Tipo de Crédito --}}
        <div class="mb-3">
            <label for="tipo_credito" class="form-label">Tipo de Crédito</label>
            <input type="text" name="tipo_credito" class="form-control" value="{{ old('tipo_credito') }}" required>
        </div>

        {{-- Fecha de Solicitud --}}
        <div class="mb-3">
            <label for="fecha_solicitud" class="form-label">Fecha de Solicitud</label>
            <input type="date" name="fecha_solicitud" class="form-control" value="{{ old('fecha_solicitud') }}" required>
        </div>

        {{-- Porcentaje de Mora --}}
        <div class="mb-3">
            <label for="porcentaje_mora_caja" class="form-label">% de Mora de la Caja</label>
            <input type="number" name="porcentaje_mora_caja" class="form-control" step="0.01" value="{{ old('porcentaje_mora_caja') }}">
        </div>

        {{-- Intereses Cobrados --}}
        <div class="mb-3">
            <label for="intereses_cobrados" class="form-label">Intereses Cobrados y Otros Ingresos</label>
            <input type="number" name="intereses_cobrados" class="form-control" step="0.01" value="{{ old('intereses_cobrados') }}">
        </div>

        {{-- Capital Social --}}
        <div class="mb-3">
            <label for="capital_social" class="form-label">Capital Social (Lps)</label>
            <input type="number" name="capital_social" class="form-control" step="0.01" value="{{ old('capital_social') }}">
        </div>

        {{-- Capital de Trabajo --}}
        <div class="mb-3">
            <label for="capital_trabajo" class="form-label">Capital de Trabajo (Lps)</label>
            <input type="number" name="capital_trabajo" class="form-control" step="0.01" value="{{ old('capital_trabajo') }}">
        </div>

        {{-- Reservas --}}
        <div class="mb-3">
            <label for="reservas" class="form-label">Reservas (Lps)</label>
            <input type="number" name="reservas" class="form-control" step="0.01" value="{{ old('reservas') }}">
        </div>

        {{-- Observaciones --}}
        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Guardar Solicitud</button>
    </form>
</div>
@stop
