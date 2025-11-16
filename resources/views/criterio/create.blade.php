@extends('adminlte::page')

@section('title', 'Crear Criterio')

@section('content_header')
    <h1 class="text-dark font-weight-bold">Crear Criterio</h1>
@endsection

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('criterio.store') }}" method="POST" role="form" aria-label="Formulario para crear un nuevo criterio">
                @csrf

                {{-- NOMBRE DEL CRITERIO --}}
                <div class="form-group">
                    <label for="variable" class="font-weight-bold">
                        Nombre del Criterio <span class="text-danger" aria-hidden="true">*</span>
                    </label>

                    <input 
                        type="text" 
                        id="variable" 
                        name="variable" 
                        class="form-control @error('variable') is-invalid @enderror" 
                        required
                        aria-required="true"
                        aria-describedby="variableHelp {{ $errors->has('variable') ? 'variableError' : '' }}"
                        value="{{ old('variable') }}"
                    >

                    <small id="variableHelp" class="form-text text-muted">
                        Escribe un nombre claro para el criterio.
                    </small>

                    @error('variable')
                        <span id="variableError" class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- DESCRIPCIÓN --}}
                <div class="form-group">
                    <label for="descripcion" class="font-weight-bold">Descripción (opcional)</label>

                    <textarea 
                        id="descripcion" 
                        name="descripcion" 
                        class="form-control @error('descripcion') is-invalid @enderror" 
                        rows="3"
                        aria-describedby="descripcionHelp {{ $errors->has('descripcion') ? 'descripcionError' : '' }}"
                    >{{ old('descripcion') }}</textarea>

                    <small id="descripcionHelp" class="form-text text-muted">
                        Puedes agregar más detalles sobre el criterio.
                    </small>

                    @error('descripcion')
                        <span id="descripcionError" class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- BOTONES --}}
                <div class="d-flex gap-2 mt-3">
                    <button 
                        type="submit" 
                        class="btn btn-success font-weight-bold"
                        aria-label="Guardar criterio"
                    >
                        Guardar
                    </button>

                    <a 
                        href="{{ route('criterio.index') }}" 
                        class="btn btn-secondary"
                        aria-label="Cancelar y volver al listado de criterios"
                    >
                        Cancelar
                    </a>
                </div>

            </form>

        </div>
    </div>
@endsection
