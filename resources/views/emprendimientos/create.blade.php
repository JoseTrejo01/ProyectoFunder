@extends('adminlte::page')

@section('title', 'Crear Emprendimiento')

@section('content_header')
    <h1>Crear Emprendimiento</h1>
@stop

@section('content')
    <form action="{{ route('emprendimientos.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="Id_Organizacion">Caja Rural</label>
            <select name="Id_Organizacion" id="Id_Organizacion" class="form-control" required>
                <option value="">Seleccione una organización</option>
                @foreach($organizaciones as $org)
                  <option value="{{ $org->Id_Organizacion }}"
    {{ old('Id_Organizacion') == $org->Id_Organizacion ? 'selected' : '' }}>
    {{ $org->Nombre_Organizacion }} - {{ $org->Estado }}
</option>
                @endforeach
            </select>
            @error('Id_Organizacion') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Aquí ocultamos TODO el resto del formulario inicialmente -->
        <div id="form-content" style="display: none;">

            <div class="form-group">
                <label for="Caja_Rural">Nombre del Emprendimiento</label>
                <input type="text" name="Caja_Rural" class="form-control" value="{{ old('Caja_Rural') }}" required>
                @error('Caja_Rural') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Select de Departamento --}}
            <div class="form-group">
                <label for="departamento">Departamento</label>
                <select name="Id_Departamento" id="departamento" class="form-control" required>
                    <option value="">Seleccione un departamento</option>
                    @foreach($departamentos as $departamento)
                        <option value="{{ $departamento->Id_Departamento }}"
                            {{ old('Id_Departamento') == $departamento->Id_Departamento ? 'selected' : '' }}>
                            {{ $departamento->Nombre_Departamento }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Select de Municipio --}}
            <div class="form-group">
                <label for="Id_Municipio">Municipio</label>
                <select name="Id_Municipio" id="municipio" class="form-control" required>
                    <option value="">Seleccione un municipio</option>
                </select>
                @error('Id_Municipio') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Select de Aldea --}}
            <div class="form-group">
                <label for="aldea">Aldea</label>
                <select name="aldea_id" id="aldea" class="form-control">
                    <option value="">Seleccione una aldea</option>
                </select>
            </div>

            <div class="form-group">
                <label for="Comunidad">Comunidad</label>
                <input type="text" name="Comunidad" class="form-control" value="{{ old('Comunidad') }}">
                @error('Comunidad') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="Tipo_Negocio">Tipo de Negocio / Descripción</label>
                <textarea name="Tipo_Negocio" class="form-control" rows="3" required>{{ old('Tipo_Negocio') }}</textarea>
                @error('Tipo_Negocio') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Socios Hombres</label>
                    <input type="number" name="Socios_Hombres" class="form-control" value="{{ old('Socios_Hombres', 0) }}" min="0">
                    @error('Socios_Hombres') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group col-md-4">
                    <label>Socias Mujeres</label>
                    <input type="number" name="Socios_Mujeres" class="form-control" value="{{ old('Socios_Mujeres', 0) }}" min="0">
                    @error('Socios_Mujeres') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group col-md-4">
                    <label>Total Socios</label>
                    <input type="number" id="Total_Socios" class="form-control" readonly>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Empleos Hombres</label>
                    <input type="number" name="Empleos_Hombres" class="form-control" value="{{ old('Empleos_Hombres', 0) }}" min="0">
                    @error('Empleos_Hombres') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group col-md-4">
                    <label>Empleos Mujeres</label>
                    <input type="number" name="Empleos_Mujeres" class="form-control" value="{{ old('Empleos_Mujeres', 0) }}" min="0">
                    @error('Empleos_Mujeres') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group col-md-4">
                    <label>Total Empleos</label>
                    <input type="number" id="Total_Empleos" class="form-control" readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="Ventas_Trimestrales">Ventas Trimestrales (L)</label>
                <input type="number" step="0.01" name="Ventas_Trimestrales" class="form-control" value="{{ old('Ventas_Trimestrales', 0) }}">
                @error('Ventas_Trimestrales') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="Fecha_Levantamiento">Fecha de Levantamiento</label>
                <input type="date" name="Fecha_Levantamiento" class="form-control" value="{{ old('Fecha_Levantamiento') }}" required>
                @error('Fecha_Levantamiento') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Guardar</button>
                <a href="{{ route('emprendimientos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
@stop

@section('js')
<script>
    // Actualizar totales socios y empleos
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
    actualizarTotales();

    // Mostrar u ocultar todo el formulario según selección de organización
    const organizacionSelect = document.getElementById('Id_Organizacion');
    const formContent = document.getElementById('form-content');

    function toggleFormContent() {
        if (organizacionSelect.value) {
            formContent.style.display = 'block';
        } else {
            formContent.style.display = 'none';
        }
    }

    // Ejecutar al cargar por si hay old() seleccionado
    toggleFormContent();

    organizacionSelect.addEventListener('change', toggleFormContent);

    // AJAX dinámico para selects dependientes
    document.getElementById('departamento').addEventListener('change', function () {
        const departamentoId = this.value;
        const municipioSelect = document.getElementById('municipio');
        const aldeaSelect = document.getElementById('aldea');

        municipioSelect.innerHTML = '<option value="">Cargando municipios...</option>';
        aldeaSelect.innerHTML = '<option value="">Seleccione una aldea</option>';

        if (departamentoId) {
            fetch(`/municipios/${departamentoId}`)
                .then(res => res.json())
                .then(data => {
                    municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
                    data.forEach(m => {
                        const opt = document.createElement('option');
                        opt.value = m.id;
                        opt.textContent = m.nombre;
                        municipioSelect.appendChild(opt);
                    });
                });
        } else {
            municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
        }
    });

    document.getElementById('municipio').addEventListener('change', function () {
        const municipioId = this.value;
        const aldeaSelect = document.getElementById('aldea');

        aldeaSelect.innerHTML = '<option value="">Cargando aldeas...</option>';

        if (municipioId) {
            fetch(`/aldeas/${municipioId}`)
                .then(res => res.json())
                .then(data => {
                    aldeaSelect.innerHTML = '<option value="">Seleccione una aldea</option>';
                    data.forEach(a => {
                        const opt = document.createElement('option');
                        opt.value = a.id;
                        opt.textContent = a.nombre;
                        aldeaSelect.appendChild(opt);
                    });
                });
        } else {
            aldeaSelect.innerHTML = '<option value="">Seleccione una aldea</option>';
        }
    });
</script>
@stop
