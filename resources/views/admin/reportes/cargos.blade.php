@extends('adminlte::page')

@section('title', 'Cargos según género')

@section('content_header')
    <h1>Cargos según género</h1>
@endsection

@section('content')
    @php
        // 1. EXTRAER TODOS LOS CARGOS ÚNICOS DEL DATASET RECIBIDO
        $cargosUnicos = [];
        if (isset($cargosGeneroResumen) && is_array($cargosGeneroResumen)) {
            foreach ($cargosGeneroResumen as $dep => $cargos) {
                // $cargos es un array de cargos para un departamento: [ 'Nombre Cargo' => [ 'M' => 1, 'F' => 0 ] ]
                foreach ($cargos as $cargoNombre => $generos) {
                    if (!in_array($cargoNombre, $cargosUnicos, true)) {
                        $cargosUnicos[] = $cargoNombre;
                    }
                }
            }
        }

        // Ordenar los cargos alfabéticamente para una vista consistente
        sort($cargosUnicos);
        $cargosList = $cargosUnicos;

        // 2. CALCULAR TOTALES POR GÉNERO Y CARGO
        $totales = [];
        $porcentajes = [];
        $totalGeneral = 0;

        foreach ($cargosList as $cargo) {
            $totales[$cargo]['M'] = 0; // Hombre
            $totales[$cargo]['F'] = 0; // Mujer

            if (isset($cargosGeneroResumen) && is_array($cargosGeneroResumen)) {
                foreach ($cargosGeneroResumen as $dep => $cargos) {
                    $totales[$cargo]['M'] += $cargos[$cargo]['M'] ?? 0;
                    $totales[$cargo]['F'] += $cargos[$cargo]['F'] ?? 0;
                }
            }

            $totalGeneral += $totales[$cargo]['M'] + $totales[$cargo]['F'];
        }

        // 3. CALCULAR PORCENTAJES
        foreach ($cargosList as $cargo) {
            $totalCargo = $totales[$cargo]['M'] + $totales[$cargo]['F'];
            $porcentajes[$cargo]['M'] = $totalCargo > 0 ? round(($totales[$cargo]['M'] / $totalCargo) * 100, 1) : 0;
            $porcentajes[$cargo]['F'] = $totalCargo > 0 ? round(($totales[$cargo]['F'] / $totalCargo) * 100, 1) : 0;
        }
    @endphp

    {{-- FILTRO --}}
    <form method="GET" action="{{ route('admin.reportes.cargos') }}" class="mb-4" id="form-cargos-genero">
        <div class="form-row align-items-end">
            <div class="col-md-4 mb-2">
                <label for="departamento_cargos">Departamento</label>
                <select name="departamento_cargos" id="departamento_cargos" class="form-control">
                    <option value="">-- Todos --</option>
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep }}" {{ (isset($departamento_cargos) && $departamento_cargos == $dep) ? 'selected' : '' }}>
                            {{ $dep }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-auto mb-2">
                <button type="submit" class="btn btn-primary">
                    Filtrar
                </button>
            </div>

            <div class="col-md-auto mb-2">
                <button class="btn btn-info" type="button" data-toggle="modal" data-target="#modalGraficoCargos">
                    Ver gráfico
                </button>
            </div>

            <div class="col-md-auto mb-2">
                <a href="{{ route('admin.reportes.cargos.export', request()->query()) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>
            </div>
        </div>
    </form>

    {{-- MODAL DEL GRÁFICO --}}
    <div class="modal fade" id="modalGraficoCargos" tabindex="-1" aria-labelledby="modalGraficoCargosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalGraficoCargosLabel">
                        Cargos según género por departamento
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <canvas id="graficoCargos" height="100"></canvas>
                </div>

            </div>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="table-responsive">
        <table id="tabla-cargos" class="table table-bordered table-striped table-hover shadow-sm">
            <thead>
                <tr>
                    <th rowspan="2">Departamento</th>
                    {{-- CABECERAS DE CARGOS DINÁMICAS --}}
                    @foreach($cargosList as $cargoNombre)
                        <th colspan="2">{{ $cargoNombre }}</th>
                    @endforeach
                </tr>
                <tr>
                    {{-- CABECERAS DE GÉNERO DINÁMICAS --}}
                    @foreach($cargosList as $cargoNombre)
                        <th>H</th> {{-- Hombre --}}
                        <th>M</th> {{-- Mujer --}}
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if(isset($cargosGeneroResumen) && count($cargosGeneroResumen) > 0)
                    {{-- FILAS DE DATOS POR DEPARTAMENTO --}}
                    @foreach($cargosGeneroResumen as $dep => $cargos)
                        <tr>
                            <td>{{ $dep }}</td>
                            @foreach($cargosList as $cargoNombre)
                                <td>{{ $cargos[$cargoNombre]['M'] ?? 0 }}</td> {{-- Hombre --}}
                                <td>{{ $cargos[$cargoNombre]['F'] ?? 0 }}</td> {{-- Mujer --}}
                            @endforeach
                        </tr>
                    @endforeach

                    {{-- FILA DE TOTALES --}}
                    <tr class="table-info font-weight-bold">
                        <td>Total</td>
                        @foreach($cargosList as $cargo)
                            <td>{{ $totales[$cargo]['M'] }}</td>
                            <td>{{ $totales[$cargo]['F'] }}</td>
                        @endforeach
                    </tr>

                    {{-- FILA DE PORCENTAJES --}}
                    <tr class="table-warning font-weight-bold">
                        <td>% Participación</td>
                        @foreach($cargosList as $cargo)
                            <td>{{ $porcentajes[$cargo]['M'] }}%</td>
                            <td>{{ $porcentajes[$cargo]['F'] }}%</td>
                        @endforeach
                    </tr>
                @else
                    <tr>
                        <td colspan="{{ count($cargosList) * 2 + 1 }}">No hay datos para mostrar.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection

@section('js')
    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Pasar datos desde Blade a JS
        const cargosGeneroResumen = @json(isset($cargosGeneroResumen) ? $cargosGeneroResumen : []);
        const cargosList = @json($cargosList ?? []);

        const departamentos = Object.keys(cargosGeneroResumen || {});
        const yValues = departamentos.map(dep => {
            let total = 0;
            if (cargosGeneroResumen[dep]) {
                Object.values(cargosGeneroResumen[dep]).forEach(cargo => {
                    total += (cargo['M'] || 0) + (cargo['F'] || 0);
                });
            }
            return total;
        });

        const barColors = [
            "#007bff", "#28a745", "#ffc107", "#dc3545", "#6f42c1",
            "#fd7e14", "#20c997", "#6610f2", "#e83e8c", "#17a2b8"
        ];

        let chartCargos = null;

        $(document).ready(function () {

            // DataTable
            $('#tabla-cargos').DataTable({
                paging: true,
                pageLength: 10,
                lengthChange: true,
                language: {
                    lengthMenu: 'Mostrar _MENU_ registros',
                    zeroRecords: 'No se encontraron resultados',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty: 'No hay datos',
                    infoFiltered: '(filtrado de _MAX_ registros)',
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
                searching: false
            });

            // Gráfico en modal (solo si hay datos)
            $('#modalGraficoCargos').on('shown.bs.modal', function () {
                if (!departamentos.length) {
                    return;
                }

                const ctx = document.getElementById('graficoCargos').getContext('2d');

                if (chartCargos) {
                    chartCargos.destroy();
                }

                chartCargos = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: departamentos,
                        datasets: [{
                            backgroundColor: departamentos.map((_, i) => barColors[i % barColors.length]),
                            data: yValues
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: {
                                display: true,
                                text: 'Total de cargos por departamento'
                            }
                        },
                        scales: {
                            x: {
                                barPercentage: 0.4,
                                categoryPercentage: 0.6
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Cantidad'
                                }
                            }
                        }
                    }
                });
            });

        });
    </script>
@endsection
