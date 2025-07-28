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
