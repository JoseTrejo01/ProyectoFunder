@extends('adminlte::page')

@section('title', 'Registrar Solicitud de Préstamo')

@section('content_header')
    <h1>Registrar Solicitud de Préstamo</h1>
@stop

@section('content')
<div class="container d-flex justify-content-center">
    <div class="w-100" style="max-width: 700px;">

        {{-- Errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Corrige los errores:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="prestamoForm" action="{{ route('prestamos.store') }}" method="POST">
            @csrf

            {{-- Tabs --}}
            <ul class="nav nav-tabs" id="prestamoTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="datos-tab" data-bs-toggle="tab" data-bs-target="#datos" type="button" role="tab">Datos Generales</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="finanzas-tab" data-bs-toggle="tab" data-bs-target="#finanzas" type="button" role="tab">Finanzas</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="otros-tab" data-bs-toggle="tab" data-bs-target="#otros" type="button" role="tab">Otros</button>
                </li>
            </ul>

            <div class="tab-content pt-3" id="prestamoTabsContent">
                {{-- TAB 1: Datos Generales --}}
                <div class="tab-pane fade show active" id="datos" role="tabpanel">
                    <div class="mb-3">
                        <label for="socio_id" class="form-label">Caja Rural</label>
                        <select name="socio_id" id="socio_id" class="form-control" required>
                            <option value="">Seleccione una</option>
                            @foreach($organizaciones as $org)
                                <option 
                                    value="{{ $org->Id_Organizacion }}" 
                                    data-nombre="{{ $org->Nombre_Organizacion }}"
                                    {{ old('socio_id') == $org->Id_Organizacion ? 'selected' : '' }}>
                                    {{ $org->Nombre_Organizacion }}
                                </option>
                            @endforeach
                        </select>   
                    </div>

                    <div class="mb-3">
                        <label for="nombre_caja_rural" class="form-label">Nombre Caja Rural</label>
                        <input type="text" name="nombre_caja_rural" id="nombre_caja_rural" class="form-control" readonly required
                            value="{{ old('nombre_caja_rural') }}">
                    </div>

                    {{-- Beneficiario --}}
                    <div class="mb-3">
                        <label for="beneficiario_id" class="form-label">Beneficiario</label>
                        <select name="beneficiario_id" id="beneficiario_id" class="form-control" required>
                            <option value="">Seleccione un beneficiario</option>
                            @foreach($beneficiarios as $beneficiario)
                                <option 
                                    value="{{ $beneficiario->Id_Beneficiario }}" 
                                    data-actividad="{{ $beneficiario->actividad_economica ?? '' }}"
                                    {{ old('beneficiario_id') == $beneficiario->Id_Beneficiario ? 'selected' : '' }}>
                                    {{ $beneficiario->Nombre_Beneficiario }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="monto_solicitado">Monto Solicitado</label>
                        <input type="number" name="monto_solicitado" class="form-control" value="{{ old('monto_solicitado') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="plazo_meses">Plazo (meses)</label>
                        <input type="number" name="plazo_meses" class="form-control" value="{{ old('plazo_meses') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="destino" class="form-label">Destino</label>
                        <select name="destino" id="destino" class="form-control" required>
                            <option value="">Seleccione una actividad</option>
                            @if(old('destino'))
                                <option selected value="{{ old('destino') }}">{{ old('destino') }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="mb-3 text-end">
                        <button type="button" class="btn btn-primary" onclick="nextTab('finanzas')">Siguiente</button>
                    </div>
                </div>

                {{-- TAB 2: Finanzas --}}
                <div class="tab-pane fade" id="finanzas" role="tabpanel">
                    <div class="mb-3">
                        <label for="tipo_credito">Tipo de Crédito</label>
                        <select name="tipo_credito" id="tipo_credito" class="form-control" required>
                            <option value="" disabled {{ old('tipo_credito') ? '' : 'selected' }}>Seleccione un tipo de crédito</option>
                            <option value="Productivo" {{ old('tipo_credito') == 'Productivo' ? 'selected' : '' }}>Productivo</option>
                            <option value="Consumo" {{ old('tipo_credito') == 'Consumo' ? 'selected' : '' }}>Consumo</option>
                            <option value="Hipotecario" {{ old('tipo_credito') == 'Hipotecario' ? 'selected' : '' }}>Hipotecario</option>
                            <option value="Microcrédito" {{ old('tipo_credito') == 'Microcrédito' ? 'selected' : '' }}>Microcrédito</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_solicitud">Fecha de Solicitud</label>
                        <input type="date" name="fecha_solicitud" class="form-control" value="{{ old('fecha_solicitud') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="porcentaje_mora_caja">Porcentaje de Mora</label>
                        <select name="porcentaje_mora_caja" id="porcentaje_mora_caja" class="form-control" required>
                            <option value="">Seleccione un porcentaje</option>
                            @foreach ($porcentajesMora as $valor => $texto)
                                <option value="{{ $valor }}" {{ old('porcentaje_mora_caja') == $valor ? 'selected' : '' }}>
                                    {{ $texto }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="intereses_cobrados">Intereses Cobrados</label>
                        <input type="number" step="0.01" name="intereses_cobrados" class="form-control" value="{{ old('intereses_cobrados') }}">
                    </div>

                    <div class="mb-3 text-end">
                        <button type="button" class="btn btn-secondary me-2" onclick="nextTab('datos')">Atrás</button>
                        <button type="button" class="btn btn-primary" onclick="nextTab('otros')">Siguiente</button>
                    </div>
                </div>

                {{-- TAB 3: Otros --}}
                <div class="tab-pane fade" id="otros" role="tabpanel">
                    <div class="mb-3">
                        <label for="capital_social">Capital Social</label>
                        <input type="number" step="0.01" name="capital_social" class="form-control" value="{{ old('capital_social') }}">
                    </div>

                    <div class="mb-3">
                        <label for="capital_trabajo">Capital de Trabajo</label>
                        <input type="number" step="0.01" name="capital_trabajo" class="form-control" value="{{ old('capital_trabajo') }}">
                    </div>

                    <div class="mb-3">
                        <label for="reservas">Reservas</label>
                        <input type="number" step="0.01" name="reservas" class="form-control" value="{{ old('reservas') }}">
                    </div>

                    <div class="mb-3">
                        <label for="observaciones">Observaciones</label>
                        <textarea name="observaciones" class="form-control">{{ old('observaciones') }}</textarea>
                    </div>

                    <div class="mb-3 text-end">
                        <button type="button" class="btn btn-secondary me-2" onclick="nextTab('finanzas')">Atrás</button>
                        <button type="submit" class="btn btn-success">Guardar Solicitud</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection

@section('js')
<script>
    // Función para navegar tabs validando campos actuales
    function nextTab(id) {
        const currentTab = document.querySelector('.tab-pane.active');

        // Validar campos visibles en el tab actual
        const inputs = currentTab.querySelectorAll('input, select, textarea');
        for (let input of inputs) {
            if (!input.checkValidity()) {
                input.reportValidity();
                return;
            }
        }

        // Ocultar todos los tabs
        const allTabs = document.querySelectorAll('.tab-pane');
        allTabs.forEach(tab => tab.classList.remove('show', 'active'));

        // Mostrar tab deseado
        const targetTab = document.querySelector(`#${id}`);
        targetTab.classList.add('show', 'active');

        // Activar botón en nav-tabs
        const allNavButtons = document.querySelectorAll('#prestamoTabs button');
        allNavButtons.forEach(btn => btn.classList.remove('active'));
        const targetBtn = document.querySelector(`#prestamoTabs button[data-bs-target="#${id}"]`);
        if (targetBtn) targetBtn.classList.add('active');
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Actualiza input nombre_caja_rural según socio_id seleccionado
    const socioSelect = document.getElementById('socio_id');
    const nombreCajaInput = document.getElementById('nombre_caja_rural');

    function actualizarNombreCaja() {
        const selectedOption = socioSelect.options[socioSelect.selectedIndex];
        nombreCajaInput.value = selectedOption.getAttribute('data-nombre') || '';
    }

    socioSelect.addEventListener('change', actualizarNombreCaja);
    actualizarNombreCaja(); // Para cargar si hay valor viejo
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Actualiza el select destino según beneficiario seleccionado (fetch a API)
    const beneficiarioSelect = document.getElementById('beneficiario_id');
    const destinoSelect = document.getElementById('destino');

    beneficiarioSelect.addEventListener('change', function() {
        const beneficiarioId = this.value;

        if (!beneficiarioId) {
            destinoSelect.innerHTML = '<option value="">Seleccione una actividad</option>';
            return;
        }

        destinoSelect.innerHTML = '<option value="">Cargando...</option>';

        fetch(`/actividades/${beneficiarioId}`)
            .then(response => response.json())
            .then(data => {
                destinoSelect.innerHTML = '<option value="">Seleccione una actividad</option>';
                for (const id in data) {
                    const option = document.createElement('option');
                    option.value = data[id];
                    option.text = data[id];
                    destinoSelect.appendChild(option);
                }

                // Si hay old() o valor anterior, seleccionarlo
                @if(old('destino'))
                    const oldDestino = "{{ old('destino') }}";
                    for(let opt of destinoSelect.options){
                        if(opt.value === oldDestino){
                            opt.selected = true;
                            break;
                        }
                    }
                @endif
            })
            .catch(error => {
                console.error('Error al cargar actividades:', error);
                destinoSelect.innerHTML = '<option value="">Error al cargar</option>';
            });
    });

    // Si carga la página con valor viejo, disparar evento para cargar actividades
    if(beneficiarioSelect.value){
        beneficiarioSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@stop
