<<<<<<< HEAD
@extends('adminlte::page')

@section('title', 'Registrar Ahorro')

@section('content_header')
    <h1>Nuevo Registro de Ahorro</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">

            <!-- Aquí agregamos la visualización de errores -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        @if ($errors->has('db_error'))
                            <li>{{ $errors->first('db_error') }}</li>
                        @endif
                    </ul>
                </div>
            @endif

            <form action="{{ route('ahorros.store') }}" method="POST">
                @csrf
                @include('ahorros.partials.form', ['modo' => 'crear'])
                <button type="submit" class="btn btn-primary">Guardar Ahorro</button>
                <a href="{{ route('ahorros.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop
=======
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar Ahorro</h2>
    <form action="{{ route('ahorros.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="id_organizacion" class="form-label">Caja Rural</label>
            <select name="id_organizacion" id="id_organizacion" class="form-control" required>
                <option value="">Seleccione una caja rural</option>
                @foreach($organizaciones as $org)
                    <option value="{{ $org->Id_Organizacion }}">{{ $org->Nombre_Organizacion }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="id_beneficiario" class="form-label">Socio/Beneficiario</label>
            <select name="id_beneficiario" id="id_beneficiario" class="form-control" required>
                <option value="">Seleccione un socio</option>
                @foreach($socios as $socio)
                    <option value="{{ $socio->Id_Beneficiario }}">{{ $socio->Nombre_Beneficiario }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="monto_ahorrado" class="form-label">Monto Ahorrado</label>
            <input type="number" step="0.01" min="0" name="monto_ahorrado" id="monto_ahorrado" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
@endsection
>>>>>>> 3ee94b0a8dc5e34c4eb7247b1e2a8c0652b035d0
