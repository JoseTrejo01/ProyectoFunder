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

    <form action="{{ route('socios.store') }}" method="POST" autocomplete="off">
        @csrf
        <input type="hidden" name="estado" value="1">

        <div class="form-group">
            <label>Organización (ID)</label>
            <input type="number" name="Id_Organizacion" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Nombre de la Caja Rural</label>
            <input type="text" name="Nombre_Caja" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Nombre completo</label>
            <input type="text" name="Nombre_Beneficiario" class="form-control" required>
        </div>

        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="DNI" class="form-control" required pattern="\d{4}-\d{4}-\d{5}" title="Formato: 0000-0000-00000">
        </div>

        <div class="form-group">
            <label>Género</label>
            <select name="genero" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
            </select>
        </div>

        <div class="form-group">
            <label>Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control">
        </div>

        <div class="form-group">
            <label>Edad</label>
            <input type="number" name="edad" class="form-control" min="15" max="100">
        </div>

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
            <input type="text" name="Telefono" class="form-control" pattern="\d{4}-\d{4}" title="Formato: 1234-5678">
            <small class="form-text text-muted">Formato: 1234-5678</small>
        </div>

        <div class="form-group">
            <label>Departamento</label>
            <select name="departamento" id="departamento" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="Atlántida">Atlántida</option>
                <option value="Choluteca">Choluteca</option>
                <option value="Colón">Colón</option>
                <option value="Comayagua">Comayagua</option>
                <option value="Copán">Copán</option>
                <option value="Cortés">Cortés</option>
                <option value="El Paraíso">El Paraíso</option>
                <option value="Francisco Morazán">Francisco Morazán</option>
                <option value="Gracias a Dios">Gracias a Dios</option>
                <option value="Intibucá">Intibucá</option>
                <option value="Islas de la Bahía">Islas de la Bahía</option>
                <option value="La Paz">La Paz</option>
                <option value="Lempira">Lempira</option>
                <option value="Ocotepeque">Ocotepeque</option>
                <option value="Olancho">Olancho</option>
                <option value="Santa Bárbara">Santa Bárbara</option>
                <option value="Valle">Valle</option>
                <option value="Yoro">Yoro</option>
            </select>
        </div>

        <div class="form-group">
            <label>Municipio</label>
            <select name="municipio" id="municipio" class="form-control" required>
                <option value="">Seleccione un municipio</option>
            </select>
        </div>

        <div class="form-group">
            <label>Comunidad</label>
            <select name="comunidad" id="comunidad" class="form-control">
                <option value="">Seleccione una comunidad</option>
            </select>
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control">
        </div>

        <div class="form-group">
            <label>Actividad económica</label>
            <input type="text" name="actividad_economica" class="form-control">
        </div>

        <div class="form-group">
            <label>Actividades no agrícolas</label>
            <input type="text" name="actividad_no_agricola" class="form-control">
        </div>

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

        <button class="btn btn-success" type="submit">Guardar</button>
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
