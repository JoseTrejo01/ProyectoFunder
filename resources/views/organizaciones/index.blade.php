@extends('adminlte::page')

@section('title', 'Gestión de Organizaciones')

@section('css')
<style>
    :root {
        --funder-azul: #005CB9;
        --funder-azul-hover: #00448b;
        --funder-amarillo: #FFCC00;
        --funder-gris-oscuro: #1F2933;
        --funder-gris-borde: #D1D5DB;
    }

    /* Botones tema FUNDER */
    .btn-funder-primary {
        background-color: var(--funder-azul);
        border-color: var(--funder-azul);
        color: #ffffff;
        font-weight: 500;
    }

    .btn-funder-primary:hover {
        background-color: var(--funder-azul-hover);
        border-color: var(--funder-azul-hover);
        color: #ffffff;
    }

    .btn-funder-outline-info {
        background-color: #ffffff;
        border-color: var(--funder-azul);
        color: var(--funder-azul);
        font-weight: 500;
    }

    .btn-funder-outline-info:hover {
        background-color: var(--funder-azul);
        border-color: var(--funder-azul);
        color: #ffffff;
    }

    .btn-funder-outline-danger {
        background-color: #ffffff;
        border-color: #B91C1C;
        color: #B91C1C;
        font-weight: 500;
    }

    .btn-funder-outline-danger:hover {
        background-color: #B91C1C;
        border-color: #B91C1C;
        color: #ffffff;
    }

    .btn-icon-xs {
        font-size: 0.85rem;
        line-height: 1;
    }

    /* Tabla accesible con buen contraste */
    .table-funder thead {
        background-color: var(--funder-azul);
        color: #ffffff;
    }

    .table-funder thead th {
        border-color: var(--funder-azul-hover);
        white-space: nowrap;
    }

    .table-funder tbody td,
    .table-funder tbody th {
        vertical-align: middle;
    }

    /* Badges de estado con contraste */
    .badge-estado-activo {
        background-color: var(--funder-azul);
        color: #ffffff;
        font-weight: 600;
        letter-spacing: 0.03em;
    }

    .badge-estado-inactivo {
        background-color: #6B7280;
        color: #ffffff;
        font-weight: 600;
        letter-spacing: 0.03em;
    }

    /* Modal: encabezado y bordes */
    .modal-header {
        background-color: #F3F4F6;
        border-bottom: 1px solid var(--funder-gris-borde);
    }

    .modal-title {
        color: var(--funder-gris-oscuro);
        font-weight: 600;
    }

    /* Asterisco de campos requeridos */
    .required-asterisk {
        color: #B91C1C;
        font-weight: 700;
    }

    /* Enfoque accesible (focus-visible) */
    .btn:focus-visible,
    .form-control:focus-visible,
    select.form-control:focus-visible {
        outline: 2px solid var(--funder-amarillo);
        outline-offset: 2px;
        box-shadow: none;
    }

    /* Campo en error (puedes usarlo desde el backend si quieres luego) */
    .form-control.is-invalid {
        border-color: #B91C1C;
    }

    /* Alineación de acciones en la tabla */
    .acciones-organizacion {
        height: 32px;
    }
</style>
@endsection

