@extends('adminlte::page')



@section('content')
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: @json(session('success')),
                confirmButtonColor: '#5B8E3E',
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false
            });
        });
    </script>
@endif
<div class="container">
  <div class="d-flex align-items-center gap-2 mb-3">
    <!-- Botón Registrar -->
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistrarOrg">
        <i class="fas fa-plus-circle"></i> Registrar
    </button>

    <!-- Botón Ver Mapa -->
    <a href="{{ route('organizaciones.mapa') }}" class="btn btn-outline-info" title="Ver Mapa de Cajas Rurales">
        <i class="fas fa-map-marked-alt"></i> Ver Mapa
    </a>

    <!-- Botón Exportar PDF -->
    <a href="{{ route('organizaciones.exportar.pdf') }}" class="btn btn-outline-danger" title="Exportar PDF">
        <i class="fas fa-file-pdf"></i> Exportar PDF
    </a>
</div>

    <div class="table-responsive">
        <table id="tabla-organizaciones" class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Departamento</th>
                    <th>Municipio</th>
                    <th>Aldea</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($organizaciones as $org)
                    <tr>
                        <td>{{ $org->Id_Organizacion }}</td>
                        <td>{{ $org->Nombre_Organizacion }}</td>
                        <td>{{ $org->aldea && $org->aldea->municipio && $org->aldea->municipio->departamento ? $org->aldea->municipio->departamento->Nombre_Departamento : '' }}</td>
                        <td>{{ $org->aldea && $org->aldea->municipio ? $org->aldea->municipio->Nombre_Municipio : '' }}</td>
                        <td>{{ $org->aldea ? $org->aldea->Nombre_Aldea : '' }}</td>
                        <td>
                            @if($org->Estado_Organizacion == 'ACTIVO')
                                <span class="badge bg-success">ACTIVO</span>
                            @else
                                <span class="badge bg-danger">INACTIVO</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1" style="height:32px;">
                                <button class="btn btn-xs btn-primary p-1" data-bs-toggle="modal" data-bs-target="#modalEditarOrg{{ $org->Id_Organizacion }}" title="Editar" style="font-size: 0.85rem;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <!-- Modal Editar Organización -->
                                <div class="modal fade" id="modalEditarOrg{{ $org->Id_Organizacion }}" tabindex="-1" aria-labelledby="modalEditarOrgLabel{{ $org->Id_Organizacion }}" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <form method="POST" action="{{ route('organizaciones.update', $org->Id_Organizacion) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="modalEditarOrgLabel{{ $org->Id_Organizacion }}">Editar Organización</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                          <div class="mb-3">
                                            <label for="Nombre_Organizacion_{{ $org->Id_Organizacion }}" class="form-label">Nombre de la Organización</label>
                                           <input type="text" class="form-control" name="Nombre_Organizacion" id="Nombre_Organizacion_{{ $org->Id_Organizacion }}" value="{{ $org->Nombre_Organizacion }}" maxlength="40" pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ\s]{1,40}" title="Solo letras y espacios, máximo 40 caracteres" required>

                                          </div>
                                          <div class="row">
                                              <div class="col-md-4">
                                                  <label class="form-label">¿Tiene personería jurídica?</label>
                                                  <select name="tiene_personeria_juridica" id="tiene_personeria_juridica_edit_{{ $org->Id_Organizacion }}" class="form-control" required>
                                                      <option value="0" {{ $org->tiene_personeria_juridica ? '' : 'selected' }}>No</option>
                                                      <option value="1" {{ $org->tiene_personeria_juridica ? 'selected' : '' }}>Sí</option>
                                                  </select>
                                              </div>
                                              <div class="col-md-4" id="fecha_personeria_juridica_div_edit_{{ $org->Id_Organizacion }}" style="display:{{ $org->tiene_personeria_juridica ? 'block' : 'none' }};">
                                                  <label for="fecha_personeria_juridica_edit_{{ $org->Id_Organizacion }}" class="form-label">Fecha de obtención</label>
                                                  <input type="date" class="form-control" name="fecha_personeria_juridica" id="fecha_personeria_juridica_edit_{{ $org->Id_Organizacion }}" value="{{ $org->fecha_personeria_juridica }}">
                                              </div>
                                          </div>
                                          <div class="row mt-3">
                                              <div class="col-md-4">
                                                  <label for="municipio_{{ $org->Id_Organizacion }}" class="form-label">Municipio</label>
                                                  <select name="municipio" id="municipio_{{ $org->Id_Organizacion }}" class="form-control" required>
                                                      <option value="">Seleccione un municipio</option>
                                                      @if($org->aldea && $org->aldea->municipio)
                                                        @foreach($municipiosPorDepto[$org->aldea->municipio->Id_Departamento] ?? [] as $muni)
                                                          <option value="{{ $muni->Id_Municipio }}" {{ $org->aldea->municipio->Id_Municipio == $muni->Id_Municipio ? 'selected' : '' }}>{{ $muni->Nombre_Municipio }}</option>
                                                        @endforeach
                                                      @endif
                                                  </select>
                                              </div>
                                              <div class="col-md-4">
                                                  <label class="form-label">¿Tiene RTN?</label>
                                                  <select name="tiene_rtn" id="tiene_rtn_edit_{{ $org->Id_Organizacion }}" class="form-control" required>
                                                      <option value="0" {{ $org->tiene_rtn ? '' : 'selected' }}>No</option>
                                                      <option value="1" {{ $org->tiene_rtn ? 'selected' : '' }}>Sí</option>
                                                  </select>
                                              </div>
                                              <div class="col-md-4" id="rtn_div_edit_{{ $org->Id_Organizacion }}" style="display:{{ $org->tiene_rtn ? 'block' : 'none' }};">
                                                  <label for="rtn_edit_{{ $org->Id_Organizacion }}" class="form-label">RTN</label>
                                                  <input type="text" class="form-control" name="rtn" id="rtn_edit_{{ $org->Id_Organizacion }}" maxlength="20" value="{{ $org->rtn }}">
                                              </div>
                                          </div>
                                          <div class="row mt-3">
                                              <div class="col-md-4">
                                                  <label for="Nombre_Aldea_{{ $org->Id_Organizacion }}" class="form-label">Aldea</label>
                                                 <input type="text" class="form-control" name="Nombre_Aldea" id="Nombre_Aldea_{{ $org->Id_Organizacion }}" value="{{ $org->aldea ? $org->aldea->Nombre_Aldea : '' }}" maxlength="40" pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ\s]{1,40}" title="Solo letras y espacios, máximo 40 caracteres" required>

                                              </div>
                                              <div class="col-md-4">
                                                  <label class="form-label">¿Tiene cuenta bancaria?</label>
                                                  <select name="tiene_cuenta_bancaria" id="tiene_cuenta_bancaria_edit_{{ $org->Id_Organizacion }}" class="form-control" required>
                                                      <option value="0" {{ $org->tiene_cuenta_bancaria ? '' : 'selected' }}>No</option>
                                                      <option value="1" {{ $org->tiene_cuenta_bancaria ? 'selected' : '' }}>Sí</option>
                                                  </select>
                                              </div>
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                          <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                                <form action="{{ route('organizaciones.destroy', $org->Id_Organizacion) }}" method="POST" style="display:inline-block" onsubmit="return confirmarInactivacion(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger p-1" title="Borrar" style="font-size: 0.85rem;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay organizaciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

