@extends('adminlte::page')

@section('title', 'Cajas rurales y distribución de socios')

@section('content_header')
    <h1 class="mb-3">Cajas rurales y distribución de socios</h1>
@endsection

@section('content')

    {{-- FILTRO --}}
    <form method="GET" action="{{ route('admin.reportes.cajas') }}" class="mb-4">
        <div class="form-row align-items-end">
            
            <div class="col-md-4 mb-2">
                <label for="departamento">Departamento</label>
                <select name="departamento" id="departamento" class="form-control">
                    <option value="">-- Todos --</option>
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep }}" {{ request('departamento') == $dep ? 'selected' : '' }}>
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
                <button class="btn btn-info" type="button" data-toggle="modal" data-target="#modalGraficoSocios">
                    Ver gráfico
                </button>
            </div>

            <div class="col-md-auto mb-2">
                <a href="{{ route('admin.reportes.cajas.export', request()->query()) }}" 
                   class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>
            </div>

        </div>
    </form>

    {{-- MODAL GRÁFICO --}}
    <div class="modal fade" id="modalGraficoSocios" tabindex="-1" aria-labelledby="modalGraficoSociosLabel">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalGraficoSociosLabel">
                        Socios y No Socios por Departamento
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <canvas id="graficoSocios" height="110"></canvas>
                </div>

            </div>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="table-responsive">
        <table id="tabla-cajas" class="table table-bordered table-striped table-hover shadow-sm">
            <thead>
                <tr>
                    <th rowspan="2">Departamento</th>
                    <th rowspan="2">No. de Cajas</th>
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

@endsection


@section('js')

{{-- DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function () {

    // === DATATABLE ===
    $('#tabla-cajas').DataTable({
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
            paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
        },
        order: [[0, 'asc']],
        searching: false
    });


    // === GRÁFICO: SE CREA UNA SOLA VEZ ===
    let graficoSocios = null;

    $('#modalGraficoSocios').on('shown.bs.modal', function () {
        if (!graficoSocios) {

            const ctx = document.getElementById('graficoSocios').getContext('2d');

            graficoSocios = new Chart(ctx, {
                type: 'bar',
                data: {
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
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Socios y No Socios por Departamento' }
                    },
                    scales: { y: { beginAtZero: true } }
                }
            });

        }
    });

});
</script>
@endsection
