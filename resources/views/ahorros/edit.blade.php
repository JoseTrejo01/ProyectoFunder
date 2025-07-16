@extends('adminlte::page')

@section('content_header')
    <h1>Editar Registro de Ahorros</h1>
@stop

@section('content')
    <form action="{{ route('ahorros.update', $ahorro->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('ahorros.partials.form', ['modo' => 'editar'])

        <button type="submit" class="btn btn-warning">
            <i class="fas fa-edit"></i> Actualizar
        </button>
        <a href="{{ route('ahorros.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Cancelar
        </a>
    </form>
@stop
