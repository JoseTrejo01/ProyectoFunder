@extends('adminlte::page')

@section('title', 'Mapa de Cajas Rurales')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 600px;
        min-height: 600px;
        width: 100%;
        border: 2px solid #ccc;
        border-radius: 8px;
    }
</style>
@endsection

@section('content')
    <h3 class="mb-3">Mapa de Cajas Rurales Registradas</h3>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="filtroDepartamento"><strong>Filtrar por Departamento:</strong></label>
            <select id="filtroDepartamento" class="form-control">
                <option value="">Todos los departamentos</option>
                @foreach($departamentos as $depto)
                    <option value="{{ $depto->Id_Departamento }}">{{ $depto->Nombre_Departamento }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="filtroMunicipio"><strong>Filtrar por Municipio:</strong></label>
            <select id="filtroMunicipio" class="form-control">
                <option value="">Todos los municipios</option>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button onclick="resetFiltros()" class="btn btn-secondary w-100">Mostrar todos</button>
        </div>
    </div>

    <div id="map"></div>
@endsection

@section('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let mapa = L.map('map').setView([14.072348, -87.214567], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: 'Map data © OpenStreetMap contributors'
    }).addTo(mapa);

    let marcadores = [];

    @foreach ($organizaciones as $org)
        @if ($org->aldea && $org->aldea->municipio && $org->aldea->municipio->coordenada)
            (function() {
                let marcador = L.marker([
                    {{ $org->aldea->municipio->coordenada->coordenada_y }},
                    {{ $org->aldea->municipio->coordenada->coordenada_x }}
                ]).bindPopup(`
                    <strong>{{ $org->Nombre_Organizacion }}</strong><br>
                    <small>Aldea: {{ $org->aldea->Nombre_Aldea ?? '-' }}</small><br>
                    <small>Municipio: {{ $org->aldea->municipio->Nombre_Municipio ?? '-' }}</small><br>
                    <small>Departamento: {{ $org->aldea->municipio->departamento->Nombre_Departamento ?? '-' }}</small><br>
                    <span class='badge bg-{{ $org->Estado_Organizacion == "ACTIVO" ? "success" : "danger" }}'>
                        {{ $org->Estado_Organizacion }}
                    </span>
                `).addTo(mapa);

                marcador.departamento = "{{ $org->aldea->municipio->departamento->Id_Departamento }}";
                marcador.municipio = "{{ $org->aldea->municipio->Nombre_Municipio ?? '' }}";
                marcadores.push(marcador);
            })();
        @endif
    @endforeach

    if (marcadores.length === 0) {
        L.marker([14.072348, -87.214567]).addTo(mapa)
            .bindPopup("Mapa cargado correctamente.<br>Sin cajas rurales con coordenadas.")
            .openPopup();
    }

    const municipiosPorDepto = @json($municipiosPorDepto);

    document.getElementById("filtroDepartamento").addEventListener("change", function () {
        const idDepto = this.value;
        const muniSelect = document.getElementById("filtroMunicipio");

        muniSelect.innerHTML = '<option value="">Todos los municipios</option>';

        if (municipiosPorDepto[idDepto]) {
            municipiosPorDepto[idDepto].forEach(muni => {
                const option = document.createElement('option');
                option.value = muni.Nombre_Municipio;
                option.textContent = muni.Nombre_Municipio;
                muniSelect.appendChild(option);
            });
        }

        filtrarMarcadores();
    });

    document.getElementById("filtroMunicipio").addEventListener("change", filtrarMarcadores);

    function filtrarMarcadores() {
        let filtroDepto = document.getElementById("filtroDepartamento").value;
        let filtroMuni = document.getElementById("filtroMunicipio").value;

        let marcadorCentrado = null;

        marcadores.forEach(m => {
            let coincide = 
                (!filtroDepto || m.departamento === filtroDepto) &&
                (!filtroMuni || m.municipio === filtroMuni);

            if (coincide) {
                mapa.addLayer(m);
                if (!marcadorCentrado) marcadorCentrado = m;
            } else {
                mapa.removeLayer(m);
            }
        });

        if (marcadorCentrado) {
            mapa.setView(marcadorCentrado.getLatLng(), 13);
            marcadorCentrado.openPopup();
        }
    }

    function resetFiltros() {
        document.getElementById("filtroDepartamento").value = '';
        document.getElementById("filtroMunicipio").innerHTML = '<option value="">Todos los municipios</option>';
        filtrarMarcadores();
    }

    setTimeout(() => {
        mapa.invalidateSize();
    }, 500);
</script>
@endsection
