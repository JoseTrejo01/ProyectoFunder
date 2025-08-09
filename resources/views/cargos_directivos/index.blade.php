@extends('adminlte::page')

@section('title', 'Cargos Directivos')

@section('content_header')
    <h1>Distribución de Cargos por Caja Rural</h1>
@stop

@section('content')
    {{-- BOTONES Y FILTROS --}}
    <div class="mb-3">
        {{-- Filtro por departamento --}}
        <form method="GET" action="{{ route('socios.cargos') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="departamento" class="form-control">
                        <option value="">Todos los departamentos</option>
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep }}" {{ request('departamento') == $dep ? 'selected' : '' }}>{{ $dep }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>

        {{-- Botones de exportación --}}
        <div class="d-flex justify-content-between">
            <div>
                {{-- Espacio para otros botones si se necesitan --}}
            </div>
            <div>
                <a href="{{ route('cargos.export', request()->query()) }}" class="btn btn-success me-2">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>
                <a href="{{ route('cargos.export-pdf', request()->query()) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
            </div>
        </div>
    </div>
    {{-- FIN BOTONES Y FILTROS --}}

    {{-- MENSAJES DE ÉXITO Y ERROR con SweetAlert2 --}}
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
            <ul class="nav nav-tabs" id="cargosTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="detalle-tab" data-bs-toggle="tab" data-bs-target="#detalle" type="button" role="tab" aria-controls="detalle" aria-selected="true">
                        <i class="fas fa-table"></i> Detalle por Caja Rural
                    </button>
                </li>
                @if(!request('departamento'))
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="resumen-tab" data-bs-toggle="tab" data-bs-target="#resumen" type="button" role="tab" aria-controls="resumen" aria-selected="false">
                        <i class="fas fa-chart-bar"></i> Resumen de Cargos Directivos
                    </button>
                </li>
                @endif
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="cargosTabsContent">
                {{-- PESTAÑA DETALLE --}}
                <div class="tab-pane fade show active" id="detalle" role="tabpanel" aria-labelledby="detalle-tab">
                    <div class="table-responsive">
                        <table id="tabla-cargos" class="table table-bordered table-striped table-hover shadow-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No.</th>
                                    @if(!request('departamento'))
                                        <th>Departamento</th>
                                    @endif
                                    <th>Caja Rural</th>
                                    <th>Presidente(a)</th>
                                    <th>Vicepresidente(a)</th>
                                    <th>Secretario(a)</th>
                                    <th>Tesorero(a)</th>
                                    <th>Vocal I</th>
                                    <th>Vocal II</th>
                                    <th>Vocal III</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cajas as $i => $caja)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        @if(!request('departamento'))
                                            <td>{{ $caja->departamento }}</td>
                                        @endif
                                        <td>{{ $caja->nombre_organizacion }}</td>
                                        <td>
                                            @if($caja->presidente_h && $caja->presidente_m)
                                                H/M
                                            @elseif($caja->presidente_h)
                                                H
                                            @elseif($caja->presidente_m)
                                                M
                                            @endif
                                        </td>
                                        <td>
                                            @if($caja->vicepresidente_h && $caja->vicepresidente_m)
                                                H/M
                                            @elseif($caja->vicepresidente_h)
                                                H
                                            @elseif($caja->vicepresidente_m)
                                                M
                                            @endif
                                        </td>
                                        <td>
                                            @if($caja->secretario_h && $caja->secretario_m)
                                                H/M
                                            @elseif($caja->secretario_h)
                                                H
                                            @elseif($caja->secretario_m)
                                                M
                                            @endif
                                        </td>
                                        <td>
                                            @if($caja->tesorero_h && $caja->tesorero_m)
                                                H/M
                                            @elseif($caja->tesorero_h)
                                                H
                                            @elseif($caja->tesorero_m)
                                                M
                                            @endif
                                        </td>
                                        <td>{{ $caja->vocal1 }}</td>
                                        <td>{{ $caja->vocal2 }}</td>
                                        <td>{{ $caja->vocal3 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- PESTAÑA RESUMEN --}}
                @if(!request('departamento'))
                <div class="tab-pane fade" id="resumen" role="tabpanel" aria-labelledby="resumen-tab">
                    <div class="mb-3">
                        <h5><i class="fas fa-chart-pie"></i> Resumen de Cargos por Departamento</h5>
                        <p class="text-muted">Estadísticas generales de la distribución de cargos directivos en todas las cajas rurales</p>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr class="bg-primary text-white">
                                    <th><i class="fas fa-map-marker-alt"></i> Departamento</th>
                                    <th><i class="fas fa-building"></i> Total Cajas</th>
                                    <th><i class="fas fa-user-tie"></i> Presidentes</th>
                                    <th><i class="fas fa-user-friends"></i> Vicepresidentes</th>
                                    <th><i class="fas fa-user-edit"></i> Secretarios</th>
                                    <th><i class="fas fa-coins"></i> Tesoreros</th>
                                    <th><i class="fas fa-users"></i> Vocales</th>
                                    <th><i class="fas fa-calculator"></i> Total Cargos</th>
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
                                        $totalGeneral['cajas'] += $datos['total_cajas'];
                                        $totalGeneral['presidentes'] += $datos['presidentes'];
                                        $totalGeneral['vicepresidentes'] += $datos['vicepresidentes'];
                                        $totalGeneral['secretarios'] += $datos['secretarios'];
                                        $totalGeneral['tesoreros'] += $datos['tesoreros'];
                                        $totalGeneral['vocales'] += $datos['vocales'];
                                        $totalGeneral['total_cargos'] += $datos['total_cargos'];
                                    @endphp
                                    <tr>
                                        <td><strong><i class="fas fa-map-marker text-primary"></i> {{ $dep }}</strong></td>
                                        <td><span class="badge badge-info">{{ $datos['total_cajas'] }}</span></td>
                                        <td><span class="badge badge-primary">{{ $datos['presidentes'] }}</span></td>
                                        <td><span class="badge badge-secondary">{{ $datos['vicepresidentes'] }}</span></td>
                                        <td><span class="badge badge-success">{{ $datos['secretarios'] }}</span></td>
                                        <td><span class="badge badge-warning">{{ $datos['tesoreros'] }}</span></td>
                                        <td><span class="badge badge-light">{{ $datos['vocales'] }}</span></td>
                                        <td><strong><span class="badge badge-dark">{{ $datos['total_cargos'] }}</span></strong></td>
                                    </tr>
                                @endforeach
                                {{-- Fila de totales --}}
                                <tr class="bg-light font-weight-bold">
                                    <td><strong><i class="fas fa-globe"></i> TOTAL GENERAL</strong></td>
                                    <td><strong><span class="badge badge-info">{{ $totalGeneral['cajas'] }}</span></strong></td>
                                    <td><strong><span class="badge badge-primary">{{ $totalGeneral['presidentes'] }}</span></strong></td>
                                    <td><strong><span class="badge badge-secondary">{{ $totalGeneral['vicepresidentes'] }}</span></strong></td>
                                    <td><strong><span class="badge badge-success">{{ $totalGeneral['secretarios'] }}</span></strong></td>
                                    <td><strong><span class="badge badge-warning">{{ $totalGeneral['tesoreros'] }}</span></strong></td>
                                    <td><strong><span class="badge badge-light">{{ $totalGeneral['vocales'] }}</span></strong></td>
                                    <td><strong><span class="badge badge-dark">{{ $totalGeneral['total_cargos'] }}</span></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Indicadores visuales adicionales --}}
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-building"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Cajas Rurales</span>
                                    <span class="info-box-number">{{ $totalGeneral['cajas'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Cargos Directivos</span>
                                    <span class="info-box-number">{{ $totalGeneral['total_cargos'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Promedio Cargos/Caja</span>
                                    <span class="info-box-number">{{ $totalGeneral['cajas'] > 0 ? number_format($totalGeneral['total_cargos'] / $totalGeneral['cajas'], 1) : '0' }}</span>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Inicializar DataTable
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

    // Manejar cambio de pestañas
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        // Redimensionar tabla cuando se muestra la pestaña de detalle
        if (e.target.id === 'detalle-tab') {
            table.columns.adjust().responsive.recalc();
        }
    });

    // Actualizar URL cuando cambia el filtro de departamento
    $('select[name="departamento"]').on('change', function() {
        const form = $(this).closest('form');
        form.submit();
    });

    // Mantener la pestaña activa después de filtrar
    @if(session('active_tab'))
        $('#{{ session('active_tab') }}').tab('show');
    @endif

    // Guardar pestaña activa antes de filtrar
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        localStorage.setItem('activeTab', e.target.id);
    });

    // Restaurar pestaña activa
    let activeTab = localStorage.getItem('activeTab');
    if (activeTab && $('#' + activeTab).length) {
        $('#' + activeTab).tab('show');
    }
});
</script>
@endsection
