@extends('adminlte::page')

@section('content_header')
    <h1>Editar Socio</h1>
@stop

@section('content')
    <form action="{{ route('socios.update', $socio->Id_Beneficiario) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        <input type="hidden" name="estado" value="{{ old('estado', $socio->estado) }}">

        <div class="form-group">
            <label>Organización (ID)</label>
            <input type="number" name="Id_Organizacion" class="form-control" required value="{{ old('Id_Organizacion', $socio->Id_Organizacion) }}">
        </div>

        <div class="form-group">
            <label>Nombre de la Caja Rural</label>
            <input type="text" name="Nombre_Caja" class="form-control" required value="{{ old('Nombre_Caja', $socio->Nombre_Caja) }}">
        </div>

        <div class="form-group">
            <label>Nombre completo</label>
            <input type="text" name="Nombre_Beneficiario" class="form-control" required value="{{ old('Nombre_Beneficiario', $socio->Nombre_Beneficiario) }}">
        </div>

        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="DNI" class="form-control" required pattern="\d{4}-\d{4}-\d{5}" title="Formato: 0000-0000-00000" value="{{ old('DNI', $socio->DNI) }}">
        </div>

        <div class="form-group">
            <label>Género</label>
            <select name="genero" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="M" {{ old('genero', $socio->genero) == 'M' ? 'selected' : '' }}>Masculino</option>
                <option value="F" {{ old('genero', $socio->genero) == 'F' ? 'selected' : '' }}>Femenino</option>
            </select>
        </div>

        <div class="form-group">
            <label>Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', $socio->fecha_nacimiento) }}">
        </div>

        <div class="form-group">
            <label>Edad</label>
            <input type="number" name="edad" class="form-control" min="15" max="100" value="{{ old('edad', $socio->edad) }}">
        </div>

        <div class="form-group">
            <label>Estado Civil</label>
            <select name="estado_civil" class="form-control">
                <option value="">Seleccione</option>
                <option value="Soltero(a)" {{ old('estado_civil', $socio->estado_civil) == 'Soltero(a)' ? 'selected' : '' }}>Soltero(a)</option>
                <option value="Casado(a)" {{ old('estado_civil', $socio->estado_civil) == 'Casado(a)' ? 'selected' : '' }}>Casado(a)</option>
                <option value="Unión Libre" {{ old('estado_civil', $socio->estado_civil) == 'Unión Libre' ? 'selected' : '' }}>Unión Libre</option>
                <option value="Viudo(a)" {{ old('estado_civil', $socio->estado_civil) == 'Viudo(a)' ? 'selected' : '' }}>Viudo(a)</option>
            </select>
        </div>

        <div class="form-group">
            <label>Etnia</label>
            <select name="etnia" class="form-control">
                <option value="">Seleccione</option>
                @php
                    $etnias = ['Lenca', 'Garífuna', 'Miskito', 'Tawahka', 'Tolupan', 'Pech', 'Maya Chortí', 'Negro de habla inglesa o Creole', 'Mestizo'];
                @endphp
                @foreach($etnias as $etnia)
                    <option value="{{ $etnia }}" {{ old('etnia', $socio->etnia) == $etnia ? 'selected' : '' }}>{{ $etnia }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Nivel Educativo</label>
            <select name="nivel_educativo" class="form-control">
                @foreach(['Sin estudios', 'Educación básica', 'Educación media', 'Educación superior'] as $nivel)
                    <option value="{{ $nivel }}" {{ old('nivel_educativo', $socio->nivel_educativo) == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Medio de Comunicación</label>
            <select name="medio_comunicacion" class="form-control">
                @foreach(['Teléfono', 'Tablet', 'Computadora'] as $medio)
                    <option value="{{ $medio }}" {{ old('medio_comunicacion', $socio->medio_comunicacion) == $medio ? 'selected' : '' }}>{{ $medio }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="Telefono" class="form-control" pattern="\d{4}-\d{4}" title="Formato: 1234-5678" value="{{ old('Telefono', $socio->Telefono) }}">
        </div>

        <div class="form-group">
            <label>Departamento</label>
            <input type="text" name="departamento" class="form-control" value="{{ old('departamento', $socio->departamento) }}">
        </div>

        <div class="form-group">
            <label>Municipio</label>
            <input type="text" name="municipio" class="form-control" value="{{ old('municipio', $socio->municipio) }}">
        </div>

        <div class="form-group">
            <label>Comunidad</label>
            <input type="text" name="comunidad" class="form-control" value="{{ old('comunidad', $socio->comunidad) }}">
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $socio->direccion) }}">
        </div>

        <div class="form-group">
            <label>Actividad económica</label>
            <input type="text" name="actividad_economica" class="form-control" value="{{ old('actividad_economica', $socio->actividad_economica) }}">
        </div>

        <div class="form-group">
            <label>Actividades no agrícolas</label>
            <input type="text" name="actividad_no_agricola" class="form-control" value="{{ old('actividad_no_agricola', $socio->actividad_no_agricola) }}">
        </div>

        <div class="form-group">
            <label>Tipo de Cargo</label>
            <input type="text" name="Tipo_Cargo" class="form-control" value="{{ old('Tipo_Cargo', $socio->Tipo_Cargo) }}">
        </div>

        <div class="form-group">
            <label>Tipo de Socio</label>
            <input type="text" name="Tipo_De_Socio" class="form-control" value="{{ old('Tipo_De_Socio', $socio->Tipo_De_Socio) }}">
        </div>

        <div class="form-group">
            <label>Categoría</label>
            <input type="text" name="categoria" class="form-control" value="{{ old('categoria', $socio->categoria) }}">
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
