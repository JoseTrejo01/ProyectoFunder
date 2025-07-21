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
               <!-- MODAL DE EDICIÓN -->
<div class="modal fade" id="modalEditarSocio{{ $socio->Id_Beneficiario }}" tabindex="-1" aria-labelledby="modalEditarSocioLabel{{ $socio->Id_Beneficiario }}" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <form action="{{ route('socios.update', $socio->Id_Beneficiario) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Editar Socio: {{ $socio->Nombre_Beneficiario }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">

          {{-- DATOS PERSONALES --}}
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Nombre completo</label>
              <input type="text" name="Nombre_Beneficiario" class="form-control" value="{{ $socio->Nombre_Beneficiario }}" required>
            </div>
            <div class="col-md-3 mb-3">
              <label>DNI</label>
              <input type="text" name="DNI" class="form-control" maxlength="13" pattern="\d{1,13}" value="{{ $socio->DNI }}" required>
            </div>
            <div class="col-md-3 mb-3">
              <label>Género</label>
              <select name="genero" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="M" {{ $socio->genero == 'M' ? 'selected' : '' }}>Masculino</option>
                <option value="F" {{ $socio->genero == 'F' ? 'selected' : '' }}>Femenino</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label>Fecha de nacimiento</label>
              <input type="date" name="fecha_nacimiento" id="fecha_nacimiento{{ $socio->Id_Beneficiario }}" class="form-control" value="{{ $socio->fecha_nacimiento }}">
            </div>
            <div class="col-md-2 mb-3">
              <label>Edad</label>
              <input type="number" name="edad" id="edad{{ $socio->Id_Beneficiario }}" class="form-control" value="{{ $socio->edad }}" readonly>
            </div>
            <div class="col-md-3 mb-3">
              <label>Nivel Educativo</label>
              <select name="nivel_educativo" id="nivel_educativo{{ $socio->Id_Beneficiario }}" class="form-control">
                <option value="">Seleccione</option>
                @php
                    $niveles = ['Sin estudios', 'Educación Prebásica', 'Primaria', 'Ciclo Común', 'Diversificado', 'Universitario', 'Post grado', 'Doctorado'];
                @endphp
                @foreach($niveles as $nivel)
                  <option value="{{ $nivel }}" {{ $socio->nivel_educativo == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 mb-3">
              <label>Años de Educación</label>
              <input type="text" class="form-control" id="anios_educacion{{ $socio->Id_Beneficiario }}" name="anios_educacion" value="{{ $socio->anios_educacion }}" readonly>
            </div>
          </div>

          {{-- INFORMACIÓN ADICIONAL --}}
          <div class="row">
            <div class="col-md-4 mb-3">
              <label>Tipo de Socio</label>
              <select name="Tipo_De_Socio" id="Tipo_De_Socio{{ $socio->Id_Beneficiario }}" class="form-control">
                <option value="">Seleccione</option>
                <option value="Socio" {{ $socio->Tipo_De_Socio == 'Socio' ? 'selected' : '' }}>Socio</option>
                <option value="Cliente" {{ $socio->Tipo_De_Socio == 'Cliente' ? 'selected' : '' }}>Cliente</option>
              </select>
            </div>
            <div class="col-md-4 mb-3" id="tipo_cargo_group{{ $socio->Id_Beneficiario }}">
              <label>Tipo de Cargo</label>
              <select name="Tipo_Cargo" class="form-control">
                <option value="">Seleccione</option>
                @php
                  $cargos = ['Presidente(a)', 'Vicepresidente(a)', 'Tesorero(a)', 'Secretario(a)', 'Vocal I', 'Vocal II', 'Vocal III', 'Comité de Crédito y Cobros', 'Junta de Vigilancia Presidente(a)', 'Junta de Vigilancia Secretario(a)', 'Junta de Vigilancia Vocal'];
                @endphp
                @foreach($cargos as $cargo)
                  <option value="{{ $cargo }}" {{ $socio->Tipo_Cargo == $cargo ? 'selected' : '' }}>{{ $cargo }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label>Categoría</label>
              <input type="text" name="categoria" class="form-control" value="{{ $socio->categoria }}">
            </div>
          </div>

          {{-- ACTIVIDADES ECONÓMICAS --}}
          <div>
            <h5 class="mt-3">Actividades Económicas</h5>
            <div id="actividadesContainer{{ $socio->Id_Beneficiario }}">
              @if($socio->actividades && count($socio->actividades))
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
                      <option value="Manzanas" {{ $actividad->Unidad == 'Manzanas' ? 'selected' : '' }}>Manzanas</option>
                      <option value="Lempiras" {{ $actividad->Unidad == 'Lempiras' ? 'selected' : '' }}>Lempiras</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <input type="number" step="0.01" name="actividades[{{ $i }}][cantidad]" class="form-control" placeholder="Cantidad" value="{{ $actividad->Cantidad }}" required>
                  </div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarActividad(this)"><i class="fas fa-trash"></i></button>
                  </div>
                </div>
                @endforeach
              @endif
            </div>
            <button type="button" class="btn btn-primary btn-sm mt-2" onclick="agregarActividad('actividadesContainer{{ $socio->Id_Beneficiario }}')">Agregar Actividad</button>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Actualizar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
