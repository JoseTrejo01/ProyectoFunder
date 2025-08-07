@extends('adminlte::page')

@section('title', 'Nuevo Socio') {{-- Cambia el título de la pestaña --}}

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
                            data-aldea="{{ $org->aldea?->Nombre_Aldea }}"
                            data-municipio="{{ $org->aldea?->municipio?->Nombre_Municipio }}"
                            data-departamento="{{ $org->aldea?->municipio?->departamento?->Nombre_Departamento }}"
                            {{ old('Id_Organizacion') == $org->Id_Organizacion ? 'selected' : '' }}
                        >{{ $org->Nombre_Organizacion }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Nombre completo</label>
                <input type="text" name="Nombre_Beneficiario" id="nombre_beneficiario" class="form-control" required maxlength="40" value="{{ old('Nombre_Beneficiario') }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>DNI</label>
                <input type="text" name="DNI" id="dni" class="form-control" required maxlength="13" minlength="13" pattern="\d{13}" title="Debe ingresar exactamente 13 dígitos numéricos" value="{{ old('DNI') }}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Género</label>
                <select name="genero" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="M" {{ old('genero') == 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ old('genero') == 'F' ? 'selected' : '' }}>Femenino</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Edad</label>
                <input type="number" name="edad" id="edad" class="form-control" min="15" max="100" readonly value="{{ old('edad') }}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Estado Civil</label>
                <select name="estado_civil" class="form-control">
                    <option value="">Seleccione</option>
                    <option value="Soltero(a)" {{ old('estado_civil') == 'Soltero(a)' ? 'selected' : '' }}>Soltero(a)</option>
                    <option value="Casado(a)" {{ old('estado_civil') == 'Casado(a)' ? 'selected' : '' }}>Casado(a)</option>
                    <option value="Unión Libre" {{ old('estado_civil') == 'Unión Libre' ? 'selected' : '' }}>Unión Libre</option>
                    <option value="Viudo(a)" {{ old('estado_civil') == 'Viudo(a)' ? 'selected' : '' }}>Viudo(a)</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Etnia</label>
                <select name="etnia" class="form-control">
                    <option value="">Seleccione</option>
                    @foreach(['Lenca', 'Garífuna', 'Miskito', 'Tawahka', 'Tolupan', 'Pech', 'Maya Chortí', 'Negro de habla inglesa o Creole', 'Mestizo'] as $etnia)
                        <option value="{{ $etnia }}" {{ old('etnia') == $etnia ? 'selected' : '' }}>{{ $etnia }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Nivel Educativo</label>
                <select name="nivel_educativo" id="nivel_educativo" class="form-control">
                    <option value="">Seleccione</option>
                    <option value="Sin estudios" {{ old('nivel_educativo') == 'Sin estudios' ? 'selected' : '' }}>Sin estudios</option>
                    <option value="Educación Prebásica" {{ old('nivel_educativo') == 'Educación Prebásica' ? 'selected' : '' }}>Educación Prebásica</option>
                    <option value="Primaria" {{ old('nivel_educativo') == 'Primaria' ? 'selected' : '' }}>Primaria</option>
                    <option value="Ciclo Común" {{ old('nivel_educativo') == 'Ciclo Común' ? 'selected' : '' }}>Ciclo Común</option>
                    <option value="Diversificado" {{ old('nivel_educativo') == 'Diversificado' ? 'selected' : '' }}>Diversificado</option>
                    <option value="Universitario" {{ old('nivel_educativo') == 'Universitario' ? 'selected' : '' }}>Universitario</option>
                    <option value="Post grado" {{ old('nivel_educativo') == 'Post grado' ? 'selected' : '' }}>Post grado</option>
                    <option value="Doctorado" {{ old('nivel_educativo') == 'Doctorado' ? 'selected' : '' }}>Doctorado</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Años estimados cursados</label>
                <input type="text" name="anios_educacion" id="anios_educacion" class="form-control" readonly value="{{ old('anios_educacion') }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Medio de Comunicación</label>
                <select name="medio_comunicacion" class="form-control">
                    <option value="">Seleccione</option>
                    <option value="Teléfono" {{ old('medio_comunicacion') == 'Teléfono' ? 'selected' : '' }}>Teléfono</option>
                    <option value="Tablet" {{ old('medio_comunicacion') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                    <option value="Computadora" {{ old('medio_comunicacion') == 'Computadora' ? 'selected' : '' }}>Computadora</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="Telefono" id="telefono" class="form-control" pattern="\d{4}-\d{4}" maxlength="9" title="Formato: 1234-5678" value="{{ old('Telefono') }}" required>
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
        <input 
            type="text" 
            name="direccion" 
            class="form-control" 
            maxlength="40" 
            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ0-9 ]{1,40}" 
            title="Solo letras, números y espacios. Máximo 40 caracteres." 
            value="{{ old('direccion') }}" 
            required
        >
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
                     <label>Tipo de Socio</label>
                       <select name="Tipo_De_Socio" id="Tipo_De_Socio" class="form-control" required>
        <option value="">Seleccione</option>
        <option value="Socio">Socio</option>
        <option value="Cliente">Cliente</option>
                     </select>
            </div>

            <div class="form-group" id="tipo_cargo_group" style="display: none;">
                <label>Tipo de Cargo</label>
                     <select name="Tipo_Cargo" class="form-control">
                    <option value="">Seleccione</option>
                    @foreach($cargosDirectivos as $cargo)
                      <option value="{{ $cargo }}">{{ $cargo }}</option>
                    @endforeach
                     </select>
</div>

<div class="form-group" id="categoria_group" style="display: none;">
    <label>Categoría o Descripción</label>
    <input 
        type="text" 
        name="categoria" 
        class="form-control" 
        id="categoria" 
        maxlength="50" 
        pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]{1,50}" 
        title="Solo letras y espacios. Máximo 50 caracteres." 
        value="{{ old('categoria') }}"
    >
</div>

            </div>
        </div>

        <button class="btn btn-success mt-3" type="submit">Guardar</button>
        <a href="{{ route('socios.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>

     

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const orgSelect = document.querySelector('select[name="Id_Organizacion"]');
    const departamentoInput = document.getElementById('departamento');
    const municipioInput = document.getElementById('municipio');
    const comunidadInput = document.getElementById('comunidad');
    const dniInput = document.getElementById('dni');
    const tipoSocioSelect = document.getElementById('Tipo_De_Socio');
    const tipoCargoGroup = document.getElementById('tipo_cargo_group');
    const categoriaGroup = document.getElementById('categoria_group');
    const fechaNacimientoInput = document.getElementById('fecha_nacimiento');
    const edadInput = document.getElementById('edad');

    fechaNacimientoInput?.addEventListener('change', function () {
    const fecha = this.value;

    // Validación de formato YYYY-MM-DD (lo hace el navegador, pero lo dejamos por seguridad)
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

    if (edad < 18) {
       Swal.fire({
    icon: 'warning',
    title: 'Edad no permitida',
    text: 'Debe tener al menos 18 años para registrarse.',
    confirmButtonText: 'Entendido',
    confirmButtonColor: '#d33'
});

        this.value = '';
        edadInput.value = '';
        return;
    }

    // Si es válida y mayor de edad
    edadInput.value = edad;
});

    dniInput?.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 13);
    });

    tipoSocioSelect?.addEventListener('change', function () {
        const valor = this.value;
        tipoCargoGroup.style.display = valor === 'Socio' ? 'block' : 'none';
        categoriaGroup.style.display = valor !== '' ? 'block' : 'none';
    });

    orgSelect?.addEventListener('change', function () {
        const selected = orgSelect.options[orgSelect.selectedIndex];
        departamentoInput.value = selected.getAttribute('data-departamento') || '';
        municipioInput.value = selected.getAttribute('data-municipio') || '';
        comunidadInput.value = selected.getAttribute('data-aldea') || '';
    });

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
        aniosEducacion.value = estructuraEducativa[this.value] || '';
    });

    const rubrosPorTipo = {
        'Agrícola': ['Granos básicos', 'Vegetales', 'Café', 'Otros cultivos'],
        'No Agrícola': ['Pecuario', 'Servicio', 'Comercio', 'Consumo', 'Otros']
    };

    let actividades = [];
    let actividadIndex = 0;
    const btnAgregar = document.getElementById('agregar-actividad');
    const tabla = document.getElementById('tabla-actividades');
    const tbody = tabla.querySelector('tbody');

    btnAgregar?.addEventListener('click', function () {
        actividades.push({ index: actividadIndex++, tipo: '', rubro: '', unidad: '', cantidad: '' });
        renderActividades();
    });

    window.eliminarActividad = function (idx) {
        actividades = actividades.filter(a => a.index !== idx);
        renderActividades();
    };

    function renderActividades() {
        tbody.innerHTML = '';
        tabla.style.display = actividades.length > 0 ? '' : 'none';

        actividades.forEach((act, i) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${i + 1}</td>
                <td>
                    <select name="actividades[${act.index}][tipo]" class="form-control form-control-sm tipo-select" data-idx="${act.index}" required>
                        <option value="">Seleccione</option>
                        <option value="Agrícola" ${act.tipo === 'Agrícola' ? 'selected' : ''}>Agrícola</option>
                        <option value="No Agrícola" ${act.tipo === 'No Agrícola' ? 'selected' : ''}>No Agrícola</option>
                    </select>
                </td>
                <td>
                    <select name="actividades[${act.index}][rubro]" class="form-control form-control-sm rubro-select" data-idx="${act.index}" required>
                        <option value="">Seleccione</option>
                        ${
                            act.tipo && rubrosPorTipo[act.tipo]
                            ? rubrosPorTipo[act.tipo].map(r =>
                                `<option value="${r}" ${r === act.rubro ? 'selected' : ''}>${r}</option>`).join('')
                            : ''
                        }
                    </select>
                </td>
                <td>
                    <select name="actividades[${act.index}][unidad]" class="form-control form-control-sm" required>
                        <option value="">Seleccione</option>
                        <option value="Manzanas" ${act.unidad === 'Manzanas' ? 'selected' : ''}>Manzanas</option>
                        <option value="Lempiras" ${act.unidad === 'Lempiras' ? 'selected' : ''}>Lempiras</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="actividades[${act.index}][cantidad]" class="form-control form-control-sm" min="0" step="0.01" value="${act.cantidad || ''}" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarActividad(${act.index})">Eliminar</button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        tbody.querySelectorAll('select, input').forEach(input => {
            input.addEventListener('change', function () {
                const idx = parseInt(this.name.match(/\[(\d+)\]/)[1]);
                const act = actividades.find(a => a.index === idx);
                if (!act) return;

                if (this.name.includes('[tipo]')) act.tipo = this.value;
                if (this.name.includes('[rubro]')) act.rubro = this.value;
                if (this.name.includes('[unidad]')) act.unidad = this.value;
                if (this.name.includes('[cantidad]')) act.cantidad = this.value;
            });
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.matches('.tipo-select')) {
            const idx = e.target.dataset.idx;
            const rubroSelect = document.querySelector(`select[name="actividades[${idx}][rubro]"]`);
            rubroSelect.innerHTML = '<option value="">Seleccione</option>';
            rubrosPorTipo[e.target.value]?.forEach(rubro => {
                const option = document.createElement('option');
                option.value = rubro;
                option.textContent = rubro;
                rubroSelect.appendChild(option);
            });

            const act = actividades.find(a => a.index == idx);
            if (act) act.tipo = e.target.value;
        }
    });

    // ←←← AGREGADO: Cargar actividades desde old() de Laravel si hubo error
    @if(old('actividades'))
        actividades = {!! json_encode(old('actividades')) !!}.map((a, i) => ({
            index: actividadIndex++,
            tipo: a.tipo || '',
            rubro: a.rubro || '',
            unidad: a.unidad || '',
            cantidad: a.cantidad || ''
        }));
        renderActividades();
    @endif
});

