@extends('adminlte::page')

@section('title', 'Nuevo Socio')

@section('content_header')
<h1 id="titulo-nuevo-socio" class="fw-bold">Nuevo Socio</h1>
@stop

@section('content')
<div role="main" aria-labelledby="titulo-nuevo-socio">

    {{-- Alertas de error accesibles --}}
    @if ($errors->any())
        <div class="alert alert-danger" role="alert" aria-live="assertive" tabindex="0">
            <strong>¡Ups!</strong> Hay problemas con los datos ingresados.
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        {{-- SweetAlert accesible --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Errores encontrados',
                    html: `<ul style='text-align:left'>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>`,
                    confirmButtonColor: '#B71C1C',
                });
            });
        </script>
    @endif

    {{-- Éxito --}}
    @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: @json(session('success')),
                    confirmButtonColor: '#1B5E20',
                    timer: 2500,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    <form action="{{ route('socios.store') }}"
          method="POST"
          autocomplete="off"
          aria-describedby="ayuda-socio-form">

        @csrf
        <input type="hidden" name="estado" value="1">

        <p id="ayuda-socio-form" class="visually-hidden">
            Formulario dividido en tres pestañas: datos personales, ubicación y datos adicionales.
        </p>

        {{-- Tabs accesibles --}}
        <ul class="nav nav-tabs mb-3" id="socioTab" role="tablist">

            <li class="nav-item" role="presentation">
                <button class="nav-link active"
                        id="datos-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#datos"
                        role="tab"
                        aria-controls="datos"
                        aria-selected="true">
                    Datos personales
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link"
                        id="ubicacion-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#ubicacion"
                        role="tab"
                        aria-controls="ubicacion"
                        aria-selected="false">
                    Ubicación
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link"
                        id="info-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#info"
                        role="tab"
                        aria-controls="info"
                        aria-selected="false">
                    Información adicional
                </button>
            </li>

        </ul>

        <div class="tab-content" id="socioTabContent">

            {{-- DATOS PERSONALES --}}
            <div class="tab-pane fade show active p-2"
                 id="datos"
                 role="tabpanel"
                 aria-labelledby="datos-tab">

                <fieldset class="border p-3 rounded">
                    <legend class="fw-semibold px-2">Datos personales</legend>

                    <div class="row">
                        {{-- Organización --}}
                        <div class="col-md-4">
                            <label for="Id_Organizacion" class="form-label fw-semibold">Organización</label>
                            <select id="Id_Organizacion"
                                    name="Id_Organizacion"
                                    class="form-control"
                                    required>
                                <option value="">Seleccione una organización</option>
                                @foreach($organizaciones as $org)
                                    <option value="{{ $org->Id_Organizacion }}"
                                        data-aldea="{{ $org->aldea?->Nombre_Aldea }}"
                                        data-municipio="{{ $org->aldea?->municipio?->Nombre_Municipio }}"
                                        data-departamento="{{ $org->aldea?->municipio?->departamento?->Nombre_Departamento }}"
                                        {{ old('Id_Organizacion') == $org->Id_Organizacion ? 'selected' : '' }}>
                                        {{ $org->Nombre_Organizacion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nombre --}}
                        <div class="col-md-4">
                            <label for="nombre_beneficiario" class="form-label fw-semibold">Nombre completo</label>
                            <input type="text"
                                   id="nombre_beneficiario"
                                   name="Nombre_Beneficiario"
                                   class="form-control"
                                   maxlength="40"
                                   required
                                   value="{{ old('Nombre_Beneficiario') }}">
                        </div>

                        {{-- DNI --}}
                        <div class="col-md-4">
                            <label for="dni" class="form-label fw-semibold">DNI</label>
                            <input type="text"
                                   id="dni"
                                   name="DNI"
                                   class="form-control"
                                   minlength="13"
                                   maxlength="13"
                                   pattern="\d{13}"
                                   title="Debe tener 13 dígitos numéricos"
                                   required
                                   value="{{ old('DNI') }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        {{-- Género --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Género</label>
                            <select name="genero" class="form-control" required>
                                <option value="">Seleccione</option>
                                <option value="M" {{ old('genero') === 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('genero') === 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>

                        {{-- Fecha nacimiento --}}
                        <div class="col-md-4">
                            <label for="fecha_nacimiento" class="form-label fw-semibold">Fecha de nacimiento</label>
                            <input type="date"
                                   id="fecha_nacimiento"
                                   name="fecha_nacimiento"
                                   class="form-control"
                                   value="{{ old('fecha_nacimiento') }}">
                        </div>

                        {{-- Edad --}}
                        <div class="col-md-4">
                            <label for="edad" class="form-label fw-semibold">Edad</label>
                            <input type="number"
                                   id="edad"
                                   name="edad"
                                   class="form-control"
                                   readonly
                                   min="15" max="100"
                                   value="{{ old('edad') }}">
                        </div>
                    </div>

                    <div class="row mt-3">

                        {{-- Estado civil --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Estado civil</label>
                            <select name="estado_civil" class="form-control">
                                <option value="">Seleccione</option>
                                @foreach(['Soltero(a)','Casado(a)','Unión Libre','Viudo(a)'] as $ec)
                                    <option value="{{ $ec }}" {{ old('estado_civil')===$ec ? 'selected':'' }}>
                                        {{ $ec }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Etnia --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Etnia</label>
                            <select name="etnia" class="form-control">
                                <option value="">Seleccione</option>
                                @foreach(['Lenca','Garífuna','Miskito','Tawahka','Tolupan','Pech','Maya Chortí','Negro de habla inglesa o Creole','Mestizo'] as $etnia)
                                    <option value="{{ $etnia }}" {{ old('etnia')==$etnia ? 'selected':'' }}>
                                        {{ $etnia }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nivel educativo --}}
                        <div class="col-md-4">
                            <label for="nivel_educativo" class="form-label fw-semibold">Nivel educativo</label>
                            <select id="nivel_educativo"
                                    name="nivel_educativo"
                                    class="form-control">
                                <option value="">Seleccione</option>
                                @foreach(['Sin estudios','Educación Prebásica','Primaria','Ciclo Común','Diversificado','Universitario','Post grado','Doctorado'] as $nivel)
                                    <option value="{{ $nivel }}" {{ old('nivel_educativo')==$nivel ? 'selected':'' }}>
                                        {{ $nivel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Años cursados --}}
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="anios_educacion" class="form-label fw-semibold">
                                Años estimados cursados
                            </label>
                            <input type="text"
                                   id="anios_educacion"
                                   name="anios_educacion"
                                   class="form-control"
                                   readonly
                                   value="{{ old('anios_educacion') }}">
                        </div>

                        {{-- Medio comunicación --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Medio de comunicación</label>
                            <select name="medio_comunicacion" class="form-control">
                                <option value="">Seleccione</option>
                                @foreach(['Teléfono','Tablet','Computadora'] as $m)
                                    <option value="{{ $m }}" {{ old('medio_comunicacion')==$m ? 'selected':'' }}>
                                        {{ $m }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Teléfono --}}
                        <div class="col-md-4">
                            <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                            <input type="text"
                                   id="telefono"
                                   name="Telefono"
                                   class="form-control"
                                   maxlength="9"
                                   pattern="\d{4}-\d{4}"
                                   title="Formato: 1234-5678"
                                   required
                                   value="{{ old('Telefono') }}">
                            <div class="form-text">Formato: 1234-5678</div>
                        </div>
                    </div>

                </fieldset>
            </div>

            {{-- UBICACIÓN --}}
            <div class="tab-pane fade p-2"
                 id="ubicacion"
                 role="tabpanel"
                 aria-labelledby="ubicacion-tab">

                <fieldset class="border p-3 rounded">
                    <legend class="fw-semibold px-2">Ubicación</legend>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="departamento" class="form-label fw-semibold">Departamento</label>
                            <input type="text"
                                   id="departamento"
                                   name="departamento"
                                   class="form-control"
                                   readonly
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label for="municipio" class="form-label fw-semibold">Municipio</label>
                            <input type="text"
                                   id="municipio"
                                   name="municipio"
                                   class="form-control"
                                   readonly
                                   required>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <label for="comunidad" class="form-label fw-semibold">Comunidad / Aldea</label>
                            <input type="text"
                                   id="comunidad"
                                   name="comunidad"
                                   class="form-control"
                                   readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="direccion" class="form-label fw-semibold">Dirección</label>
                            <input type="text"
                                   id="direccion"
                                   name="direccion"
                                   class="form-control"
                                   maxlength="40"
                                   pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ0-9 ]{1,40}"
                                   title="Solo letras, números y espacios. Máximo 40 caracteres."
                                   value="{{ old('direccion') }}"
                                   required>
                        </div>

                    </div>
                </fieldset>

            </div>

            {{-- INFORMACIÓN ADICIONAL --}}
            <div class="tab-pane fade p-2"
                 id="info"
                 role="tabpanel"
                 aria-labelledby="info-tab">

                <fieldset class="border p-3 rounded">
                    <legend class="fw-semibold px-2">Información adicional</legend>

                    <h5 class="fw-bold">Actividades económicas</h5>

                    <div id="actividades-container"></div>

                    <button type="button"
                            id="agregar-actividad"
                            class="btn btn-sm fw-semibold mb-2"
                            style="background-color:#0D47A1; color:white;">
                        Registrar actividad
                    </button>

                    <table class="table table-bordered table-sm mt-2" id="tabla-actividades" style="display:none;">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Tipo</th>
                                <th>Rubro</th>
                                <th>Unidad</th>
                                <th>Cantidad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    {{-- Tipo de socio --}}
                    <div class="form-group mt-3">
                        <label for="Tipo_De_Socio" class="form-label fw-semibold">Tipo de socio</label>
                        <select id="Tipo_De_Socio"
                                name="Tipo_De_Socio"
                                class="form-control"
                                required>
                            <option value="">Seleccione</option>
                            <option value="Socio">Socio</option>
                            <option value="Cliente">Cliente</option>
                        </select>
                    </div>

                    {{-- Tipo de cargo --}}
                    <div class="form-group mt-3" id="tipo_cargo_group" style="display:none;">
                        <label class="form-label fw-semibold">Tipo de cargo</label>
                        <select name="Tipo_Cargo" class="form-control">
                            <option value="">Seleccione</option>
                            @foreach($cargosDirectivos as $cargo)
                                <option value="{{ $cargo }}">{{ $cargo }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Categoría --}}
                    <div class="form-group mt-3" id="categoria_group" style="display:none;">
                        <label for="categoria" class="form-label fw-semibold">Categoría o descripción</label>
                        <input type="text"
                               id="categoria"
                               name="categoria"
                               class="form-control"
                               maxlength="50"
                               pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]{1,50}"
                               value="{{ old('categoria') }}">
                    </div>

                </fieldset>

            </div>

        </div>

        {{-- BOTONES FINALES --}}
        <button type="submit"
                class="btn fw-semibold mt-3"
                style="background-color:#1B5E20; color:white;">
            Guardar
        </button>

        <a href="{{ route('socios.index') }}"
           class="btn mt-3 fw-semibold"
           style="background-color:#424242; color:white;">
            Cancelar
        </a>

    </form>

</div>
@stop

@section('js')
{{-- todo tu JS se mantiene EXACTO --}}
@parent
@endsection
