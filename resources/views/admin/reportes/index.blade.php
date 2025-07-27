@extends('adminlte::page')

@section('title', 'Reportes Dinámicos')

@section('content_header')
    <h1>Reportes Dinámicos</h1>
@endsection

@section('content')
    <!-- PESTAÑAS DE REPORTES -->
    <ul class="nav nav-tabs mb-3" id="reporteTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="reporte1-tab" data-bs-toggle="tab" data-bs-target="#reporte1" type="button" role="tab" aria-controls="reporte1" aria-selected="true">Resumen General</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="reporte2-tab" data-bs-toggle="tab" data-bs-target="#reporte2" type="button" role="tab" aria-controls="reporte2" aria-selected="false">Cargos según género</button>
        </li>
    </ul>
    <div class="tab-content" id="reporteTabsContent">
        <div class="tab-pane fade show active" id="reporte1" role="tabpanel" aria-labelledby="reporte1-tab">
            <form method="GET" action="{{ route('admin.reportes.index') }}" class="mb-4">
                <div class="form-group">
                    <label for="departamento">Departamento</label>
                    <select name="departamento" id="departamento" class="form-control" style="width: 300px;">
                        <option value="">-- Todos --</option>
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep }}" {{ $departamento == $dep ? 'selected' : '' }}>{{ $dep }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
            <!-- Botón para abrir el modal del gráfico -->
            <button class="btn btn-info mb-3" type="button" data-bs-toggle="modal" data-bs-target="#modalGraficoSocios">Ver gráfico</button>
            <!-- Modal del gráfico -->
            <div class="modal fade" id="modalGraficoSocios" tabindex="-1" aria-labelledby="modalGraficoSociosLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalGraficoSociosLabel">Socios y No Socios por Departamento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <canvas id="graficoSocios" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let graficoSociosInstancia = null;
