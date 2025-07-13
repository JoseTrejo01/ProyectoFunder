@extends('adminlte::page')

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
            <a href="{{ route('socios.export-pdf', request()->query()) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
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

    {{-- TABLA --}}
    <table class="table table-striped table-hover table-bordered">
        <thead class="table-primary">
            <tr>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($socios as $socio)
                <tr>
                    <td>{{ $socio->Nombre_Beneficiario }}</td>
                    <td>{{ $socio->DNI }}</td>
                    <td>{{ $socio->Telefono }}</td>
                    <td>
                        <!-- Botón para abrir el modal de edición -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarSocio{{ $socio->Id_Beneficiario }}">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <a href="{{ route('socios.ficha', $socio->Id_Beneficiario) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ficha
                        </a>

                        @if($socio->estado == 1)
                            {{-- Botón inactivar --}}
                            <form action="{{ route('socios.destroy', $socio->Id_Beneficiario) }}"
                                  method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirmarEliminacion(event)">
                                    <i class="fas fa-trash"></i> Inactivar
                                </button>
                            </form>
                        @else
                            {{-- Botón reactivar --}}
                            <form action="{{ route('socios.reactivar', $socio->Id_Beneficiario) }}"
                                  method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-success btn-sm"
                                        onclick="return confirm('¿Seguro de reactivar este socio?')">
                                    <i class="fas fa-check"></i> Activar
                                </button>
                            </form>
                        @endif
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
                          <div class="row">
                            <div class="col-md-6 mb-3">
                              <label>Nombre completo</label>
                              <input type="text" name="Nombre_Beneficiario" class="form-control" value="{{ $socio->Nombre_Beneficiario }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                              <label>DNI</label>
                              <input type="text" name="DNI" class="form-control" value="{{ $socio->DNI }}" required pattern="\d{4}-\d{4}-\d{5}" title="Formato: 0000-0000-00000">
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6 mb-3">
                              <label>Teléfono</label>
                              <input type="text" name="Telefono" class="form-control" value="{{ $socio->Telefono }}" pattern="\d{4}-\d{4}" title="Formato: 1234-5678">
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
                              <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $socio->fecha_nacimiento }}">
                            </div>
                            <div class="col-md-6 mb-3">
                              <label>Edad</label>
                              <input type="number" name="edad" class="form-control" value="{{ $socio->edad }}" min="15" max="100">
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
                              <label>Nivel Educativo</label>
                              <select name="nivel_educativo" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="Sin estudios" {{ $socio->nivel_educativo == 'Sin estudios' ? 'selected' : '' }}>Sin estudios</option>
                                <option value="Educación básica" {{ $socio->nivel_educativo == 'Educación básica' ? 'selected' : '' }}>Educación básica</option>
                                <option value="Educación media" {{ $socio->nivel_educativo == 'Educación media' ? 'selected' : '' }}>Educación media</option>
                                <option value="Educación superior" {{ $socio->nivel_educativo == 'Educación superior' ? 'selected' : '' }}>Educación superior</option>
                              </select>
                            </div>
                            <div class="col-md-6 mb-3">
                              <label>Medio de Comunicación</label>
                              <select name="medio_comunicacion" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="Teléfono" {{ $socio->medio_comunicacion == 'Teléfono' ? 'selected' : '' }}>Teléfono</option>
                                <option value="Tablet" {{ $socio->medio_comunicacion == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="Computadora" {{ $socio->medio_comunicacion == 'Computadora' ? 'selected' : '' }}>Computadora</option>
                              </select>
                            </div>
                          </div>
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
                              <input type="text" name="direccion" class="form-control" value="{{ $socio->direccion }}">
                            </div>
                          </div>
                          <div class="row">
  <!-- Formulario dinámico de actividades económicas -->
  <div class="col-12 mb-3">
    @if($socio->actividades && count($socio->actividades))
      <label class="fw-bold">Actividades Económicas</label>
      <div id="actividadesContainer{{ $socio->Id_Beneficiario }}">
        @foreach($socio->actividades as $i => $actividad)
        <div class="row mb-2 actividad-item">
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
            <input type="number" name="actividades[{{ $i }}][numero]" class="form-control" placeholder="N°" value="{{ $actividad->Numero }}" required>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarActividad(this)"><i class="fas fa-trash"></i></button>
          </div>
        </div>
        @endforeach
      </div>
      <button type="button" class="btn btn-success btn-sm mt-2" onclick="agregarActividad('actividadesContainer{{ $socio->Id_Beneficiario }}')"><i class="fas fa-plus"></i> Agregar actividad</button>
    @endif
  </div>
</div>
                          <div class="row">
                            <div class="col-md-6 mb-3">
                              <label>Tipo de Cargo</label>
                              <select name="Tipo_Cargo" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="Presidente(a)" {{ $socio->Tipo_Cargo == 'Presidente(a)' ? 'selected' : '' }}>Presidente(a)</option>
                                <option value="Vicepresidente(a)" {{ $socio->Tipo_Cargo == 'Vicepresidente(a)' ? 'selected' : '' }}>Vicepresidente(a)</option>
                                <option value="Tesorero(a)" {{ $socio->Tipo_Cargo == 'Tesorero(a)' ? 'selected' : '' }}>Tesorero(a)</option>
                                <option value="Secretario(a)" {{ $socio->Tipo_Cargo == 'Secretario(a)' ? 'selected' : '' }}>Secretario(a)</option>
                                <option value="Vocal I" {{ $socio->Tipo_Cargo == 'Vocal I' ? 'selected' : '' }}>Vocal I</option>
                                <option value="Vocal II" {{ $socio->Tipo_Cargo == 'Vocal II' ? 'selected' : '' }}>Vocal II</option>
                                <option value="Vocal III" {{ $socio->Tipo_Cargo == 'Vocal III' ? 'selected' : '' }}>Vocal III</option>
                                <option value="Comité de Crédito y Cobros" {{ $socio->Tipo_Cargo == 'Comité de Crédito y Cobros' ? 'selected' : '' }}>Comité de Crédito y Cobros</option>
                                <option value="Junta de Vigilancia Presidente(a)" {{ $socio->Tipo_Cargo == 'Junta de Vigilancia Presidente(a)' ? 'selected' : '' }}>Junta de Vigilancia Presidente(a)</option>
                                <option value="Junta de Vigilancia Secretario(a)" {{ $socio->Tipo_Cargo == 'Junta de Vigilancia Secretario(a)' ? 'selected' : '' }}>Junta de Vigilancia Secretario(a)</option>
                                <option value="Junta de Vigilancia Vocal" {{ $socio->Tipo_Cargo == 'Junta de Vigilancia Vocal' ? 'selected' : '' }}>Junta de Vigilancia Vocal</option>
                              </select>
                            </div>
                            <div class="col-md-6 mb-3">
                              <label>Tipo de Socio</label>
                              <input type="text" name="Tipo_De_Socio" class="form-control" value="{{ $socio->Tipo_De_Socio }}">
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6 mb-3">
                              <label>Categoría</label>
                              <input type="text" name="categoria" class="form-control" value="{{ $socio->categoria }}">
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
                    <td colspan="4" class="text-center">No hay socios registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PAGINACIÓN --}}
    {{ $socios->withQueryString()->links() }}

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    function agregarActividad(containerId) {
      var container = document.getElementById(containerId);
      var index = container.querySelectorAll('.actividad-item').length;
      var row = document.createElement('div');
      row.className = 'row mb-2 actividad-item';
      row.innerHTML = `
        <div class=\"col-md-2\"> ... 
      `;
      container.appendChild(row);
    }
    function eliminarActividad(btn) {
      var row = btn.closest('.actividad-item');
      row.remove();
    }
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
    </script>
