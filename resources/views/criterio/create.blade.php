@extends('adminlte::page')

@section('title', 'Crear Criterio')

@section('content_header')
    <h1>Crear Criterio</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('criterio.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="variable">Nombre del Criterio</label>
                    <input type="text" name="variable" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción (opcional)</label>
                    <textarea name="descripcion" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-success">Guardar</button>
                <a href="{{ route('criterio.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection
