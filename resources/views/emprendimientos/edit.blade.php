@extends('adminlte::page')

@section('title', 'Editar Emprendimiento')

@section('content_header')
    <h1 class="text-dark font-weight-bold">Editar Emprendimiento</h1>
@stop

@section('content')
<form id="emprendimientoForm" 
      action="{{ route('emprendimientos.update', ['emprendimiento' => $emprendimiento->Id_Emprendimiento]) }}"
      method="POST" role="form">
    @csrf
    @method('PUT')

    {{-- ===============================================
         SELECT ORGANIZACIÓN
    ================================================ --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="form-group">
                <label for="Id_Organizacion" class="font-weight-bold">Caja Rural *</label>
                <select name="Id_Organizacion" id="Id_Organizacion"
                        class="form-control @error('Id_Organizacion') is-invalid @enderror"
                        required>
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

    {{-- ===============================================
         TABS
    ================================================ --}}
    <ul class="nav nav-tabs mt-3" id="emprendimientoTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active font-weight-bold" id="general-tab" data-toggle="tab" href="#general" role="tab">
                Datos Generales
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" id="socios-tab" data-toggle="tab" href="#socios" role="tab">
                Socios y Empleos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" id="otros-tab" data-toggle="tab" href="#otros" role="tab">
                Otros Datos
            </a>
        </li>
    </ul>

    {{-- ===============================================
         CONTENIDO TABS
    ================================================ --}}
    <div class="tab-content p-3 border shadow-sm bg-white rounded-bottom">

        {{-- TAB GENERAL --}}
        <div class="tab-pane fade show active" id="general" role="tabpanel">

            <div class="form-group">
                <label for="Caja_Rural" class="font-weight-bold">Nombre del Emprendimiento *</label>
                <input type="text" id="Caja_Rural" name="Caja_Rural"
                       maxlength="40"
                       class="form-control solo-texto"
                       value="{{ old('Caja_Rural', $emprendimiento->Caja_Rural) }}"
                       required>
                @error('Caja_Rural') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Departamento --}}
            <div class="form-group">
                <label for="departamento" class="font-weight-bold">Departamento *</label>
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

            {{-- Municipio --}}
            <div class="form-group">
                <label for="municipio" class="font-weight-bold">Municipio *</label>
                <select name="Id_Municipio" id="municipio" class="form-control" required>
                    <option value="{{ $emprendimiento->municipio->id }}" selected>
                        {{ $emprendimiento->municipio->nombre }}
                    </option>
                </select>
            </div>

            {{-- Aldea --}}
            <div class="form-group">
                <label for="aldea" class="font-weight-bold">Aldea *</label>
                <select name="aldea_id" id="aldea" class="form-control" required>
                    <option value="{{ $emprendimiento->aldea->id ?? '' }}" selected>
                        {{ $emprendimiento->aldea->nombre ?? 'Seleccione una aldea' }}
                    </option>
                </select>
            </div>

            {{-- Comunidad --}}
            <div class="form-group">
                <label for="Comunidad" class="font-weight-bold">Comunidad *</label>
                <input type="text" id="Comunidad" name="Comunidad"
                       maxlength="40"
                       class="form-control solo-texto"
                       value="{{ old('Comunidad', $emprendimiento->Comunidad) }}"
                       required>
            </div>

            <div class="text-right mt-4">
                <button type="button" class="btn btn-primary" onclick="siguienteTab('socios')">Siguiente</button>
            </div>
        </div>

        {{-- TAB SOCIOS --}}
        <div class="tab-pane fade" id="socios" role="tabpanel">

            <div class="form-row">

                <div class="form-group col-md-4">
                    <label class="font-weight-bold">Socios Hombres</label>
                    <input type="number" name="Socios_Hombres"
                           class="form-control solo-numeros"
                           min="0"
                           value="{{ old('Socios_Hombres', $emprendimiento->Socios_Hombres) }}">
                </div>

                <div class="form-group col-md-4">
                    <label class="font-weight-bold">Socias Mujeres</label>
                    <input type="number" name="Socios_Mujeres"
                           class="form-control solo-numeros"
                           min="0"
                           value="{{ old('Socios_Mujeres', $emprendimiento->Socios_Mujeres) }}">
                </div>

                <div class="form-group col-md-4">
                    <label class="font-weight-bold">Total Socios</label>
                    <input type="number" id="Total_Socios" class="form-control" readonly>
                </div>
            </div>

            <div class="form-group d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary" onclick="anteriorTab('general')">Atrás</button>
                <button type="button" class="btn btn-primary" onclick="siguienteTab('otros')">Siguiente</button>
            </div>
        </div>

        {{-- TAB OTROS --}}
        <div class="tab-pane fade" id="otros" role="tabpanel">

            <div class="form-group">
                <label for="Tipo_Negocio" class="font-weight-bold">Tipo de Negocio *</label>
                <textarea name="Tipo_Negocio" id="Tipo_Negocio"
                          class="form-control solo-texto"
                          rows="3"
                          required>{{ old('Tipo_Negocio', $emprendimiento->Tipo_Negocio) }}</textarea>
            </div>

            <div class="form-group">
                <label for="Ventas_Trimestrales" class="font-weight-bold">Ventas Trimestrales (L)</label>
                <input type="number" step="0.0000000001"
                       name="Ventas_Trimestrales"
                       class="form-control solo-numeros"
                       min="0"
                       value="{{ old('Ventas_Trimestrales', $emprendimiento->Ventas_Trimestrales) }}">
            </div>

            <div class="form-group">
                <label for="Fecha_Levantamiento" class="font-weight-bold">Fecha de Levantamiento *</label>
                <input type="date" name="Fecha_Levantamiento" id="Fecha_Levantamiento"
                       class="form-control"
                       value="{{ old('Fecha_Levantamiento', \Carbon\Carbon::parse($emprendimiento->Fecha_Levantamiento)->format('Y-m-d')) }}"
                       required>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('emprendimientos.index') }}" id="btnCancelar" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </div>
