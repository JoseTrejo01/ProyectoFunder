@extends('adminlte::page')

@section('title', 'Crear Emprendimiento')

@section('content_header')
    <h1 class="text-dark font-weight-bold">Crear Emprendimiento</h1>
@stop

@section('content')
<form id="emprendimientoForm" action="{{ route('emprendimientos.store') }}" method="POST" role="form">
    @csrf

    {{-- ===============================================
         BLOQUE 1: SELECCIÓN DE ORGANIZACIÓN
    ================================================ --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="form-group">
                <label for="Id_Organizacion" class="font-weight-bold">Caja Rural <span class="text-danger">*</span></label>
                <select name="Id_Organizacion" id="Id_Organizacion"
                        class="form-control @error('Id_Organizacion') is-invalid @enderror"
                        required aria-required="true">
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

    {{-- ===============================================
         CONTENIDO DEL FORMULARIO
    ================================================ --}}
    <div id="form-content" style="display:none;" class="mt-3">

        {{-- ===============================================
             TABS
        ================================================ --}}
        <ul class="nav nav-tabs" id="emprendimientoTabs" role="tablist">

            <li class="nav-item">
                <a class="nav-link active font-weight-bold"
                   id="general-tab" data-toggle="tab" href="#general"
                   role="tab">Datos Generales</a>
            </li>

            <li class="nav-item">
                <a class="nav-link font-weight-bold"
                   id="socios-tab" data-toggle="tab" href="#socios"
                   role="tab">Socios y Empleos</a>
            </li>

            <li class="nav-item">
                <a class="nav-link font-weight-bold"
                   id="otros-tab" data-toggle="tab" href="#otros"
                   role="tab">Otros Datos</a>
            </li>
        </ul>

        <div class="tab-content p-3 border shadow-sm bg-white rounded-bottom" id="emprendimientoTabsContent">

            {{-- ===============================================
                 TAB 1: DATOS GENERALES
            ================================================ --}}
            <div class="tab-pane fade show active" id="general" role="tabpanel">

                <div class="form-group">
                    <label for="Caja_Rural" class="font-weight-bold">Nombre del Emprendimiento *</label>
                    <input type="text" name="Caja_Rural" id="Caja_Rural"
                           maxlength="40"
                           class="form-control solo-texto"
                           value="{{ old('Caja_Rural') }}"
                           required>
                    @error('Caja_Rural') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Departamento --}}
                <div class="form-group">
                    <label for="departamento" class="font-weight-bold">Departamento *</label>
                    <select name="Id_Departamento" id="departamento"
                            class="form-control" required>
                        <option value="">Seleccione un departamento</option>
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep->Id_Departamento }}"
                                {{ old('Id_Departamento') == $dep->Id_Departamento ? 'selected' : '' }}>
                                {{ $dep->Nombre_Departamento }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Municipio --}}
                <div class="form-group">
                    <label for="municipio" class="font-weight-bold">Municipio *</label>
                    <select name="Id_Municipio" id="municipio"
                            class="form-control @error('Id_Municipio') is-invalid @enderror"
                            required>
                        <option value="">Seleccione un municipio</option>
                    </select>
                    @error('Id_Municipio') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Aldea --}}
                <div class="form-group">
                    <label for="aldea" class="font-weight-bold">Aldea *</label>
                    <select name="aldea_id" id="aldea" class="form-control" required>
                        <option value="">Seleccione una aldea</option>
                    </select>
                </div>

                {{-- Comunidad --}}
                <div class="form-group">
                    <label for="Comunidad" class="font-weight-bold">Comunidad *</label>
                    <input type="text" name="Comunidad" id="Comunidad"
                           maxlength="40"
                           class="form-control solo-texto"
                           value="{{ old('Comunidad') }}"
                           required>
                </div>

                <div class="text-right mt-4">
                    <button type="button" class="btn btn-primary font-weight-bold" onclick="siguienteTab('socios')">Siguiente</button>
                </div>
            </div>

            {{-- ===============================================
                 TAB 2: SOCIOS Y EMPLEOS
            ================================================ --}}
            <div class="tab-pane fade" id="socios" role="tabpanel">

                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label class="font-weight-bold">Socios Hombres</label>
                        <input type="number" name="Socios_Hombres"
                               class="form-control solo-numeros"
                               value="{{ old('Socios_Hombres', 0) }}" min="0">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="font-weight-bold">Socias Mujeres</label>
                        <input type="number" name="Socios_Mujeres"
                               class="form-control solo-numeros"
                               value="{{ old('Socios_Mujeres', 0) }}" min="0">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="font-weight-bold">Total Socios</label>
                        <input type="number" id="Total_Socios"
                               class="form-control" readonly>
                    </div>

                </div>

                <div class="form-group d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary" onclick="anteriorTab('general')">Atrás</button>
                    <button type="button" class="btn btn-primary" onclick="siguienteTab('otros')">Siguiente</button>
                </div>
            </div>

            {{-- ===============================================
                 TAB 3: OTROS DATOS
            ================================================ --}}
            <div class="tab-pane fade" id="otros" role="tabpanel">

                <div class="form-group">
                    <label for="Tipo_Negocio" class="font-weight-bold">Tipo de Negocio *</label>
                    <textarea name="Tipo_Negocio" id="Tipo_Negocio"
                              class="form-control solo-texto"
                              rows="3" maxlength="300"
                              required>{{ old('Tipo_Negocio') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="Ventas_Trimestrales" class="font-weight-bold">Ventas Trimestrales (L)</label>
                    <input type="number" step="0.0000000001"
                           name="Ventas_Trimestrales"
                           class="form-control solo-numeros"
                           min="0"
                           value="{{ old('Ventas_Trimestrales', 0) }}">
                </div>

                <div class="form-group">
                    <label for="Fecha_Levantamiento" class="font-weight-bold">Fecha de Levantamiento *</label>
                    <input type="date" name="Fecha_Levantamiento" id="Fecha_Levantamiento"
                           class="form-control"
                           value="{{ old('Fecha_Levantamiento') }}"
                           required>
                </div>

                <div class="text-right mt-4">
                    <button type="submit" class="btn btn-success font-weight-bold">Guardar</button>
                    <a href="{{ route('emprendimientos.index') }}"
                       id="btnCancelar" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
</form>
@stop

{{-- ===============================================
     CSS MEJORADO
=============================================== --}}
@section('css')
<style>
    #emprendimientoTabs .nav-link {
        pointer-events: none !important;
        cursor: default;
        color: #6c757d;
    }
    #emprendimientoTabs .nav-link.active {
        pointer-events: auto !important;
        color: #000;
        font-weight: bold;
    }
