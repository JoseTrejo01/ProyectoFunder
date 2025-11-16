@extends('adminlte::page')

@section('title', 'Editar Criterio')

@section('content_header')
    <h1 class="font-weight-bold text-dark">Editar Criterio</h1>
@endsection

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form 
                action="{{ route('criterio.update', $criterio) }}" 
                method="POST" 
                role="form"
                aria-label="Formulario para editar criterio"
            >
                @csrf
                @method('PUT')

                {{-- SUBÍNDICE --}}
                <div class="form-group">
                    <label for="subindice" class="font-weight-bold">Subíndice</label>

                    <input 
                        type="text"
                        id="subindice"
                        name="subindice"
                        class="form-control @error('subindice') is-invalid @enderror"
                        value="{{ old('subindice', $criterio->subindice) }}"
                        aria-describedby="subindiceHelp {{ $errors->has('subindice') ? 'subindiceError' : '' }}"
                    >

                    <small id="subindiceHelp" class="form-text text-muted">
                        Opcional. Agrega un subíndice para clasificar el criterio.
                    </small>

                    @error('subindice')
                        <span id="subindiceError" class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- VARIABLE --}}
                <div class="form-group">
                    <label for="variable" class="font-weight-bold">
                        Variable <span class="text-danger" aria-hidden="true">*</span>
                    </label>

                    <input 
                        type="text"
                        id="variable"
                        name="variable"
                        class="form-control @error('variable') is-invalid @enderror"
                        value="{{ old('variable', $criterio->variable) }}"
                        required
                        aria-required="true"
                        aria-describedby="variableHelp {{ $errors->has('variable') ? 'variableError' : '' }}"
                    >

                    <small id="variableHelp" class="form-text text-muted">
                        Este nombre identifica el criterio de evaluación.
                    </small>

                    @error('variable')
                        <span id="variableError" class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- DESCRIPCIÓN --}}
                <div class="form-group">
                    <label for="descripcion" class="font-weight-bold">Descripción</label>

                    <textarea 
                        id="descripcion"
                        name="descripcion"
                        class="form-control @error('descripcion') is-invalid @enderror"
                        rows="3"
                        aria-describedby="descripcionHelp {{ $errors->has('descripcion') ? 'descripcionError' : '' }}"
                    >{{ old('descripcion', $criterio->descripcion) }}</textarea>

                    <small id="descripcionHelp" class="form-text text-muted">
                        Describe brevemente la finalidad del criterio.
                    </small>

                    @error('descripcion')
                        <span id="descripcionError" class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- BOTONES --}}
                <div class="d-flex gap-2 mt-3">
                    <a 
                        href="{{ route('criterio.index') }}" 
                        class="btn btn-secondary"
                        aria-label="Cancelar edición y volver al listado"
                    >
                        Cancelar
                    </a>

                    <button 
                        type="submit" 
                        class="btn btn-primary font-weight-bold"
                        aria-label="Actualizar criterio"
                    >
                        Actualizar
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection
