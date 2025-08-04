@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="card">
  <div class="card-header"><h3 class="card-title">Comparativa Mensual</h3></div>
  <div class="card-body">
    <canvas id="multiChart" style="height: 200px;"></canvas>
  </div>
</div>

@stop

@section('css')
    <!-- Agrega aquí los estilos adicionales si es necesario -->
@stop

@section('js')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Indica aquí los módulos que deseas graficar
   const params = new URLSearchParams();
['evaluacion','socios','emprendimientos'].forEach(m => params.append('modules[]', m));
const url = "{{ route('dashboard.chart-data') }}?" + params.toString();
      fetch(url)
        .then(res => res.json())
        .then(json => {
         console.log('Respuesta del API:', json);
          const ctx = document.getElementById('multiChart').getContext('2d');
          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: json.labels,
              datasets: json.datasets
            },
            options: {
              responsive: true,
              scales: {
                y: { beginAtZero: true }
              }
            }
          });
        })
        .catch(console.error);
    });
  </script>
@stop
