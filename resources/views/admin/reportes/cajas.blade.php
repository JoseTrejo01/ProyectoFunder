@extends('adminlte::page')

@section('title', 'Cajas rurales y distribución de socios')

@section('content_header')
    <h1>Cajas rurales y distribución de socios</h1>
@endsection

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

@section('content')
    <form method="GET" action="{{ route('admin.reportes.cajas') }}" class="mb-4">
        <div class="form-group d-flex align-items-end gap-2" style="flex-wrap: wrap;">
            <div>
                <label for="departamento">Departamento</label>
                <select name="departamento" id="departamento" class="form-control" style="width: 300px; display: inline-block;">
                    <option value="">-- Todos --</option>
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep }}" {{ (isset($departamento) && $departamento == $dep) ? 'selected' : '' }}>{{ $dep }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary ms-2">Filtrar</button>
            <button class="btn btn-info ms-2" type="button" data-bs-toggle="modal" data-bs-target="#modalGraficoSocios">Ver gráfico</button>
            <a href="{{ route('admin.reportes.cajas.export', request()->query()) }}" class="btn btn-success ms-2">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </div>
    </form>
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
        <table id="tabla-cajas" class="table table-bordered table-striped table-hover shadow-sm">
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
@endsection

@section('js')
@parent
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" />
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#tabla-cajas').DataTable({
        paging: true,
        pageLength: 10,
        lengthChange: true,
        dom: 'lfrtip',
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
        searching: false
    });
});
</script>
@endsection

