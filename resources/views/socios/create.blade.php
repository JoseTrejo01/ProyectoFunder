@extends('adminlte::page')

@section('content_header')
    <h1>Nuevo Socio</h1>
@stop

@section('content')


    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Ups!</strong> Hay algunos problemas con los datos ingresados.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Mostrar errores de validación con SweetAlert2 --}}
    @if ($errors->any())
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: '¡Ups!',
                    html: `<ul style='text-align:left;'>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>`,
                    confirmButtonColor: '#d33',
                });
            });
        </script>
    @endif
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

    <form action="{{ route('socios.store') }}" method="POST" autocomplete="off">
        @csrf
        <input type="hidden" name="estado" value="1">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs mb-3" id="socioTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="datos-tab" data-bs-toggle="tab" data-bs-target="#datos" type="button" role="tab" aria-controls="datos" aria-selected="true">Datos personales</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="ubicacion-tab" data-bs-toggle="tab" data-bs-target="#ubicacion" type="button" role="tab" aria-controls="ubicacion" aria-selected="false">Ubicación</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="false">Información adicional</button>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" id="socioTabContent">
            <!-- Datos personales -->
            <div class="tab-pane fade show active" id="datos" role="tabpanel" aria-labelledby="datos-tab">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Organización</label>
                            <select name="Id_Organizacion" class="form-control" required>
                                <option value="">Seleccione una organización</option>
                                @foreach($organizaciones as $org)
                                    <option value="{{ $org->Id_Organizacion }}"
                                        data-aldea="{{ $org->aldea ? $org->aldea->Nombre_Aldea : '' }}"
                                        data-municipio="{{ $org->aldea && $org->aldea->municipio ? $org->aldea->municipio->Nombre_Municipio : '' }}"
                                        data-departamento="{{ $org->aldea && $org->aldea->municipio && $org->aldea->municipio->departamento ? $org->aldea->municipio->departamento->Nombre_Departamento : '' }}"
                                    >{{ $org->Nombre_Organizacion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Campo de Nombre de la Caja Rural eliminado, ahora se selecciona desde el select de organización -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nombre completo</label>
                            <input type="text" name="Nombre_Beneficiario" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>DNI</label>
                            <input type="text" name="DNI" class="form-control" required pattern="\d{4}-\d{4}-\d{5}" title="Formato: 0000-0000-00000">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Género</label>
                            <select name="genero" class="form-control" required>
                                <option value="">Seleccione</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha de nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Edad</label>
                            <input type="number" name="edad" id="edad" class="form-control" min="15" max="100" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Estado Civil</label>
                            <select name="estado_civil" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="Soltero(a)">Soltero(a)</option>
                                <option value="Casado(a)">Casado(a)</option>
                                <option value="Unión Libre">Unión Libre</option>
                                <option value="Viudo(a)">Viudo(a)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Etnia</label>
                            <select name="etnia" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="Lenca">Lenca</option>
                                <option value="Garífuna">Garífuna</option>
                                <option value="Miskito">Miskito</option>
                                <option value="Tawahka">Tawahka</option>
                                <option value="Tolupan">Tolupan</option>
                                <option value="Pech">Pech</option>
                                <option value="Maya Chortí">Maya Chortí</option>
                                <option value="Negro de habla inglesa o Creole">Negro de habla inglesa o Creole</option>
                                <option value="Mestizo">Mestizo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nivel Educativo</label>
                            <select name="nivel_educativo" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="Sin estudios">Sin estudios</option>
                                <option value="Educación básica">Educación básica</option>
                                <option value="Educación media">Educación media</option>
                                <option value="Educación superior">Educación superior</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Medio de Comunicación</label>
                            <select name="medio_comunicacion" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="Teléfono">Teléfono</option>
                                <option value="Tablet">Tablet</option>
                                <option value="Computadora">Computadora</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" name="Telefono" class="form-control" pattern="\d{4}-\d{4}" title="Formato: 1234-5678">
                            <small class="form-text text-muted">Formato: 1234-5678</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ubicación -->
            <div class="tab-pane fade" id="ubicacion" role="tabpanel" aria-labelledby="ubicacion-tab">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Departamento</label>
                            <input type="text" name="departamento" id="departamento" class="form-control" required readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Municipio</label>
                            <input type="text" name="municipio" id="municipio" class="form-control" required readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Comunidad/Aldea</label>
                            <input type="text" name="comunidad" id="comunidad" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Dirección</label>
                            <input type="text" name="direccion" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info-tab">
                <hr>
                <h5>Actividades económicas</h5>
                <div id="actividades-container">
                    <!-- Aquí se agregan dinámicamente las actividades -->
                </div>
                <button type="button" class="btn btn-primary btn-sm mb-2" id="agregar-actividad">Registrar actividad</button>
                <table class="table table-bordered table-sm" id="tabla-actividades" style="display:none;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tipo</th>
                            <th>Rubro</th>
                            <th>Unidad de Medida</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas generadas por JS -->
                    </tbody>
                </table>
                <div class="form-group">
                    <label>Tipo de Cargo</label>
                    <select name="Tipo_Cargo" class="form-control">
                        <option value="">Seleccione</option>
                        <option value="Presidente(a)">Presidente(a)</option>
                        <option value="Vicepresidente(a)">Vicepresidente(a)</option>
                        <option value="Tesorero(a)">Tesorero(a)</option>
                        <option value="Secretario(a)">Secretario(a)</option>
                        <option value="Vocal I">Vocal I</option>
                        <option value="Vocal II">Vocal II</option>
                        <option value="Vocal III">Vocal III</option>
                        <option value="Comité de Crédito y Cobros">Comité de Crédito y Cobros</option>
                        <option value="Junta de Vigilancia Presidente(a)">Junta de Vigilancia Presidente(a)</option>
                        <option value="Junta de Vigilancia Secretario(a)">Junta de Vigilancia Secretario(a)</option>
                        <option value="Junta de Vigilancia Vocal">Junta de Vigilancia Vocal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tipo de Socio</label>
                    <input type="text" name="Tipo_De_Socio" class="form-control">
                </div>
                <div class="form-group">
                    <label>Categoría</label>
                    <input type="text" name="categoria" class="form-control">
                </div>
            </div>
        </div>

        <button class="btn btn-success mt-3" type="submit">Guardar</button>
        <a href="{{ route('socios.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>

      <script>
document.addEventListener('DOMContentLoaded', function () {
    const orgSelect = document.querySelector('select[name="Id_Organizacion"]');
    const departamentoInput = document.getElementById('departamento');
    const municipioInput = document.getElementById('municipio');
    const comunidadInput = document.getElementById('comunidad');

    const fechaNacimientoInput = document.getElementById('fecha_nacimiento');
    const edadInput = document.getElementById('edad');
    fechaNacimientoInput?.addEventListener('change', function () {
        const fecha = this.value;
        if (fecha) {
            const hoy = new Date();
            const nacimiento = new Date(fecha);
            let edad = hoy.getFullYear() - nacimiento.getFullYear();
            const m = hoy.getMonth() - nacimiento.getMonth();
            if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) edad--;
            edadInput.value = edad;
        } else {
            edadInput.value = '';
        }
    });

    orgSelect?.addEventListener('change', function () {
        const selected = orgSelect.options[orgSelect.selectedIndex];
        departamentoInput.value = selected.getAttribute('data-departamento') || '';
        municipioInput.value = selected.getAttribute('data-municipio') || '';
        comunidadInput.value = selected.getAttribute('data-aldea') || '';
    });

    // Estructura educativa
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
    const nivelEducativo = document.getElementById('nivel_educativo');
    const aniosEducacion = document.getElementById('anios_educacion');
    nivelEducativo?.addEventListener('change', function () {
        const nivel = this.value;
        aniosEducacion.value = estructuraEducativa[nivel] || '';
    });

    // Actividades económicas
    let actividades = [];
    const btnAgregar = document.getElementById('agregar-actividad');
    const tabla = document.getElementById('tabla-actividades');
    const tbody = tabla.querySelector('tbody');

    btnAgregar?.addEventListener('click', function () {
        const idx = actividades.length;
        actividades.push({ tipo: '', rubro: '', unidad: '', cantidad: '' });
        renderActividades();
    });

    function renderActividades() {
        tbody.innerHTML = '';
        if (actividades.length > 0) tabla.style.display = '';
        else tabla.style.display = 'none';

        actividades.forEach((act, i) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${i + 1}</td>
                <td>
                    <select name="actividades[${i}][tipo]" class="form-control form-control-sm tipo-select" data-idx="${i}" required>
                        <option value="">Seleccione</option>
                        <option value="Agrícola">Agrícola</option>
                        <option value="No Agrícola">No Agrícola</option>
                    </select>
                </td>
                <td>
                    <select name="actividades[${i}][rubro]" class="form-control form-control-sm rubro-select" required></select>
                </td>
                <td>
                    <select name="actividades[${i}][unidad]" class="form-control form-control-sm" required>
                        <option value="">Seleccione</option>
                        <option value="Manzanas">Manzanas</option>
                        <option value="Lempiras">Lempiras</option>
                    </select>
                </td>
                <td><input type="number" name="actividades[${i}][cantidad]" class="form-control form-control-sm" min="0" step="0.01" required></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarActividad(${i})">Eliminar</button></td>
            `;
            tbody.appendChild(tr);
        });
    }

    window.eliminarActividad = function (idx) {
        actividades.splice(idx, 1);
        renderActividades();
    };

    const rubrosPorTipo = {
        'Agrícola': ['Granos básicos', 'Vegetales', 'Café', 'Otros cultivos'],
        'No Agrícola': ['Pecuario', 'Servicio', 'Comercio', 'Consumo', 'Otros']
    };

    document.addEventListener('change', function (e) {
        if (e.target.matches('.tipo-select')) {
            const idx = e.target.dataset.idx;
            const rubroSelect = document.querySelector(`select[name="actividades[${idx}][rubro]"]`);
            if (rubroSelect) {
                rubroSelect.innerHTML = '<option value="">Seleccione</option>';
                rubrosPorTipo[e.target.value]?.forEach(rubro => {
                    const option = document.createElement('option');
                    option.value = rubro;
                    option.textContent = rubro;
                    rubroSelect.appendChild(option);
                });
            }
        }
    });
});
      </script>
</script>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