<!-- Modal Registrar Organización -->
<div class="modal fade" id="modalRegistrarOrg" tabindex="-1" aria-labelledby="modalRegistrarOrgLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('organizaciones.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="modalRegistrarOrgLabel">Registrar Organización</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="Nombre_Organizacion" class="form-label">Nombre de la Organización</label>
            <input type="text" class="form-control" name="Nombre_Organizacion" id="Nombre_Organizacion" maxlength="40" pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ\s]{1,40}" title="Solo letras y espacios, máximo 40 caracteres" required>

          </div>
          
          <div class="mb-3">
            <label for="departamento" class="form-label">Departamento</label>
            <select name="departamento" id="departamento" class="form-control" required>
              <option value="">Seleccione</option>
              @foreach($departamentos as $depto)
                <option value="{{ $depto->Id_Departamento }}">{{ $depto->Nombre_Departamento }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="municipio" class="form-label">Municipio</label>
            <select name="municipio" id="municipio" class="form-control" required>
              <option value="">Seleccione un municipio</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="Nombre_Aldea" class="form-label">Aldea</label>
            <input type="text" class="form-control" name="Nombre_Aldea" id="Nombre_Aldea" maxlength="40" pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ\s]{1,40}" title="Solo letras y espacios, máximo 40 caracteres" required>

          </div>
          <div class="mb-3">
            <label for="map">Ubicación geográfica</label>
            <div id="map" style="height: 300px;"></div>
          </div>
          <div class="row">
              <div class="col">
                  <label for="coordenada_y">Latitud</label>
                  <input
                      type="number"
                      step="0.00000001"
                      min="-90"
                      max="90"
                      name="coordenada_y"
                      id="coordenada_y"
                      class="form-control"
                      required
                  >
              </div>
              <div class="col">
                  <label for="coordenada_x">Longitud</label>
                  <input type="text" name="coordenada_x" id="coordenada_x" class="form-control" required>
              </div>
          </div>
          <div class="row mt-3">
              <div class="col-md-6">
                  <label class="form-label">¿Tiene personería jurídica?</label>
                  <select name="tiene_personeria_juridica" id="tiene_personeria_juridica" class="form-control" required>
                      <option value="0">No</option>
                      <option value="1">Sí</option>
                  </select>
              </div>
              <div class="col-md-6" id="fecha_personeria_juridica_div" style="display:none;">
                  <label for="fecha_personeria_juridica" class="form-label">Fecha de obtención</label>
                  <input type="date" class="form-control" name="fecha_personeria_juridica" id="fecha_personeria_juridica">
              </div>
          </div>
          <div class="row mt-3">
              <div class="col-md-6">
                  <label class="form-label">¿Tiene RTN?</label>
                  <select name="tiene_rtn" id="tiene_rtn" class="form-control" required>
                      <option value="0">No</option>
                      <option value="1">Sí</option>
                  </select>
              </div>
              <div class="col-md-6" id="rtn_div" style="display:none;">
                  <label for="rtn" class="form-label">RTN</label>
                  <input type="text" class="form-control" name="rtn" id="rtn" maxlength="20">
              </div>
          </div>
          <div class="row mt-3">
              <div class="col-md-6">
                  <label class="form-label">¿Tiene cuenta bancaria?</label>
                  <select name="tiene_cuenta_bancaria" id="tiene_cuenta_bancaria" class="form-control" required>
                      <option value="0">No</option>
                      <option value="1">Sí</option>
                  </select>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
      </form>
    </div>
  </div>
