@extends('adminlte::page')

@section('content_header')
    <h1>Ficha del Ahorro</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            Ahorro de: {{ $ahorro->beneficiario->Nombre_Beneficiario ?? 'N/D' }}
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item"><strong>Organización:</strong> {{ $ahorro->organizacion->Nombre_Organizacion ?? 'N/D' }}</li>
                <li class="list-group-item"><strong>Beneficiario:</strong> {{ $ahorro->beneficiario->Nombre_Beneficiario ?? 'N/D' }}</li>
                <li class="list-group-item"><strong>Tipo:</strong> {{ $ahorro->beneficiario->Tipo_De_Socio ?? 'N/D' }}</li>
                <li class="list-group-item"><strong>Monto:</strong> L. {{ number_format($ahorro->Monto, 2, '.', ',') }}</li>
                <li class="list-group-item"><strong>Fecha:</strong> {{ $ahorro->Fecha }}</li>
                <li class="list-group-item"><strong>Creado en:</strong> {{ $ahorro->created_at }}</li>
                <li class="list-group-item"><strong>Última actualización:</strong> {{ $ahorro->updated_at }}</li>
            </ul>
        </div>
        <div class="card-footer">
            <a href="{{ route('ahorros.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
@stop
