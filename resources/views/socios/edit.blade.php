@extends('adminlte::page')

@section('content_header')
    <h1>Editar Socio</h1>
@stop

@section('content')
    <form action="{{ route('socios.update', $socio->Id_Beneficiario) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Organización (ID)</label>
            <input type="number" name="Id_Organizacion" class="form-control" value="{{ $socio->Id_Organizacion }}" required>
        </div>

        <div class="form-group">
            <label>Nombre completo</label>
            <input type="text" name="Nombre_Beneficiario" class="form-control" value="{{ $socio->Nombre_Beneficiario }}" required>
        </div>

        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="DNI" class="form-control" value="{{ $socio->DNI }}" required>
        </div>

        <div class="form-group">
            <label>Género</label>
            <select name="genero" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="M" {{ $socio->genero == 'M' ? 'selected' : '' }}>Masculino</option>
                <option value="F" {{ $socio->genero == 'F' ? 'selected' : '' }}>Femenino</option>
            </select>
        </div>

        <div class="form-group">
            <label>Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $socio->fecha_nacimiento }}">
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="Telefono" class="form-control" value="{{ $socio->Telefono }}">
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ $socio->direccion }}">
        </div>

        <div class="form-group">
            <label>Actividad económica</label>
            <input type="text" name="actividad_economica" class="form-control" value="{{ $socio->actividad_economica }}">
        </div>

        <div class="form-group">
            <label>Tipo de Cargo</label>
            <input type="text" name="Tipo_Cargo" class="form-control" value="{{ $socio->Tipo_Cargo }}">
        </div>

        <div class="form-group">
            <label>Tipo de Socio</label>
            <input type="text" name="Tipo_De_Socio" class="form-control" value="{{ $socio->Tipo_De_Socio }}">
        </div>

        <button class="btn btn-primary" type="submit">Actualizar</button>
        <a href="{{ route('socios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@stop