</div>

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const municipios = @json($municipiosPorDepto);
    const coordenadasPorMunicipio = @json($coordenadas);

    // Manejador para cambio de departamento
    document.getElementById('departamento').addEventListener('change', function () {
        const deptoId = this.value;
        const municipioSelect = document.getElementById('municipio');
        municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
        if (municipios[deptoId]) {
            municipios[deptoId].forEach(muni => {
                const option = document.createElement('option');
                option.value = muni.Id_Municipio;
                option.textContent = muni.Nombre_Municipio;
                municipioSelect.appendChild(option);
            });
        }
    });

    // Inactivación
    function confirmarInactivacion(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Estás seguro?',
            text: '¡Esta acción inactivará la organización!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, inactivar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                e.target.closest('form').submit();
            }
        });
        return false;
    }

    // DataTable
    $(document).ready(function () {
        $('#tabla-organizaciones').DataTable({
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
            order: [[0, 'desc']]
        });
    });

    // --- Mapa dentro del modal ---
    let map;
    let marker;

    $('#modalRegistrarOrg').on('shown.bs.modal', function () {
        // Inicializar solo si no existe
        if (!map) {
            map = L.map('map', {
                center: [14.634915, -87.849243],
                zoom: 8,
                zoomControl: true,
                scrollWheelZoom: true,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19,
            }).addTo(map);

            map.on('click', function (e) {
                const lat = e.latlng.lat.toFixed(8);
                const lng = e.latlng.lng.toFixed(8);
                document.getElementById('coordenada_y').value = lat;
                document.getElementById('coordenada_x').value = lng;
                actualizarMarcador(lat, lng);
            });
        }

        setTimeout(() => {
            map.invalidateSize();
        }, 200);
    });

    function actualizarMarcador(lat, lng) {
        const nuevaPos = L.latLng(lat, lng);
        if (marker) map.removeLayer(marker);
        marker = L.marker(nuevaPos).addTo(map);
        map.setView(nuevaPos, 14);
    }

    function esCoordenadaValida(lat, lng) {
        return (
            !isNaN(lat) && !isNaN(lng) &&
            lat >= -90 && lat <= 90 &&
            lng >= -180 && lng <= 180
        );
    }

    document.getElementById('coordenada_y').addEventListener('input', function () {
        const lat = parseFloat(this.value);
        const lng = parseFloat(document.getElementById('coordenada_x').value);
        if (esCoordenadaValida(lat, lng)) actualizarMarcador(lat, lng);
    });

    document.getElementById('coordenada_x').addEventListener('input', function () {
        const lng = parseFloat(this.value);
        const lat = parseFloat(document.getElementById('coordenada_y').value);
        if (esCoordenadaValida(lat, lng)) actualizarMarcador(lat, lng);
    });
    // Detectar apertura del modal y redibujar el mapa
