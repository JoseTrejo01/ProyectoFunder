@extends('adminlte::page')

@section('title', 'Ahorros - Selección de Caja Rural')

@section('content_header')
    <h1>Consulta de Ahorros por Caja Rural</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
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
                    <li class="list-group-item">📌 <strong>No. de Socios:</strong> <span id="numSocios">0</span></li>
                    <li class="list-group-item">💰 <strong>Total de Ahorros:</strong> L. <span id="totalAhorros">0.00</span></li>
                    <li class="list-group-item">📊 <strong>Promedio de Ahorros:</strong> L. <span id="promedioAhorros">0.00</span></li>
                </ul>
            </div>
        </div>
    </div>
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
@stop
