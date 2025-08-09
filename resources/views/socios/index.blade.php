@extends('adminlte::page')

@section('title', 'Socio/Clientes') {{-- Cambia el título de la pestaña --}}

@section('content_header')
    <h1>Listado de Socios</h1>
@stop

@section('content')
    {{-- BOTONES --}}
    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('socios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Socio
        </a>
        <div>
            <a href="{{ route('socios.export') }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
            <a href="{{ route('socios.export-pdf', request()->query()) }}" class="btn btn-danger">Exportar PDF</a>

        </div>
    </div>
    {{-- FIN BOTONES --}}

    {{-- MENSAJES DE ÉXITO Y ERROR con SweetAlert2 --}}
    {{-- MENSAJES DE ÉXITO Y ERROR con SweetAlert2 --}}
    @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    @if(session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: @json(session('error')),
                    confirmButtonColor: '#d33',
                });
            });
        </script>
    @endif

    {{-- BUSCADOR --}}
    <form method="GET" action="{{ route('socios.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-2">
                <input type="text" name="search" class="form-control"
                       placeholder="Buscar nombre, DNI, teléfono"
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="genero" class="form-control">
                    <option value="">Género</option>
                    <option value="M" {{ request('genero')=='M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ request('genero')=='F' ? 'selected' : '' }}>Femenino</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="localidad" class="form-control"
                       placeholder="Localidad" value="{{ request('localidad') }}">
            </div>
            <div class="col-md-2">
                <input type="text" name="tipo" class="form-control"
                       placeholder="Tipo de socio" value="{{ request('tipo') }}">
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-control">
                    <option value="">Todos</option>
                    <option value="1" {{ request('estado')=='1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ request('estado')=='0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
                <div class="col-md-2 mt-2">
    <select name="departamento" class="form-control">
        <option value="">Departamento</option>
        @foreach(["Atlántida","Choluteca","Colón","Comayagua","Copán","Cortés","El Paraíso","Francisco Morazán","Gracias a Dios","Intibucá","Islas de la Bahía","La Paz","Lempira","Ocotepeque","Olancho","Santa Bárbara","Valle","Yoro"] as $dep)
            <option value="{{ $dep }}" {{ request('departamento') == $dep ? 'selected' : '' }}>{{ $dep }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-2 mt-2">
    <select name="estado_civil" class="form-control">
        <option value="">Estado Civil</option>
        @foreach(["Soltero(a)","Casado(a)","Unión Libre","Viudo(a)"] as $estado)
            <option value="{{ $estado }}" {{ request('estado_civil') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-2 mt-2">
    <select name="nivel_educativo" class="form-control">
        <option value="">Nivel Educativo</option>
        @foreach(["Sin estudios","Educación básica","Educación media","Educación superior"] as $nivel)
            <option value="{{ $nivel }}" {{ request('nivel_educativo') == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-2 mt-2">
    <input type="number" name="edad" class="form-control"
           placeholder="Edad" value="{{ request('edad') }}">
</div>

        </div>
    </form>
    {{-- FIN BUSCADOR --}}

    {{-- TABLA SOCIOS --}}
    <div class="table-responsive">
        <table id="tabla-socios" class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Caja Rural</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
        @forelse($socios as $socio)
            <tr>
                <td>{{ $socio->Nombre_Beneficiario }}</td>
                <td>{{ $socio->organizacion->Nombre_Organizacion ?? 'N/D' }}</td>
                <td>{{ $socio->DNI }}</td>
                <td>{{ $socio->Telefono }}</td>
                <td class="text-center py-2">
    <div class="d-flex justify-content-center flex-wrap">
        {{-- Botón Editar - Azul --}}
        <button class="btn btn-primary btn-sm rounded mx-1"
                data-bs-toggle="modal"
                data-bs-target="#modalEditarSocio{{ $socio->Id_Beneficiario }}"
                title="Editar">
            <i class="fas fa-edit"></i>
        </button>

        {{-- Botón Ficha - Azul --}}
        <a href="{{ route('socios.ficha', $socio->Id_Beneficiario) }}"
           class="btn btn-dark btn-sm rounded mx-1"
           title="Ficha">
            <i class="fas fa-eye"></i>
        </a>

        @if($socio->estado == 1)
            {{-- Botón Inactivar - Rojo --}}
            <form action="{{ route('socios.destroy', $socio->Id_Beneficiario) }}" method="POST" class="mx-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm rounded" onclick="return confirmarEliminacion(event)" title="Inactivar">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        @else
            {{-- Botón Activar - Verde --}}
            <form action="{{ route('socios.reactivar', $socio->Id_Beneficiario) }}" method="POST" class="mx-1">
                @csrf
                <button class="btn btn-success btn-sm rounded" onclick="return confirm('¿Seguro de reactivar este socio?')" title="Activar">
                    <i class="fas fa-check"></i>
                </button>
            </form>
        @endif
    </div>
</td>
                </tr>
                <!-- Modal Editar Socio -->
<div class="modal fade" id="modalEditarSocio{{ $socio->Id_Beneficiario }}" tabindex="-1" aria-labelledby="modalEditarSocioLabel{{ $socio->Id_Beneficiario }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="{{ route('socios.update', $socio->Id_Beneficiario) }}">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarSocioLabel{{ $socio->Id_Beneficiario }}">Editar Socio</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs" id="editTabs{{ $socio->Id_Beneficiario }}" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#datosPersonales{{ $socio->Id_Beneficiario }}">Datos Personales</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#ubicacion{{ $socio->Id_Beneficiario }}">Ubicación</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#informacionAdicional{{ $socio->Id_Beneficiario }}">Información Adicional</a></li>
          </ul>
          
          <div class="tab-content pt-3">
            <!-- Pestaña Datos Personales -->
            <div class="tab-pane fade show active" id="datosPersonales{{ $socio->Id_Beneficiario }}">
              <div class="row">
    <div class="col-md-6 mb-3">
      <label>Nombre completo</label>
      <input type="text" name="Nombre_Beneficiario"
       id="nombreBeneficiario{{ $socio->Id_Beneficiario }}"
       class="form-control"
       value="{{ $socio->Nombre_Beneficiario }}"
       maxlength="40"
       required>
              </div>
                <div class="col-md-6 mb-3">
                  <label>DNI</label>
                  <input type="text" name="DNI" class="form-control" value="{{ $socio->DNI }}" required pattern="\d{13}" maxlength="13" title="Ingrese 13 dígitos sin guiones">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
    <label>Teléfono</label>
    <input 
        type="text" 
        name="Telefono" 
        class="form-control" 
        id="telefono{{ $socio->Id_Beneficiario }}" 
        value="{{ old('Telefono', $socio->Telefono) }}" 
        required 
        pattern="\d{4}-\d{4}" 
        maxlength="9" 
        title="Formato: 1234-5678"
    >
    <small class="form-text text-muted">Formato: 1234-5678</small>
</div>
                <div class="col-md-6 mb-3">
                  <label>Género</label>
                  <select name="genero" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="M" {{ $socio->genero == 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ $socio->genero == 'F' ? 'selected' : '' }}>Femenino</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label>Fecha de nacimiento</label>
                  <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', \Carbon\Carbon::parse($socio->fecha_nacimiento)->format('Y-m-d')) }}">
                  </div>
                <div class="col-md-6 mb-3">
                  <label>Edad</label>
                 <input type="number" name="edad" id="edad" class="form-control" min="15" max="100" readonly value="{{ old('edad', \Carbon\Carbon::parse($socio->fecha_nacimiento)->age) }}">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label>Estado Civil</label>
                  <select name="estado_civil" class="form-control">
                    <option value="">Seleccione</option>
                    <option value="Soltero(a)" {{ $socio->estado_civil == 'Soltero(a)' ? 'selected' : '' }}>Soltero(a)</option>
                    <option value="Casado(a)" {{ $socio->estado_civil == 'Casado(a)' ? 'selected' : '' }}>Casado(a)</option>
                    <option value="Unión Libre" {{ $socio->estado_civil == 'Unión Libre' ? 'selected' : '' }}>Unión Libre</option>
                    <option value="Viudo(a)" {{ $socio->estado_civil == 'Viudo(a)' ? 'selected' : '' }}>Viudo(a)</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label>Etnia</label>
                  <select name="etnia" class="form-control">
                    <option value="">Seleccione</option>
                    <option value="Lenca" {{ $socio->etnia == 'Lenca' ? 'selected' : '' }}>Lenca</option>
                    <option value="Garífuna" {{ $socio->etnia == 'Garífuna' ? 'selected' : '' }}>Garífuna</option>
                    <option value="Miskito" {{ $socio->etnia == 'Miskito' ? 'selected' : '' }}>Miskito</option>
                    <option value="Tawahka" {{ $socio->etnia == 'Tawahka' ? 'selected' : '' }}>Tawahka</option>
                    <option value="Tolupan" {{ $socio->etnia == 'Tolupan' ? 'selected' : '' }}>Tolupan</option>
                    <option value="Pech" {{ $socio->etnia == 'Pech' ? 'selected' : '' }}>Pech</option>
                    <option value="Maya Chortí" {{ $socio->etnia == 'Maya Chortí' ? 'selected' : '' }}>Maya Chortí</option>
                    <option value="Negro de habla inglesa o Creole" {{ $socio->etnia == 'Negro de habla inglesa o Creole' ? 'selected' : '' }}>Negro de habla inglesa o Creole</option>
                    <option value="Mestizo" {{ $socio->etnia == 'Mestizo' ? 'selected' : '' }}>Mestizo</option>
                  </select>
                </div>
              </div>
             <div class="row">
              <div class="col-md-6 mb-3">
                  <div class="form-group">
      <label>Nivel Educativo</label>
      <select name="nivel_educativo" id="nivel_educativo{{ $socio->Id_Beneficiario }}" class="form-control">
        <option value="">Seleccione</option>
        <option value="Sin estudios" {{ $socio->nivel_educativo == 'Sin estudios' ? 'selected' : '' }}>Sin estudios</option>
        <option value="Educación Prebásica" {{ $socio->nivel_educativo == 'Educación Prebásica' ? 'selected' : '' }}>Educación Prebásica</option>
        <option value="Primaria" {{ $socio->nivel_educativo == 'Primaria' ? 'selected' : '' }}>Primaria</option>
        <option value="Ciclo Común" {{ $socio->nivel_educativo == 'Ciclo Común' ? 'selected' : '' }}>Ciclo Común</option>
        <option value="Diversificado" {{ $socio->nivel_educativo == 'Diversificado' ? 'selected' : '' }}>Diversificado</option>
        <option value="Universitario" {{ $socio->nivel_educativo == 'Universitario' ? 'selected' : '' }}>Universitario</option>
        <option value="Post grado" {{ $socio->nivel_educativo == 'Post grado' ? 'selected' : '' }}>Post grado</option>
        <option value="Doctorado" {{ $socio->nivel_educativo == 'Doctorado' ? 'selected' : '' }}>Doctorado</option>
      </select>
    </div>
  </div>

  <!-- Años estimados cursados -->
  <div class="col-md-6 mb-3">
     <div class="form-group">
      <label>Años estimados cursados</label>
      <input type="text" name="anios_educacion" id="anios_educacion{{ $socio->Id_Beneficiario }}" class="form-control" readonly value="{{ $socio->anios_educacion }}">
    </div>
  </div>
</div>
  <!-- Medio de Comunicación -->
  <div class="col-md-6 mb-3">
    <div class="form-group">
      <label>Medio de Comunicación</label>
      <select name="medio_comunicacion" class="form-control">
        <option value="">Seleccione</option>
        <option value="Teléfono" {{ $socio->medio_comunicacion == 'Teléfono' ? 'selected' : '' }}>Teléfono</option>
        <option value="Tablet" {{ $socio->medio_comunicacion == 'Tablet' ? 'selected' : '' }}>Tablet</option>
        <option value="Computadora" {{ $socio->medio_comunicacion == 'Computadora' ? 'selected' : '' }}>Computadora</option>
      </select>
    </div>
  </div>
</div>
            <!-- Pestaña Ubicación -->
            <div class="tab-pane fade" id="ubicacion{{ $socio->Id_Beneficiario }}">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label>Departamento</label>
                  <input type="text" name="departamento" class="form-control" value="{{ $socio->departamento }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                  <label>Municipio</label>
                  <input type="text" name="municipio" class="form-control" value="{{ $socio->municipio }}" readonly>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label>Comunidad/Aldea</label>
                  <input type="text" name="comunidad" class="form-control" value="{{ $socio->comunidad }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
    <label>Dirección</label>
    <input 
        type="text" 
        name="direccion" 
        class="form-control" 
        id="direccion{{ $socio->Id_Beneficiario }}" 
        value="{{ old('direccion', $socio->direccion) }}" 
        maxlength="40" 
        pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ0-9 ]{1,40}" 
        title="Solo letras, números y espacios. Máximo 40 caracteres." 
        required
    >
</div>

              </div>
            </div>
            
  <!-- Pestaña Información Adicional -->
                                     <div class="tab-pane fade" id="informacionAdicional{{ $socio->Id_Beneficiario }}">
                                            <h5>Actividades Económicas</h5>
                                            
                                            <div id="actividadesContainer{{ $socio->Id_Beneficiario }}">
                                                @if($socio->actividades && count($socio->actividades))
                                                    @foreach($socio->actividades as $i => $actividad)
                                                    <div class="row mb-2 actividad-item">
                                                        {{-- Campo Número --}}
            <div class="col-md-2">
                <input type="number" name="actividades[{{ $i }}][numero]" class="form-control"
                       placeholder="Número" value="{{ $actividad->Numero }}" required>
            </div>

                                                        <div class="col-md-2">
                                                            <input type="text" name="actividades[{{ $i }}][rubro]" class="form-control" placeholder="Rubro" value="{{ $actividad->Rubro }}" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="actividades[{{ $i }}][tipo]" class="form-control" required>
                                                                <option value="Agrícola" {{ $actividad->Tipo == 'Agrícola' ? 'selected' : '' }}>Agrícola</option>
                                                                <option value="No Agrícola" {{ $actividad->Tipo == 'No Agrícola' ? 'selected' : '' }}>No Agrícola</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="actividades[{{ $i }}][unidad]" class="form-control" required>
                                                                <option value="Manzanas" {{ $actividad->Unidad_Medida == 'Manzanas' ? 'selected' : '' }}>Manzanas</option>
                                                                <option value="Lempiras" {{ $actividad->Unidad_Medida == 'Lempiras' ? 'selected' : '' }}>Lempiras</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="number" step="0.01" name="actividades[{{ $i }}][cantidad]" class="form-control" placeholder="Cantidad" value="{{ $actividad->Cantidad }}" required>
                                                        </div>
                                                    
                                                        <div class="col-md-2">
                                                           <button type="button" class="btn btn-danger btn-sm"
        onclick="eliminarActividadEditar(this, {{ $socio->Id_Beneficiario }})">
    <i class="fas fa-trash"></i>
</button>

                                                        </div>
                                                    </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            
                                            <button type="button" class="btn btn-success btn-sm mt-2" onclick="agregarActividad('actividadesContainer{{ $socio->Id_Beneficiario }}')">
                                                <i class="fas fa-plus"></i> Agregar actividad
                                            </button>
                                            


              // Información adicional del socio
              <div class="row">
                 <div class="col-md-6 mb-3">
    <label>Tipo de Socio</label>
    <select name="Tipo_De_Socio" id="tipoSocio{{ $socio->Id_Beneficiario }}" class="form-control" required>
        <option value="">Seleccione</option>
        <option value="Socio" {{ $socio->Tipo_De_Socio == 'Socio' ? 'selected' : '' }}>Socio</option>
        <option value="Cliente" {{ $socio->Tipo_De_Socio == 'Cliente' ? 'selected' : '' }}>Cliente</option>
    </select>
</div>
                 <div class="col-md-6 mb-3" id="tipoCargoGroup{{ $socio->Id_Beneficiario }}" style="display: {{ $socio->Tipo_De_Socio == 'Socio' ? 'block' : 'none' }};">
                  <label for="Tipo_Cargo">Tipo de Cargo</label>
                  <select name="Tipo_Cargo" class="form-control">
                    <option value="">Seleccione</option>
                    @foreach($cargosDirectivos as $cargo)
                      <option value="{{ $cargo }}" {{ $socio->Tipo_Cargo == $cargo ? 'selected' : '' }}>{{ $cargo }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              
              <div class="row">
    <div class="col-md-6 mb-3">
      <div class="form-group" id="categoriaGroup{{ $socio->Id_Beneficiario }}" style="display: {{ $socio->Tipo_De_Socio != '' ? 'block' : 'none' }};">
        <label for="categoria">Categoría o Descripción</label>
        <input 
            type="text" 
            name="categoria" 
            class="form-control" 
            id="categoria{{ $socio->Id_Beneficiario }}" 
            value="{{ old('categoria', $socio->categoria) }}" 
            maxlength="50" 
            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]{1,50}" 
            title="Solo letras y espacios. Máximo 50 caracteres."
        >
    </div>
</div>

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
 @empty
                <tr>
                    <td colspan="5" class="text-center">No hay socios registrados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

@stop
@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/* === ACTIVIDADES - FORMULARIO CREACIÓN === */
function agregarActividad(containerId) {
    const tbody = document.getElementById(containerId);
    const index = tbody.querySelectorAll('tr').length;

    const fila = document.createElement('tr');
    fila.classList.add('actividad-item');

    fila.innerHTML = `
        <td>${index + 1}</td>
        <td>
            <select name="actividades[${index}][tipo]" class="form-control form-control-sm tipo-select" required onchange="actualizarRubro(this)">
                <option value="">Seleccione</option>
                <option value="Agrícola">Agrícola</option>
                <option value="No Agrícola">No Agrícola</option>
            </select>
        </td>
        <td>
            <select name="actividades[${index}][rubro]" class="form-control form-control-sm rubro-select" required>
                <option value="">Seleccione</option>
            </select>
        </td>
        <td>
            <select name="actividades[${index}][unidad]" class="form-control form-control-sm" required>
                <option value="">Seleccione</option>
                <option value="Manzanas">Manzanas</option>
                <option value="Lempiras">Lempiras</option>
            </select>
        </td>
        <td>
            <input type="number" step="0.01" name="actividades[${index}][cantidad]" class="form-control form-control-sm" required>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarActividad(this)">
                Eliminar
            </button>
        </td>
    `;

    tbody.appendChild(fila);
}

function eliminarActividad(btn) {
    const row = btn.closest('tr');
    row.remove();

    const rows = row.parentElement.querySelectorAll('tr');
    rows.forEach((r, i) => {
        r.querySelector('td:first-child').textContent = i + 1;
        r.querySelectorAll('select, input').forEach(el => {
            if (el.name) {
                el.name = el.name.replace(/\[\d+\]/, `[${i}]`);
            }
        });
    });
}

function actualizarRubro(select) {
    const tipo = select.value;
    const rubroSelect = select.closest('tr').querySelector('.rubro-select');
    
    rubroSelect.innerHTML = '<option value="">Seleccione</option>';

    if (tipo === 'Agrícola') {
        rubroSelect.innerHTML += `
            <option value="Maíz">Maíz</option>
            <option value="Frijol">Frijol</option>
            <option value="Café">Café</option>
        `;
    } else if (tipo === 'No Agrícola') {
        rubroSelect.innerHTML += `
            <option value="Comercio">Comercio</option>
            <option value="Servicios">Servicios</option>
            <option value="Oficios varios">Oficios varios</option>
        `;
    }
}

/* === ACTIVIDADES - MODAL EDICIÓN === */
function agregarActividadEditar(id) {
    const tabla = document.querySelector(`#tablaActividadesEditar${id}`);
    const tbody = tabla.querySelector('tbody');
    const index = tbody.querySelectorAll('tr').length;

    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${index + 1}</td>
        <td>
            <select name="actividades[${index}][tipo]" class="form-control form-control-sm tipo-select" required onchange="actualizarRubroEditar(this)">
                <option value="">Seleccione</option>
                <option value="Agrícola">Agrícola</option>
                <option value="No Agrícola">No Agrícola</option>
            </select>
        </td>
        <td>
            <select name="actividades[${index}][rubro]" class="form-control form-control-sm rubro-select" required>
                <option value="">Seleccione</option>
            </select>
        </td>
        <td>
            <select name="actividades[${index}][unidad]" class="form-control form-control-sm" required>
                <option value="Manzanas">Manzanas</option>
                <option value="Lempiras">Lempiras</option>
            </select>
        </td>
        <td>
            <input type="number" step="0.01" name="actividades[${index}][cantidad]" class="form-control form-control-sm" required>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarActividadEditar(this, ${id})">Eliminar</button>
        </td>
    `;
    tbody.appendChild(tr);
    tabla.style.display = '';
}

function eliminarActividadEditar(btn, id) {
    const row = btn.closest('.actividad-item');
    if (!row) {
        console.warn('No se encontró el contenedor de la actividad.');
        return;
    }

    row.remove();

    const container = document.querySelector(`#actividadesContainer${id}`);
    const filas = container.querySelectorAll('.actividad-item');

    // Reindexar nombres de los campos
    filas.forEach((fila, i) => {
        fila.querySelectorAll('input, select').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/g, `[${i}]`);
        });
    });
}

function actualizarRubroEditar(select) {
    const tipo = select.value;
    const rubroSelect = select.closest('tr').querySelector('.rubro-select');
    rubroSelect.innerHTML = '<option value="">Seleccione</option>';

    if (tipo === 'Agrícola') {
        ['Granos básicos', 'Vegetales', 'Café', 'Otros cultivos'].forEach(rubro => {
            rubroSelect.innerHTML += `<option value="${rubro}">${rubro}</option>`;
        });
    } else if (tipo === 'No Agrícola') {
        ['Pecuario', 'Servicio', 'Comercio', 'Consumo', 'Otros'].forEach(rubro => {
            rubroSelect.innerHTML += `<option value="${rubro}">${rubro}</option>`;
        });
    }
}

function mostrarTipoCargoEditar(select, id) {
    const grupo = document.getElementById(`tipoCargoEditar${id}`);
    grupo.style.display = select.value === 'Socio' ? 'block' : 'none';
}

/* === CONFIRMACIÓN DE ELIMINACIÓN === */
function confirmarEliminacion(e) {
    e.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¡Esta acción inactivará al socio!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            e.target.form.submit();
        }
    });
    return false;
}
document.addEventListener('DOMContentLoaded', function () {
    // Aplica validación a todos los campos de nombre en modales de edición
    document.querySelectorAll('input[name="Nombre_Beneficiario"]').forEach(input => {
        // KEY PRESS: Bloquea caracteres inválidos al escribir
        input.addEventListener('keypress', function (e) {
            const char = e.key;
            const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]$/;
            if (!regex.test(char)) {
                e.preventDefault();
            }
        });

        // INPUT: Corrige texto pegado
        input.addEventListener('input', function () {
            this.value = this.value
                .replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')   // quita símbolos/números
                .replace(/\s{2,}/g, ' ')                   // evita múltiples espacios

            if (this.value.length > 40) {
                this.value = this.value.substring(0, 40); // máximo 40 caracteres
            }

            // Primera letra mayúscula
            if (this.value.length === 1) {
                this.value = this.value.charAt(0).toUpperCase();
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Validación para todos los campos de DNI en los modales
    document.querySelectorAll('input[name="DNI"]').forEach(input => {
        // Bloquear letras y símbolos al escribir
        input.addEventListener('keypress', function (e) {
            const char = e.key;
            if (!/^\d$/.test(char)) {
                e.preventDefault(); // Solo permite números
            }
        });

        // Limpiar caracteres inválidos pegados con Ctrl+V
        input.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, ''); // Solo números

            if (this.value.length > 13) {
                this.value = this.value.slice(0, 13); // Máximo 13 dígitos
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const fechaNacimientoInput = document.getElementById('fecha_nacimiento');
    const edadInput = document.getElementById('edad');

    fechaNacimientoInput?.addEventListener('change', function () {
        const fecha = this.value;

        // Validación de formato YYYY-MM-DD
        const regexFecha = /^\d{4}-\d{2}-\d{2}$/;
        if (!regexFecha.test(fecha)) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha inválida',
                text: 'Debe usar el formato YYYY-MM-DD.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#d33'
            });

            this.value = '';
            edadInput.value = '';
            return;
        }

        const nacimiento = new Date(fecha);
        const hoy = new Date();
        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const m = hoy.getMonth() - nacimiento.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) edad--;

        if (edad < 18 || edad > 100) {
            Swal.fire({
                icon: 'warning',
                title: 'Edad no permitida',
                text: 'Debe tener entre 18 y 100 años para registrarse.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#d33'
            });

            this.value = '';
            edadInput.value = '';
            return;
        }

        // Si es válida
        edadInput.value = edad;
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const estructuraEducativa = {
        "Sin estudios": "0",
        "Educación Prebásica": "2 a 3 años",
        "Primaria": "6 años (Total: 8 incluyendo Prebásica)",
        "Ciclo Común": "3 años (Total: 11)",
        "Diversificado": "1 a 3 años (Total: 14)",
        "Universitario": "1 a 5 años (Total: 19)",
        "Post grado": "2 años (Total: 21)",
        "Doctorado": "3 a 5 años (Total: 22)"
    };

    // Para todos los select de nivel educativo en los modales
    document.querySelectorAll('select[name="nivel_educativo"]').forEach(select => {
        const id = select.id.replace('nivel_educativo', '');
        const inputAnios = document.getElementById('anios_educacion' + id);

        // Cuando el usuario cambia el nivel educativo
        select.addEventListener('change', function () {
            inputAnios.value = estructuraEducativa[this.value] || '';
        });

        // Al cargar el modal con valor precargado
        if (select.value && estructuraEducativa[select.value]) {
            inputAnios.value = estructuraEducativa[select.value];
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
        @foreach($socios as $socio)
            const telefonoInput{{ $socio->Id_Beneficiario }} = document.getElementById('telefono{{ $socio->Id_Beneficiario }}');

            telefonoInput{{ $socio->Id_Beneficiario }}?.addEventListener('keypress', function (e) {
                const char = e.key;
                if (!/\d/.test(char) || this.value.replace('-', '').length >= 8) {
                    e.preventDefault();
                }
            });

            telefonoInput{{ $socio->Id_Beneficiario }}?.addEventListener('input', function () {
                let cleanValue = this.value.replace(/\D/g, '');
                if (cleanValue.length > 4) {
                    cleanValue = cleanValue.slice(0, 4) + '-' + cleanValue.slice(4, 8);
                }
                this.value = cleanValue.slice(0, 9);
            });
        @endforeach
    });

document.addEventListener('DOMContentLoaded', function () {
        @foreach($socios as $socio)
            const direccionInput{{ $socio->Id_Beneficiario }} = document.getElementById('direccion{{ $socio->Id_Beneficiario }}');

            direccionInput{{ $socio->Id_Beneficiario }}?.addEventListener('input', function () {
                this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ0-9 ]/g, '').slice(0, 40);
            });
        @endforeach
    });

     document.addEventListener('DOMContentLoaded', function () {
        @foreach($socios as $socio)
            const categoriaInput{{ $socio->Id_Beneficiario }} = document.getElementById('categoria{{ $socio->Id_Beneficiario }}');

            categoriaInput{{ $socio->Id_Beneficiario }}?.addEventListener('input', function () {
                // Permitir solo letras con tildes y espacios
                this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]/g, '').slice(0, 50);
            });
        @endforeach
    });

    document.addEventListener('DOMContentLoaded', function () {
        @foreach($socios as $socio)
            const tipoSocioSelect{{ $socio->Id_Beneficiario }} = document.getElementById('tipoSocio{{ $socio->Id_Beneficiario }}');
            const tipoCargoGroup{{ $socio->Id_Beneficiario }} = document.getElementById('tipoCargoGroup{{ $socio->Id_Beneficiario }}');
            const categoriaGroup{{ $socio->Id_Beneficiario }} = document.getElementById('categoriaGroup{{ $socio->Id_Beneficiario }}');

            if (tipoSocioSelect{{ $socio->Id_Beneficiario }} && tipoCargoGroup{{ $socio->Id_Beneficiario }} && categoriaGroup{{ $socio->Id_Beneficiario }}) {
                tipoSocioSelect{{ $socio->Id_Beneficiario }}.addEventListener('change', function () {
                    const valor = this.value;

                    tipoCargoGroup{{ $socio->Id_Beneficiario }}.style.display = valor === 'Socio' ? 'block' : 'none';
                    categoriaGroup{{ $socio->Id_Beneficiario }}.style.display = valor !== '' ? 'block' : 'none';
                });
            }
        @endforeach
    });

    // Configuración de DataTable para la tabla de socios
    $(document).ready(function() {
        $('#tabla-socios').DataTable({
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
            order: [[0, 'asc']], // Ordenar por nombre ascendente
            pageLength: 50, // Mostrar 50 registros por página
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]], // Opciones de registros por página
            responsive: true, // Hacer la tabla responsive
            searching: false // Desactivar el buscador de DataTables
        });
    });

</script>

 
@endsection
