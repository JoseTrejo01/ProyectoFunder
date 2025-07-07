@extends('adminlte::page')

@section('content_header')
    <h1>Editar Socio</h1>
@stop

@section('content')
    <form action="{{ route('socios.update', $socio->Id_Beneficiario) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Organización (ID)</label>
            <input type="number" name="Id_Organizacion" class="form-control" value="{{ $socio->Id_Organizacion }}" required>
        </div>

        <div class="form-group">
            <label>Nombre de la Caja Rural</label>
            <input type="text" name="Nombre_Caja" class="form-control" value="{{ $socio->Nombre_Caja }}">
        </div>

        <div class="form-group">
            <label>Nombre completo</label>
            <input type="text" name="Nombre_Beneficiario" class="form-control" value="{{ $socio->Nombre_Beneficiario }}" required>
        </div>

        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="DNI" class="form-control" value="{{ $socio->DNI }}" required>
        </div>

        <div class="form-group">
            <label>Género</label>
            <select name="genero" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="M" {{ $socio->genero == 'M' ? 'selected' : '' }}>Masculino</option>
                <option value="F" {{ $socio->genero == 'F' ? 'selected' : '' }}>Femenino</option>
            </select>
        </div>

        <div class="form-group">
            <label>Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $socio->fecha_nacimiento }}">
        </div>

        <div class="form-group">
            <label>Edad</label>
            <input type="number" name="edad" class="form-control" value="{{ $socio->edad }}">
        </div>

        <div class="form-group">
            <label>Estado Civil</label>
            <select name="estado_civil" class="form-control">
                <option value="">Seleccione</option>
                <option value="Soltero(a)" {{ $socio->estado_civil == 'Soltero(a)' ? 'selected' : '' }}>Soltero(a)</option>
                <option value="Casado(a)" {{ $socio->estado_civil == 'Casado(a)' ? 'selected' : '' }}>Casado(a)</option>
                <option value="Unión Libre" {{ $socio->estado_civil == 'Unión Libre' ? 'selected' : '' }}>Unión Libre</option>
                <option value="Viudo(a)" {{ $socio->estado_civil == 'Viudo(a)' ? 'selected' : '' }}>Viudo(a)</option>
            </select>
        </div>

        <div class="form-group">
            <label>Etnia</label>
            <select name="etnia" class="form-control">
                <option value="">Seleccione</option>
                @foreach(["Lenca","Garífuna","Miskito","Tawahka","Tolupan","Pech","Maya Chortí","Negro de habla inglesa o Creole","Mestizo"] as $etnia)
                    <option value="{{ $etnia }}" {{ $socio->etnia == $etnia ? 'selected' : '' }}>{{ $etnia }}</option>
                @endforeach
            </select>
        </div>

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


        <div class="form-group">
    <label>Medio de Comunicación</label>
    <select name="medio_comunicacion" class="form-control">
        <option value="">Seleccione</option>
        <option value="Teléfono">Teléfono</option>
        <option value="Tablet">Tablet</option>
        <option value="Computadora">Computadora</option>
    </select>
    </div>


        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="Telefono" class="form-control" value="{{ $socio->Telefono }}">
        </div>

        <div class="form-group">
            <label>Departamento</label>
            <select name="departamento" id="departamento" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach(["Atlántida","Choluteca","Colón","Comayagua","Copán","Cortés","El Paraíso","Francisco Morazán","Gracias a Dios","Intibucá","Islas de la Bahía","La Paz","Lempira","Ocotepeque","Olancho","Santa Bárbara","Valle","Yoro"] as $dep)
                    <option value="{{ $dep }}" {{ $socio->departamento == $dep ? 'selected' : '' }}>{{ $dep }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Municipio</label>
            <select name="municipio" id="municipio" class="form-control" required>
                <option value="{{ $socio->municipio }}">{{ $socio->municipio }}</option>
            </select>
        </div>

        <div class="form-group">
            <label>Comunidad</label>
            <select name="comunidad" id="comunidad" class="form-control">
                <option value="{{ $socio->comunidad }}">{{ $socio->comunidad }}</option>
            </select>
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ $socio->direccion }}">
        </div>

        <div class="form-group">
            <label>Actividad económica</label>
            <input type="text" name="actividad_economica" class="form-control" value="{{ $socio->actividad_economica }}">
        </div>

        <div class="form-group">
            <label>Actividades no agrícolas</label>
            <input type="text" name="actividad_no_agricola" class="form-control" value="{{ $socio->actividad_no_agricola }}">
        </div>

        <div class="form-group">
            <label>Tipo de Cargo</label>
            <select name="Tipo_Cargo" class="form-control">
                <option value="">Seleccione</option>
                @foreach([
                    "Presidente(a)",
                    "Vicepresidente(a)",
                    "Tesorero(a)",
                    "Secretario(a)",
                    "Vocal I",
                    "Vocal II",
                    "Vocal III",
                    "Comité de Crédito y Cobros",
                    "Junta de Vigilancia Presidente(a)",
                    "Junta de Vigilancia Secretario(a)",
                    "Junta de Vigilancia Vocal"
                ] as $cargo)
                    <option value="{{ $cargo }}" {{ $socio->Tipo_Cargo == $cargo ? 'selected' : '' }}>{{ $cargo }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Tipo de Socio</label>
            <input type="text" name="Tipo_De_Socio" class="form-control" value="{{ $socio->Tipo_De_Socio }}">
        </div>

        <div class="form-group">
            <label>Categoría</label>
            <input type="text" name="categoria" class="form-control" value="{{ $socio->categoria }}">
        </div>

        <button class="btn btn-primary" type="submit">Actualizar</button>
        <a href="{{ route('socios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    <script>
    const municipios = {
        "Atlántida": ["La Ceiba", "Tela", "Jutiapa", "El Porvenir", "Esparta", "Arizona"],
        "Colón": ["Trujillo", "Tocoa", "Balfate", "Iriona", "Limón"],
        "Comayagua": ["Comayagua", "Siguatepeque", "La Paz", "Ajuterique"],
        "Copán": ["Santa Rosa de Copán", "Copán Ruinas", "Santa Rita", "Cabañas"],
        "Cortés": ["San Pedro Sula", "Villanueva", "Choloma", "Puerto Cortés", "Omoa"],
        "Choluteca": ["Choluteca", "San Lorenzo", "Namasigüe"],
        "El Paraíso": ["Yuscarán", "Danlí", "Trojes"],
        "Francisco Morazán": ["Tegucigalpa", "Valle de Ángeles", "Talanga"],
        "Gracias a Dios": ["Puerto Lempira", "Villeda Morales"],
        "Intibucá": ["La Esperanza", "Jesús de Otoro"],
        "Islas de la Bahía": ["Roatán", "Guanaja", "Utila"],
        "La Paz": ["La Paz", "Opatoro"],
        "Lempira": ["Gracias", "Lepaera"],
        "Ocotepeque": ["Ocotepeque", "Sinuapa"],
        "Olancho": ["Juticalpa", "Gualaco"],
        "Santa Bárbara": ["Santa Bárbara", "Quimistán"],
        "Valle": ["Nacaome", "Amapala"],
        "Yoro": ["Yoro", "Olanchito"]
    };

    const comunidades = {
        "La Ceiba": ["Barrio Inglés", "Colonia Sitraterco"],
        "Tela": ["Tornabé", "Triunfo de la Cruz"],
        "Jutiapa": ["Manga Seca", "Río Viejo"],
        "El Porvenir": ["San José", "La Unión"],
        "Esparta": ["San Juan Pueblo", "Nueva Armenia"],
        "Arizona": ["El Pino", "La Colorada"],
        "Trujillo": ["Barras", "Santa Fe"],
        "Tocoa": ["Taujica", "Sonaguera"],
        "Balfate": ["Sambo Creek", "Río Esteban"],
        "Iriona": ["Cusuna", "Punta Piedra"],
        "Limón": ["Ciriboya", "Santa Rosa de Aguán"],
        "Comayagua": ["San Jerónimo", "La Libertad"],
        "Siguatepeque": ["El Rosario", "San José de Comayagua"],
        "La Paz": ["Meámbar", "San Pedro de Tutule"],
        "Ajuterique": ["Lejamaní", "Ojos de Agua"],
        "Santa Rosa de Copán": ["El Paraíso", "Dulce Nombre"],
        "Copán Ruinas": ["Las Flores", "La Pintada"],
        "Santa Rita": ["Corquín", "Cucuyagua"],
        "Cabañas": ["Dolores", "San José"],
        "San Pedro Sula": ["Chamelecón", "Rivera Hernández"],
        "Villanueva": ["Cofradía", "Monterrey"],
        "Choloma": ["Río Blanquito", "La Jutosa"],
        "Puerto Cortés": ["Travesía", "Bajamar"],
        "Omoa": ["Pueblo Nuevo", "El Porvenir"],
        "Choluteca": ["San Marcos", "El Corpus"],
        "San Lorenzo": ["El Triunfo", "Marcovia"],
        "Namasigüe": ["El Tular", "La Fraternidad"],
        "Yuscarán": ["Oropolí", "Potrerillos"],
        "Danlí": ["El Chile", "Morocelí"],
        "Trojes": ["Las Flores", "Patuca"],
        "Tegucigalpa": ["Comayagüela", "El Hatillo"],
        "Valle de Ángeles": ["San Juancito", "Santa Lucía"],
        "Talanga": ["Cedros", "Guaimaca"],
        "Puerto Lempira": ["Ahuas", "Brus Laguna"],
        "Villeda Morales": ["Wampusirpi", "Kaukira"],
        "La Esperanza": ["Intibucá", "Yamaranguila"],
        "Jesús de Otoro": ["San Juan", "Magdalena"],
        "Roatán": ["Coxen Hole", "French Harbour"],
        "Guanaja": ["Bonacca", "Savannah Bight"],
        "Utila": ["East Harbour", "Sandy Bay"],
        "Opatoro": ["Guajiquiro", "Santa Ana"],
        "Gracias": ["Las Flores", "San Juan"],
        "Lepaera": ["La Campa", "Talgua"],
        "Ocotepeque": ["Sensenti", "San Francisco del Valle"],
        "Sinuapa": ["La Labor", "Lucerna"],
        "Juticalpa": ["Catacamas", "Manto"],
        "Gualaco": ["San Esteban", "Silca"],
        "Santa Bárbara": ["Ilama", "San Vicente"],
        "Quimistán": ["Petoa", "Trinidad"],
        "Nacaome": ["San Lorenzo", "Goascorán"],
        "Amapala": ["Coyolito", "El Tigre"],
        "Yoro": ["El Negrito", "Morazán"],
        "Olanchito": ["Arenal", "Jocón"]
    };

    document.addEventListener('DOMContentLoaded', function() {
        const departamentoSelect = document.getElementById('departamento');
        const municipioSelect = document.getElementById('municipio');
        const comunidadSelect = document.getElementById('comunidad');

        // capturamos valores viejos
        const selectedMunicipio = "{{ $socio->municipio }}";
        const selectedComunidad = "{{ $socio->comunidad }}";

        // al cargar la página
        const depto = departamentoSelect.value;

        if (municipios[depto]) {
            municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
            municipios[depto].forEach(muni => {
                const option = document.createElement('option');
                option.value = muni;
                option.textContent = muni;
                if (muni === selectedMunicipio) {
                    option.selected = true;
                }
                municipioSelect.appendChild(option);
            });
        }

        if (comunidades[selectedMunicipio]) {
            comunidadSelect.innerHTML = '<option value="">Seleccione una comunidad</option>';
            comunidades[selectedMunicipio].forEach(comu => {
                const option = document.createElement('option');
                option.value = comu;
                option.textContent = comu;
                if (comu === selectedComunidad) {
                    option.selected = true;
                }
                comunidadSelect.appendChild(option);
            });
        }

        // eventos de cambio dinámico
        departamentoSelect.addEventListener('change', function() {
            const depto = this.value;
            municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
            comunidadSelect.innerHTML = '<option value="">Seleccione una comunidad</option>';

            if (municipios[depto]) {
                municipios[depto].forEach(muni => {
                    const option = document.createElement('option');
                    option.value = muni;
                    option.textContent = muni;
                    municipioSelect.appendChild(option);
                });
            }
        });

        municipioSelect.addEventListener('change', function() {
            const muni = this.value;
            comunidadSelect.innerHTML = '<option value="">Seleccione una comunidad</option>';

            if (comunidades[muni]) {
                comunidades[muni].forEach(comu => {
                    const option = document.createElement('option');
                    option.value = comu;
                    option.textContent = comu;
                    comunidadSelect.appendChild(option);
                });
            }
        });
    });
</script>

@stop