document.addEventListener('shown.bs.modal', function (event) {
    if (event.target.id === 'modalGraficoSocios' && !graficoSociosInstancia) {
        const ctx = document.getElementById('graficoSocios').getContext('2d');
        const data = {
            labels: [
                @foreach($resumen as $row)
                    "{{ $row->departamento }}",
                @endforeach
            ],
            datasets: [
                {
                    label: 'Socios',
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    data: [
                        @foreach($resumen as $row)
                            {{ $row->socios ?? 0 }},
                        @endforeach
                    ]
                },
                {
                    label: 'No Socios',
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    data: [
                        @foreach($resumen as $row)
                            {{ $row->no_socios ?? 0 }},
                        @endforeach
                    ]
                }
            ]
        };
        graficoSociosInstancia = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Socios y No Socios por Departamento' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
});
</script>
@endsection
            <thead>
                <tr>
                    <th rowspan="2">Departamento</th>
                    <th rowspan="2">No. de Cajas Rurales</th>
                    <th rowspan="2">Municipios</th>
                    <th rowspan="2">Comunidades</th>
                    <th colspan="2">Socios</th>
                    <th colspan="2">Particulares</th>
                    <th colspan="3">Beneficiarios</th>
                </tr>
                <tr>
                    <th>H</th>
                    <th>M</th>
                    <th>Adultos</th>
                    <th>Niños</th>
                    <th>Socios</th>
                    <th>No Socios</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resumen as $row)
                    <tr>
                        <td>{{ $row->departamento }}</td>
                        <td>{{ $row->cajas ?? '-' }}</td>
                        <td>{{ $row->municipios ?? '-' }}</td>
                        <td>{{ $row->comunidades ?? '-' }}</td>
                        <td>{{ $row->socios_h ?? '-' }}</td>
                        <td>{{ $row->socios_m ?? '-' }}</td>
                        <td>{{ $row->socios_adultos ?? '-' }}</td>
                        <td>{{ $row->socios_ninos ?? '-' }}</td>
                        <td>{{ $row->socios ?? '-' }}</td>
                        <td>{{ $row->no_socios ?? '-' }}</td>
                        <td>{{ ($row->socios + $row->no_socios) ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12">No hay datos para mostrar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
            </div>
        </div>
        <div class="tab-pane fade" id="reporte2" role="tabpanel" aria-labelledby="reporte2-tab">
            <!-- CARGOS SEGÚN GÉNERO -->
            <form method="GET" action="{{ route('admin.reportes.index') }}" class="mb-4" id="form-cargos-genero">
                <input type="hidden" name="tab" value="reporte2">
                <div class="form-group">
                    <label for="departamento_cargos">Departamento</label>
                    <select name="departamento_cargos" id="departamento_cargos" class="form-control" style="width: 300px;">
                        <option value="">-- Todos --</option>
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep }}" {{ (isset($departamento_cargos) && $departamento_cargos == $dep) ? 'selected' : '' }}>{{ $dep }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2">Departamento</th>
                            <th colspan="2">Presidente Consejo Admon.</th>
                            <th colspan="2">Secretario Consejo Admon.</th>
                            <th colspan="2">Tesorero Consejo Admon.</th>
                            <th colspan="2">Presidente Comité de Crédito</th>
                            <th colspan="2">Presidente Consejo Vigilancia</th>
                        </tr>
                        <tr>
                            <th>H</th>
                            <th>M</th>
                            <th>H</th>
                            <th>M</th>
                            <th>H</th>
                            <th>M</th>
                            <th>H</th>
                            <th>M</th>
                            <th>H</th>
                            <th>M</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($cargosGeneroResumen) && count($cargosGeneroResumen))
                            @foreach($cargosGeneroResumen as $dep => $cargos)
                                <tr>
                                    <td>{{ $dep }}</td>
                                    <td>{{ $cargos['Presidente Consejo Admon.']['M'] ?? 0 }}</td>
                                    <td>{{ $cargos['Presidente Consejo Admon.']['F'] ?? 0 }}</td>
                                    <td>{{ $cargos['Secretario Consejo Admon.']['M'] ?? 0 }}</td>
                                    <td>{{ $cargos['Secretario Consejo Admon.']['F'] ?? 0 }}</td>
                                    <td>{{ $cargos['Tesorero Consejo Admon.']['M'] ?? 0 }}</td>
                                    <td>{{ $cargos['Tesorero Consejo Admon.']['F'] ?? 0 }}</td>
                                    <td>{{ $cargos['Presidente Comité de Crédito']['M'] ?? 0 }}</td>
                                    <td>{{ $cargos['Presidente Comité de Crédito']['F'] ?? 0 }}</td>
                                    <td>{{ $cargos['Presidente Consejo Vigilancia']['M'] ?? 0 }}</td>
                                    <td>{{ $cargos['Presidente Consejo Vigilancia']['F'] ?? 0 }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="11">No hay datos para mostrar.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let graficoSociosInstancia = null;
let graficoCargosInstancia = null;
document.addEventListener('shown.bs.modal', function (event) {
    if (event.target.id === 'modalGraficoSocios' && !graficoSociosInstancia) {
        const ctx = document.getElementById('graficoSocios').getContext('2d');
        const data = {
            labels: [
                @foreach($resumen as $row)
                    "{{ $row->departamento }}",
                @endforeach
            ],
            datasets: [
                {
                    label: 'Socios',
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    data: [
                        @foreach($resumen as $row)
                            {{ $row->socios ?? 0 }},
                        @endforeach
                    ]
                },
                {
                    label: 'No Socios',
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    data: [
                        @foreach($resumen as $row)
                            {{ $row->no_socios ?? 0 }},
                        @endforeach
                    ]
                }
            ]
        };
        graficoSociosInstancia = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Socios y No Socios por Departamento' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
    if (event.target.id === 'modalGraficoCargos' && !graficoCargosInstancia) {
        @if(isset($cargosResumen) && isset($cargos))
        const ctx = document.getElementById('graficoCargos').getContext('2d');
        const labels = [
            @foreach(array_keys($cargosResumen) as $dep)
                "{{ $dep }}",
            @endforeach
        ];
        const colores = [
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)'
        ];
        const datasets = [];
        @php $cargoIdx = 0; @endphp
        @foreach($cargos as $cargo)
            datasets.push({
                label: '{{ $cargo }} (M)',
                backgroundColor: colores[@php echo $cargoIdx; @endphp],
                borderColor: colores[@php echo $cargoIdx; @endphp],
                borderWidth: 1,
                data: [
                    @foreach(array_keys($cargosResumen) as $dep)
                        {{ $cargosResumen[$dep][$cargo]['M'] ?? 0 }},
                    @endforeach
                ]
            });
            datasets.push({
                label: '{{ $cargo }} (F)',
                backgroundColor: colores[@php echo $cargoIdx; @endphp].replace('0.7', '0.3'),
                borderColor: colores[@php echo $cargoIdx; @endphp],
                borderWidth: 1,
                data: [
                    @foreach(array_keys($cargosResumen) as $dep)
                        {{ $cargosResumen[$dep][$cargo]['F'] ?? 0 }},
                    @endforeach
                ]
            });
            @php $cargoIdx++; @endphp
        @endforeach
        graficoCargosInstancia = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Cargos según género por Departamento' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        @endif
    }
});
// Activar la pestaña correcta según el parámetro 'tab' en la URL o el hash
document.addEventListener('DOMContentLoaded', function() {
    // Solución robusta: Prevenir submit, poner el hash y luego enviar el formulario manualmente
    var formCargos = document.getElementById('form-cargos-genero');
    if(formCargos) {
        formCargos.addEventListener('submit', function(e) {
            e.preventDefault();
            // Cambia el hash antes de enviar
            if(window.location.hash !== '#reporte2') {
                window.location.hash = '#reporte2';
            }
            // Espera a que el hash cambie y luego envía el formulario
            setTimeout(function() {
                formCargos.submit();
            }, 10);
        });
    }
    // Activar la pestaña según el hash
    function activarTabPorHash() {
        if(window.location.hash === '#reporte2') {
            var tabBtn = document.getElementById('reporte2-tab');
            if(tabBtn && window.bootstrap) {
                var tabInstance = window.bootstrap.Tab.getOrCreateInstance(tabBtn);
                tabInstance.show();
            } else if(tabBtn) {
                tabBtn.click();
            }
        } else {
            // Por defecto, mostrar la primera pestaña
            var tabBtn1 = document.getElementById('reporte1-tab');
            if(tabBtn1 && window.bootstrap) {
                var tabInstance1 = window.bootstrap.Tab.getOrCreateInstance(tabBtn1);
                tabInstance1.show();
            } else if(tabBtn1) {
                tabBtn1.click();
            }
        }
    }
    activarTabPorHash();
    window.addEventListener('hashchange', activarTabPorHash);
    // Sincronizar el hash al cambiar de pestaña
    var tabBtns = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabBtns.forEach(function(btn) {
        btn.addEventListener('shown.bs.tab', function(e) {
            var target = btn.getAttribute('data-bs-target');
            if(target) window.location.hash = target;
        });
    });
});
</script>
@endsection
        </div>
    </div>
@endsection
