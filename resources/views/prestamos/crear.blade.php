@extends('adminlte::page')

@section('title', 'Registrar Solicitud de Préstamo')

@section('content_header')
    <h1 id="titulo-solicitud" class="fw-bold">
        Registrar Solicitud de Préstamo
    </h1>
@stop

@section('content')

<div class="container d-flex justify-content-center" role="main" aria-labelledby="titulo-solicitud">
    <div class="w-100" style="max-width: 700px;">

        {{-- Errores --}}
        @if ($errors->any())
            <div class="alert alert-danger" role="alert" aria-live="assertive" tabindex="0">
                <strong>Corrige los errores:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="prestamoForm"
              action="{{ route('prestamos.store') }}"
              method="POST"
              role="form"
              aria-describedby="ayuda-formulario">

            @csrf

            <p id="ayuda-formulario" class="visually-hidden">
                Formulario dividido en tres pestañas: datos generales, finanzas y otros campos necesarios para registrar una solicitud de préstamo.
            </p>

            {{-- TABS ACCESIBLES --}}
            <ul class="nav nav-tabs" id="prestamoTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active"
                            id="datos-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#datos"
                            type="button"
                            role="tab"
                            aria-controls="datos"
                            aria-selected="true">
                        Datos Generales
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            id="finanzas-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#finanzas"
                            type="button"
                            role="tab"
                            aria-controls="finanzas"
                            aria-selected="false">
                        Finanzas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            id="otros-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#otros"
                            type="button"
                            role="tab"
                            aria-controls="otros"
                            aria-selected="false">
                        Otros
                    </button>
                </li>
            </ul>

            <div class="tab-content pt-3" id="prestamoTabsContent">

                {{-- TAB 1: Datos Generales --}}
                <div class="tab-pane fade show active"
                     id="datos"
                     role="tabpanel"
                     aria-labelledby="datos-tab">

                    <fieldset class="border p-3 rounded mb-4">
                        <legend class="fw-semibold px-2">Datos Generales</legend>

                        <div class="mb-3">
                            <label for="socio_id" class="form-label fw-semibold">
                                Caja Rural <span class="text-danger">*</span>
                            </label>
                            <select name="socio_id"
                                    id="socio_id"
                                    class="form-control"
                                    required
                                    aria-required="true">
                                <option value="">Seleccione una</option>
                                @foreach($organizaciones as $org)
                                    <option value="{{ $org->Id_Organizacion }}"
                                            data-nombre="{{ $org->Nombre_Organizacion }}"
                                            {{ old('socio_id') == $org->Id_Organizacion ? 'selected' : '' }}>
                                        {{ $org->Nombre_Organizacion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nombre_caja_rural" class="form-label fw-semibold">
                                Nombre Caja Rural
                            </label>
                            <input type="text"
                                   name="nombre_caja_rural"
                                   id="nombre_caja_rural"
                                   class="form-control"
                                   readonly
                                   required
                                   aria-required="true"
                                   value="{{ old('nombre_caja_rural') }}">
                        </div>

                        <div class="mb-3">
                            <label for="beneficiario_id" class="form-label fw-semibold">
                                Beneficiario <span class="text-danger">*</span>
                            </label>
                            <select name="beneficiario_id"
                                    id="beneficiario_id"
                                    class="form-control"
                                    required
                                    aria-required="true">
                                <option value="">Seleccione un beneficiario</option>
                                @foreach($beneficiarios as $beneficiario)
                                    <option value="{{ $beneficiario->Id_Beneficiario }}"
                                            data-actividad="{{ $beneficiario->actividad_economica ?? '' }}"
                                            {{ old('beneficiario_id') == $beneficiario->Id_Beneficiario ? 'selected' : '' }}>
                                        {{ $beneficiario->Nombre_Beneficiario }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="departamento_id" class="form-label fw-semibold">
                                Departamento <span class="text-danger">*</span>
                            </label>
                            <select name="departamento_id"
                                    id="departamento_id"
                                    class="form-control"
                                    required
                                    aria-required="true">
                                <option value="">Seleccione un departamento</option>
                                @foreach($departamentos as $dep)
                                    <option value="{{ $dep->Id_Departamento }}"
                                            {{ old('departamento_id') == $dep->Id_Departamento ? 'selected' : '' }}>
                                        {{ $dep->Nombre_Departamento }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="monto_solicitado" class="form-label fw-semibold">
                                Monto Solicitado <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   name="monto_solicitado"
                                   id="monto_solicitado"
                                   class="form-control"
                                   value="{{ old('monto_solicitado') }}"
                                   min="0"
                                   required
                                   aria-required="true">
                        </div>

                        <div class="mb-3">
                            <label for="plazo_meses" class="form-label fw-semibold">
                                Plazo (meses) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   name="plazo_meses"
                                   id="plazo_meses"
                                   class="form-control"
                                   value="{{ old('plazo_meses') }}"
                                   min="1"
                                   required
                                   aria-required="true">
                        </div>

                        <div class="mb-3">
                            <label for="destino" class="form-label fw-semibold">
                                Destino <span class="text-danger">*</span>
                            </label>
                            <select name="destino"
                                    id="destino"
                                    class="form-control"
                                    required
                                    aria-required="true">
                                <option value="">Seleccione una actividad</option>
                            </select>
                        </div>

                        <div class="mb-3 text-end">
                            <button type="button"
                                    class="btn fw-semibold px-3"
                                    style="background-color:#0D47A1; color:white;"
                                    onclick="nextTab('finanzas')">
                                Siguiente
                            </button>
                        </div>
                    </fieldset>
                </div>

                {{-- TAB 2: Finanzas --}}
                <div class="tab-pane fade"
                     id="finanzas"
                     role="tabpanel"
                     aria-labelledby="finanzas-tab">

                    <fieldset class="border p-3 rounded mb-4">
                        <legend class="fw-semibold px-2">Finanzas</legend>

                        <div class="mb-3">
                            <label for="tipo_credito" class="form-label fw-semibold">
                                Tipo de Crédito <span class="text-danger">*</span>
                            </label>
                            <select name="tipo_credito"
                                    id="tipo_credito"
                                    class="form-control"
                                    required
                                    aria-required="true">
                                <option value="">Seleccione un tipo de crédito</option>
                                <option value="Productivo" {{ old('tipo_credito') == 'Productivo' ? 'selected' : '' }}>Productivo</option>
                                <option value="Consumo" {{ old('tipo_credito') == 'Consumo' ? 'selected' : '' }}>Consumo</option>
                                <option value="Hipotecario" {{ old('tipo_credito') == 'Hipotecario' ? 'selected' : '' }}>Hipotecario</option>
                                <option value="Microcrédito" {{ old('tipo_credito') == 'Microcrédito' ? 'selected' : '' }}>Microcrédito</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_solicitud" class="form-label fw-semibold">
                                Fecha de Solicitud <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   name="fecha_solicitud"
                                   id="fecha_solicitud"
                                   class="form-control"
                                   value="{{ old('fecha_solicitud') }}"
                                   required
                                   aria-required="true">
                        </div>

                        <div class="mb-3">
                            <label for="porcentaje_mora_caja" class="form-label fw-semibold">
                                Porcentaje de Mora <span class="text-danger">*</span>
                            </label>
                            <select name="porcentaje_mora_caja"
                                    id="porcentaje_mora_caja"
                                    class="form-control"
                                    required
                                    aria-required="true">
                                <option value="">Seleccione un porcentaje</option>
                                @foreach ($porcentajesMora as $valor => $texto)
                                    <option value="{{ $valor }}"
                                            {{ old('porcentaje_mora_caja') == $valor ? 'selected' : '' }}>
                                        {{ $texto }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="intereses_cobrados" class="form-label fw-semibold">
                                Intereses Cobrados
                            </label>
                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="intereses_cobrados"
                                   id="intereses_cobrados"
                                   class="form-control"
                                   value="{{ old('intereses_cobrados') }}">
                        </div>

                        <div class="mb-3 text-end">
                            <button type="button"
                                    class="btn btn-secondary me-2 fw-semibold px-3"
                                    onclick="nextTab('datos')">
                                Atrás
                            </button>
                            <button type="button"
                                    class="btn fw-semibold px-3"
                                    style="background-color:#0D47A1; color:white;"
                                    onclick="nextTab('otros')">
                                Siguiente
                            </button>
                        </div>
                    </fieldset>
                </div>

                {{-- TAB 3: Otros --}}
                <div class="tab-pane fade"
                     id="otros"
                     role="tabpanel"
                     aria-labelledby="otros-tab">

                    <fieldset class="border p-3 rounded mb-4">
                        <legend class="fw-semibold px-2">Otros Datos</legend>

                        <div class="mb-3">
                            <label for="capital_social" class="form-label fw-semibold">
                                Capital Social
                            </label>
                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="capital_social"
                                   id="capital_social"
                                   class="form-control"
                                   value="{{ old('capital_social') }}">
                        </div>

                        <div class="mb-3">
                            <label for="capital_trabajo" class="form-label fw-semibold">
                                Capital de Trabajo
                            </label>
                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="capital_trabajo"
                                   id="capital_trabajo"
                                   class="form-control"
                                   value="{{ old('capital_trabajo') }}">
                        </div>

                        <div class="mb-3">
                            <label for="reservas" class="form-label fw-semibold">
                                Reservas
                            </label>
                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="reservas"
                                   id="reservas"
                                   class="form-control"
                                   value="{{ old('reservas') }}">
                        </div>

                        <div class="mb-3">
                            <label for="observaciones" class="form-label fw-semibold">
                                Observaciones
                            </label>
                            <textarea name="observaciones"
                                      id="observaciones"
                                      class="form-control">{{ old('observaciones') }}</textarea>
                        </div>

                        <div class="mb-3 text-end">
                            <button type="button"
                                    class="btn btn-secondary me-2 fw-semibold px-3"
                                    onclick="nextTab('finanzas')">
                                Atrás
                            </button>
                            <button type="button"
                                    class="btn btn-info fw-semibold px-3"
                                    onclick="mostrarPlanTemporal()">
                                Ver Plan Temporal
                            </button>
                            <button type="submit"
                                    class="btn btn-success fw-semibold px-3">
                                Guardar Solicitud
                            </button>
                        </div>
                    </fieldset>
                </div>

            </div>
        </form>

        {{-- Modal Plan Temporal --}}
        <div class="modal fade"
             id="planTemporalModal"
             tabindex="-1"
             aria-labelledby="planTemporalLabel"
             aria-hidden="true"
             role="dialog"
             aria-modal="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-semibold" id="planTemporalLabel">
                            Plan Temporal de Pago
                        </h5>
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive" role="region" aria-labelledby="tabla-plan-temporal-titulo">
                            <table class="table table-striped table-bordered">
                                <caption id="tabla-plan-temporal-titulo" class="visually-hidden">
                                    Tabla que muestra un plan temporal de pagos calculado en base al monto y plazo del préstamo.
                                </caption>
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">ID Pago</th>
                                        <th scope="col">Fecha Programada</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaPlanTemporal"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary fw-semibold" onclick="imprimirPlanTemporal()">
                            Imprimir
                        </button>
                        <button type="button"
                                class="btn btn-secondary fw-semibold"
                                data-bs-dismiss="modal">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('js')
<script>
function nextTab(id) {
    const currentTab = document.querySelector('.tab-pane.active');
    const inputs = currentTab.querySelectorAll('input, select, textarea');
    for (let input of inputs) {
        if (!input.checkValidity()) {
            input.reportValidity();
            return;
        }
    }

    document.querySelectorAll('.tab-pane').forEach(tab => {
        tab.classList.remove('show', 'active');
    });
    document.querySelector('#' + id).classList.add('show', 'active');

    // Actualiza clases y ARIA en botones de tabs
    document.querySelectorAll('#prestamoTabs button').forEach(btn => {
        btn.classList.remove('active');
        btn.setAttribute('aria-selected', 'false');
    });
    const targetBtn = document.querySelector('#prestamoTabs button[data-bs-target="#' + id + '"]');
    if (targetBtn) {
        targetBtn.classList.add('active');
        targetBtn.setAttribute('aria-selected', 'true');
        targetBtn.focus();
    }
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Manejo de cierre sin guardar
    let formularioModificado = false;
    const form = document.getElementById('prestamoForm');

    form.addEventListener('input', function() {
        if (!formularioModificado) {
            formularioModificado = true;
        }
    });

    form.addEventListener('submit', function() {
        formularioModificado = false;
    });

    window.addEventListener('beforeunload', function(e) {
        if (formularioModificado) {
            e.preventDefault();
            e.returnValue = 'Hay datos no guardados. ¿Está seguro de que desea salir?';
            return 'Hay datos no guardados. ¿Está seguro de que desea salir?';
        }
    });

    // Autocompletar nombre de caja rural
    const socioSelect = document.getElementById('socio_id');
    const nombreCajaInput = document.getElementById('nombre_caja_rural');

    socioSelect.addEventListener('change', function () {
        const selected = socioSelect.options[socioSelect.selectedIndex];
        nombreCajaInput.value = selected.getAttribute('data-nombre') || '';
    });

    socioSelect.dispatchEvent(new Event('change'));

    // Cargar actividades según beneficiario
    const beneficiarioSelect = document.getElementById('beneficiario_id');
    const destinoSelect = document.getElementById('destino');

    beneficiarioSelect.addEventListener('change', function () {
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

                @if(old('destino'))
                    const oldDestino = "{{ old('destino') }}";
                    for (let opt of destinoSelect.options) {
                        if (opt.value === oldDestino) {
                            opt.selected = true;
                            break;
                        }
                    }
                @endif
            })
            .catch(() => destinoSelect.innerHTML = '<option value="">Error al cargar</option>');
    });

    if (beneficiarioSelect.value) {
        beneficiarioSelect.dispatchEvent(new Event('change'));
    }

    // Validaciones frontend
    form.addEventListener('submit', function (event) {
        const inputs = form.querySelectorAll('input, select, textarea');
        let formValid = true;

        inputs.forEach(input => {
            input.setCustomValidity('');

            if (input.hasAttribute('required') && !input.value.trim()) {
                input.setCustomValidity('Este campo es obligatorio.');
                formValid = false;
            }

            if (input.type === 'number') {
                const valor = parseFloat(input.value);
                if (isNaN(valor)) {
                    input.setCustomValidity('Por favor, ingrese un número válido.');
                    formValid = false;
                } else if (valor < 0) {
                    input.setCustomValidity('No se permiten valores negativos.');
                    formValid = false;
                }
            }

            if (input.tagName === 'SELECT' && input.hasAttribute('required') && !input.value) {
                input.setCustomValidity('Por favor, seleccione una opción.');
                formValid = false;
            }

            if (!input.checkValidity()) {
                input.reportValidity();
            }
        });

        if (!formValid) {
            event.preventDefault();
            const primeroInvalido = form.querySelector(':invalid');
            if (primeroInvalido) {
                primeroInvalido.focus();
            }
        }
    });

    // Previene ingreso de negativos
    const numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
        input.addEventListener('keydown', e => {
            if (e.key === '-' || e.key === 'e') e.preventDefault();
        });
        input.addEventListener('input', () => {
            if (parseFloat(input.value) < 0) input.value = '';
        });
    });

    // Observaciones sin caracteres especiales
    const obsInput = document.querySelector('textarea[name="observaciones"]');
    if (obsInput) {
        obsInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ.,()\s]/g, '');
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('prestamoForm');

    // Validación en tiempo real para campos tipo número
    form.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function () {
            const valor = this.value.trim();
            this.setCustomValidity('');

            if (valor === '') {
                this.setCustomValidity('Este campo es obligatorio.');
            } else if (isNaN(valor)) {
                this.setCustomValidity('Por favor, ingrese un número válido.');
            } else if (parseFloat(valor) < 0) {
                this.setCustomValidity('No se permiten valores negativos.');
            }
        });

        input.addEventListener('keydown', function (event) {
            if (event.key === '-' || event.key === 'Subtract') {
                event.preventDefault();
            }
        });
    });

    // Bloquear caracteres especiales en inputs de texto
    form.querySelectorAll('input[type="text"]').forEach(input => {
        input.addEventListener('keypress', function (event) {
            const tecla = event.key;
            const regex = /^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ.,()/\-]*$/;

            if (!regex.test(tecla)) {
                event.preventDefault();
            }
        });
    });

    // Validación general al enviar el formulario
    form.addEventListener('submit', function (event) {
        let valido = true;

        form.querySelectorAll('input, select').forEach(campo => {
            campo.setCustomValidity('');

            if (campo.hasAttribute('required') && campo.value.trim() === '') {
                campo.setCustomValidity('Este campo es obligatorio.');
                valido = false;
            }

            if (campo.type === 'number') {
                const valor = campo.value.trim();

                if (valor === '') {
                    campo.setCustomValidity('Este campo es obligatorio.');
                    valido = false;
                } else if (isNaN(valor)) {
                    campo.setCustomValidity('Por favor, ingrese un número válido.');
                    valido = false;
                } else if (parseFloat(valor) < 0) {
                    campo.setCustomValidity('No se permiten valores negativos.');
                    valido = false;
                }
            }
        });

        if (!valido) {
            event.preventDefault();
            const primeroInvalido = form.querySelector(':invalid');
            if (primeroInvalido) {
                primeroInvalido.focus();
            }
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Lógica adicional de precarga de actividades (si aplica)
    const beneficiarioId = {{ $prestamo->beneficiario_id ?? 'null' }};
    const oldDestino = "{{ old('destino') }}";

    if (beneficiarioId) {
        fetch(`/ruta/para/obtener-actividades/${beneficiarioId}`)
            .then(response => response.json())
            .then(data => {
                const selectDestino = document.getElementById('destino');
                selectDestino.innerHTML = '<option value="">Seleccione una actividad</option>';

                for (const [id, rubro] of Object.entries(data)) {
                    const option = document.createElement('option');
                    option.value = rubro;
                    option.textContent = rubro;

                    if (rubro === oldDestino) {
                        option.selected = true;
                    }

                    selectDestino.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error al cargar actividades:', error);
            });
    }
});
</script>

<script>
    // Inicializa modal una sola vez
    var planTemporalModal = new bootstrap.Modal(document.getElementById('planTemporalModal'));

    function mostrarPlanTemporal() {
        let monto = parseFloat(document.querySelector('[name="monto_solicitado"]').value) || 0;
        let plazo = parseInt(document.querySelector('[name="plazo_meses"]').value) || 0;

        if (monto <= 0 || plazo <= 0) {
            alert("Debe ingresar monto y plazo válidos.");
            return;
        }

        let tabla = document.getElementById('tablaPlanTemporal');
        tabla.innerHTML = '';

        let montoCuota = (monto / plazo).toFixed(2);
        let fechaActual = new Date();

        for (let i = 1; i <= plazo; i++) {
            fechaActual.setMonth(fechaActual.getMonth() + 1);
            let fechaStr = fechaActual.toISOString().split('T')[0];

            tabla.innerHTML += `
                <tr>
                    <td>${i}</td>
                    <td>${fechaStr}</td>
                    <td>${parseFloat(montoCuota).toLocaleString('es-HN', {minimumFractionDigits: 2})}</td>
                    <td>Pendiente</td>
                    <td>Pago automático generado</td>
                </tr>
            `;
        }

        planTemporalModal.show();
    }

    function imprimirPlanTemporal() {
        let contenido = document.getElementById('planTemporalModal').querySelector('.modal-body').innerHTML;
        let ventana = window.open('', '', 'width=900,height=600');
        ventana.document.write('<html><head><title>Plan Temporal</title></head><body>');
        ventana.document.write(contenido);
        ventana.document.write('</body></html>');
        ventana.document.close();
        ventana.print();
    }
</script>

@endsection