</style>
@stop

{{-- ===============================================
     JS COMPLETO MEJORADO (TODO EN UN SOLO SCRIPT)
=============================================== --}}
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/* ========================================================
   INCIDENCIA 33: DETECTAR FORMULARIO SIN GUARDAR
======================================================== */
let formularioModificado = false;

document.querySelector('#emprendimientoForm').addEventListener('input', () => {
    formularioModificado = true;
});

window.addEventListener('beforeunload', function(e) {
    if (formularioModificado) {
        e.preventDefault();
        e.returnValue = 'Hay datos no guardados. ¿Está seguro de que desea salir?';
    }
});

document.querySelector('#btnCancelar').addEventListener('click', function(e) {
    if (formularioModificado) {
        e.preventDefault();
        Swal.fire({
            title: '¿Desea salir sin guardar?',
            text: 'Se perderán todos los datos ingresados.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, salir',
            cancelButtonText: 'No, quedarme'
        }).then((result) => {
            if (result.isConfirmed) {
                formularioModificado = false;
                window.location.href = this.href;
            }
        });
    }
});

/* ========================================================
   VALIDACIÓN solo-texto (MAYÚSCULAS + NO NÚMEROS)
======================================================== */
document.querySelectorAll('.solo-texto').forEach(input => {

    input.addEventListener('input', function() {
        let v = this.value;
        v = v.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s.,\-\/]/g, '');
        this.value = v.toUpperCase();
    });

    input.addEventListener('keypress', function(e) {
        const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s.,\-\/]$/;
        if (!regex.test(e.key)) e.preventDefault();
    });
});

/* ========================================================
   NUMÉRICOS SIN NEGATIVOS
======================================================== */
document.querySelectorAll('.solo-numeros').forEach(input => {
    input.addEventListener('keypress', e => {
        if (e.key === '-') e.preventDefault();
    });
});

/* ========================================================
   TOTALIZADORES
======================================================== */
function actualizarTotales() {
    const h = parseInt(document.querySelector('[name="Socios_Hombres"]').value || 0);
    const m = parseInt(document.querySelector('[name="Socios_Mujeres"]').value || 0);
    document.getElementById('Total_Socios').value = h + m;
}

document.querySelectorAll('input[type="number"]').forEach(e => {
    e.addEventListener('input', actualizarTotales);
});

actualizarTotales();

/* ========================================================
   MOSTRAR/OCULTAR FORMULARIO SEGÚN ORGANIZACIÓN
======================================================== */
const IdOrg = document.getElementById('Id_Organizacion');
const formContent = document.getElementById('form-content');

function toggleForm() {
    formContent.style.display = IdOrg.value ? 'block' : 'none';
}
toggleForm();
IdOrg.addEventListener('change', toggleForm);

/* ========================================================
   CARGAR MUNICIPIOS
======================================================== */
document.getElementById('departamento').addEventListener('change', function() {
    const id = this.value;
    const municipio = document.getElementById('municipio');
    const aldea = document.getElementById('aldea');

    municipio.innerHTML = '<option>Cargando...</option>';
    aldea.innerHTML = '<option>Seleccione una aldea</option>';

    fetch(`/municipios/${id}`)
        .then(r => r.json())
        .then(data => {
            municipio.innerHTML = '<option value="">Seleccione un municipio</option>';
            data.forEach(d => municipio.innerHTML += `<option value="${d.id}">${d.nombre}</option>`);
        });
});

/* ========================================================
   CARGAR ALDEAS
======================================================== */
document.getElementById('municipio').addEventListener('change', function() {
    const id = this.value;
    const aldea = document.getElementById('aldea');

    aldea.innerHTML = '<option>Cargando...</option>';

    fetch(`/aldeas/${id}`)
        .then(r => r.json())
        .then(data => {
            aldea.innerHTML = '<option value="">Seleccione una aldea</option>';
            data.forEach(d => aldea.innerHTML += `<option value="${d.id}">${d.nombre}</option>`);
        });
});

/* ========================================================
   NAVEGACIÓN ENTRE TABS
======================================================== */
function siguienteTab(id) {
    const actual = document.querySelector('.tab-pane.active');
    const fields = actual.querySelectorAll('input[required], select[required], textarea[required]');

    let valido = true;
    fields.forEach(f => {
        if (!f.checkValidity()) {
            f.reportValidity();
            f.classList.add('is-invalid');
            valido = false;
        } else {
            f.classList.remove('is-invalid');
        }
    });

    if (!valido) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos vacíos',
            text: 'Debe completar los campos obligatorios.'
        });
        return;
    }

    $(`#emprendimientoTabs a[href="#${id}"]`).tab('show');
}

function anteriorTab(id) {
    $(`#emprendimientoTabs a[href="#${id}"]`).tab('show');
}

window.siguienteTab = siguienteTab;
window.anteriorTab = anteriorTab;

</script>
@stop
