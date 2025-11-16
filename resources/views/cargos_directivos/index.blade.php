@extends('adminlte::page')

@section('title', 'Cargos Directivos')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h1 class="mb-0 fw-bold text-dark">
                <i class="fas fa-users-cog"></i> Distribución de Cargos por Caja Rural
            </h1>
            <p class="text-muted small mb-0">
                Visualiza y exporta la composición de cargos directivos por organización y departamento.
            </p>
        </div>
    </div>
@stop

@section('content')

    {{-- ================== FILTRO + EXPORTACIONES ================== --}}
    <div class="mb-3">

        {{-- FILTRO POR DEPARTAMENTO --}}
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('socios.cargos') }}" class="row g-2 align-items-end" aria-label="Filtro por departamento">
                    <div class="col-12 col-md-4 col-lg-3">
                        <label for="filtro_departamento" class="form-label fw-semibold text-dark">
                            <i class="fas fa-map-marker-alt"></i> Departamento
                        </label>
                        <select id="filtro_departamento" name="departamento" class="form-control form-select">
                            <option value="">Todos los departamentos</option>
                            @foreach($departamentos as $dep)
                                <option value="{{ $dep }}" {{ request('departamento') == $dep ? 'selected' : '' }}>
                                    {{ $dep }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3 col-lg-2">
                        <button type="submit" class="btn btn-primary w-100 mt-2 mt-md-0">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- BOTONES DE EXPORTACIÓN --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
            <div class="text-muted small">
                @if(request('departamento'))
                    Mostrando datos para el departamento: <strong>{{ request('departamento') }}</strong>
                @else
                    Mostrando datos para <strong>todos los departamentos</strong>.
                @endif
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('cargos.export', request()->query()) }}"
                   class="btn btn-success btn-sm shadow-sm"
                   aria-label="Exportar a Excel cargos directivos">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>

                <a href="{{ route('cargos.export-pdf', request()->query()) }}"
                   class="btn btn-danger btn-sm shadow-sm"
                   aria-label="Exportar a PDF cargos directivos">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
            </div>
        </div>
    </div>

    {{-- ================== MENSAJES DE ESTADO (SweetAlert2) ================== --}}
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

    {{-- ================== CARD PRINCIPAL CON TABS ================== --}}
    <div class="card shadow-sm border-0">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs flex-wrap" id="cargosTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link active"
                        id="detalle-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#detalle"
                        type="button"
                        role="tab"
                        aria-controls="detalle"
                        aria-selected="true">
                        <i class="fas fa-table"></i> Detalle por Caja Rural
                    </button>
                </li>

                @if(!request('departamento'))
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link"
                            id="resumen-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#resumen"
                            type="button"
                            role="tab"
                            aria-controls="resumen"
                            aria-selected="false">
                            <i class="fas fa-chart-bar"></i> Resumen de Cargos Directivos
                        </button>
                    </li>
                @endif
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="cargosTabsContent">

                {{-- ================== TAB 1: DETALLE ================== --}}
                <div class="tab-pane fade show active" id="detalle" role="tabpanel" aria-labelledby="detalle-tab">
                    <div class="table-responsive">
                        <table id="tabla-cargos" class="table table-bordered table-striped table-hover align-middle mb-0">
                            <caption class="sr-only">
                                Detalle de la distribución de cargos directivos por caja rural.
                            </caption>
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th scope="col">No.</th>
                                    @if(!request('departamento'))
                                        <th scope="col">Departamento</th>
                                    @endif
                                    <th scope="col">Caja Rural</th>
                                    <th scope="col">Presidente(a)</th>
                                    <th scope="col">Vicepresidente(a)</th>
                                    <th scope="col">Secretario(a)</th>
                                    <th scope="col">Tesorero(a)</th>
                                    <th scope="col">Vocal I</th>
                                    <th scope="col">Vocal II</th>
                                    <th scope="col">Vocal III</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cajas as $i => $caja)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>

                                        @if(!request('departamento'))
                                            <td>{{ $caja->departamento }}</td>
                                        @endif

                                        <td class="fw-semibold text-dark">
                                            {{ $caja->nombre_organizacion }}
                                        </td>

                                        {{-- Presidente --}}
                                        <td class="text-center">
                                            @if($caja->presidente_h && $caja->presidente_m)
                                                <span class="badge bg-info" title="Hombre y Mujer">H/M</span>
                                            @elseif($caja->presidente_h)
                                                <span class="badge bg-primary" title="Hombre">H</span>
                                            @elseif($caja->presidente_m)
                                                <span class="badge bg-pink" style="background-color:#e83e8c" title="Mujer">M</span>
                                            @endif
                                        </td>

                                        {{-- Vicepresidente --}}
                                        <td class="text-center">
                                            @if($caja->vicepresidente_h && $caja->vicepresidente_m)
                                                <span class="badge bg-info">H/M</span>
                                            @elseif($caja->vicepresidente_h)
                                                <span class="badge bg-primary">H</span>
                                            @elseif($caja->vicepresidente_m)
                                                <span class="badge bg-pink" style="background-color:#e83e8c">M</span>
                                            @endif
                                        </td>

                                        {{-- Secretario --}}
                                        <td class="text-center">
                                            @if($caja->secretario_h && $caja->secretario_m)
                                                <span class="badge bg-info">H/M</span>
                                            @elseif($caja->secretario_h)
                                                <span class="badge bg-primary">H</span>
                                            @elseif($caja->secretario_m)
                                                <span class="badge bg-pink" style="background-color:#e83e8c">M</span>
                                            @endif
                                        </td>

                                        {{-- Tesorero --}}
                                        <td class="text-center">
                                            @if($caja->tesorero_h && $caja->tesorero_m)
                                                <span class="badge bg-info">H/M</span>
                                            @elseif($caja->tesorero_h)
                                                <span class="badge bg-primary">H</span>
                                            @elseif($caja->tesorero_m)
                                                <span class="badge bg-pink" style="background-color:#e83e8c">M</span>
                                            @endif
                                        </td>

                                        {{-- Vocales --}}
                                        <td class="text-center">{{ $caja->vocal1 }}</td>
                                        <td class="text-center">{{ $caja->vocal2 }}</td>
                                        <td class="text-center">{{ $caja->vocal3 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ================== TAB 2: RESUMEN ================== --}}
                @if(!request('departamento'))
                    <div class="tab-pane fade" id="resumen" role="tabpanel" aria-labelledby="resumen-tab">
                        <div class="mb-3">
                            <h5 class="fw-bold text-dark">
                                <i class="fas fa-chart-pie"></i> Resumen de Cargos por Departamento
                            </h5>
                            <p class="text-muted small">
                                Estadísticas generales de la distribución de cargos directivos en todas las cajas rurales.
                            </p>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <caption class="sr-only">
                                    Resumen de cargos directivos por departamento.
                                </caption>
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th scope="col"><i class="fas fa-map-marker-alt"></i> Departamento</th>
                                        <th scope="col"><i class="fas fa-building"></i> Total Cajas</th>
                                        <th scope="col"><i class="fas fa-user-tie"></i> Presidentes</th>
                                        <th scope="col"><i class="fas fa-user-friends"></i> Vicepresidentes</th>
                                        <th scope="col"><i class="fas fa-user-edit"></i> Secretarios</th>
                                        <th scope="col"><i class="fas fa-coins"></i> Tesoreros</th>
                                        <th scope="col"><i class="fas fa-users"></i> Vocales</th>
                                        <th scope="col"><i class="fas fa-calculator"></i> Total Cargos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $totalGeneral = [
                                            'cajas' => 0,
                                            'presidentes' => 0,
                                            'vicepresidentes' => 0,
                                            'secretarios' => 0,
                                            'tesoreros' => 0,
                                            'vocales' => 0,
                                            'total_cargos' => 0
                                        ];
                                    @endphp

                                    @foreach($resumenDepartamentos as $dep => $datos)
                                        @php
                                            $totalGeneral['cajas']            += $datos['total_cajas'];
                                            $totalGeneral['presidentes']      += $datos['presidentes'];
                                            $totalGeneral['vicepresidentes']  += $datos['vicepresidentes'];
                                            $totalGeneral['secretarios']      += $datos['secretarios'];
                                            $totalGeneral['tesoreros']        += $datos['tesoreros'];
                                            $totalGeneral['vocales']          += $datos['vocales'];
                                            $totalGeneral['total_cargos']     += $datos['total_cargos'];
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>
                                                    <i class="fas fa-map-marker text-primary"></i> {{ $dep }}
                                                </strong>
                                            </td>
                                            <td><span class="badge bg-info">{{ $datos['total_cajas'] }}</span></td>
                                            <td><span class="badge bg-primary">{{ $datos['presidentes'] }}</span></td>
                                            <td><span class="badge bg-secondary">{{ $datos['vicepresidentes'] }}</span></td>
                                            <td><span class="badge bg-success">{{ $datos['secretarios'] }}</span></td>
                                            <td><span class="badge bg-warning text-dark">{{ $datos['tesoreros'] }}</span></td>
                                            <td><span class="badge bg-light text-dark">{{ $datos['vocales'] }}</span></td>
                                            <td><span class="badge bg-dark">{{ $datos['total_cargos'] }}</span></td>
                                        </tr>
                                    @endforeach

                                    {{-- Fila de totales --}}
                                    <tr class="bg-light fw-bold">
                                        <td>
                                            <strong><i class="fas fa-globe"></i> TOTAL GENERAL</strong>
                                        </td>
                                        <td><span class="badge bg-info">{{ $totalGeneral['cajas'] }}</span></td>
                                        <td><span class="badge bg-primary">{{ $totalGeneral['presidentes'] }}</span></td>
                                        <td><span class="badge bg-secondary">{{ $totalGeneral['vicepresidentes'] }}</span></td>
                                        <td><span class="badge bg-success">{{ $totalGeneral['secretarios'] }}</span></td>
                                        <td><span class="badge bg-warning text-dark">{{ $totalGeneral['tesoreros'] }}</span></td>
                                        <td><span class="badge bg-light text-dark">{{ $totalGeneral['vocales'] }}</span></td>
                                        <td><span class="badge bg-dark">{{ $totalGeneral['total_cargos'] }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Indicadores visuales --}}
                        <div class="row mt-4 g-3">
                            <div class="col-12 col-md-4">
                                <div class="info-box bg-info">
                                    <span class="info-box-icon"><i class="fas fa-building"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Cajas Rurales</span>
                                        <span class="info-box-number">{{ $totalGeneral['cajas'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Cargos Directivos</span>
                                        <span class="info-box-number">{{ $totalGeneral['total_cargos'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="info-box bg-warning">
                                    <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Promedio Cargos por Caja</span>
                                        <span class="info-box-number">
                                            {{ $totalGeneral['cajas'] > 0 ? number_format($totalGeneral['total_cargos'] / $totalGeneral['cajas'], 1) : '0' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endif

            </div>
        </div>
    </div>

@stop

@section('js')
    {{-- Bootstrap 5 + SweetAlert 2 + DataTables --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Inicializar DataTable con idioma español y responsive
            let table = $('#tabla-cargos').DataTable({
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
                order: [[0, 'asc']],
                pageLength: 50,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
                responsive: true,
                searching: false
            });

            // Ajustar columnas al cambiar de pestaña (para que no se rompa DataTables)
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                if (e.target.id === 'detalle-tab') {
                    table.columns.adjust().responsive.recalc();
                }
            });

            // Enviar formulario cuando cambie el filtro de departamento
            $('#filtro_departamento').on('change', function () {
                $(this).closest('form').submit();
            });

            // Restaurar pestaña activa desde localStorage
            let activeTab = localStorage.getItem('cargos_active_tab');
            if (activeTab && document.getElementById(activeTab)) {
                let tabTriggerEl = document.getElementById(activeTab);
                let tab = new bootstrap.Tab(tabTriggerEl);
                tab.show();
            }

            // Guardar pestaña activa
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                localStorage.setItem('cargos_active_tab', e.target.id);
            });
        });
    </script>
@endsection