<script>
function agregarActividad(containerId) {
  var container = document.getElementById(containerId);
  var index = container.querySelectorAll('.actividad-item').length;
  var row = document.createElement('div');
  row.className = 'row mb-2 actividad-item';
  row.innerHTML = `
    <div class=\"col-md-2\">
      <input type=\"text\" name=\"actividades[${index}][rubro]\" class=\"form-control\" placeholder=\"Rubro\" required>
    </div>
    <div class=\"col-md-2\">
      <select name=\"actividades[${index}][tipo]\" class=\"form-control\" required>
        <option value=\"Agrícola\">Agrícola</option>
        <option value=\"No Agrícola\">No Agrícola</option>
      </select>
    </div>
    <div class=\"col-md-2\">
      <select name=\"actividades[${index}][unidad]\" class=\"form-control\" required>
        <option value=\"Manzanas\">Manzanas</option>
        <option value=\"Lempiras\">Lempiras</option>
      </select>
    </div>
    <div class=\"col-md-2\">
      <input type=\"number\" step=\"0.01\" name=\"actividades[${index}][cantidad]\" class=\"form-control\" placeholder=\"Cantidad\" required>
    </div>
    <div class=\"col-md-2\">
      <input type=\"number\" name=\"actividades[${index}][numero]\" class=\"form-control\" placeholder=\"N°\" required>
    </div>
    <div class=\"col-md-2\">
      <button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"eliminarActividad(this)\"><i class=\"fas fa-trash\"></i></button>
    </div>
  `;
  container.appendChild(row);
}
function eliminarActividad(btn) {
  var row = btn.closest('.actividad-item');
  row.remove();
}
</script>
@endsection
