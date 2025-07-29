@php dd($criterio); @endphp
@extends('adminlte::page')

@section('title', 'Editar Criterio')

@section('content_header')
    <h1>Editar Criterio</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('criterio.update', $criterio) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="subindice">Subíndice</label>
                    <input type="text" name="subindice" class="form-control" value="{{ old('subindice', $criterio->subindice) }}">
                </div>

                <div class="form-group">
                    <label for="variable">Variable <span class="text-danger">*</span></label>
                    <input type="text" name="variable" class="form-control" value="{{ old('variable', $criterio->variable) }}" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $criterio->descripcion) }}</textarea>
                </div>

                <a href="{{ route('criterio.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
@endsection
