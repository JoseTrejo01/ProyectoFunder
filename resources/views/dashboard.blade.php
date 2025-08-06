@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex flex-column">
        <h1>Dashboard</h1>
        @auth
            <h5 class="text-muted">Bienvenido, {{ auth()->user()->Nombre_Usuario }}</h5>
        @else
            <h5 class="text-muted">Bienvenido</h5>
        @endauth
    </div>
@stop

@section('content')
<div class="card">
  <div class="card-header"><h3 class="card-title">Comparativa Mensual</h3></div>
  <div class="card-body">
    <canvas id="multiChart" style="height: 200px;"></canvas>
  </div>
</div>

<div class="row mt-4 justify-content-center">
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Distribución de Socios</h5>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="sociosDoughnutChart" width="220" height="220" style="max-width:220px;max-height:220px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-12 mb-3">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Participación de Hombres y Mujeres en Cargos Directivos</h5>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="cargosBarChart" width="440" height="220" style="max-width:440px;max-height:220px;"></canvas>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <!-- Agrega aquí los estilos adicionales si es necesario -->
@stop

@section('js')
<<<<<<< HEAD
<<<<<<< HEAD
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Gráfico de anillo de socios
            var ctx = document.getElementById('sociosDoughnutChart').getContext('2d');
            var sociosDoughnutChart = new Chart(ctx, {
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
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(201, 203, 207, 0.7)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(201, 203, 207, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: false
                        }
                    }
                }
            });

            // Gráfico de barras horizontal de cargos directivos
            var ctxBar = document.getElementById('cargosBarChart').getContext('2d');
            var cargosBarChart = new Chart(ctxBar, {
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
                            backgroundColor: '#36a2eb',
                            borderColor: '#1e90ff',
                            borderWidth: 2,
                            borderRadius: 12,
                            hoverBackgroundColor: '#1e90ff',
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
                            backgroundColor: '#ff6384',
                            borderColor: '#e75480',
                            borderWidth: 2,
                            borderRadius: 12,
                            hoverBackgroundColor: '#e75480',
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
                    aspectRatio: 1.2,
                    animation: {
                        duration: 1200,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 15,
                                    weight: 'bold'
                                }
                            }
                        },
                        title: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: '#fff',
                            titleColor: '#333',
                            bodyColor: '#333',
                            borderColor: '#36a2eb',
                            borderWidth: 1,
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.x;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            stepSize: 1,
                            grid: {
                                color: '#e0e0e0',
                                borderDash: [4, 4]
                            },
                            ticks: {
                                font: {
                                    size: 13
                                }
                            }
                        },
                        y: {
                            grid: {
                                color: '#e0e0e0',
                                borderDash: [4, 4]
                            },
                            ticks: {
                                font: {
                                    size: 13
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
=======
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Indica aquí los módulos que deseas graficar
   const params = new URLSearchParams();
['evaluacion','socios','emprendimientos', 'organizacion'].forEach(m => params.append('modules[]', m));
const url = "{{ route('dashboard.chart-data') }}?" + params.toString();
      fetch(url)
=======
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams();
    ['evaluacion', 'socios', 'emprendimientos', 'organizacion'].forEach(m => params.append('modules[]', m));
    const url = "{{ route('dashboard.chart-data') }}?" + params.toString();

    fetch(url)
>>>>>>> origin/version_final
        .then(res => res.json())
        .then(json => {
            const ctx = document.getElementById('multiChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: { labels: json.labels, datasets: json.datasets },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                    interaction: { mode: 'nearest', intersect: true },
                    plugins: { tooltip: { enabled: true } }
                }
            });
        })
        .catch(console.error);
<<<<<<< HEAD
    });
  </script>
>>>>>>> bc713d5e6cb391f2e180dc9d81a79cb57559ef99
=======

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
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(201, 203, 207, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(201, 203, 207, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: false }
                }
            }
        });
    }

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
                        backgroundColor: '#36a2eb',
                        borderColor: '#1e90ff',
                        borderWidth: 2,
                        borderRadius: 12,
                        hoverBackgroundColor: '#1e90ff',
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
                        backgroundColor: '#ff6384',
                        borderColor: '#e75480',
                        borderWidth: 2,
                        borderRadius: 12,
                        hoverBackgroundColor: '#e75480',
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
                        labels: {
                            font: { size: 15, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#fff',
                        titleColor: '#333',
                        bodyColor: '#333',
                        borderColor: '#36a2eb',
                        borderWidth: 1,
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.x;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: '#e0e0e0', borderDash: [4, 4] },
                        ticks: { font: { size: 13 } }
                    },
                    y: {
                        grid: { color: '#e0e0e0', borderDash: [4, 4] },
                        ticks: { font: { size: 13 } }
                    }
                }
            }
        });
    }
});
</script>
>>>>>>> origin/version_final
@stop
