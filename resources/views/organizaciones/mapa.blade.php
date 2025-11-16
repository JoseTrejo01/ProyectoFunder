@extends('adminlte::page')

@section('title', 'Mapa de Cajas Rurales')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    /* --- Mejoras de contraste WCAG AA --- */
    h3 {
        color: #222; /* mejor contraste */
        font-weight: 700;
    }

    label {
        font-weight: 600;
        color: #1f2937;
    }

    .form-control {
        border: 2px solid #b5b5b5;
        border-radius: 6px;
    }

    .form-control:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 4px rgba(37, 99, 235, 0.6) !important;
        outline: none;
    }

    /* Botón accesible */
    .btn-secondary {
        background-color: #374151 !important;
        color: #fff !important;
        border: none;
    }

    .btn-secondary:hover {
        background-color: #111827 !important;
    }

    /* Mapa */
    #map {
        height: 600px;
        min-height: 600px;
        width: 100%;
        border: 3px solid #4b5563; /* alto contraste */
        border-radius: 10px;
    }

    /* Popup accesible Leaflet */
    .leaflet-popup-content {
        color: #111 !important;
        font-size: 14px;
        line-height: 1.4;
    }

    .leaflet-popup-content strong {
        color: #000;
        font-weight: 700;
    }
</style>
@endsection

@section('content')

    <section role="region" aria-labelledby="tituloMapa">
        <h3 id="tituloMapa" class="mb-3">Mapa de Cajas Rurales Registradas</h3>
    </section>

    <section role="search" aria-label="Filtros del mapa" class="mb-3">
        <div class="row g-3">

            <div class="col-md-4">
                <label for="filtroDepartamento" class="form-label">
                    <strong>Filtrar por Departamento:</strong>
                </label>
                <select id="filtroDepartamento" name="filtroDepartamento" class="form-control"
                        aria-label="Filtro por departamento">
                    <option value="">Todos los departamentos</option>
                    @foreach($departamentos as $depto)
                        <option value="{{ $depto->Id_Departamento }}">{{ $depto->Nombre_Departamento }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="filtroMunicipio" class="form-label">
                    <strong>Filtrar por Municipio:</strong>
                </label>
                <select id="filtroMunicipio" name="filtroMunicipio" class="form-control"
                        aria-label="Filtro por municipio">
                    <option value="">Todos los municipios</option>
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button onclick="resetFiltros()" 
                        class="btn btn-secondary w-100" 
                        aria-label="Mostrar todas las cajas rurales">
                    Mostrar todos
                </button>
            </div>

        </div>
    </section>

    <section role="application" aria-label="Mapa interactivo de cajas rurales">
        <div id="map"></div>
    </section>

@endsection

@section('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let mapa = L.map('map', {
        scrollWheelZoom: true,
        keyboard: true
    }).setView([14.072348, -87.214567], 7);

    // Capa base accesible
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: 'Mapa © OpenStreetMap'
    }).addTo(mapa);

    let marcadores = [];

    /* =======================
       Renderizado de marcadores
       ======================= */
    @foreach ($organizaciones as $org)
        @if ($org->aldea && $org->aldea->municipio && $org->aldea->municipio->coordenada)
            (function() {
                let marker = L.marker([
                    {{ $org->aldea->municipio->coordenada->coordenada_y }},
                    {{ $org->aldea->municipio->coordenada->coordenada_x }}
                ]).bindPopup(`
                    <div style="color:#111;">
                        <strong>{{ e($org->Nombre_Organizacion) }}</strong><br>
                        <small><strong>Aldea:</strong> {{ e($org->aldea->Nombre_Aldea) }}</small><br>
                        <small><strong>Municipio:</strong> {{ e($org->aldea->municipio->Nombre_Municipio) }}</small><br>
                        <small><strong>Departamento:</strong> {{ e($org->aldea->municipio->departamento->Nombre_Departamento) }}</small><br>
                        <span class="badge bg-{{ $org->Estado_Organizacion == 'ACTIVO' ? 'success' : 'danger' }}">
                            {{ e($org->Estado_Organizacion) }}
                        </span><br>
                        @if(isset($org->socios_count))
                            <small><strong>Total de Socios:</strong> {{ $org->socios_count }}</small>
                        @endif
                    </div>
                `).addTo(mapa);

                marker.departamento = "{{ $org->aldea->municipio->departamento->Id_Departamento }}";
                marker.municipio = "{{ $org->aldea->municipio->Nombre_Municipio }}";

                marcadores.push(marker);
            })();
        @endif
    @endforeach

    if (marcadores.length === 0) {
        L.marker([14.072348, -87.214567]).addTo(mapa)
            .bindPopup("<strong>Sin datos geográficos.</strong><br>El mapa se cargó correctamente.")
            .openPopup();
    }

    const municipiosPorDepto = @json($municipiosPorDepto);

    /* ========================================
       Lógica de filtros accesibles
    ========================================== */

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

    setTimeout(() => mapa.invalidateSize(), 500);
</script>
@endsection
