@extends('adminlte::page')

@section('title', 'Capacitaciones')

@section('content_header')
    <h1>Registro de Capacitaciones</h1>
@stop

@section('content')
<div class="container-fluid">
    {{-- BOTONES DE EXPORTACIÓN --}}
    <div class="mb-3 d-flex justify-content-end">
        <a href="{{ route('reporte.exportCapacitacionesExcel') }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Exportar Excel
        </a>
    </div>

    {{-- MENSAJES DE ÉXITO Y ERROR --}}
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
        <div class="card-header p-0">
            <ul class="nav nav-tabs" id="capacitacionesTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="formulario-tab" data-toggle="tab" data-target="#formulario" type="button" role="tab" aria-controls="formulario" aria-selected="true">
                        <i class="fas fa-plus-circle"></i> Registrar Capacitación
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="modulos-tab" data-toggle="tab" data-target="#modulos" type="button" role="tab" aria-controls="modulos" aria-selected="false">
                        <i class="fas fa-book"></i> Módulos y Temas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reporte-tab" data-toggle="tab" data-target="#reporte" type="button" role="tab" aria-controls="reporte" aria-selected="false">
                        <i class="fas fa-chart-bar"></i> Reporte de Capacitaciones
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="capacitacionesTabsContent">
                {{-- PESTAÑA INFORMACIÓN BÁSICA --}}
                <div class="tab-pane fade show active" id="formulario" role="tabpanel" aria-labelledby="formulario-tab">
                    <div class="row">
                        <div class="col-12">
                            <h5><i class="fas fa-info-circle"></i> Información Básica de la Capacitación</h5>
                            <p class="text-muted">Complete los datos generales de la capacitación antes de seleccionar los módulos y temas</p>
                        </div>
                    </div>

                    <form action="{{ route('capacitacion.store') }}" method="POST" id="capacitacionForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="Id_Organizacion" class="form-label">
                                        <i class="fas fa-building"></i> Caja Rural
                                    </label>
                                    <select name="Id_Organizacion" id="Id_Organizacion" class="form-control" required>
                                        <option value="">Seleccione una caja rural</option>
                                        @foreach($organizaciones as $org)
                                            <option value="{{ $org->Id_Organizacion }}">{{ $org->Nombre_Organizacion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="Fecha" class="form-label">
                                        <i class="fas fa-calendar"></i> Fecha de Capacitación
                                    </label>
                                    <input type="date" name="Fecha" id="Fecha" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="beneficiarios" class="form-label">
                                <i class="fas fa-users"></i> Beneficiarios Participantes
                            </label>
                            <select name="beneficiarios[]" id="beneficiarios" class="form-control" multiple required style="min-height: 150px;">
                                {{-- Se llenará dinámicamente según la caja rural seleccionada --}}
                            </select>
                            <small class="text-muted">Mantenga presionado Ctrl (Windows) o Cmd (Mac) para seleccionar múltiples beneficiarios</small>
                        </div>

                        {{-- Información de resumen --}}
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="info-box bg-info">
                                            <span class="info-box-icon"><i class="fas fa-building"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Caja Rural</span>
                                                <span class="info-box-number" id="info-caja">No seleccionada</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box bg-success">
                                            <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Beneficiarios</span>
                                                <span class="info-box-number" id="info-beneficiarios">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box bg-warning">
                                            <span class="info-box-icon"><i class="fas fa-calendar"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Fecha</span>
                                                <span class="info-box-number" id="info-fecha">No seleccionada</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <button type="button" class="btn btn-primary" onclick="irAModulos()">
                                        <i class="fas fa-arrow-right"></i> Continuar a Módulos y Temas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- PESTAÑA MÓDULOS Y TEMAS --}}
                <div class="tab-pane fade" id="modulos" role="tabpanel" aria-labelledby="modulos-tab">
                    <div class="row">
                        <div class="col-12">
                            <h5><i class="fas fa-book"></i> Selección de Módulos y Temas</h5>
                            <p class="text-muted">Marque los temas que cada beneficiario recibió en esta capacitación</p>
                        </div>
                    </div>

                    {{-- Información de la capacitación seleccionada --}}
                    <div class="alert alert-info" id="info-capacitacion" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><i class="fas fa-info-circle"></i> Capacitación:</strong>
                                <span id="resumen-info"></span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="volverAInformacion()">
                                <i class="fas fa-edit"></i> Modificar Información
                            </button>
                        </div>
                    </div>

                    <div id="contenido-modulos">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Complete primero la información básica:</strong> Debe seleccionar la caja rural, beneficiarios y fecha en la pestaña anterior antes de continuar.
                        </div>
                    </div>

                    {{-- Aquí se cargará dinámicamente el contenido de módulos --}}
                    <div id="modulos-dinamicos" style="display: none;">
                        @php
                            $modulosUnicos = collect($modulos)->unique('Nombre_Modulo')->values();
                        @endphp
                        
                        {{-- Pestañas de módulos --}}
                        <ul class="nav nav-tabs nav-tabs-bordered mb-3" id="moduloTabs" role="tablist">
                            @foreach($modulosUnicos as $idx => $modulo)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $idx === 0 ? 'active' : '' }}" id="tabModulo{{ $modulo->Id_Modulo }}" data-toggle="tab" data-target="#modulo{{ $modulo->Id_Modulo }}" type="button" role="tab" aria-controls="modulo{{ $modulo->Id_Modulo }}" aria-selected="{{ $idx === 0 ? 'true' : 'false' }}">
                                        <i class="fas fa-book-open"></i> {{ $modulo->Nombre_Modulo }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        
                        <div class="tab-content" id="moduloTabsContent">
                            @foreach($modulosUnicos as $idx => $modulo)
                                <div class="tab-pane fade {{ $idx === 0 ? 'show active' : '' }}" id="modulo{{ $modulo->Id_Modulo }}" role="tabpanel" aria-labelledby="tabModulo{{ $modulo->Id_Modulo }}">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6><i class="fas fa-book-open"></i> {{ $modulo->Nombre_Modulo }}</h6>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="seleccionarTodosModulo({{ $modulo->Id_Modulo }})">
                                                <i class="fas fa-check-double"></i> Seleccionar Todos
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deseleccionarTodosModulo({{ $modulo->Id_Modulo }})">
                                                <i class="fas fa-times"></i> Deseleccionar Todos
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-bordered" id="tabla-capacitacion-{{ $modulo->Id_Modulo }}">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th style="width: 50%;">
                                                        <i class="fas fa-bookmark"></i> Tema
                                                    </th>
                                                    <th style="width: 10%;">
                                                        <i class="fas fa-check-circle"></i> Todos
                                                    </th>
                                                    <th style="width: 40%;">
                                                        <i class="fas fa-users"></i> Beneficiarios
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($temasPorModulo[$modulo->Id_Modulo] as $tema)
                                                    <tr data-tema-id="{{ $tema->Id_Tema }}">
                                                        <td class="align-middle fw-semibold">{{ $tema->Nombre_Tema }}</td>
                                                        <td class="text-center align-middle">
                                                            <input type="checkbox" class="form-check-input tema-todos" onchange="toggleTema({{ $tema->Id_Tema }}, this.checked)">
                                                        </td>
                                                        <td class="beneficiarios-checkboxes align-middle">
                                                            {{-- Los checkboxes se llenan por JS --}}
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
                            <button type="button" class="btn btn-success btn-lg" onclick="enviarCapacitacion()">
                                <i class="fas fa-save"></i> Guardar Capacitación
                            </button>
                            <button type="button" class="btn btn-secondary btn-lg ms-2" onclick="volverAInformacion()">
                                <i class="fas fa-arrow-left"></i> Volver a Información
                            </button>
                        </div>
                    </div>
                </div>

                {{-- PESTAÑA REPORTE --}}
                <div class="tab-pane fade" id="reporte" role="tabpanel" aria-labelledby="reporte-tab">
                    <div class="row">
                        <div class="col-12">
                            <h5><i class="fas fa-chart-bar"></i> Reporte de Capacitaciones por Socio</h5>
                            <p class="text-muted">Consulte los módulos recibidos por cada socio en las capacitaciones registradas</p>
                        </div>
                    </div>

                    {{-- Filtros del reporte --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-filter"></i> Filtros de Búsqueda</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="filtro_reporte_organizacion" class="form-label">Caja Rural</label>
                                    <select id="filtro_reporte_organizacion" class="form-control">
                                        <option value="">Todas las cajas rurales</option>
                                        @foreach($organizaciones as $org)
                                            <option value="{{ $org->Id_Organizacion }}">{{ $org->Nombre_Organizacion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="filtro_reporte_fecha_inicio" class="form-label">Fecha Inicio</label>
                                    <input type="date" id="filtro_reporte_fecha_inicio" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="filtro_reporte_fecha_fin" class="form-label">Fecha Fin</label>
                                    <input type="date" id="filtro_reporte_fecha_fin" class="form-control">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-primary w-100" onclick="cargarReporte()">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla de reporte --}}
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-table"></i> Reporte de Capacitaciones</h6>
                        </div>
                        <div class="card-body">
                            <div id="loading-reporte" class="text-center" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Cargando...</span>
                                </div>
                                <p class="mt-2">Generando reporte...</p>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="tabla-reporte">
                                    <thead class="table-primary">
                                        <tr>
                                            <th><i class="fas fa-building"></i> Caja Rural</th>
                                            <th><i class="fas fa-user"></i> Socio/Beneficiario</th>
                                            <th><i class="fas fa-calendar"></i> Fechas de Capacitación</th>
                                            <th><i class="fas fa-book"></i> Módulos Recibidos</th>
                                            <th><i class="fas fa-bookmark"></i> Total Temas</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-reporte">
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="fas fa-info-circle"></i> Haga clic en "Generar Reporte" para cargar los datos
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Paginación --}}
                            <div id="paginacion-reporte" class="d-flex justify-content-between align-items-center mt-3" style="display: none;">
                                <div>
                                    <span class="text-muted" id="info-registros"></span>
                                </div>
                                <nav aria-label="Paginación del reporte">
                                    <ul class="pagination pagination-sm mb-0" id="pagination-controls">
                                        <!-- Los controles de paginación se cargarán aquí -->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    // Obtener datos de beneficiarios del controlador
    let beneficiariosPorOrg = @json($beneficiariosPorOrg);
    
    console.log('=== DEBUG NUEVA IMPLEMENTACIÓN ===');
    console.log('Datos recibidos del servidor:', beneficiariosPorOrg);
    console.log('Tipo de datos:', typeof beneficiariosPorOrg);
    console.log('Claves disponibles:', Object.keys(beneficiariosPorOrg || {}));
    
    // Función simplificada para cargar beneficiarios
    function cargarBeneficiariosSimplificado(organizacionId) {
        console.log('>>> Iniciando carga de beneficiarios para org:', organizacionId);
        
        const $selectBeneficiarios = $('#beneficiarios');
        $selectBeneficiarios.empty();
        
        if (!organizacionId) {
            $selectBeneficiarios.append('<option value="">Primero seleccione una caja rural</option>');
            return;
        }
        
        // Buscar beneficiarios con diferentes métodos de acceso
        let beneficiarios = null;
        
        // Método 1: Clave como string
        if (beneficiariosPorOrg[organizacionId.toString()]) {
            beneficiarios = beneficiariosPorOrg[organizacionId.toString()];
            console.log('Encontrados con clave string');
        }
        // Método 2: Clave como número
        else if (beneficiariosPorOrg[parseInt(organizacionId)]) {
            beneficiarios = beneficiariosPorOrg[parseInt(organizacionId)];
            console.log('Encontrados con clave numérica');
        }
        // Método 3: Búsqueda manual
        else {
            console.log('Búsqueda manual en todas las claves...');
            for (let key in beneficiariosPorOrg) {
                console.log(`Comparando: "${key}" == "${organizacionId}"`);
                if (key == organizacionId) {
                    beneficiarios = beneficiariosPorOrg[key];
                    console.log('Encontrado con búsqueda manual');
                    break;
                }
            }
        }
        
        console.log('Beneficiarios encontrados:', beneficiarios);
        
        if (!beneficiarios || !Array.isArray(beneficiarios) || beneficiarios.length === 0) {
            console.log('No hay beneficiarios disponibles');
            $selectBeneficiarios.append('<option value="">No hay beneficiarios disponibles</option>');
            
            // Mostrar alerta solo si no hay beneficiarios
            if (organizacionId) {
                Swal.fire({
                    icon: 'info',
                    title: 'Sin beneficiarios',
                    text: 'Esta caja rural no tiene beneficiarios registrados.',
                    confirmButtonColor: '#3085d6'
                });
            }
            return;
        }
        
        // Agregar opción por defecto
        $selectBeneficiarios.append('<option value="">Seleccione beneficiarios</option>');
        
        // Cargar beneficiarios
        beneficiarios.forEach(function(beneficiario) {
            console.log('Agregando beneficiario:', beneficiario.Nombre_Beneficiario);
            $selectBeneficiarios.append(
                `<option value="${beneficiario.Id_Beneficiario}">${beneficiario.Nombre_Beneficiario}</option>`
            );
        });
        
        console.log(`>>> Carga completada: ${beneficiarios.length} beneficiarios`);
    }

    $(document).ready(function() {
        console.log('=== INICIALIZACIÓN DOCUMENT READY ===');
        
        // Evento cambio organización
        $('#Id_Organizacion').on('change', function() {
            const organizacionId = $(this).val();
            const organizacionNombre = $(this).find('option:selected').text();
            $('#info-caja').text(organizacionNombre !== 'Seleccione una caja rural' ? organizacionNombre : 'No seleccionada');
            cargarBeneficiariosSimplificado(organizacionId);
            updateInfoDisplays();
        });
        
        // Otros eventos
        $('#beneficiarios').on('change', updateInfoDisplays);
        $('#Fecha').on('change', updateInfoDisplays);
        
        // Tabs persistencia
        $('button[data-toggle="tab"]').on('shown.bs.tab', e => localStorage.setItem('activeCapacitacionTab', e.target.id));
        let activeTab = localStorage.getItem('activeCapacitacionTab');
        if (activeTab && $('#' + activeTab).length) { $('#' + activeTab).tab('show'); }
        
        // Carga inicial
        const orgInicial = $('#Id_Organizacion').val();
        if (orgInicial) cargarBeneficiariosSimplificado(orgInicial);
        updateInfoDisplays();
        console.log('=== INICIALIZACIÓN COMPLETADA ===');
    });

    function updateInfoDisplays() {
        // Actualizar contador de beneficiarios
        let beneficiariosCount = $('#beneficiarios option:selected').length;
        $('#info-beneficiarios').text(beneficiariosCount);
        
        // Actualizar fecha
        let fecha = $('#Fecha').val();
        $('#info-fecha').text(fecha || 'No seleccionada');
        
        // Actualizar resumen en la segunda pestaña
        updateResumenCapacitacion();
    }

    function updateResumenCapacitacion() {
        let $caja = $('#Id_Organizacion');
        let $beneficiarios = $('#beneficiarios');
        let fecha = $('#Fecha').val();
        
        if ($caja.val() && $beneficiarios.val() && $beneficiarios.val().length > 0 && fecha) {
            let cajaText = $caja.find('option:selected').text();
            let beneficiariosCount = $beneficiarios.val().length;
            
            $('#resumen-info').html(`<strong>${cajaText}</strong> - ${beneficiariosCount} beneficiarios - ${fecha}`);
        } else {
            $('#resumen-info').html('Información incompleta');
        }
    }

    // Función para ir a la pestaña de módulos
    function irAModulos() {
        let caja = $('#Id_Organizacion').val();
        let beneficiarios = $('#beneficiarios').val();
        let fecha = $('#Fecha').val();
        
        console.log('Validando para ir a módulos:', { caja, beneficiarios, fecha });
        
        if (!caja || !beneficiarios || beneficiarios.length === 0 || !fecha) {
            Swal.fire({
                icon: 'warning',
                title: 'Información incompleta',
                text: 'Por favor complete todos los campos antes de continuar.',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        // Cambiar a la pestaña de módulos
        $('#modulos-tab').tab('show');
        
        // Mostrar información de la capacitación
        $('#info-capacitacion').show();
        
        // Ocultar mensaje de advertencia y mostrar módulos
        $('#contenido-modulos .alert-warning').hide();
        $('#modulos-dinamicos').show();
        
        // Llenar los checkboxes de beneficiarios en cada tema
        llenarCheckboxesBeneficiarios();
    }

    // Función para volver a la pestaña de información
    function volverAInformacion() {
        $('#formulario-tab').tab('show');
    }

    // Función para llenar los checkboxes de beneficiarios en cada tema
    function llenarCheckboxesBeneficiarios() {
        console.log('Llenando checkboxes de beneficiarios...');
        
        let beneficiariosSeleccionados = $('#beneficiarios').val();
        if (!beneficiariosSeleccionados || beneficiariosSeleccionados.length === 0) {
            console.log('No hay beneficiarios seleccionados');
            return;
        }
        
        // Limpiar todas las celdas de beneficiarios
        $('.beneficiarios-checkboxes').empty();
        
        // Para cada fila de tema
        $('tr[data-tema-id]').each(function() {
            let temaId = $(this).data('tema-id');
            let celda = $(this).find('.beneficiarios-checkboxes');
            
            // Obtener el ID del módulo desde la tabla padre
            let moduloId = $(this).closest('.tab-pane').attr('id').replace('modulo', '');
            
            // Crear checkboxes para cada beneficiario seleccionado
            beneficiariosSeleccionados.forEach(function(idBeneficiario) {
                let nombreBeneficiario = $(`#beneficiarios option[value="${idBeneficiario}"]`).text();
                
                let checkbox = `
                    <div class="form-check form-check-inline">
                        <input class="form-check-input beneficiario-checkbox" 
                               type="checkbox" 
                               id="beneficiario_${idBeneficiario}_modulo_${moduloId}_tema_${temaId}"
                               name="recibio[${idBeneficiario}][${moduloId}][${temaId}]"
                               value="1"
                               data-beneficiario="${idBeneficiario}"
                               data-modulo="${moduloId}"
                               data-tema="${temaId}">
                        <label class="form-check-label small" for="beneficiario_${idBeneficiario}_modulo_${moduloId}_tema_${temaId}">
                            ${nombreBeneficiario}
                        </label>
                    </div>
                `;
                
                celda.append(checkbox);
            });
        });
        
        console.log('Checkboxes de beneficiarios creados');
    }

    // Función para seleccionar todos los temas de un módulo
    function seleccionarTodosModulo(idModulo) {
        $(`#modulo${idModulo} .beneficiario-checkbox`).prop('checked', true);
        $(`#modulo${idModulo} .tema-todos`).prop('checked', true);
    }

    // Función para deseleccionar todos los temas de un módulo
    function deseleccionarTodosModulo(idModulo) {
        $(`#modulo${idModulo} .beneficiario-checkbox`).prop('checked', false);
        $(`#modulo${idModulo} .tema-todos`).prop('checked', false);
    }

    // Función para togglear todos los beneficiarios de un tema
    function toggleTema(idTema, checked) {
        $(`.beneficiario-checkbox[data-tema="${idTema}"]`).prop('checked', checked);
    }

    // Función para actualizar checkboxes
    function actualizarCheckboxes() {
        // Esta función se mantiene para compatibilidad
        console.log('Actualizando checkboxes...');
    }

    // Función para enviar la capacitación
    function enviarCapacitacion() {
        console.log('Preparando envío de capacitación...');
        
        // Validar que hay información básica
        let caja = $('#Id_Organizacion').val();
        let beneficiarios = $('#beneficiarios').val();
        let fecha = $('#Fecha').val();
        
        if (!caja || !beneficiarios || beneficiarios.length === 0 || !fecha) {
            Swal.fire({
                icon: 'warning',
                title: 'Información incompleta',
                text: 'Por favor complete la información básica antes de guardar.',
                confirmButtonColor: '#f39c12'
            });
            return;
        }
        
        // Crear FormData
        let formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('Id_Organizacion', caja);
        formData.append('Fecha', fecha);
        
        // Recopilar checkboxes marcados - FORMATO CORRECTO
        let temasSeleccionados = 0;
        $('.beneficiario-checkbox:checked').each(function() {
            let beneficiarioId = $(this).data('beneficiario');
            let moduloId = $(this).data('modulo');
            let temaId = $(this).data('tema');
            
            // Formato: recibio[beneficiario][modulo][tema] = 1
            let fieldName = `recibio[${beneficiarioId}][${moduloId}][${temaId}]`;
            formData.append(fieldName, '1');
            temasSeleccionados++;
            
            console.log(`Agregando: ${fieldName} = 1`);
        });
        
        console.log('Total temas seleccionados:', temasSeleccionados);
        
        if (temasSeleccionados === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Sin temas seleccionados',
                text: 'Por favor seleccione al menos un tema para un beneficiario.',
                confirmButtonColor: '#f39c12'
            });
            return;
        }
        
        // Mostrar loading
        Swal.fire({
            title: 'Guardando capacitación...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Debug: Mostrar todos los datos que se envían
        console.log('Datos a enviar:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        // Enviar via AJAX
        $.ajax({
            url: "{{ route('capacitacion.store') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Capacitación guardada exitosamente');
                
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Capacitación registrada correctamente.',
                    confirmButtonColor: '#28a745'
                }).then(function() {
                    // Recargar página para limpiar formulario
                    location.reload();
                });
            },
            error: function(xhr) {
                console.error('Error al guardar capacitación:', xhr);
                
                let errorMessage = 'Hubo un error al registrar la capacitación.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    console.log('Response text:', xhr.responseText);
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

    // ========== FUNCIONES PARA REPORTE DE CAPACITACIONES ==========
    
    // Función para cargar reporte
    function cargarReporte() {
        console.log('Cargando reporte de capacitaciones...');
        
        // Mostrar loading
        $('#loading-reporte').show();
        $('#tbody-reporte').html('<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Generando reporte...</td></tr>');
        $('#paginacion-reporte').hide();
        
        // Obtener filtros
        let filtros = {
            organizacion: $('#filtro_reporte_organizacion').val(),
            fecha_inicio: $('#filtro_reporte_fecha_inicio').val(),
            fecha_fin: $('#filtro_reporte_fecha_fin').val(),
            page: 1 // Siempre empezar en la página 1 cuando se hace una nueva búsqueda
        };
        
        console.log('Filtros del reporte:', filtros);
        
        // Llamada AJAX
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
                                <i class="fas fa-search"></i> No se encontraron datos con los filtros aplicados
                            </td>
                        </tr>
                    `);
                    $('#paginacion-reporte').hide();
                }
            },
            error: function(xhr) {
                $('#loading-reporte').hide();
                console.error('Error cargando reporte:', xhr);
                
                $('#tbody-reporte').html(`
                    <tr>
                        <td colspan="5" class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle"></i> Error al cargar el reporte
                        </td>
                    </tr>
                `);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar el reporte de capacitaciones',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    }
    
    // Función para cargar datos en la tabla de reporte
    function cargarTablaReporte(datos, pagination) {
        console.log('Cargando', datos.length, 'registros en la tabla de reporte');
        
        let html = '';
        datos.forEach(function(item) {
            let fechasHtml = item.fechas.map(fecha => {
                return `<span class="badge badge-info mr-1">${new Date(fecha).toLocaleDateString('es-ES')}</span>`;
            }).join('');
            
            // Convertir nombres de módulos a formato "Módulo I", "Módulo II", etc.
            let modulosHtml = item.modulos.map(modulo => {
                let numeroModulo = convertirANumeroRomano(modulo);
                return `<span class="mr-2">${numeroModulo}</span>`;
            }).join('');
            
            html += `
                <tr>
                    <td class="align-middle">
                        <strong><i class="fas fa-building text-primary"></i> ${item.caja_rural}</strong>
                    </td>
                    <td class="align-middle">
                        <i class="fas fa-user text-info"></i> ${item.beneficiario}
                    </td>
                    <td class="align-middle">
                        ${fechasHtml}
                    </td>
                    <td class="align-middle">
                        ${modulosHtml}
                    </td>
                    <td class="text-center align-middle">
                        <span class="badge badge-warning">${item.total_temas}</span>
                    </td>
                </tr>
            `;
        });
        
        $('#tbody-reporte').html(html);
        
        // Mostrar información de paginación si existe
        if (pagination) {
            mostrarPaginacionReporte(pagination);
        }
    }
    
    // Función para convertir nombres de módulos a números romanos
    function convertirANumeroRomano(nombreModulo) {
        // Mapeo básico para números romanos
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
        
        // Convertir a mayúsculas para la búsqueda
        let nombreUpper = nombreModulo.toUpperCase();
        
        // Si encontramos una coincidencia exacta, devolverla
        if (numerosRomanos[nombreUpper]) {
            return numerosRomanos[nombreUpper];
        }
        
        // Si contiene "MODULO" seguido de un número, convertirlo
        if (nombreUpper.includes('MODULO')) {
            // Extraer el número si está presente
            let match = nombreUpper.match(/MODULO\s*(\d+)/);
            if (match) {
                let numero = parseInt(match[1]);
                return `Módulo ${convertirAromano(numero)}`;
            }
        }
        
        // Si no se puede convertir, devolver el original
        return nombreModulo;
    }
    
    // Función auxiliar para convertir números a romanos
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
    
    // Función para mostrar controles de paginación
    function mostrarPaginacionReporte(pagination) {
        let desde = ((pagination.current_page - 1) * pagination.per_page) + 1;
        let hasta = Math.min(pagination.current_page * pagination.per_page, pagination.total);
        
        $('#info-registros').text(`Mostrando ${desde} a ${hasta} de ${pagination.total} registros`);
        
        let paginationHtml = '';
        
        // Botón anterior
        if (pagination.current_page > 1) {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="cargarPaginaReporte(${pagination.current_page - 1})">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            `;
        }
        
        // Números de página
        let startPage = Math.max(1, pagination.current_page - 2);
        let endPage = Math.min(pagination.last_page, pagination.current_page + 2);
        
        for (let i = startPage; i <= endPage; i++) {
            paginationHtml += `
                <li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="cargarPaginaReporte(${i})">${i}</a>
                </li>
            `;
        }
        
        // Botón siguiente
        if (pagination.current_page < pagination.last_page) {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="cargarPaginaReporte(${pagination.current_page + 1})">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            `;
        }
        
        $('#pagination-controls').html(paginationHtml);
        $('#paginacion-reporte').show();
    }
    
    // Función para cargar una página específica
    function cargarPaginaReporte(page) {
        let filtros = {
            organizacion: $('#filtro_reporte_organizacion').val(),
            fecha_inicio: $('#filtro_reporte_fecha_inicio').val(),
            fecha_fin: $('#filtro_reporte_fecha_fin').val(),
            page: page
        };
        
        $('#loading-reporte').show();
        $('#tbody-reporte').html('<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando página...</td></tr>');
        
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
            error: function(xhr) {
                $('#loading-reporte').hide();
                console.error('Error cargando página:', xhr);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar la página solicitada',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    }

</script>
@endsection
