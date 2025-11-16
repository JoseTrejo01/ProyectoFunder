@extends('adminlte::page')

@section('title', 'Capacitaciones')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h1 class="mb-0 fw-bold text-dark">
            <i class="fas fa-chalkboard-teacher mr-2"></i> Registro de Capacitaciones
        </h1>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('reporte.exportCapacitacionesExcel') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </div>
    </div>
@stop

@section('css')
    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- Tema Bootstrap4 para Select2 (AdminLTE 3 usa Bootstrap 4) --}}
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

    <style>
        /* Layout general */
        .card {
            border-radius: 1rem;
            border: 1px solid #e0e0e0;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }

        .nav-tabs .nav-link {
            border-radius: 0;
            font-weight: 600;
        }

        .nav-tabs .nav-link.active {
            background-color: #5B8E3E;
            color: #fff !important;
            border-color: #5B8E3E;
        }

        .nav-tabs .nav-link i {
            margin-right: .25rem;
        }

        /* Select2 */
        .select2-container--bootstrap4 .select2-selection {
            border-radius: 0.5rem !important;
            min-height: 2.5rem;
            border: 1px solid #3b3b3b !important;
        }

        .select2-container--bootstrap4 .select2-selection__rendered {
            padding-left: .75rem;
            color: #111 !important;
            font-weight: 500;
        }

        .select2-container--bootstrap4 .select2-selection__arrow {
            height: 100% !important;
        }

        .select2-container--bootstrap4 .select2-results__option--highlighted {
            background-color: #0d6efd !important;
            color: #fff !important;
        }

        /* Info-box accesibles */
        .info-box {
            border-radius: .75rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .info-box .info-box-text {
            font-size: .85rem;
        }

        .info-box .info-box-number {
            font-size: 1rem;
            font-weight: 700;
        }

        /* Tablas */
        table.table {
            font-size: 0.9rem;
        }

        thead.table-primary th,
        thead.table-primary td {
            color: #fff;
            background-color: #1b263b !important;
        }

        thead.table-primary th i {
            margin-right: .25rem;
        }

        .table thead th {
            vertical-align: middle !important;
        }

        .badge-warning {
            color: #856404;
            background-color: #fff3cd;
        }

        /* Responsivo */
        @media (max-width: 767.98px) {
            .info-box .info-box-text {
                font-size: .75rem;
            }
            .info-box .info-box-number {
                font-size: .9rem;
            }
            .nav-tabs .nav-link {
                font-size: .85rem;
                padding: .35rem .5rem;
            }
            .card-header h5, .card-header h6 {
                font-size: 1rem;
            }
        }
    </style>
@stop

@section('content')
<div class="container-fluid">

    {{-- MENSAJES DE ÉXITO Y ERROR (SweetAlert2) --}}
    @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: @json(session('success')),
                    confirmButtonColor: '#5B8E3E',
                    timer: 2500,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            });
        </script>
    @endif
    @if(session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: @json(session('error')),
                    confirmButtonColor: '#d33',
                });
            });
        </script>
    @endif

    {{-- PESTAÑAS (TABS) --}}
    <div class="card">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="capacitacionesTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active"
                            id="formulario-tab"
                            data-toggle="tab"
                            data-target="#formulario"
                            type="button"
                            role="tab"
                            aria-controls="formulario"
                            aria-selected="true">
                        <i class="fas fa-plus-circle"></i> Registrar Capacitación
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            id="modulos-tab"
                            data-toggle="tab"
                            data-target="#modulos"
                            type="button"
                            role="tab"
                            aria-controls="modulos"
                            aria-selected="false">
                        <i class="fas fa-book"></i> Módulos y Temas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            id="reporte-tab"
                            data-toggle="tab"
                            data-target="#reporte"
                            type="button"
                            role="tab"
                            aria-controls="reporte"
                            aria-selected="false">
                        <i class="fas fa-chart-bar"></i> Reporte de Capacitaciones
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="capacitacionesTabsContent">

                {{-- ================== PESTAÑA INFORMACIÓN BÁSICA ================== --}}
                <div class="tab-pane fade show active"
                     id="formulario"
                     role="tabpanel"
                     aria-labelledby="formulario-tab">
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="fw-bold">
                                <i class="fas fa-info-circle"></i> Información Básica de la Capacitación
                            </h5>
                            <p class="text-muted mb-2">
                                Complete los datos generales de la capacitación antes de seleccionar los módulos y temas.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('capacitacion.store') }}" method="POST" id="capacitacionForm">
                        @csrf

                        <div class="row">
                            {{-- Caja Rural --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="Id_Organizacion" class="form-label fw-semibold">
                                        <i class="fas fa-building"></i> Caja Rural
                                    </label>
                                    <select name="Id_Organizacion"
                                            id="Id_Organizacion"
                                            class="form-control"
                                            required
                                            aria-required="true">
                                        <option value="">Seleccione una caja rural</option>
                                        @foreach($organizaciones as $org)
                                            <option value="{{ $org->Id_Organizacion }}">{{ $org->Nombre_Organizacion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Fecha --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="Fecha" class="form-label fw-semibold">
                                        <i class="fas fa-calendar"></i> Fecha de Capacitación
                                    </label>
                                    <input type="date"
                                           name="Fecha"
                                           id="Fecha"
                                           class="form-control"
                                           required
                                           aria-required="true">
                                </div>
                            </div>
                        </div>

                        {{-- Beneficiarios --}}
                        <div class="mb-4">
                            <label for="beneficiarios" class="form-label fw-semibold">
                                <i class="fas fa-users"></i> Beneficiarios Participantes
                            </label>
                            <select name="beneficiarios[]"
                                    id="beneficiarios"
                                    class="form-control"
                                    multiple
                                    required
                                    aria-required="true"
                                    aria-describedby="beneficiariosHelp">
                                {{-- Se llenará dinámicamente según la caja rural --}}
                            </select>
                            <small id="beneficiariosHelp" class="text-muted">
                                Puede seleccionar múltiples beneficiarios utilizando Ctrl (Windows) o Cmd (Mac), o clic individual si está en móvil.
                            </small>
                        </div>

                        {{-- Resumen informativo --}}
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="info-box bg-info text-white">
                                            <span class="info-box-icon">
                                                <i class="fas fa-building"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Caja Rural</span>
                                                <span class="info-box-number" id="info-caja">No seleccionada</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="info-box bg-success text-white">
                                            <span class="info-box-icon">
                                                <i class="fas fa-users"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Beneficiarios</span>
                                                <span class="info-box-number" id="info-beneficiarios">0</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="info-box bg-warning">
                                            <span class="info-box-icon">
                                                <i class="fas fa-calendar"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Fecha</span>
                                                <span class="info-box-number" id="info-fecha">No seleccionada</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="text-center mt-3">
                                    <button type="button"
                                            class="btn btn-primary btn-lg px-4"
                                            onclick="irAModulos()">
                                        <i class="fas fa-arrow-right"></i> Continuar a Módulos y Temas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- ================== PESTAÑA MÓDULOS Y TEMAS ================== --}}
                <div class="tab-pane fade"
                     id="modulos"
                     role="tabpanel"
                     aria-labelledby="modulos-tab">

                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="fw-bold">
                                <i class="fas fa-book"></i> Selección de Módulos y Temas
                            </h5>
                            <p class="text-muted">
                                Marque los temas que cada beneficiario recibió en esta capacitación.
                            </p>
                        </div>
                    </div>

                    {{-- Información resumen --}}
                    <div class="alert alert-info d-none" id="info-capacitacion" role="status">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                            <div class="mb-2 mb-md-0">
                                <strong><i class="fas fa-info-circle"></i> Capacitación: </strong>
                                <span id="resumen-info">Información incompleta</span>
                            </div>
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    onclick="volverAInformacion()">
                                <i class="fas fa-edit"></i> Modificar Información
                            </button>
                        </div>
                    </div>

                    {{-- Mensaje inicial --}}
                    <div id="contenido-modulos">
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Complete primero la información básica:</strong>
                            seleccione Caja Rural, Beneficiarios y Fecha en la pestaña anterior.
                        </div>
                    </div>

                    {{-- Contenido dinámico de módulos --}}
                    <div id="modulos-dinamicos" style="display: none;">
                        @php
                            $modulosUnicos = collect($modulos)->unique('Nombre_Modulo')->values();
                        @endphp

                        {{-- Tabs de módulos --}}
                        <ul class="nav nav-tabs nav-tabs-bordered mb-3" id="moduloTabs" role="tablist">
                            @foreach($modulosUnicos as $idx => $modulo)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $idx === 0 ? 'active' : '' }}"
                                            id="tabModulo{{ $modulo->Id_Modulo }}"
                                            data-toggle="tab"
                                            data-target="#modulo{{ $modulo->Id_Modulo }}"
                                            type="button"
                                            role="tab"
                                            aria-controls="modulo{{ $modulo->Id_Modulo }}"
                                            aria-selected="{{ $idx === 0 ? 'true' : 'false' }}">
                                        <i class="fas fa-book-open"></i> {{ $modulo->Nombre_Modulo }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Contenido de cada módulo --}}
                        <div class="tab-content" id="moduloTabsContent">
                            @foreach($modulosUnicos as $idx => $modulo)
                                <div class="tab-pane fade {{ $idx === 0 ? 'show active' : '' }}"
                                     id="modulo{{ $modulo->Id_Modulo }}"
                                     role="tabpanel"
                                     aria-labelledby="tabModulo{{ $modulo->Id_Modulo }}">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                                        <h6 class="mb-2 mb-md-0 fw-bold">
                                            <i class="fas fa-book-open"></i> {{ $modulo->Nombre_Modulo }}
                                        </h6>
                                        <div class="btn-group" role="group" aria-label="Acciones rápidas módulo">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-success"
                                                    onclick="seleccionarTodosModulo({{ $modulo->Id_Modulo }})">
                                                <i class="fas fa-check-double"></i> Seleccionar Todos
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    onclick="deseleccionarTodosModulo({{ $modulo->Id_Modulo }})">
                                                <i class="fas fa-times"></i> Deseleccionar Todos
                                            </button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-bordered"
                                               id="tabla-capacitacion-{{ $modulo->Id_Modulo }}">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th scope="col" style="width: 40%;">
                                                        <i class="fas fa-bookmark"></i> Tema
                                                    </th>
                                                    <th scope="col" style="width: 10%;" class="text-center">
                                                        <i class="fas fa-check-circle"></i> Todos
                                                    </th>
                                                    <th scope="col" style="width: 50%;">
                                                        <i class="fas fa-users"></i> Beneficiarios
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($temasPorModulo[$modulo->Id_Modulo] as $tema)
                                                    <tr data-tema-id="{{ $tema->Id_Tema }}">
                                                        <td class="align-middle fw-semibold">{{ $tema->Nombre_Tema }}</td>
                                                        <td class="text-center align-middle">
                                                            <input type="checkbox"
                                                                   class="form-check-input tema-todos"
                                                                   onchange="toggleTema({{ $tema->Id_Tema }}, this.checked)"
                                                                   aria-label="Seleccionar todos los beneficiarios para el tema {{ $tema->Nombre_Tema }}">
                                                        </td>
                                                        <td class="beneficiarios-checkboxes align-middle">
                                                            {{-- Checkboxes generados por JS --}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 text-center">
                            <button type="button"
                                    class="btn btn-success btn-lg px-4"
                                    onclick="enviarCapacitacion()">
                                <i class="fas fa-save"></i> Guardar Capacitación
                            </button>
                            <button type="button"
                                    class="btn btn-secondary btn-lg px-4 ml-md-2 mt-2 mt-md-0"
                                    onclick="volverAInformacion()">
                                <i class="fas fa-arrow-left"></i> Volver a Información
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ================== PESTAÑA REPORTE ================== --}}
                <div class="tab-pane fade"
                     id="reporte"
                     role="tabpanel"
                     aria-labelledby="reporte-tab">

                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="fw-bold">
                                <i class="fas fa-chart-bar"></i> Reporte de Capacitaciones por Socio
                            </h5>
                            <p class="text-muted">
                                Consulte los módulos recibidos por cada socio en las capacitaciones registradas.
                            </p>
                        </div>
                    </div>

                    {{-- Filtros --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0 fw-bold">
                                <i class="fas fa-filter"></i> Filtros de Búsqueda
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="filtro_reporte_organizacion" class="form-label fw-semibold">
                                        <i class="fas fa-building"></i> Caja Rural
                                    </label>
                                    <select id="filtro_reporte_organizacion"
                                            class="form-control"
                                            aria-label="Filtrar por Caja Rural">
                                        <option value="">Todas las cajas rurales</option>
                                        @foreach($organizaciones as $org)
                                            <option value="{{ $org->Id_Organizacion }}">{{ $org->Nombre_Organizacion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="filtro_reporte_fecha_inicio" class="form-label fw-semibold">
                                        <i class="fas fa-calendar-day"></i> Fecha Inicio
                                    </label>
                                    <input type="date"
                                           id="filtro_reporte_fecha_inicio"
                                           class="form-control"
                                           aria-label="Fecha inicio del filtro">
                                </div>
                                <div class="col-md-3">
                                    <label for="filtro_reporte_fecha_fin" class="form-label fw-semibold">
                                        <i class="fas fa-calendar-day"></i> Fecha Fin
                                    </label>
                                    <input type="date"
                                           id="filtro_reporte_fecha_fin"
                                           class="form-control"
                                           aria-label="Fecha fin del filtro">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button"
                                            class="btn btn-primary w-100"
                                            onclick="cargarReporte()">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla reporte --}}
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-bold">
                                <i class="fas fa-table"></i> Reporte de Capacitaciones
                            </h6>
                        </div>
                        <div class="card-body">

                            <div id="loading-reporte" class="text-center my-3" style="display: none;">
                                <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
                                <p class="mt-2 mb-0">Generando reporte...</p>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="tabla-reporte">
                                    <thead class="table-primary">
                                        <tr>
                                            <th scope="col"><i class="fas fa-building"></i> Caja Rural</th>
                                            <th scope="col"><i class="fas fa-user"></i> Socio / Beneficiario</th>
                                            <th scope="col"><i class="fas fa-calendar"></i> Fechas de Capacitación</th>
                                            <th scope="col"><i class="fas fa-book"></i> Módulos Recibidos</th>
                                            <th scope="col" class="text-center"><i class="fas fa-bookmark"></i> Total Temas</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-reporte">
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="fas fa-info-circle"></i>
                                                Haga clic en <strong>Buscar</strong> para cargar los datos.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Paginación --}}
                            <div id="paginacion-reporte"
                                 class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3"
                                 style="display: none;">
                                <div class="mb-2 mb-md-0">
                                    <span class="text-muted" id="info-registros"></span>
                                </div>
                                <nav aria-label="Paginación del reporte">
                                    <ul class="pagination pagination-sm mb-0" id="pagination-controls">
                                        {{-- Se llena por JS --}}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>

                </div> {{-- Fin pestaña reporte --}}

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    {{-- jQuery ya viene con AdminLTE --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ================== DATOS DESDE EL SERVIDOR ==================
    let beneficiariosPorOrg = @json($beneficiariosPorOrg);

    // ================== SELECT2 INIT ==================
    function initSelect2() {
        $('#Id_Organizacion').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Seleccione una caja rural',
            allowClear: true
        });

        $('#beneficiarios').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Seleccione beneficiarios',
            allowClear: true
        });

        $('#filtro_reporte_organizacion').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Todas las cajas rurales',
            allowClear: true
        });
    }

    // ================== CARGA DE BENEFICIARIOS POR CAJA ==================
    function cargarBeneficiariosSimplificado(organizacionId) {
        const $selectBeneficiarios = $('#beneficiarios');
        $selectBeneficiarios.empty().trigger('change');

        if (!organizacionId) {
            $selectBeneficiarios.append('<option value="">Primero seleccione una caja rural</option>');
            $selectBeneficiarios.trigger('change');
            return;
        }

        let beneficiarios = null;

        if (beneficiariosPorOrg[organizacionId.toString()]) {
            beneficiarios = beneficiariosPorOrg[organizacionId.toString()];
        } else if (beneficiariosPorOrg[parseInt(organizacionId)]) {
            beneficiarios = beneficiariosPorOrg[parseInt(organizacionId)];
        } else {
            for (let key in beneficiariosPorOrg) {
                if (key == organizacionId) {
                    beneficiarios = beneficiariosPorOrg[key];
                    break;
                }
            }
        }

        if (!beneficiarios || !Array.isArray(beneficiarios) || beneficiarios.length === 0) {
            $selectBeneficiarios.append('<option value="">No hay beneficiarios disponibles</option>');
            $selectBeneficiarios.trigger('change');

            Swal.fire({
                icon: 'info',
                title: 'Sin beneficiarios',
                text: 'Esta caja rural no tiene beneficiarios registrados.',
                confirmButtonColor: '#3085d6'
            });

            return;
        }

        $selectBeneficiarios.append('<option value="">Seleccione beneficiarios</option>');
        beneficiarios.forEach(function(b) {
            $selectBeneficiarios.append(
                `<option value="${b.Id_Beneficiario}">${b.Nombre_Beneficiario}</option>`
            );
        });
        $selectBeneficiarios.trigger('change');
    }

    // ================== INFO RESUMEN ==================
    function updateInfoDisplays() {
        let beneficiariosCount = ($('#beneficiarios').val() || []).length;
        $('#info-beneficiarios').text(beneficiariosCount);

        let fecha = $('#Fecha').val();
        $('#info-fecha').text(fecha || 'No seleccionada');

        updateResumenCapacitacion();
    }

    function updateResumenCapacitacion() {
        let $caja = $('#Id_Organizacion');
        let $beneficiarios = $('#beneficiarios');
        let fecha = $('#Fecha').val();

        if ($caja.val() && $beneficiarios.val() && $beneficiarios.val().length > 0 && fecha) {
            let cajaText = $caja.find('option:selected').text();
            let beneficiariosCount = $beneficiarios.val().length;

            $('#resumen-info').html(
                `<strong>${cajaText}</strong> &mdash; ${beneficiariosCount} beneficiario(s) &mdash; ${fecha}`
            );
        } else {
            $('#resumen-info').html('Información incompleta');
        }
    }

    // ================== NAVEGACIÓN ENTRE TABS ==================
    function irAModulos() {
        let caja = $('#Id_Organizacion').val();
        let beneficiarios = $('#beneficiarios').val();
        let fecha = $('#Fecha').val();

        if (!caja || !beneficiarios || beneficiarios.length === 0 || !fecha) {
            Swal.fire({
                icon: 'warning',
                title: 'Información incompleta',
                text: 'Por favor complete Caja Rural, Beneficiarios y Fecha antes de continuar.',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        $('#modulos-tab').tab('show');
        $('#info-capacitacion').removeClass('d-none');
        $('#contenido-modulos .alert-warning').hide();
        $('#modulos-dinamicos').show();

        llenarCheckboxesBeneficiarios();
    }

    function volverAInformacion() {
        $('#formulario-tab').tab('show');
    }

    // ================== CHECKBOXES POR TEMA Y BENEFICIARIO ==================
    function llenarCheckboxesBeneficiarios() {
        let beneficiariosSeleccionados = $('#beneficiarios').val() || [];
        if (beneficiariosSeleccionados.length === 0) {
            return;
        }

        $('.beneficiarios-checkboxes').empty();

        $('tr[data-tema-id]').each(function() {
            let temaId = $(this).data('tema-id');
            let celda = $(this).find('.beneficiarios-checkboxes');
            let moduloId = $(this).closest('.tab-pane').attr('id').replace('modulo', '');

            beneficiariosSeleccionados.forEach(function(idBeneficiario) {
                let nombreBeneficiario = $(`#beneficiarios option[value="${idBeneficiario}"]`).text();

                let checkboxHtml = `
                    <div class="form-check form-check-inline mb-1">
                        <input class="form-check-input beneficiario-checkbox"
                               type="checkbox"
                               id="beneficiario_${idBeneficiario}_modulo_${moduloId}_tema_${temaId}"
                               name="recibio[${idBeneficiario}][${moduloId}][${temaId}]"
                               value="1"
                               data-beneficiario="${idBeneficiario}"
                               data-modulo="${moduloId}"
                               data-tema="${temaId}">
                        <label class="form-check-label small"
                               for="beneficiario_${idBeneficiario}_modulo_${moduloId}_tema_${temaId}">
                            ${nombreBeneficiario}
                        </label>
                    </div>
                `;
                celda.append(checkboxHtml);
            });
        });
    }

    function seleccionarTodosModulo(idModulo) {
        $(`#modulo${idModulo} .beneficiario-checkbox`).prop('checked', true);
        $(`#modulo${idModulo} .tema-todos`).prop('checked', true);
    }

    function deseleccionarTodosModulo(idModulo) {
        $(`#modulo${idModulo} .beneficiario-checkbox`).prop('checked', false);
        $(`#modulo${idModulo} .tema-todos`).prop('checked', false);
    }

    function toggleTema(idTema, checked) {
        $(`.beneficiario-checkbox[data-tema="${idTema}"]`).prop('checked', checked);
    }

    // ================== ENVÍO DE CAPACITACIÓN (AJAX) ==================
    function enviarCapacitacion() {
        let caja = $('#Id_Organizacion').val();
        let beneficiarios = $('#beneficiarios').val();
        let fecha = $('#Fecha').val();

        if (!caja || !beneficiarios || beneficiarios.length === 0 || !fecha) {
            Swal.fire({
                icon: 'warning',
                title: 'Información incompleta',
                text: 'Complete la información básica antes de guardar.',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        let formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('Id_Organizacion', caja);
        formData.append('Fecha', fecha);

        let temasSeleccionados = 0;
        $('.beneficiario-checkbox:checked').each(function() {
            let beneficiarioId = $(this).data('beneficiario');
            let moduloId = $(this).data('modulo');
            let temaId = $(this).data('tema');

            let fieldName = `recibio[${beneficiarioId}][${moduloId}][${temaId}]`;
            formData.append(fieldName, '1');
            temasSeleccionados++;
        });

        if (temasSeleccionados === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Sin temas seleccionados',
                text: 'Seleccione al menos un tema para algún beneficiario.',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        Swal.fire({
            title: 'Guardando capacitación...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: "{{ route('capacitacion.store') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Capacitación registrada correctamente.',
                    confirmButtonColor: '#28a745'
                }).then(() => location.reload());
            },
            error: function(xhr) {
                let errorMessage = 'Hubo un error al registrar la capacitación.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    }

    // ================== REPORTE DE CAPACITACIONES ==================
    function cargarReporte() {
        $('#loading-reporte').show();
        $('#tbody-reporte').html(`
            <tr>
                <td colspan="5" class="text-center">
                    <i class="fas fa-spinner fa-spin"></i> Generando reporte...
                </td>
            </tr>
        `);
        $('#paginacion-reporte').hide();

        let filtros = {
            organizacion: $('#filtro_reporte_organizacion').val(),
            fecha_inicio: $('#filtro_reporte_fecha_inicio').val(),
            fecha_fin: $('#filtro_reporte_fecha_fin').val(),
            page: 1
        };

        $.ajax({
            url: "{{ route('capacitaciones.reporte') }}",
            method: 'GET',
            data: filtros,
            success: function(response) {
                $('#loading-reporte').hide();

                if (response.success && response.data.length > 0) {
                    cargarTablaReporte(response.data, response.pagination);
                } else {
                    $('#tbody-reporte').html(`
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="fas fa-search"></i> No se encontraron datos con los filtros aplicados.
                            </td>
                        </tr>
                    `);
                    $('#paginacion-reporte').hide();
                }
            },
            error: function() {
                $('#loading-reporte').hide();
                $('#tbody-reporte').html(`
                    <tr>
                        <td colspan="5" class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle"></i> Error al cargar el reporte.
                        </td>
                    </tr>
                `);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar el reporte de capacitaciones.',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    }

    function cargarTablaReporte(datos, pagination) {
        let html = '';
        datos.forEach(function(item) {
            let fechasHtml = item.fechas.map(fecha => {
                return `<span class="badge badge-info mr-1">${new Date(fecha).toLocaleDateString('es-ES')}</span>`;
            }).join('');

            let modulosHtml = item.modulos.map(m => convertirANumeroRomano(m)).join(' ');

            html += `
                <tr>
                    <td class="align-middle">
                        <strong><i class="fas fa-building text-primary"></i> ${item.caja_rural}</strong>
                    </td>
                    <td class="align-middle">
                        <i class="fas fa-user text-info"></i> ${item.beneficiario}
                    </td>
                    <td class="align-middle">${fechasHtml}</td>
                    <td class="align-middle">${modulosHtml}</td>
                    <td class="text-center align-middle">
                        <span class="badge badge-warning">${item.total_temas}</span>
                    </td>
                </tr>
            `;
        });

        $('#tbody-reporte').html(html);

        if (pagination) {
            mostrarPaginacionReporte(pagination);
        }
    }

    function convertirANumeroRomano(nombreModulo) {
        const numerosRomanos = {
            'MODULO I': 'Módulo I',
            'MODULO II': 'Módulo II', 
            'MODULO III': 'Módulo III',
            'MODULO IV': 'Módulo IV',
            'MODULO V': 'Módulo V',
            'MODULO VI': 'Módulo VI',
            'MODULO VII': 'Módulo VII',
            'MODULO VIII': 'Módulo VIII',
            'MODULO IX': 'Módulo IX',
            'MODULO X': 'Módulo X'
        };

        let upper = nombreModulo.toUpperCase();
        if (numerosRomanos[upper]) return numerosRomanos[upper];

        if (upper.includes('MODULO')) {
            let match = upper.match(/MODULO\s*(\d+)/);
            if (match) {
                let numero = parseInt(match[1]);
                return `Módulo ${convertirAromano(numero)}`;
            }
        }
        return nombreModulo;
    }

    function convertirAromano(num) {
        const valores = [10, 9, 5, 4, 1];
        const simbolos = ['X', 'IX', 'V', 'IV', 'I'];
        let resultado = '';

        for (let i = 0; i < valores.length; i++) {
            while (num >= valores[i]) {
                resultado += simbolos[i];
                num -= valores[i];
            }
        }
        return resultado;
    }

    function mostrarPaginacionReporte(pagination) {
        let desde = ((pagination.current_page - 1) * pagination.per_page) + 1;
        let hasta = Math.min(pagination.current_page * pagination.per_page, pagination.total);

        $('#info-registros').text(`Mostrando ${desde} a ${hasta} de ${pagination.total} registros`);

        let paginationHtml = '';

        if (pagination.current_page > 1) {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="cargarPaginaReporte(${pagination.current_page - 1}); return false;">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            `;
        }

        let startPage = Math.max(1, pagination.current_page - 2);
        let endPage = Math.min(pagination.last_page, pagination.current_page + 2);

        for (let i = startPage; i <= endPage; i++) {
            paginationHtml += `
                <li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="cargarPaginaReporte(${i}); return false;">${i}</a>
                </li>
            `;
        }

        if (pagination.current_page < pagination.last_page) {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="cargarPaginaReporte(${pagination.current_page + 1}); return false;">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            `;
        }

        $('#pagination-controls').html(paginationHtml);
        $('#paginacion-reporte').show();
    }

    function cargarPaginaReporte(page) {
        $('#loading-reporte').show();
        $('#tbody-reporte').html(`
            <tr>
                <td colspan="5" class="text-center">
                    <i class="fas fa-spinner fa-spin"></i> Cargando página...
                </td>
            </tr>
        `);

        let filtros = {
            organizacion: $('#filtro_reporte_organizacion').val(),
            fecha_inicio: $('#filtro_reporte_fecha_inicio').val(),
            fecha_fin: $('#filtro_reporte_fecha_fin').val(),
            page: page
        };

        $.ajax({
            url: "{{ route('capacitaciones.reporte') }}",
            method: 'GET',
            data: filtros,
            success: function(response) {
                $('#loading-reporte').hide();
                if (response.success && response.data.length > 0) {
                    cargarTablaReporte(response.data, response.pagination);
                }
            },
            error: function() {
                $('#loading-reporte').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar la página solicitada.',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    }

    // ================== INIT DOCUMENT ==================
    $(document).ready(function() {
        initSelect2();

        $('#Id_Organizacion').on('change', function() {
            const id = $(this).val();
            const texto = $(this).find('option:selected').text();
            $('#info-caja').text(id ? texto : 'No seleccionada');
            cargarBeneficiariosSimplificado(id);
            updateInfoDisplays();
        });

        $('#beneficiarios').on('change', updateInfoDisplays);
        $('#Fecha').on('change', updateInfoDisplays);

        // Persistencia de pestaña activa
        $('button[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            localStorage.setItem('activeCapacitacionTab', e.target.id);
        });
        const activeTab = localStorage.getItem('activeCapacitacionTab');
        if (activeTab && $('#' + activeTab).length) {
            $('#' + activeTab).tab('show');
        }

        // Carga inicial si ya hay org seleccionada
        const orgInicial = $('#Id_Organizacion').val();
        if (orgInicial) {
            cargarBeneficiariosSimplificado(orgInicial);
        }
        updateInfoDisplays();
    });
</script>
@endsection
