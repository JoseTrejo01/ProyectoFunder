<form method="GET" action="{{ route('admin.reportes.index') }}#reporte2" class="mb-4">
    <div class="form-group">
        <label for="departamento2">Departamento</label>
        <select name="departamento" id="departamento2" class="form-control" style="width: 300px;">
            <option value="">-- Todos --</option>
            @foreach($departamentos as $dep)
                <option value="{{ $dep }}" {{ $departamento == $dep ? 'selected' : '' }}>{{ $dep }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Filtrar</button>
</form>
<button class="btn btn-info mb-3" type="button" data-bs-toggle="modal" data-bs-target="#modalGraficoCargos">Ver gráfico</button>
<!-- Modal del gráfico de cargos -->
<div class="modal fade" id="modalGraficoCargos" tabindex="-1" aria-labelledby="modalGraficoCargosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGraficoCargosLabel">Cargos según género por Departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <canvas id="graficoCargos" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Departamento</th>
                <th>Cargo</th>
                <th>Hombres</th>
                <th>Mujeres</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($cargosResumen) && isset($cargos))
                @foreach($cargosResumen as $dep => $cargosDep)
                    @foreach($cargos as $cargo)
                        <tr>
                            <td>{{ $dep }}</td>
                            <td>{{ $cargo }}</td>
                            <td>{{ $cargosDep[$cargo]['M'] ?? 0 }}</td>
                            <td>{{ $cargosDep[$cargo]['F'] ?? 0 }}</td>
                            <td>{{ ($cargosDep[$cargo]['M'] ?? 0) + ($cargosDep[$cargo]['F'] ?? 0) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @else
                <tr><td colspan="5">No hay datos para mostrar.</td></tr>
            @endif
        </tbody>
    </table>
</div>
@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let graficoCargosInstancia = null;
document.addEventListener('shown.bs.modal', function (event) {
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
</script>
@endsection
