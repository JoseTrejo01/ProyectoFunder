@extends('adminlte::page')

@section('title', 'Crear Emprendimiento')

@section('content_header')
    <h1>Crear Emprendimiento</h1>
@stop

@section('content')
    <form action="{{ route('emprendimientos.store') }}" method="POST">
        @csrf

        {{-- Selección de Organización --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="form-group">
                    <label for="Id_Organizacion">Caja Rural</label>
                    <select name="Id_Organizacion" id="Id_Organizacion" class="form-control" required>
                        <option value="">Seleccione una organización</option>
                        @foreach($organizaciones as $org)
                            <option value="{{ $org->Id_Organizacion }}"
                                {{ old('Id_Organizacion') == $org->Id_Organizacion ? 'selected' : '' }}>
                                {{ $org->Nombre_Organizacion }} - {{ $org->Estado_Organizacion }}
                            </option>
                        @endforeach
                    </select>
                    @error('Id_Organizacion') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        {{-- Tabs con el resto del formulario --}}
        <div id="form-content" style="display:none;" class="mt-3">
            <ul class="nav nav-tabs" id="emprendimientoTabs" role="tablist">
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

                {{-- Tab 1: Datos Generales --}}
                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <div class="form-group">
                        <label for="Caja_Rural">Nombre del Emprendimiento</label>
                        <input type="text" name="Caja_Rural" maxlength="40" class="form-control solo-texto" value="{{ old('Caja_Rural') }}" required>
                        @error('Caja_Rural') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

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

                    <div class="form-group">
                        <label for="municipio">Municipio</label>
                        <select name="Id_Municipio" id="municipio" class="form-control" required>
                            <option value="">Seleccione un municipio</option>
                        </select>
                        @error('Id_Municipio') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="aldea">Aldea</label>
                        <select name="aldea_id" id="aldea" class="form-control" required>
                            <option value="">Seleccione una aldea</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Comunidad">Comunidad</label>
                        <input type="text" name="Comunidad" maxlength="40" class="form-control solo-texto" value="{{ old('Comunidad') }}" required>
                        @error('Comunidad') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group text-right mt-4">
    <button type="button" class="btn btn-primary" onclick="siguienteTab('socios')">Siguiente</button>
</div>
                </div>

                {{-- Tab 2: Socios y Empleos --}}
                <div class="tab-pane fade" id="socios" role="tabpanel" aria-labelledby="socios-tab">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Socios Hombres</label>
                            <input type="number" name="Socios_Hombres" class="form-control solo-numeros" value="{{ old('Socios_Hombres', 0) }}" min="0">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Socias Mujeres</label>
                            <input type="number" name="Socios_Mujeres" class="form-control solo-numeros" value="{{ old('Socios_Mujeres', 0) }}" min="0">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Total Socios</label>
                            <input type="number" id="Total_Socios" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Empleos Hombres</label>
                            <input type="number" name="Empleos_Hombres" class="form-control solo-numeros" value="{{ old('Empleos_Hombres', 0) }}" min="0">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Empleos Mujeres</label>
                            <input type="number" name="Empleos_Mujeres" class="form-control solo-numeros" value="{{ old('Empleos_Mujeres', 0) }}" min="0">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Total Empleos</label>
                            <input type="number" id="Total_Empleos" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-between mt-4">
    <button type="button" class="btn btn-secondary" onclick="anteriorTab('general')">Atrás</button>
    <button type="button" class="btn btn-primary" onclick="siguienteTab('otros')">Siguiente</button>
</div>

                </div>

                {{-- Tab 3: Otros Datos --}}
                <div class="tab-pane fade" id="otros" role="tabpanel" aria-labelledby="otros-tab">
                    <div class="form-group">
                        <label for="Tipo_Negocio">Tipo de Negocio / Descripción</label>
                      <textarea name="Tipo_Negocio" class="form-control solo-texto" maxlength="300" rows="3" required>{{ old('Tipo_Negocio') }}</textarea>
                        @error('Tipo_Negocio') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="Ventas_Trimestrales">Ventas Trimestrales (L)</label>
                        <input type="number" step="0.0000000001" name="Ventas_Trimestrales" class="form-control solo-numeros" value="{{ old('Ventas_Trimestrales', 0) }}" min="0">
                        @error('Ventas_Trimestrales') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="Fecha_Levantamiento">Fecha de Levantamiento</label>
                        <input type="date" name="Fecha_Levantamiento" class="form-control" value="{{ old('Fecha_Levantamiento') }}" required>
                        @error('Fecha_Levantamiento') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <a href="{{ route('emprendimientos.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
@stop
@section('css')
<style>
    #emprendimientoTabs .nav-link {
        pointer-events: none !important;
        cursor: default;
        color: #6c757d; /* gris para parecer deshabilitado */
    }
</style>
@stop
@section('js')
<script>
    // Actualizar totales
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

    // Mostrar formulario si hay organización
    const organizacionSelect = document.getElementById('Id_Organizacion');
    const formContent = document.getElementById('form-content');
    function toggleFormContent() {
        formContent.style.display = organizacionSelect.value ? 'block' : 'none';
    }
    toggleFormContent();
    organizacionSelect.addEventListener('change', toggleFormContent);

    // Municipios
    document.getElementById('departamento').addEventListener('change', function () {
        const departamentoId = this.value;
        const municipioSelect = document.getElementById('municipio');
        const aldeaSelect = document.getElementById('aldea');
        municipioSelect.innerHTML = '<option>Cargando...</option>';
        aldeaSelect.innerHTML = '<option>Seleccione una aldea</option>';

        if (departamentoId) {
            fetch(`/municipios/${departamentoId}`)
                .then(res => res.json())
                .then(data => {
                    municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
                    data.forEach(m => {
                        municipioSelect.innerHTML += `<option value="${m.id}">${m.nombre}</option>`;
                    });
                });
        }
    });

    // Aldeas
    document.getElementById('municipio').addEventListener('change', function () {
        const municipioId = this.value;
        const aldeaSelect = document.getElementById('aldea');
        aldeaSelect.innerHTML = '<option>Cargando...</option>';
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

 // Bloquear números y caracteres especiales en campos de solo texto
document.querySelectorAll('.solo-texto').forEach(input => {
    input.addEventListener('keypress', function (e) {
        const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]$/;
        if (!regex.test(e.key)) {
            e.preventDefault();
        }
    });
});


    // Bloquear negativos en campos numéricos
    document.querySelectorAll('.solo-numeros').forEach(input => {
        input.addEventListener('keydown', function (e) {
            if (e.key === '-') e.preventDefault();
        });
    });
</script>
<script>
    function siguienteTab(id) {
        const actual = document.querySelector('.tab-pane.active');
        const inputs = actual.querySelectorAll('input, select, textarea');
        let valido = true;

        inputs.forEach(input => {
            if (input.hasAttribute('required') && !input.value.trim()) {
                input.classList.add('is-invalid');
                valido = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!valido) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos requeridos vacíos',
                text: 'Por favor, complete todos los campos obligatorios antes de continuar.'
            });
            return;
        }

        // Cambiar pestaña
        document.querySelector(`[href="#${id}"]`).click();
    }
  
    function anteriorTab(id) {
        document.querySelector(`[href="#${id}"]`).click();
    }
  
</script>


@stop

