@extends('adminlte::page')

@section('title', 'Nuevo Indicador de Género')

@section('content_header')
    <h1>Agregar Nuevo Registro</h1>
@stop

@section('content')
    <a href="{{ route('genero.index') }}" class="btn btn-secondary mb-3">⬅️ Volver</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Ups!</strong> Hay errores en el formulario.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="formulario-genero" action="{{ route('genero.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nombre de Caja Rural</label>
                <input type="text" name="nombre_caja_rural" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Departamento</label>
                <input type="text" name="departamento" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Municipio</label>
                <input type="text" name="municipio" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Comunidad</label>
                <input type="text" name="comunidad" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Nombre y Apellidos</label>
                <input type="text" name="nombre_apellidos" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Etnia</label>
                <input type="text" name="etnia" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Sexo</label>
                <select name="sexo" class="form-control" required>
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" required>
                <small id="fechaError" class="text-danger d-none">⚠️ La fecha de nacimiento no puede ser en el futuro ni inválida.</small>
            </div>

            <div class="col-md-3 mb-3">
                <label>Edad</label>
                <input type="number" name="edad" id="edad" class="form-control" min="0" required readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label>No. de Identidad</label>
                <input type="text" name="identidad" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Cargo</label>
                <input type="text" name="cargo" class="form-control" required>
            </div>
        </div>

        <div class="col-md-6 mb-3">
    <label>Sexo</label>
    <select name="sexo" class="form-control" required>
        <option value="">Seleccione una opción</option>
        <option value="Masculino">Masculino</option>
        <option value="Femenino">Femenino</option>
    </select>
</div>


        <button type="submit" class="btn btn-primary">💾 Guardar</button>
    </form>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fechaInput = document.getElementById('fecha_nacimiento');
        const edadInput = document.getElementById('edad');
        const fechaError = document.getElementById('fechaError');
        const formulario = document.getElementById('formulario-genero');

        let fechaValida = false;

        fechaInput.addEventListener('change', function () {
            const fechaNacimiento = new Date(this.value);
            const hoy = new Date();

            if (isNaN(fechaNacimiento.getTime()) || fechaNacimiento > hoy) {
                edadInput.value = '';
                fechaError.classList.remove('d-none');
                fechaValida = false;
            } else {
                let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
                const mes = hoy.getMonth() - fechaNacimiento.getMonth();
                if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                    edad--;
                }
                edadInput.value = edad;
                fechaError.classList.add('d-none');
                fechaValida = true;
            }
        });

        formulario.addEventListener('submit', function (e) {
            if (!fechaValida) {
                e.preventDefault();
                alert('⚠️ Corrige la fecha de nacimiento antes de guardar.');
            }
        });
    });
</script>
@endsection