@section('content')
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: @json(session('success')),
                    confirmButtonColor: '#005CB9',
                    timer: 2500,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    <section class="container" aria-labelledby="titulo-organizaciones">
        <div class="d-flex align-items-center gap-2 mb-3">
            <h1 id="titulo-organizaciones" class="h3 mb-0">
                Gestión de organizaciones
            </h1>
        </div>

        <div
            class="d-flex align-items-center gap-2 mb-3 flex-wrap"
            aria-label="Acciones principales de organizaciones"
        >
            <button
                type="button"
                class="btn btn-funder-primary"
                data-bs-toggle="modal"
                data-bs-target="#modalRegistrarOrg"
                aria-haspopup="dialog"
                aria-controls="modalRegistrarOrg"
            >
                <i class="fas fa-plus-circle" aria-hidden="true"></i>
                <span>Registrar</span>
            </button>

            <a
                href="{{ route('organizaciones.mapa') }}"
                class="btn btn-funder-outline-info"
                title="Ver mapa de Cajas Rurales"
            >
                <i class="fas fa-map-marked-alt" aria-hidden="true"></i>
                <span>Ver mapa</span>
            </a>

            <a
                href="{{ route('organizaciones.exportar.pdf') }}"
                class="btn btn-funder-outline-danger"
                title="Exportar listado de organizaciones a PDF"
            >
                <i class="fas fa-file-pdf" aria-hidden="true"></i>
                <span>Exportar PDF</span>
            </a>
        </div>

        <div class="table-responsive">
            <table
                id="tabla-organizaciones"
                class="table table-bordered table-hover shadow-sm table-funder"
                aria-describedby="tabla-organizaciones-descripcion"
            >
                <caption id="tabla-organizaciones-descripcion" class="sr-only">
                    Listado de organizaciones con su ubicación, estado y acciones de edición o inactivación.
                </caption>
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Departamento</th>
                        <th scope="col">Municipio</th>
                        <th scope="col">Aldea</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizaciones as $org)
                        <tr>
                            <th scope="row">{{ $org->Id_Organizacion }}</th>
                            <td>{{ $org->Nombre_Organizacion }}</td>
                            <td>
                                {{ $org->aldea && $org->aldea->municipio && $org->aldea->municipio->departamento
                                    ? $org->aldea->municipio->departamento->Nombre_Departamento
                                    : '' }}
                            </td>
                            <td>{{ $org->aldea && $org->aldea->municipio ? $org->aldea->municipio->Nombre_Municipio : '' }}</td>
                            <td>{{ $org->aldea ? $org->aldea->Nombre_Aldea : '' }}</td>
                            <td>
                                @if($org->Estado_Organizacion == 'ACTIVO')
                                    <span
                                        class="badge badge-estado-activo"
                                        aria-label="Organización activa"
                                    >
                                        ACTIVO
                                    </span>
                                @else
                                    <span
                                        class="badge badge-estado-inactivo"
                                        aria-label="Organización inactiva"
                                    >
                                        INACTIVO
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1 acciones-organizacion">
                                    {{-- BOTÓN EDITAR --}}
                                    <button
                                        type="button"
                                        class="btn btn-primary btn-xs p-1 btn-icon-xs"
                                        style="background-color: var(--funder-azul); border-color: var(--funder-azul);"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarOrg{{ $org->Id_Organizacion }}"
                                        data-id-organizacion="{{ $org->Id_Organizacion }}"
                                        title="Editar organización {{ $org->Nombre_Organizacion }}"
                                        aria-haspopup="dialog"
                                        aria-controls="modalEditarOrg{{ $org->Id_Organizacion }}"
                                    >
                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                        <span class="sr-only">Editar</span>
                                    </button>

                                    {{-- MODAL EDITAR --}}
                                    <div
                                        class="modal fade"
                                        id="modalEditarOrg{{ $org->Id_Organizacion }}"
                                        tabindex="-1"
                                        aria-labelledby="modalEditarOrgLabel{{ $org->Id_Organizacion }}"
                                        aria-hidden="true"
                                        role="dialog"
                                    >
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <form
                                                    method="POST"
                                                    action="{{ route('organizaciones.update', $org->Id_Organizacion) }}"
                                                    data-form-id="{{ $org->Id_Organizacion }}"
                                                    aria-describedby="ayuda-campos-obligatorios-edit-{{ $org->Id_Organizacion }}"
                                                >
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-header">
                                                        <h5
                                                            class="modal-title"
                                                            id="modalEditarOrgLabel{{ $org->Id_Organizacion }}"
                                                        >
                                                            Editar organización
                                                        </h5>
                                                        <button
                                                            type="button"
                                                            class="btn-close btn-cerrar-modal"
                                                            data-bs-dismiss="modal"
                                                            data-modal-id="modalEditarOrg{{ $org->Id_Organizacion }}"
                                                            aria-label="Cerrar"
                                                        ></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p
                                                            id="ayuda-campos-obligatorios-edit-{{ $org->Id_Organizacion }}"
                                                            class="text-muted small"
                                                        >
                                                            Los campos marcados con
                                                            <span class="required-asterisk">*</span>
                                                            son obligatorios.
                                                        </p>

                                                        {{-- NOMBRE --}}
                                                        <div class="mb-3">
                                                            <label
                                                                for="Nombre_Organizacion_{{ $org->Id_Organizacion }}"
                                                                class="form-label"
                                                            >
                                                                Nombre de la organización
                                                                <span class="required-asterisk" aria-hidden="true">*</span>
                                                            </label>
                                                            <input
                                                                type="text"
                                                                class="form-control form-control-changed"
                                                                name="Nombre_Organizacion"
                                                                id="Nombre_Organizacion_{{ $org->Id_Organizacion }}"
                                                                value="{{ $org->Nombre_Organizacion }}"
                                                                maxlength="40"
                                                                pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ\s]{1,40}"
                                                                title="Solo letras y espacios, máximo 40 caracteres"
                                                                required
                                                                autocomplete="organization"
                                                            >
                                                        </div>

                                                        {{-- DEPARTAMENTO (OCULTO) --}}
                                                        <input
                                                            type="hidden"
                                                            name="departamento"
                                                            value="{{ $org->aldea->municipio->Id_Departamento ?? '' }}"
                                                        >

                                                        {{-- MUNICIPIO / ALDEA --}}
                                                        <div class="row" aria-label="Ubicación administrativa">
                                                            <div class="col-md-6 mb-3">
                                                                <label
                                                                    for="municipio_{{ $org->Id_Organizacion }}"
                                                                    class="form-label"
                                                                >
                                                                    Municipio
                                                                    <span class="required-asterisk" aria-hidden="true">*</span>
                                                                </label>
                                                                <select
                                                                    name="municipio"
                                                                    id="municipio_{{ $org->Id_Organizacion }}"
                                                                    class="form-control form-control-changed"
                                                                    required
                                                                >
                                                                    <option value="">Seleccione un municipio</option>
                                                                    @if(
                                                                        $org->aldea &&
                                                                        $org->aldea->municipio &&
                                                                        isset($municipiosPorDepto[$org->aldea->municipio->Id_Departamento])
                                                                    )
                                                                        @foreach($municipiosPorDepto[$org->aldea->municipio->Id_Departamento] as $muni)
                                                                            <option
                                                                                value="{{ $muni->Id_Municipio }}"
                                                                                {{ $org->aldea->municipio->Id_Municipio == $muni->Id_Municipio ? 'selected' : '' }}
                                                                            >
                                                                                {{ $muni->Nombre_Municipio }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label
                                                                    for="Nombre_Aldea_{{ $org->Id_Organizacion }}"
                                                                    class="form-label"
                                                                >
                                                                    Aldea
                                                                    <span class="required-asterisk" aria-hidden="true">*</span>
                                                                </label>
                                                                <input
                                                                    type="text"
                                                                    class="form-control form-control-changed"
                                                                    name="Nombre_Aldea"
                                                                    id="Nombre_Aldea_{{ $org->Id_Organizacion }}"
                                                                    value="{{ $org->aldea ? $org->aldea->Nombre_Aldea : '' }}"
                                                                    maxlength="40"
                                                                    pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ\s]{1,40}"
                                                                    title="Solo letras y espacios, máximo 40 caracteres"
                                                                    required
                                                                >
                                                            </div>
                                                        </div>

                                                        {{-- ESTADO --}}
                                                        <div class="mb-3">
                                                            <label
                                                                for="Estado_Organizacion_{{ $org->Id_Organizacion }}"
                                                                class="form-label"
                                                            >
                                                                Estado de la organización
                                                                <span class="required-asterisk" aria-hidden="true">*</span>
                                                            </label>
                                                            <select
                                                                name="Estado_Organizacion"
                                                                id="Estado_Organizacion_{{ $org->Id_Organizacion }}"
                                                                class="form-control form-control-changed"
                                                                required
                                                            >
                                                                <option
                                                                    value="ACTIVO"
                                                                    {{ $org->Estado_Organizacion == 'ACTIVO' ? 'selected' : '' }}
                                                                >
                                                                    ACTIVO
                                                                </option>
                                                                <option
                                                                    value="INACTIVO"
                                                                    {{ $org->Estado_Organizacion == 'INACTIVO' ? 'selected' : '' }}
                                                                >
                                                                    INACTIVO
                                                                </option>
                                                            </select>
                                                        </div>

                                                        {{-- MAPA / COORDENADAS --}}
                                                        <fieldset class="mb-3">
                                                            <legend class="h6">Ubicación geográfica</legend>
                                                            <p class="text-muted small">
                                                                Haga clic en el mapa o ingrese la latitud y longitud manualmente.
                                                            </p>

                                                            <div
                                                                id="map_edit_{{ $org->Id_Organizacion }}"
                                                                style="height: 250px;"
                                                                role="region"
                                                                aria-label="Mapa de ubicación de la organización"
                                                            ></div>

                                                            <div class="row mt-3">
                                                                <div class="col">
                                                                    <label
                                                                        for="coordenada_y_edit_{{ $org->Id_Organizacion }}"
                                                                        class="form-label"
                                                                    >
                                                                        Latitud
                                                                        <span class="required-asterisk" aria-hidden="true">*</span>
                                                                    </label>
                                                                    <input
                                                                        type="number"
                                                                        step="0.00000001"
                                                                        min="-90"
                                                                        max="90"
                                                                        name="coordenada_y"
                                                                        id="coordenada_y_edit_{{ $org->Id_Organizacion }}"
                                                                        class="form-control form-control-changed"
                                                                        value="{{ $coordenadas[$org->aldea->municipio->Id_Municipio]->coordenada_y ?? '' }}"
                                                                        required
                                                                    >
                                                                </div>
                                                                <div class="col">
                                                                    <label
                                                                        for="coordenada_x_edit_{{ $org->Id_Organizacion }}"
                                                                        class="form-label"
                                                                    >
                                                                        Longitud
                                                                        <span class="required-asterisk" aria-hidden="true">*</span>
                                                                    </label>
                                                                    <input
                                                                        type="number"
                                                                        step="0.00000001"
                                                                        min="-180"
                                                                        max="180"
                                                                        name="coordenada_x"
                                                                        id="coordenada_x_edit_{{ $org->Id_Organizacion }}"
                                                                        class="form-control form-control-changed"
                                                                        value="{{ $coordenadas[$org->aldea->municipio->Id_Municipio]->coordenada_x ?? '' }}"
                                                                        required
                                                                    >
                                                                </div>
                                                            </div>
                                                        </fieldset>

                                                        {{-- PERSONERÍA / RTN / CUENTA --}}
                                                        <fieldset class="mt-3">
                                                            <legend class="h6">Personería jurídica y RTN</legend>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label
                                                                        for="tiene_personeria_juridica_edit_{{ $org->Id_Organizacion }}"
                                                                        class="form-label"
                                                                    >
                                                                        ¿Tiene personería jurídica?
                                                                        <span class="required-asterisk" aria-hidden="true">*</span>
                                                                    </label>
                                                                    <select
                                                                        name="tiene_personeria_juridica"
                                                                        id="tiene_personeria_juridica_edit_{{ $org->Id_Organizacion }}"
                                                                        class="form-control form-control-changed"
                                                                        required
                                                                    >
                                                                        <option
                                                                            value="0"
                                                                            {{ !$org->tiene_personeria_juridica ? 'selected' : '' }}
                                                                        >
                                                                            No
                                                                        </option>
                                                                        <option
                                                                            value="1"
                                                                            {{ $org->tiene_personeria_juridica ? 'selected' : '' }}
                                                                        >
                                                                            Sí
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                                <div
                                                                    class="col-md-6"
                                                                    id="fecha_personeria_juridica_div_edit_{{ $org->Id_Organizacion }}"
                                                                    style="display:{{ $org->tiene_personeria_juridica ? 'block' : 'none' }};"
                                                                >
                                                                    <label
                                                                        for="fecha_personeria_juridica_edit_{{ $org->Id_Organizacion }}"
                                                                        class="form-label"
                                                                    >
                                                                        Fecha de obtención
                                                                    </label>
                                                                    <input
                                                                        type="date"
                                                                        class="form-control form-control-changed"
                                                                        name="fecha_personeria_juridica"
                                                                        id="fecha_personeria_juridica_edit_{{ $org->Id_Organizacion }}"
                                                                        value="{{ $org->fecha_personeria_juridica }}"
                                                                    >
                                                                </div>
                                                            </div>

                                                            <div class="row mt-3">
                                                                <div class="col-md-6">
                                                                    <label
                                                                        for="tiene_rtn_edit_{{ $org->Id_Organizacion }}"
                                                                        class="form-label"
                                                                    >
                                                                        ¿Tiene RTN?
                                                                        <span class="required-asterisk" aria-hidden="true">*</span>
                                                                    </label>
                                                                    <select
                                                                        name="tiene_rtn"
                                                                        id="tiene_rtn_edit_{{ $org->Id_Organizacion }}"
                                                                        class="form-control form-control-changed"
                                                                        required
                                                                    >
                                                                        <option
                                                                            value="0"
                                                                            {{ !$org->tiene_rtn ? 'selected' : '' }}
                                                                        >
                                                                            No
                                                                        </option>
                                                                        <option
                                                                            value="1"
                                                                            {{ $org->tiene_rtn ? 'selected' : '' }}
                                                                        >
                                                                            Sí
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                                <div
                                                                    class="col-md-6"
                                                                    id="rtn_div_edit_{{ $org->Id_Organizacion }}"
                                                                    style="display:{{ $org->tiene_rtn ? 'block' : 'none' }};"
                                                                >
                                                                    <label
                                                                        for="rtn_edit_{{ $org->Id_Organizacion }}"
                                                                        class="form-label"
                                                                    >
                                                                        RTN
                                                                    </label>
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-changed"
                                                                        name="rtn"
                                                                        id="rtn_edit_{{ $org->Id_Organizacion }}"
                                                                        maxlength="20"
                                                                        value="{{ $org->rtn }}"
                                                                    >
                                                                </div>
                                                            </div>

                                                            <div class="row mt-3">
                                                                <div class="col-md-6">
                                                                    <label
                                                                        for="tiene_cuenta_bancaria_edit_{{ $org->Id_Organizacion }}"
                                                                        class="form-label"
                                                                    >
                                                                        ¿Tiene cuenta bancaria?
                                                                        <span class="required-asterisk" aria-hidden="true">*</span>
                                                                    </label>
                                                                    <select
                                                                        name="tiene_cuenta_bancaria"
                                                                        id="tiene_cuenta_bancaria_edit_{{ $org->Id_Organizacion }}"
                                                                        class="form-control form-control-changed"
                                                                        required
                                                                    >
                                                                        <option
                                                                            value="0"
                                                                            {{ !$org->tiene_cuenta_bancaria ? 'selected' : '' }}
                                                                        >
                                                                            No
                                                                        </option>
                                                                        <option
                                                                            value="1"
                                                                            {{ $org->tiene_cuenta_bancaria ? 'selected' : '' }}
                                                                        >
                                                                            Sí
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button
                                                            type="button"
                                                            class="btn btn-secondary btn-cerrar-modal"
                                                            data-bs-dismiss="modal"
                                                            data-modal-id="modalEditarOrg{{ $org->Id_Organizacion }}"
                                                        >
                                                            Cancelar
                                                        </button>
                                                        <button type="submit" class="btn btn-funder-primary">
                                                            Guardar cambios
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- FORM INACTIVAR / BORRAR --}}
                                    <form
                                        action="{{ route('organizaciones.destroy', $org->Id_Organizacion) }}"
                                        method="POST"
                                        style="display:inline-block"
                                        onsubmit="return confirmarInactivacion(event)"
                                        aria-label="Inactivar organización {{ $org->Nombre_Organizacion }}"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn btn-xs btn-danger p-1 btn-icon-xs"
                                            title="Inactivar organización {{ $org->Nombre_Organizacion }}"
                                        >
                                            <i class="fas fa-trash-alt" aria-hidden="true"></i>
                                            <span class="sr-only">Inactivar</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                No hay organizaciones registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

