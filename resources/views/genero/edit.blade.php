@extends('adminlte::page')

@section('title', 'Editar Indicador de Género')

@section('content_header')
    <h1>Editar Indicador de Género</h1>
@stop

@section('content')
<form action="{{ route('genero.update', $genero->id) }}" method="POST">
    @csrf
    @method('PUT')

    <ul class="nav nav-tabs" id="tabGenero" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">📌 General</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="socio-tab" data-toggle="tab" href="#socio" role="tab">🧍 Socio(a)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="demo-tab" data-toggle="tab" href="#demo" role="tab">🧬 Demográficos</a>
        </li>
    </ul>

    <div class="tab-content pt-3" id="tabGeneroContent">
        <!-- General -->
        <div class="tab-pane fade show active" id="general" role="tabpanel">
            @include('genero.partials.form-general', ['genero' => $genero])
        </div>

        <!-- Socio -->
        <div class="tab-pane fade" id="socio" role="tabpanel">
            @include('genero.partials.form-socio', ['genero' => $genero])
        </div>

        <!-- Demográficos -->
        <div class="tab-pane fade" id="demo" role="tabpanel">
            @include('genero.partials.form-demograficos', ['genero' => $genero])
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary" type="submit">Actualizar</button>
        <a href="{{ route('genero.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
@stop

@section('js')
<script>
    document.querySelector('input[name="fecha_nacimiento"]').addEventListener('change', function() {
        const fecha = new Date(this.value);
        const hoy = new Date();
        let edad = hoy.getFullYear() - fecha.getFullYear();
        const m = hoy.getMonth() - fecha.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < fecha.getDate())) edad--;
        document.querySelector('input[name="edad"]').value = edad;
    });
</script>
@stop
