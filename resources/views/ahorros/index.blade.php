@extends('adminlte::page')

@section('title', 'Ahorros - Selección de Caja Rural')

@section('content_header')
    <h1>Consulta de Ahorros por Caja Rural</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- Botón para crear nuevo ahorro --}}
            <a href="{{ route('ahorros.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Nuevo Ahorro
            </a>

            <form id="formCajaRural">
                <div class="form-group">
                    <label for="cajaRural">Seleccione una Caja Rural:</label>
                    <select name="cajaRural" id="cajaRural" class="form-control">
                        <option value="">-- Seleccione --</option>
                        @foreach($organizaciones as $organizacion)
                            <option value="{{ $organizacion->Id_Organizacion }}">{{ $organizacion->Nombre_Organizacion }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <hr>

            <div id="datosCaja" style="display:none;">
                <h5><strong>Resumen de la Caja Rural Seleccionada:</strong></h5>
                <ul class="list-group">
                    <li class="list-group-item">📌 <strong>No. de Ahorrantes:</strong> <span id="numSocios">0</span></li>
                    <li class="list-group-item">💰 <strong>Total de Ahorros:</strong> L. <span id="totalAhorros">0.00</span></li>
                    <li class="list-group-item">📊 <strong>Promedio de Ahorros:</strong> L. <span id="promedioAhorros">0.00</span></li>
                </ul>
            </div>
        </div>
    </div>
<<<<<<< HEAD
@stop

@section('js')
    <script>
        document.getElementById('cajaRural').addEventListener('change', function () {
            const id = this.value;
            if (!id) return;

            fetch(`/api/cajas/${id}/resumen`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('numSocios').textContent = data.num_socios;
                    document.getElementById('totalAhorros').textContent = parseFloat(data.total_ahorros).toFixed(2);
                    document.getElementById('promedioAhorros').textContent = parseFloat(data.promedio_ahorros).toFixed(2);
                    document.getElementById('datosCaja').style.display = 'block';
                })
                .catch(err => {
                    console.error('Error al obtener datos:', err);
                    alert('Hubo un error al cargar los datos.');
                });
        });
    </script>
=======

    {{-- MENSAJES DE ÉXITO --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- TABLA RESUMEN AGRUPADO --}}
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-primary">
            <tr>
                <th>Caja Rural</th>
                <th>Socios<br><small>(Cantidad / Total / Promedio)</small></th>
                <th>No Socios Adultos<br><small>(Cantidad / Total / Promedio)</small></th>
                <th>No Socios Jóvenes<br><small>(Cantidad / Total / Promedio)</small></th>
            </tr>
        </thead>
        <tbody>
            @forelse($agrupados as $caja => $datos)
                <tr>
                    <td>{{ $caja }}</td>
                    <td>
                        {{ $datos['socios']['cantidad'] }}<br>
                        L {{ number_format($datos['socios']['total'], 2) }}<br>
                        L {{ number_format($datos['socios']['promedio'], 2) }}
                    </td>
                    <td>
                        {{ $datos['no_socios_adultos']['cantidad'] }}<br>
                        L {{ number_format($datos['no_socios_adultos']['total'], 2) }}<br>
                        L {{ number_format($datos['no_socios_adultos']['promedio'], 2) }}
                    </td>
                    <td>
                        {{ $datos['no_socios_jovenes']['cantidad'] }}<br>
                        L {{ number_format($datos['no_socios_jovenes']['total'], 2) }}<br>
                        L {{ number_format($datos['no_socios_jovenes']['promedio'], 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay registros de ahorros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
>>>>>>> 580ad3c1da54615285b0879c8d6f6ca4b0b072d6
@stop
