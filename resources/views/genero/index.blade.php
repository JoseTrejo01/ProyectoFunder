@extends('adminlte::page')

@section('title', 'Indicadores de Género')

@section('content_header')
    <h1>Indicadores de Género y Edad</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <canvas id="chartGenero" style="max-width: 600px; margin-bottom: 40px;"></canvas>
            <canvas id="chartEdades" style="max-width: 600px;"></canvas>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const generos = @json($generos);
const edades = @json($edades);

// Función para calcular porcentaje y mostrar en etiquetas
function calculatePercentages(data) {
  const total = Object.values(data).reduce((a, b) => a + b, 0);
  return Object.values(data).map(value => ((value / total) * 100).toFixed(1) + '%');
}

// Colores pastel para los gráficos
const coloresGeneros = ['#6c757d', '#f67280']; // gris y rosa suave
const coloresEdades = ['#4caf50', '#81c784', '#a5d6a7', '#c8e6c9']; // varios verdes pastel

// Gráfico Géneros
const ctxGenero = document.getElementById('chartGenero').getContext('2d');
const chartGenero = new Chart(ctxGenero, {
    type: 'bar',
    data: {
        labels: Object.keys(generos),
        datasets: [{
            label: 'Cantidad',
            data: Object.values(generos),
            backgroundColor: coloresGeneros,
            borderRadius: 10,
            borderSkipped: false,
            borderWidth: 1,
            borderColor: '#ddd',
            hoverBackgroundColor: '#495057',
            hoverBorderColor: '#343a40',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => {
                        const count = ctx.parsed.y;
                        const percent = calculatePercentages(generos)[ctx.dataIndex];
                        return ` ${count} (${percent})`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f0f0f0' },
                ticks: { color: '#495057', font: { size: 14 } }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#495057', font: { size: 14 } }
            }
        }
    }
});

// Gráfico Edades
const ctxEdades = document.getElementById('chartEdades').getContext('2d');
const chartEdades = new Chart(ctxEdades, {
    type: 'bar',
    data: {
        labels: Object.keys(edades),
        datasets: [{
            label: 'Cantidad',
            data: Object.values(edades),
            backgroundColor: coloresEdades,
            borderRadius: 10,
            borderSkipped: false,
            borderWidth: 1,
            borderColor: '#ddd',
            hoverBackgroundColor: '#388e3c',
            hoverBorderColor: '#2e7d32',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => {
                        const count = ctx.parsed.y;
                        const percent = calculatePercentages(edades)[ctx.dataIndex];
                        return ` ${count} (${percent})`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f0f0f0' },
                ticks: { color: '#495057', font: { size: 14 } }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#495057', font: { size: 14 } }
            }
        }
    }
});
</script>
@stop

