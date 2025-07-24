@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalle de Ahorro</h2>
    <div class="card">
        <div class="card-body">
            <p><strong>Caja Rural:</strong> {{ $ahorro->organizacion->Nombre_Organizacion ?? '-' }}</p>
            <p><strong>Socio/Beneficiario:</strong> {{ $ahorro->beneficiario->Nombre_Beneficiario ?? '-' }}</p>
            <p><strong>Monto Ahorrado:</strong> L {{ number_format($ahorro->monto_ahorrado, 2) }}</p>
            <a href="{{ route('ahorros.index') }}" class="btn btn-secondary">Volver al listado</a>
        </div>
    </div>
</div>
@endsection