const nombreInput = document.getElementById('nombre_beneficiario');

nombreInput?.addEventListener('keypress', function (e) {
    const char = e.key;

    // Permitir solo letras y espacio
    const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]$/;
    if (!regex.test(char)) {
        e.preventDefault(); // Bloquea el carácter
    }
});

nombreInput?.addEventListener('input', function () {
    // Elimina cualquier carácter pegado con copiar/pegar que no sea válido
    this.value = this.value
        .replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')    // limpia símbolos, números, etc.
        .replace(/\s{2,}/g, ' ');                   // evita espacios múltiples

    // Limita a 40 caracteres
    if (this.value.length > 40) {
        this.value = this.value.substring(0, 40);
    }

    // Forzar que la primera letra sea mayúscula
    if (this.value.length === 1) {
        this.value = this.value.charAt(0).toUpperCase();
    }
});
const dniInput = document.getElementById('dni');

// Bloquear entrada de letras y símbolos
dniInput?.addEventListener('keypress', function (e) {
    const char = e.key;
    if (!/^\d$/.test(char)) {
        e.preventDefault(); // Bloquea letras y símbolos
    }
});

// Limpiar cualquier caracter inválido pegado
dniInput?.addEventListener('input', function () {
    // Elimina todo lo que no sea número
    this.value = this.value.replace(/\D/g, '');

    // Limita a 13 caracteres
    if (this.value.length > 13) {
        this.value = this.value.slice(0, 13);
    }
});
const telefonoInput = document.getElementById('telefono');