</form>
@stop

{{-- ===============================================
         CSS
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
         JS COMPLETO OPTIMIZADO (TODO EN UNO)
=============================================== --}}
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/* ========================================================
   TOTALIZADORES
======================================================== */
function actualizarTotales() {
    const h = parseInt(document.querySelector('[name="Socios_Hombres"]').value || 0);
    const m = parseInt(document.querySelector('[name="Socios_Mujeres"]').value || 0);
    document.getElementById('Total_Socios').value = h + m;
}
document.querySelectorAll('input[type="number"]').forEach(i => i.addEventListener('input', actualizarTotales));
actualizarTotales();

/* ========================================================
   BLOQUEAR NEGATIVOS
======================================================== */
document.querySelectorAll('.solo-numeros').forEach(i => {
    i.addEventListener('keypress', e => {
        if (e.key === '-') e.preventDefault();
    });
});

/* ========================================================
   CAMPOS SOLO TEXTO
======================================================== */
document.querySelectorAll('.solo-texto').forEach(input => {

    input.addEventListener('input', function() {
        let v = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s.,\-\/]/g, '');
        this.value = v.toUpperCase();
    });

    input.addEventListener('keypress', function(e) {
        const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s.,\-\/]$/;
        if (!regex.test(e.key)) e.preventDefault();
    });
});

/* ========================================================
   CARGA - MUNICIPIOS Y ALDEAS
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
            municipio.innerHTML = `<option value="">Seleccione un municipio</option>`;
            data.forEach(d => municipio.innerHTML += `<option value="${d.id}">${d.nombre}</option>`);
        });
});

document.getElementById('municipio').addEventListener('change', function() {
    const id = this.value;
    const aldea = document.getElementById('aldea');

    aldea.innerHTML = '<option>Cargando...</option>';

    fetch(`/aldeas/${id}`)
        .then(r => r.json())
        .then(data => {
            aldea.innerHTML = `<option value="">Seleccione una aldea</option>`;
            data.forEach(d => aldea.innerHTML += `<option value="${d.id}">${d.nombre}</option>`);
        });
});

/* ========================================================
   CARGA AUTOMÁTICA INICIAL
======================================================== */
window.addEventListener('DOMContentLoaded', () => {
    const depId = document.getElementById('departamento').value;
    const municipioIdActual = '{{ $emprendimiento->Id_Municipio }}';
    const aldeaIdActual = '{{ $emprendimiento->Id_Aldea ?? '' }}';

    if (!depId) return;

    fetch(`/municipios/${depId}`)
        .then(r => r.json())
        .then(data => {
            const municipio = document.getElementById('municipio');
            municipio.innerHTML = '<option value="">Seleccione un municipio</option>';

            data.forEach(m =>
                municipio.innerHTML += `<option value="${m.id}" ${m.id == municipioIdActual ? 'selected' : ''}>
                    ${m.nombre}
                </option>`
            );

            if (municipioIdActual) {
                fetch(`/aldeas/${municipioIdActual}`)
                    .then(r => r.json())
                    .then(data => {
                        const aldea = document.getElementById('aldea');
                        aldea.innerHTML = '<option value="">Seleccione una aldea</option>';
                        data.forEach(a =>
                            aldea.innerHTML += `<option value="${a.id}" ${a.id == aldeaIdActual ? 'selected' : ''}>
                                ${a.nombre}
                            </option>`
                        );
                    });
            }
        });
});

/* ========================================================
   NAVEGACIÓN ENTRE TABS
======================================================== */
function siguienteTab(tab) {
    $(`#emprendimientoTabs a[href="#${tab}"]`).tab('show');
}

function anteriorTab(tab) {
    $(`#emprendimientoTabs a[href="#${tab}"]`).tab('show');
}

window.siguienteTab = siguienteTab;
window.anteriorTab = anteriorTab;
</script>
@stop
