@extends('adminlte::page')

@section('title', 'Editar Emprendimiento')

@section('content_header')
    <h1>Editar Emprendimiento</h1>
@stop

@section('content')
    <form action="{{ route('emprendimientos.update', ['emprendimiento' => $emprendimiento->Id_Emprendimiento]) }}" method="POST">
        @csrf
        @method('PUT')

<<<<<<< HEAD
        {{-- Select de Organización --}}
<div class="form-group">
    <label for="Id_Organizacion">Caja Rural</label>
    <select name="Id_Organizacion" id="Id_Organizacion" class="form-control" required>
        <option value="">Seleccione una organización</option>
        @foreach($organizaciones as $org)
            <option value="{{ $org->Id_Organizacion }}" 
                {{ old('Id_Organizacion', $emprendimiento->Id_Organizacion) == $org->Id_Organizacion ? 'selected' : '' }}>
                {{ $org->Nombre_Organizacion }} - {{ $org->Estado_Organizacion }}
            </option>
        @endforeach
    </select>
    @error('Id_Organizacion') <small class="text-danger">{{ $message }}</small> @enderror
</div>
        <div class="form-group">
            <label for="Caja_Rural">Nombre del Emprendimiento </label>
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
=======
        {{-- Selección de Organización --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="form-group">
                    <label for="Id_Organizacion">Caja Rural</label>
                    <select name="Id_Organizacion" id="Id_Organizacion" class="form-control" required>
                        <option value="">Seleccione una organización</option>
                        @foreach($organizaciones as $org)
                            <option value="{{ $org->Id_Organizacion }}" 
                                {{ old('Id_Organizacion', $emprendimiento->Id_Organizacion) == $org->Id_Organizacion ? 'selected' : '' }}>
                                {{ $org->Nombre_Organizacion }} - {{ $org->Estado_Organizacion }}
                            </option>
                        @endforeach
                    </select>
                    @error('Id_Organizacion') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mt-3" id="emprendimientoTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general"
                   role="tab" aria-controls="general" aria-selected="true">Datos Generales</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="socios-tab" data-toggle="tab" href="#socios"
                   role="tab" aria-controls="socios" aria-selected="false">Socios y Empleos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="otros-tab" data-toggle="tab" href="#otros"
                   role="tab" aria-controls="otros" aria-selected="false">Otros Datos</a>
            </li>
        </ul>

        <div class="tab-content p-3 border border-top-0 shadow-sm bg-white rounded-bottom" id="emprendimientoTabsContent">

            {{-- TAB 1: Datos Generales --}}
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="form-group">
                    <label for="Caja_Rural">Nombre del Emprendimiento </label>
                    <input type="text" name="Caja_Rural" class="form-control" 
                           value="{{ old('Caja_Rural', $emprendimiento->Caja_Rural) }}" required>
                </div>
>>>>>>> 2f054e49169a2bb64cc5edc8129e26f159f4f85a

                <div class="form-group">
                    <label for="departamento">Departamento</label>
                    <select id="departamento" class="form-control" required>
                        <option value="">Seleccione un departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->Id_Departamento }}" 
                                {{ $departamento->Id_Departamento == $emprendimiento->municipio->departamento->Id_Departamento ? 'selected' : '' }}>
                                {{ $departamento->Nombre_Departamento }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="municipio">Municipio</label>
                    <select name="Id_Municipio" id="municipio" class="form-control" required>
                        <option value="{{ $emprendimiento->municipio->id }}" selected>
                            {{ $emprendimiento->municipio->nombre }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="aldea">Aldea</label>
                    <select name="aldea_id" id="aldea" class="form-control">
                        <option value="{{ $emprendimiento->aldea->id ?? '' }}" selected>
                            {{ $emprendimiento->aldea->nombre ?? 'Seleccione una aldea' }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Comunidad">Comunidad</label>
                    <input type="text" name="Comunidad" class="form-control" 
                           value="{{ old('Comunidad', $emprendimiento->Comunidad) }}">
                </div>
            </div>

            {{-- TAB 2: Socios y Empleos --}}
            <div class="tab-pane fade" id="socios" role="tabpanel" aria-labelledby="socios-tab">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Socios Hombres</label>
                        <input type="number" name="Socios_Hombres" class="form-control" 
                               value="{{ old('Socios_Hombres', $emprendimiento->Socios_Hombres) }}" min="0">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Socias Mujeres</label>
                        <input type="number" name="Socios_Mujeres" class="form-control" 
                               value="{{ old('Socios_Mujeres', $emprendimiento->Socios_Mujeres) }}" min="0">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Total Socios</label>
                        <input type="number" id="Total_Socios" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Empleos Hombres</label>
                        <input type="number" name="Empleos_Hombres" class="form-control" 
                               value="{{ old('Empleos_Hombres', $emprendimiento->Empleos_Hombres) }}" min="0">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Empleos Mujeres</label>
                        <input type="number" name="Empleos_Mujeres" class="form-control" 
                               value="{{ old('Empleos_Mujeres', $emprendimiento->Empleos_Mujeres) }}" min="0">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Total Empleos</label>
                        <input type="number" id="Total_Empleos" class="form-control" readonly>
                    </div>
                </div>
            </div>

            {{-- TAB 3: Otros Datos --}}
            <div class="tab-pane fade" id="otros" role="tabpanel" aria-labelledby="otros-tab">
                <div class="form-group">
                    <label for="Tipo_Negocio">Tipo de Negocio / Descripción</label>
                    <textarea name="Tipo_Negocio" class="form-control" rows="3" required>
                        {{ old('Tipo_Negocio', $emprendimiento->Tipo_Negocio) }}
                    </textarea>
                </div>

                <div class="form-group">
                    <label for="Ventas_Trimestrales">Ventas Trimestrales (L)</label>
                    <input type="number" step="0.01" name="Ventas_Trimestrales" class="form-control" 
                           value="{{ old('Ventas_Trimestrales', $emprendimiento->Ventas_Trimestrales) }}">
                </div>

                <div class="form-group">
                    <label for="Fecha_Levantamiento">Fecha de Levantamiento</label>
                    <input type="date" name="Fecha_Levantamiento" class="form-control" 
                           value="{{ old('Fecha_Levantamiento', $emprendimiento->Fecha_Levantamiento) }}" required>
                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('emprendimientos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </div>
    </form>
@stop

@section('js')
    <script>
        function actualizarTotales() {
            const hombres = parseInt(document.querySelector('[name="Socios_Hombres"]').value || 0);
            const mujeres = parseInt(document.querySelector('[name="Socios_Mujeres"]').value || 0);
            document.getElementById('Total_Socios').value = hombres + mujeres;

<<<<<<< HEAD
            const empH = parseInt(document.querySelector('[name="Empleos_Hombres"]').value || 0);
            const empM = parseInt(document.querySelector('[name="Empleos_Mujeres"]').value || 0);
            document.getElementById('Total_Empleos').value = empH + empM;
=======
        const empH = parseInt(document.querySelector('[name="Empleos_Hombres"]').value || 0);
        const empM = parseInt(document.querySelector('[name="Empleos_Mujeres"]').value || 0);
        document.getElementById('Total_Empleos').value = empH + empM;
    }

    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', actualizarTotales);
    });

    actualizarTotales();

    // AJAX dinámico
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
                        municipioSelect.innerHTML += `<option value="${m.id}">${m.nombre}</option>`;
                    });
                });
>>>>>>> 2f054e49169a2bb64cc5edc8129e26f159f4f85a
        }

<<<<<<< HEAD
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', actualizarTotales);
        });

        actualizarTotales(); // inicializar al cargar
    </script>
=======
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
                        aldeaSelect.innerHTML += `<option value="${a.id}">${a.nombre}</option>`;
                    });
                });
        }
    });
</script>
>>>>>>> 2f054e49169a2bb64cc5edc8129e26f159f4f85a
@stop
