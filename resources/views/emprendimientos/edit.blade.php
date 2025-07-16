@extends('adminlte::page')

@section('title', 'Editar Emprendimiento')

@section('content_header')
    <h1>Editar Emprendimiento</h1>
@stop

@section('content')
    <form action="{{ route('emprendimientos.update', ['emprendimiento' => $emprendimiento->Id_Emprendimiento]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="Caja_Rural">Nombre del Emprendimiento (Caja Rural)</label>
            <input type="text" name="Caja_Rural" class="form-control" value="{{ old('Caja_Rural', $emprendimiento->Caja_Rural) }}" required>
        </div>

        <div class="form-group">
            <label for="Id_Municipio">Municipio</label>
            <select name="Id_Municipio" class="form-control" required>
                <option value="">Seleccione un municipio</option>
                @foreach($municipios as $municipio)
                    <option value="{{ $municipio->Id_Municipio }}" {{ old('Id_Municipio', $emprendimiento->Id_Municipio) == $municipio->Id_Municipio ? 'selected' : '' }}>
                        {{ $municipio->Nombre_Municipio ?? $municipio->Id_Municipio }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="Comunidad">Comunidad</label>
            <input type="text" name="Comunidad" class="form-control" value="{{ old('Comunidad', $emprendimiento->Comunidad) }}">
        </div>

        <div class="form-group">
            <label for="Tipo_Negocio">Tipo de Negocio / Descripción</label>
            <textarea name="Tipo_Negocio" class="form-control" rows="3" required>{{ old('Tipo_Negocio', $emprendimiento->Tipo_Negocio) }}</textarea>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Socios Hombres</label>
                <input type="number" name="Socios_Hombres" class="form-control" value="{{ old('Socios_Hombres', $emprendimiento->Socios_Hombres) }}" min="0">
            </div>
            <div class="form-group col-md-4">
                <label>Socias Mujeres</label>
                <input type="number" name="Socios_Mujeres" class="form-control" value="{{ old('Socios_Mujeres', $emprendimiento->Socios_Mujeres) }}" min="0">
            </div>
            <div class="form-group col-md-4">
                <label>Total Socios</label>
                <input type="number" id="Total_Socios" class="form-control" readonly>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Empleos Hombres</label>
                <input type="number" name="Empleos_Hombres" class="form-control" value="{{ old('Empleos_Hombres', $emprendimiento->Empleos_Hombres) }}" min="0">
            </div>
            <div class="form-group col-md-4">
                <label>Empleos Mujeres</label>
                <input type="number" name="Empleos_Mujeres" class="form-control" value="{{ old('Empleos_Mujeres', $emprendimiento->Empleos_Mujeres) }}" min="0">
            </div>
            <div class="form-group col-md-4">
                <label>Total Empleos</label>
                <input type="number" id="Total_Empleos" class="form-control" readonly>
            </div>
        </div>

        <div class="form-group">
            <label for="Ventas_Trimestrales">Ventas Trimestrales (L)</label>
            <input type="number" step="0.01" name="Ventas_Trimestrales" class="form-control" value="{{ old('Ventas_Trimestrales', $emprendimiento->Ventas_Trimestrales) }}">
        </div>

        <div class="form-group">
            <label for="Fecha_Levantamiento">Fecha de Levantamiento</label>
            <input type="date" name="Fecha_Levantamiento" class="form-control" value="{{ old('Fecha_Levantamiento', \Carbon\Carbon::parse($emprendimiento->Fecha_Levantamiento)->format('Y-m-d')) }}" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('emprendimientos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@stop

@section('js')
    <script>
        function actualizarTotales() {
            const hombres = parseInt(document.querySelector('[name="Socios_Hombres"]').value || 0);
            const mujeres = parseInt(document.querySelector('[name="Socios_Mujeres"]').value || 0);
            document.getElementById('Total_Socios').value = hombres + mujeres;

            const empH = parseInt(document.querySelector('[name="Empleos_Hombres"]').value || 0);
            const empM = parseInt(document.querySelector('[name="Empleos_Mujeres"]').value || 0);
            document.getElementById('Total_Empleos').value = empH + empM;
        }

        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', actualizarTotales);
        });

        actualizarTotales(); // inicializar al cargar
    </script>
@stop
