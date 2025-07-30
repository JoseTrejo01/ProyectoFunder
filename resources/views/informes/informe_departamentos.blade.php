@extends('adminlte::page')

@section('title', 'Estado Financiero por Departamento')

@section('content_header')
    <h1>Estado Financiero por Departamento</h1>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let graficoFinancieroInstancia = null;
document.addEventListener('shown.bs.modal', function (event) {
    if (event.target.id === 'modalGraficoFinanciero' && !graficoFinancieroInstancia) {
        const ctx = document.getElementById('graficoFinanciero').getContext('2d');
        const data = {
            labels: [
                @foreach($resultados as $row)
                    "{{ $row->departamento }}",
                @endforeach
            ],
            datasets: [
                {
                    label: 'Total Monto Solicitado',
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    data: [
                        @foreach($resultados as $row)
                            {{ $row->total_monto_solicitado ?? 0 }},
                        @endforeach
                    ]
                },
                {
                    label: 'Depósitos de Ahorro',
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    data: [
                        @foreach($resultados as $row)
                            {{ $row->depositos_ahorro ?? 0 }},
                        @endforeach
                    ]
                }
            ]
        };
        graficoFinancieroInstancia = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Estado Financiero por Departamento' }
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
    <form method="GET" action="{{ url('informe-financiero') }}" class="mb-4">
        <div class="form-group d-flex align-items-end gap-2" style="flex-wrap: wrap;">
            <div>
                <label for="departamento">Departamento</label>
                <select name="departamento" id="departamento" class="form-control" style="width: 300px; display: inline-block;">
                    <option value="">-- Todos --</option>
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep }}" {{ (request('departamento') == $dep) ? 'selected' : '' }}>{{ $dep }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary ms-2">Filtrar</button>
            <button class="btn btn-info ms-2" type="button" data-bs-toggle="modal" data-bs-target="#modalGraficoFinanciero">Ver gráfico</button>
            <a href="{{ url('informe-financiero/export', request()->query()) }}" class="btn btn-success ms-2">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </div>
    </form>

    <!-- Modal del gráfico -->
    <div class="modal fade" id="modalGraficoFinanciero" tabindex="-1" aria-labelledby="modalGraficoFinancieroLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalGraficoFinancieroLabel">Estado Financiero por Departamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <canvas id="graficoFinanciero" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tabla-estado-financiero" class="table table-bordered table-striped table-hover shadow-sm">
            <thead>
                <tr>
                    <th>Departamento</th>
                    <th>Total Monto Solicitado</th>
                    <th>Intereses Cobrados</th>
                    <th>Capital Social</th>
                    <th>Capital de Trabajo</th>
                    <th>Reservas</th>
                    <th>Capital Semilla</th>
                    <th>Total Préstamos Registrados</th>
                    <th>Préstamos por Cobrar</th>
                    <th>Préstamos por Pagar</th>
                    <th>Saldo en Mora</th>
                    <th>% Mora</th>
                    <th>Depósitos de Ahorro</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resultados as $row)
                    <tr>
                        <td>{{ $row->departamento }}</td>
                        <td>{{ number_format($row->total_monto_solicitado ?? 0, 2) }}</td>
                        <td>{{ number_format($row->total_intereses_cobrados ?? 0, 2) }}</td>
                        <td>{{ number_format($row->total_capital_social ?? 0, 2) }}</td>
                        <td>{{ number_format($row->total_capital_trabajo ?? 0, 2) }}</td>
                        <td>{{ number_format($row->total_reservas ?? 0, 2) }}</td>
                        <td>{{ number_format($row->total_capital_semilla ?? 0, 2) }}</td>
                        <td>{{ $row->total_prestamos_registrados ?? 0 }}</td>
                        <td>{{ number_format($row->prestamos_por_cobrar ?? 0, 2) }}</td>
                        <td>{{ number_format($row->prestamos_por_pagar ?? 0, 2) }}</td>
                        <td>{{ number_format($row->saldo_prestamo_mora ?? 0, 2) }}</td>
                        <td>{{ $row->porcentaje_mora ?? 0 }}%</td>
                        <td>{{ number_format($row->depositos_ahorro ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center">No hay datos para mostrar.</td>
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
    $('#tabla-estado-financiero').DataTable({
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
