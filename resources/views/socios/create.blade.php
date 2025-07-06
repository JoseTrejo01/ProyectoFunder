@extends('adminlte::page')

@section('content_header')
    <h1>Nuevo Socio</h1>
@stop

@section('content')
    <form action="{{ route('socios.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Organización (ID)</label>
            <input type="number" name="Id_Organizacion" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Nombre completo</label>
            <input type="text" name="Nombre_Beneficiario" class="form-control" required>
        </div>

        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="DNI" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Género</label>
            <select name="genero" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
            </select>
        </div>

        <div class="form-group">
            <label>Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control">
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="Telefono" class="form-control">
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control">
        </div>

        <div class="form-group">
            <label>Actividad económica</label>
            <input type="text" name="actividad_economica" class="form-control">
        </div>

        <div class="form-group">
            <label>Tipo de Cargo</label>
            <input type="text" name="Tipo_Cargo" class="form-control">
        </div>

        <div class="form-group">
            <label>Tipo de Socio</label>
            <input type="text" name="Tipo_De_Socio" class="form-control">
        </div>

        <button class="btn btn-success" type="submit">Guardar</button>
        <a href="{{ route('socios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@stop