{{-- MODAL REGISTRAR --}}
<div
    class="modal fade"
    id="modalRegistrarOrg"
    tabindex="-1"
    aria-labelledby="modalRegistrarOrgLabel"
    aria-hidden="true"
    role="dialog"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form
                action="{{ route('organizaciones.store') }}"
                method="POST"
                data-form-id="registro"
                aria-describedby="ayuda-campos-obligatorios-registro"
            >
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRegistrarOrgLabel">Registrar organización</h5>
                    <button
                        type="button"
                        class="btn-close btn-cerrar-modal"
                        data-bs-dismiss="modal"
                        data-modal-id="modalRegistrarOrg"
                        aria-label="Cerrar"
                    ></button>
                </div>
                <div class="modal-body">
                    <p id="ayuda-campos-obligatorios-registro" class="text-muted small">
                        Los campos marcados con
                        <span class="required-asterisk">*</span>
                        son obligatorios.
                    </p>

                    {{-- NOMBRE --}}
                    <div class="mb-3">
                        <label for="Nombre_Organizacion" class="form-label">
                            Nombre de la organización
                            <span class="required-asterisk" aria-hidden="true">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control form-control-changed"
                            name="Nombre_Organizacion"
                            id="Nombre_Organizacion"
                            maxlength="40"
                            pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ\s]{1,40}"
                            title="Solo letras y espacios, máximo 40 caracteres"
                            required
                            autocomplete="organization"
                        >
                    </div>

                    {{-- UBICACIÓN ADMIN --}}
                    <div class="mb-3">
                        <label for="departamento" class="form-label">
                            Departamento
                            <span class="required-asterisk" aria-hidden="true">*</span>
                        </label>
                        <select
                            name="departamento"
                            id="departamento"
                            class="form-control form-control-changed"
                            required
                        >
                            <option value="">Seleccione</option>
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto->Id_Departamento }}">
                                    {{ $depto->Nombre_Departamento }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="municipio" class="form-label">
                            Municipio
                            <span class="required-asterisk" aria-hidden="true">*</span>
                        </label>
                        <select
                            name="municipio"
                            id="municipio"
                            class="form-control form-control-changed"
                            required
                        >
                            <option value="">Seleccione un municipio</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="Nombre_Aldea" class="form-label">
                            Aldea
                            <span class="required-asterisk" aria-hidden="true">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control form-control-changed"
                            name="Nombre_Aldea"
                            id="Nombre_Aldea"
                            maxlength="40"
                            pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ\s]{1,40}"
                            title="Solo letras y espacios, máximo 40 caracteres"
                            required
                        >
                    </div>

                    {{-- MAPA / COORDENADAS --}}
                    <fieldset class="mb-3">
                        <legend class="h6">Ubicación geográfica</legend>
                        <p class="text-muted small">
                            Haga clic en el mapa o ingrese la latitud y longitud manualmente.
                        </p>

                        <div
                            id="map"
                            style="height: 300px;"
                            role="region"
                            aria-label="Mapa de ubicación de la organización"
                        ></div>

                        <div class="row mt-3">
                            <div class="col">
                                <label for="coordenada_y" class="form-label">
                                    Latitud
                                    <span class="required-asterisk" aria-hidden="true">*</span>
                                </label>
                                <input
                                    type="number"
                                    step="0.00000001"
                                    min="-90"
                                    max="90"
                                    name="coordenada_y"
                                    id="coordenada_y"
                                    class="form-control form-control-changed"
                                    required
                                >
                            </div>
                            <div class="col">
                                <label for="coordenada_x" class="form-label">
                                    Longitud
                                    <span class="required-asterisk" aria-hidden="true">*</span>
                                </label>
                                <input
                                    type="number"
                                    step="0.00000001"
                                    min="-180"
                                    max="180"
                                    name="coordenada_x"
                                    id="coordenada_x"
                                    class="form-control form-control-changed"
                                    required
                                >
                            </div>
                        </div>
                    </fieldset>

                    {{-- PERSONERÍA / RTN / CUENTA --}}
                    <fieldset class="mt-3">
                        <legend class="h6">Personería jurídica y RTN</legend>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="tiene_personeria_juridica" class="form-label">
                                    ¿Tiene personería jurídica?
                                    <span class="required-asterisk" aria-hidden="true">*</span>
                                </label>
                                <select
                                    name="tiene_personeria_juridica"
                                    id="tiene_personeria_juridica"
                                    class="form-control form-control-changed"
                                    required
                                >
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>
                            <div
                                class="col-md-6"
                                id="fecha_personeria_juridica_div"
                                style="display:none;"
                            >
                                <label for="fecha_personeria_juridica" class="form-label">
                                    Fecha de obtención
                                </label>
                                <input
                                    type="date"
                                    class="form-control form-control-changed"
                                    name="fecha_personeria_juridica"
                                    id="fecha_personeria_juridica"
                                >
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="tiene_rtn" class="form-label">
                                    ¿Tiene RTN?
                                    <span class="required-asterisk" aria-hidden="true">*</span>
                                </label>
                                <select
                                    name="tiene_rtn"
                                    id="tiene_rtn"
                                    class="form-control form-control-changed"
                                    required
                                >
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>
                            <div
                                class="col-md-6"
                                id="rtn_div"
                                style="display:none;"
                            >
                                <label for="rtn" class="form-label">RTN</label>
                                <input
                                    type="text"
                                    class="form-control form-control-changed"
                                    name="rtn"
                                    id="rtn"
                                    maxlength="20"
                                >
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="tiene_cuenta_bancaria" class="form-label">
                                    ¿Tiene cuenta bancaria?
                                    <span class="required-asterisk" aria-hidden="true">*</span>
                                </label>
                                <select
                                    name="tiene_cuenta_bancaria"
                                    id="tiene_cuenta_bancaria"
                                    class="form-control form-control-changed"
                                    required
                                >
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary btn-cerrar-modal"
                        data-bs-dismiss="modal"
                        data-modal-id="modalRegistrarOrg"
                    >
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-funder-primary">
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
    {{-- Bootstrap, SweetAlert2, Leaflet --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const municipios = @json($municipiosPorDepto);
        const coordenadasPorMunicipio = @json($coordenadas);
        let maps = {}; // mapas de edición
        let formChanged = {}; // seguimiento de cambios

        // ------------------------------------------------------
        // MARCAR FORMULARIO COMO MODIFICADO
        // ------------------------------------------------------
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('form-control-changed')) {
                const form = e.target.closest('form');
                if (form) {
                    const formId = form.getAttribute('data-form-id');
                    if (formId) {
                        formChanged[formId] = true;
                    }
                }
            }
        });

        // ------------------------------------------------------
        // ADVERTENCIA AL CERRAR MODAL SI HAY CAMBIOS
        // ------------------------------------------------------
        document.querySelectorAll('.btn-cerrar-modal').forEach(button => {
            button.addEventListener('click', function(e) {
                const modalId = this.getAttribute('data-modal-id');
                const modalElement = document.getElementById(modalId);
                const formElement = modalElement ? modalElement.querySelector('form') : null;
                const formId = formElement ? formElement.getAttribute('data-form-id') : null;

                if (formId && formChanged[formId]) {
                    e.preventDefault();

                    Swal.fire({
                        title: '¿Desea descartar los cambios?',
                        text: 'Ha realizado cambios sin guardar. ¿Desea cerrar el formulario?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#B91C1C',
                        cancelButtonColor: '#005CB9',
                        confirmButtonText: 'Sí, descartar',
                        cancelButtonText: 'No, continuar editando'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formChanged[formId] = false;
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) {
                                modal.hide();
                            }
                        }
                    });
                }
            });
        });

        // Resetear estado de cambios al abrir modal
        document.querySelectorAll('.modal.fade').forEach(modal => {
            const form = modal.querySelector('form');
            if (form) {
                const formId = form.getAttribute('data-form-id');
                $(modal).on('show.bs.modal', function () {
                    if (formId) {
                        formChanged[formId] = false;
                    }
                });

                form.addEventListener('submit', function() {
                    if (formId) {
                        formChanged[formId] = false;
                    }
                });
            }
        });

        // ------------------------------------------------------
        // VALIDACIÓN: SOLO LETRAS + MAYÚSCULAS
        // ------------------------------------------------------
        function convertToUppercase(input) {
            const sanitizedValue = input.value.replace(/[^A-Za-zÁÉÍÓÚÑáéíóúñ\s]/g, '');
            input.value = sanitizedValue.toUpperCase();
        }

        function setupInputValidation(modalId) {
            const isRegister = modalId === 'modalRegistrarOrg';
            const orgId = isRegister ? '' : modalId.replace('modalEditarOrg', '');

            const nombreOrgId = isRegister ? 'Nombre_Organizacion' : `Nombre_Organizacion_${orgId}`;
            const nombreAldeaId = isRegister ? 'Nombre_Aldea' : `Nombre_Aldea_${orgId}`;

            const nombreOrgInput = document.getElementById(nombreOrgId);
            if (nombreOrgInput) {
                nombreOrgInput.addEventListener('input', function() {
                    convertToUppercase(this);
                });
            }

            const nombreAldeaInput = document.getElementById(nombreAldeaId);
            if (nombreAldeaInput) {
                nombreAldeaInput.addEventListener('input', function() {
                    convertToUppercase(this);
                });
            }
        }

        // Aplicar validación para formulario de registro
        setupInputValidation('modalRegistrarOrg');

        // ------------------------------------------------------
        // SELECT DEPARTAMENTO → MUNICIPIO (REGISTRO)
        // ------------------------------------------------------
        document.getElementById('departamento').addEventListener('change', function () {
            const deptoId = this.value;
            const municipioSelect = document.getElementById('municipio');
            municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
            if (municipios[deptoId]) {
                municipios[deptoId].forEach(muni => {
                    const option = document.createElement('option');
                    option.value = muni.Id_Municipio;
                    option.textContent = muni.Nombre_Municipio;
                    municipioSelect.appendChild(option);
                });
            }
        });

        // ------------------------------------------------------
        // CONFIRMAR INACTIVACIÓN
        // ------------------------------------------------------
        window.confirmarInactivacion = function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción inactivará la organización.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#B91C1C',
                cancelButtonColor: '#005CB9',
                confirmButtonText: 'Sí, inactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                    e.target.closest('form').submit();
                }
            });
            return false;
        };

        // ------------------------------------------------------
        // DATATABLE
        // ------------------------------------------------------
        $(document).ready(function () {
            $('#tabla-organizaciones').DataTable({
                language: {
                    lengthMenu: 'Mostrar _MENU_ registros',
                    zeroRecords: 'No se encontraron resultados',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
                    infoFiltered: '(filtrado de un total de _MAX_ registros)',
                    search: 'Buscar:',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    },
                    processing: 'Procesando...'
                },
                order: [[0, 'desc']]
            });
        });

        // ------------------------------------------------------
        // MAPA REGISTRO
        // ------------------------------------------------------
        let map;
        let marker;

        $('#modalRegistrarOrg').on('shown.bs.modal', function () {
            if (!map) {
                map = L.map('map', {
                    center: [14.634915, -87.849243],
                    zoom: 8,
                    zoomControl: true,
                    scrollWheelZoom: true
                });

                L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap',
                    maxZoom: 19
                }).addTo(map);

                map.on('click', function (e) {
                    const lat = e.latlng.lat.toFixed(8);
                    const lng = e.latlng.lng.toFixed(8);
                    document.getElementById('coordenada_y').value = lat;
                    document.getElementById('coordenada_x').value = lng;
                    marker = actualizarMarcador(lat, lng, map, marker);
                });
            }
            setTimeout(() => { map.invalidateSize(); }, 200);
        });

        function actualizarMarcador(lat, lng, currentMap, currentMarker) {
            const nuevaPos = L.latLng(lat, lng);
            if (currentMarker) currentMap.removeLayer(currentMarker);
            currentMarker = L.marker(nuevaPos).addTo(currentMap);
            currentMap.setView(nuevaPos, 14);
            return currentMarker;
        }

        function esCoordenadaValida(lat, lng) {
            return (
                !isNaN(lat) && !isNaN(lng) &&
                lat >= -90 && lat <= 90 &&
                lng >= -180 && lng <= 180
            );
        }

        document.getElementById('coordenada_y').addEventListener('input', function () {
            const lat = parseFloat(this.value);
            const lng = parseFloat(document.getElementById('coordenada_x').value);
            if (esCoordenadaValida(lat, lng) && map) {
                marker = actualizarMarcador(lat, lng, map, marker);
            }
        });

        document.getElementById('coordenada_x').addEventListener('input', function () {
            const lng = parseFloat(this.value);
            const lat = parseFloat(document.getElementById('coordenada_y').value);
            if (esCoordenadaValida(lat, lng) && map) {
                marker = actualizarMarcador(lat, lng, map, marker);
            }
        });

        // ------------------------------------------------------
        // MAPAS DE EDICIÓN
        // ------------------------------------------------------
        function initEditMap(orgId, lat, lng) {
            if (maps[orgId] && maps[orgId].map) {
                maps[orgId].map.remove();
            }

            const mapId = `map_edit_${orgId}`;
            const defaultLat = lat || 14.634915;
            const defaultLng = lng || -87.849243;
            const defaultZoom = (lat && lng) ? 14 : 8;

            const editMap = L.map(mapId, {
                center: [defaultLat, defaultLng],
                zoom: defaultZoom,
                zoomControl: true,
                scrollWheelZoom: true
            });

            L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap',
                maxZoom: 19
            }).addTo(editMap);

            let editMarker = (lat && lng) ? L.marker([lat, lng]).addTo(editMap) : null;

            editMap.on('click', function (e) {
                const newLat = e.latlng.lat.toFixed(8);
                const newLng = e.latlng.lng.toFixed(8);
                document.getElementById(`coordenada_y_edit_${orgId}`).value = newLat;
                document.getElementById(`coordenada_x_edit_${orgId}`).value = newLng;
                editMarker = actualizarMarcador(newLat, newLng, editMap, editMarker);
            });

            document.getElementById(`coordenada_y_edit_${orgId}`).addEventListener('input', function () {
                const currentLat = parseFloat(this.value);
                const currentLng = parseFloat(document.getElementById(`coordenada_x_edit_${orgId}`).value);
                if (esCoordenadaValida(currentLat, currentLng)) {
                    editMarker = actualizarMarcador(currentLat, currentLng, editMap, editMarker);
                }
            });

            document.getElementById(`coordenada_x_edit_${orgId}`).addEventListener('input', function () {
                const currentLng = parseFloat(this.value);
                const currentLat = parseFloat(document.getElementById(`coordenada_y_edit_${orgId}`).value);
                if (esCoordenadaValida(currentLat, currentLng)) {
                    editMarker = actualizarMarcador(currentLat, currentLng, editMap, editMarker);
                }
            });

            maps[orgId] = { map: editMap, marker: editMarker };
        }

        // Inicializar mapas y validación en modales de edición
        document.querySelectorAll('.modal.fade').forEach(modal => {
            if (modal.id.startsWith('modalEditarOrg')) {
                const orgId = modal.id.replace('modalEditarOrg', '');

                $(modal).on('shown.bs.modal', function () {
                    const latInput = document.getElementById(`coordenada_y_edit_${orgId}`);
                    const lngInput = document.getElementById(`coordenada_x_edit_${orgId}`);
                    const lat = parseFloat(latInput.value);
                    const lng = parseFloat(lngInput.value);

                    initEditMap(orgId, lat, lng);
                    setupInputValidation(`modalEditarOrg${orgId}`);

                    setTimeout(() => {
                        if (maps[orgId] && maps[orgId].map) {
                            maps[orgId].map.invalidateSize();
                        }
                    }, 200);
                });

                $(modal).on('hidden.bs.modal', function () {
                    if (maps[orgId] && maps[orgId].map) {
                        maps[orgId].map.remove();
                        maps[orgId] = null;
                    }
                });
            }
        });

        // ------------------------------------------------------
        // MOSTRAR/OCULTAR CAMPOS SEGÚN SELECCIÓN (REGISTRO / EDICIÓN)
        // ------------------------------------------------------
        document.addEventListener('DOMContentLoaded', function() {
            const setupRegisterFields = () => {
                const tienePersoneria = document.getElementById('tiene_personeria_juridica');
                const tieneRtn = document.getElementById('tiene_rtn');

                if (tienePersoneria) {
                    const divPersoneria = document.getElementById('fecha_personeria_juridica_div');
                    const inputPersoneria = document.getElementById('fecha_personeria_juridica');

                    const togglePersoneria = function() {
                        if (this.value === '1') {
                            divPersoneria.style.display = 'block';
                            inputPersoneria.setAttribute('required', 'required');
                        } else {
                            divPersoneria.style.display = 'none';
                            inputPersoneria.removeAttribute('required');
                        }
                    };
                    tienePersoneria.addEventListener('change', togglePersoneria);
                    togglePersoneria.call(tienePersoneria);
                }

                if (tieneRtn) {
                    const divRtn = document.getElementById('rtn_div');
                    const inputRtn = document.getElementById('rtn');

                    const toggleRtn = function() {
                        if (this.value === '1') {
                            divRtn.style.display = 'block';
                            inputRtn.setAttribute('required', 'required');
                        } else {
                            divRtn.style.display = 'none';
                            inputRtn.removeAttribute('required');
                        }
                    };
                    tieneRtn.addEventListener('change', toggleRtn);
                    toggleRtn.call(tieneRtn);
                }
            };

            const setupEditFields = () => {
                document.querySelectorAll('select[id^="tiene_personeria_juridica_edit_"]').forEach(select => {
                    const orgId = select.id.replace('tiene_personeria_juridica_edit_', '');
                    const div = document.getElementById(`fecha_personeria_juridica_div_edit_${orgId}`);
                    const input = document.getElementById(`fecha_personeria_juridica_edit_${orgId}`);

                    const togglePersoneriaEdit = function() {
                        if (this.value === '1') {
                            div.style.display = 'block';
                            input.setAttribute('required', 'required');
                        } else {
                            div.style.display = 'none';
                            input.removeAttribute('required');
                        }
                    };
                    select.addEventListener('change', togglePersoneriaEdit);
                    togglePersoneriaEdit.call(select);
                });

                document.querySelectorAll('select[id^="tiene_rtn_edit_"]').forEach(select => {
                    const orgId = select.id.replace('tiene_rtn_edit_', '');
                    const div = document.getElementById(`rtn_div_edit_${orgId}`);
                    const input = document.getElementById(`rtn_edit_${orgId}`);

                    const toggleRtnEdit = function() {
                        if (this.value === '1') {
                            div.style.display = 'block';
                            input.setAttribute('required', 'required');
                        } else {
                            div.style.display = 'none';
                            input.removeAttribute('required');
                        }
                    };
                    select.addEventListener('change', toggleRtnEdit);
                    toggleRtnEdit.call(select);
                });
            };

            setupRegisterFields();
            setupEditFields();
        });
    </script>
@endsection
