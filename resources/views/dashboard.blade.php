@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<div class="d-flex flex-column" role="banner">
    <h1 id="titulo-dashboard" class="fw-bold">Dashboard</h1>

    @auth
        <h5 class="text-muted" aria-label="Usuario autenticado">
            Bienvenido, {{ auth()->user()->Nombre_Usuario }}
        </h5>
    @else
        <h5 class="text-muted">Bienvenido</h5>
    @endauth
</div>
@stop

@section('content')

{{-- CARD DE COMPARATIVA --}}
<div class="card shadow-sm" role="region" aria-labelledby="comparativa-titulo">
    <div class="card-header text-white fw-semibold"
         style="background-color:#0D47A1;">
        <h3 id="comparativa-titulo" class="card-title mb-0">
            Comparativa Mensual
        </h3>
    </div>

    <div class="card-body">
        <canvas id="multiChart"
                style="height: 220px;"
                role="img"
                aria-label="Gráfico de líneas comparativo mensual"></canvas>
    </div>
</div>

{{-- FILA DE TARJETAS --}}
<div class="row mt-4 justify-content-center">

    {{-- DONUT SOCIOS --}}
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card h-100 shadow-sm"
             role="region"
             aria-labelledby="chart-socios-titulo">

            <div class="card-header text-white fw-semibold"
                 style="background-color:#1B5E20;">
                <h5 id="chart-socios-titulo" class="mb-0">
                    Distribución de Socios
                </h5>
            </div>

            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="sociosDoughnutChart"
                        width="220" height="220"
                        role="img"
                        aria-label="Gráfico de distribución de socios"></canvas>
            </div>
        </div>
    </div>

    {{-- BARRAS DE CARGOS --}}
    <div class="col-lg-8 col-md-12 mb-3">
        <div class="card h-100 shadow-sm"
             role="region"
             aria-labelledby="chart-cargos-titulo">

            <div class="card-header text-white fw-semibold"
                 style="background-color:#F9A825; color:black;">
                <h5 id="chart-cargos-titulo" class="mb-0">
                    Participación de Hombres y Mujeres en Cargos Directivos
                </h5>
            </div>

            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="cargosBarChart"
                        width="440" height="220"
                        role="img"
                        aria-label="Gráfico de barras de participación en cargos directivos"></canvas>
            </div>
        </div>
    </div>

</div>
@stop

@section('css')
{{-- Área para estilos adicionales --}}
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ============================
       CARGAR GRÁFICO MULTISERIES
       ============================ */
    const params = new URLSearchParams();
    ['evaluacion', 'socios', 'emprendimientos', 'organizacion']
        .forEach(m => params.append('modules[]', m));

    const url = "{{ route('dashboard.chart-data') }}?" + params.toString();

    fetch(url)
        .then(res => res.json())
        .then(json => {
            const ctx = document.getElementById('multiChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: json.labels,
                    datasets: json.datasets
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                    interaction: { mode: 'nearest', intersect: true },
                    plugins: {
                        tooltip: { enabled: true }
                    }
                }
            });
        })
        .catch(console.error);


    /* ============================
         GRÁFICO DONUT SOCIOS
       ============================ */
    const ctxSocios = document.getElementById('sociosDoughnutChart')?.getContext('2d');

    if (ctxSocios) {
        new Chart(ctxSocios, {
            type: 'doughnut',
            data: {
                labels: ['Hombres', 'Mujeres', 'Niños', 'No Socios'],
                datasets: [{
                    data: [
                        {{ $hombres ?? 0 }},
                        {{ $mujeres ?? 0 }},
                        {{ $ninos ?? 0 }},
                        {{ $noSocios ?? 0 }}
                    ],
                    backgroundColor: [
                        '#0D47A1CC',  // azul
                        '#B71C1CCC',  // rojo
                        '#F9A825CC',  // amarillo
                        '#424242CC'   // gris
                    ],
                    borderColor: [
                        '#0D47A1',
                        '#B71C1C',
                        '#F9A825',
                        '#424242'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: false }
                }
            }
        });
    }


    /* ============================
         GRÁFICO BARRAS CARGOS
       ============================ */
    const ctxBar = document.getElementById('cargosBarChart')?.getContext('2d');

    if (ctxBar) {
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: [
                    'Presidente Consejo Adm.',
                    'Secretario Consejo Adm.',
                    'Tesorero Consejo Adm.',
                    'Presidente Comité de Crédito',
                    'Presidente Consejo Vigilancia'
                ],
                datasets: [
                    {
                        label: 'Hombres',
                        backgroundColor: '#0D47A1',
                        borderColor: '#08306b',
                        borderWidth: 2,
                        borderRadius: 10,
                        hoverBackgroundColor: '#08306b',
                        data: [
                            {{ $participacionCargos['presidente']['H'] ?? 0 }},
                            {{ $participacionCargos['secretario']['H'] ?? 0 }},
                            {{ $participacionCargos['tesorero']['H'] ?? 0 }},
                            {{ $participacionCargos['presidente_credito']['H'] ?? 0 }},
                            {{ $participacionCargos['presidente_vigilancia']['H'] ?? 0 }}
                        ]
                    },
                    {
                        label: 'Mujeres',
                        backgroundColor: '#B71C1C',
                        borderColor: '#7f0000',
                        borderWidth: 2,
                        borderRadius: 10,
                        hoverBackgroundColor: '#7f0000',
                        data: [
                            {{ $participacionCargos['presidente']['M'] ?? 0 }},
                            {{ $participacionCargos['secretario']['M'] ?? 0 }},
                            {{ $participacionCargos['tesorero']['M'] ?? 0 }},
                            {{ $participacionCargos['presidente_credito']['M'] ?? 0 }},
                            {{ $participacionCargos['presidente_vigilancia']['M'] ?? 0 }}
                        ]
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { size: 14, weight: 'bold' } }
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#fff',
                        borderColor: '#0D47A1',
                        borderWidth: 1,
                        titleColor: '#333',
                        bodyColor: '#333',
                        padding: 10,
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: '#e0e0e0', borderDash: [4, 4] },
                        ticks: { font: { size: 12 } }
                    },
                    y: {
                        grid: { color: '#e0e0e0', borderDash: [4, 4] },
                        ticks: { font: { size: 12 } }
                    }
                }
            }
        });
    }

});
</script>
@stop