// Bloquear letras y símbolos (permitir solo números)
telefonoInput?.addEventListener('keypress', function (e) {
    const char = e.key;
    // Permitir solo números mientras se escriben los primeros 8 dígitos (excluyendo guion)
    if (!/\d/.test(char) || this.value.length >= 9) {
        e.preventDefault();
    }
});

// Formatear automáticamente como 1234-5678
telefonoInput?.addEventListener('input', function () {
    // Eliminar todo lo que no sea número
    let cleanValue = this.value.replace(/\D/g, '');

    // Insertar guion después del cuarto dígito
    if (cleanValue.length > 4) {
        cleanValue = cleanValue.slice(0, 4) + '-' + cleanValue.slice(4, 8);
    }

    this.value = cleanValue.slice(0, 9); // limitar a 9 caracteres
});

document.addEventListener('DOMContentLoaded', function () {
    const direccionInput = document.querySelector('input[name="direccion"]');
    
    direccionInput?.addEventListener('input', function () {
        // Eliminar caracteres que no sean letras, números o espacio
        this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ0-9 ]/g, '').slice(0, 40);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const categoriaInput = document.getElementById('categoria');

    categoriaInput?.addEventListener('input', function () {
        // Permitir solo letras (mayúsculas, minúsculas, tildes, ñ) y espacios
        this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]/g, '').slice(0, 50);
    });
});
</script>
@endsection