$('#modalRegistrarOrg').on('shown.bs.modal', function () {
    setTimeout(() => {
        map.invalidateSize();
    }, 200); // pequeño retardo para asegurar que el modal esté visible
});

    // Mostrar/ocultar campos según selección en registro
    document.addEventListener('DOMContentLoaded', function() {
        const tienePersoneria = document.getElementById('tiene_personeria_juridica');
        const fechaPersoneriaDiv = document.getElementById('fecha_personeria_juridica_div');
        tienePersoneria.addEventListener('change', function() {
            fechaPersoneriaDiv.style.display = this.value == '1' ? 'block' : 'none';
        });
        const tieneRTN = document.getElementById('tiene_rtn');
        const rtnDiv = document.getElementById('rtn_div');
        tieneRTN.addEventListener('change', function() {
            rtnDiv.style.display = this.value == '1' ? 'block' : 'none';
        });
    });

    // Mostrar/ocultar campos en formularios de edición
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($organizaciones as $org)
            const tienePersoneriaEdit{{ $org->Id_Organizacion }} = document.getElementById('tiene_personeria_juridica_edit_{{ $org->Id_Organizacion }}');
            const fechaPersoneriaDivEdit{{ $org->Id_Organizacion }} = document.getElementById('fecha_personeria_juridica_div_edit_{{ $org->Id_Organizacion }}');
            if(tienePersoneriaEdit{{ $org->Id_Organizacion }}){
                tienePersoneriaEdit{{ $org->Id_Organizacion }}.addEventListener('change', function() {
                    fechaPersoneriaDivEdit{{ $org->Id_Organizacion }}.style.display = this.value == '1' ? 'block' : 'none';
                });
            }
            const tieneRTNEdit{{ $org->Id_Organizacion }} = document.getElementById('tiene_rtn_edit_{{ $org->Id_Organizacion }}');
            const rtnDivEdit{{ $org->Id_Organizacion }} = document.getElementById('rtn_div_edit_{{ $org->Id_Organizacion }}');
            if(tieneRTNEdit{{ $org->Id_Organizacion }}){
                tieneRTNEdit{{ $org->Id_Organizacion }}.addEventListener('change', function() {
                    rtnDivEdit{{ $org->Id_Organizacion }}.style.display = this.value == '1' ? 'block' : 'none';
                });
            }
        @endforeach
    });
</script>
<script>
    // Evitar el ingreso de números en campos de texto
    function bloquearNumeros(inputSelector) {
        document.querySelectorAll(inputSelector).forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (/\d/.test(e.key)) {
                    e.preventDefault();
                }
            });

            // Limpiar números pegados con CTRL+V
            input.addEventListener('input', function () {
                this.value = this.value.replace(/[0-9]/g, '');
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        bloquearNumeros('input[name="Nombre_Organizacion"]');
        bloquearNumeros('input[name="Nombre_Aldea"]');
        @foreach($organizaciones as $org)
            bloquearNumeros('#Nombre_Organizacion_{{ $org->Id_Organizacion }}');
            bloquearNumeros('#Nombre_Aldea_{{ $org->Id_Organizacion }}');
        @endforeach
    });
</script>

@endsection
