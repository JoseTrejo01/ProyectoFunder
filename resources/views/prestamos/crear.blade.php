{{-- Archivo: resources/views/prestamos/create.blade.php --}}
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
                                <option value="{{ $org->Id_Organizacion }}" data-nombre="{{ $org->Nombre_Organizacion }}" {{ old('socio_id') == $org->Id_Organizacion ? 'selected' : '' }}>
                                    {{ $org->Nombre_Organizacion }}
                                </option>
                            @endforeach
                        </select> 
                    </div>

                    <div class="mb-3">
                        <label for="nombre_caja_rural" class="form-label">Nombre Caja Rural</label>
                        <input type="text" name="nombre_caja_rural" id="nombre_caja_rural" class="form-control" readonly required value="{{ old('nombre_caja_rural') }}">
                    </div>

                    <div class="mb-3">
                        <label for="beneficiario_id" class="form-label">Beneficiario</label>
                        <select name="beneficiario_id" id="beneficiario_id" class="form-control" required>
                            <option value="">Seleccione un beneficiario</option>
                            @foreach($beneficiarios as $beneficiario)
                                <option value="{{ $beneficiario->Id_Beneficiario }}" data-actividad="{{ $beneficiario->actividad_economica ?? '' }}" {{ old('beneficiario_id') == $beneficiario->Id_Beneficiario ? 'selected' : '' }}>
                                    {{ $beneficiario->Nombre_Beneficiario }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="departamento_id" class="form-label">Departamento</label>
                        <select name="departamento_id" id="departamento_id" class="form-control" required>
                            <option value="">Seleccione un departamento</option>
                            @foreach($departamentos as $dep)
                                <option value="{{ $dep->Id_Departamento }}" {{ old('departamento_id') == $dep->Id_Departamento ? 'selected' : '' }}>
                                    {{ $dep->Nombre_Departamento }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="monto_solicitado">Monto Solicitado</label>
                        <input type="number" name="monto_solicitado" class="form-control" value="{{ old('monto_solicitado') }}" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="plazo_meses">Plazo (meses)</label>
                        <input type="number" name="plazo_meses" class="form-control" value="{{ old('plazo_meses') }}" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="destino" class="form-label">Destino</label>
                        <select name="destino" id="destino" class="form-control" required>
                            <option value="">Seleccione una actividad</option>
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
                            <option value="">Seleccione un tipo de crédito</option>
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
                        <input type="number" step="0.01" min="0" name="intereses_cobrados" class="form-control" value="{{ old('intereses_cobrados') }}">
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
                        <input type="number" step="0.01" min="0" name="capital_social" class="form-control" value="{{ old('capital_social') }}">
                    </div>

                    <div class="mb-3">
                        <label for="capital_trabajo">Capital de Trabajo</label>
                        <input type="number" step="0.01" min="0" name="capital_trabajo" class="form-control" value="{{ old('capital_trabajo') }}">
                    </div>

                    <div class="mb-3">
                        <label for="reservas">Reservas</label>
                        <input type="number" step="0.01" min="0" name="reservas" class="form-control" value="{{ old('reservas') }}">
                    </div>

                    <div class="mb-3">
                        <label for="observaciones">Observaciones</label>
                        <textarea name="observaciones" class="form-control">{{ old('observaciones') }}</textarea>
                    </div>
                   <div class="mb-3 text-end">
                        <button type="button" class="btn btn-secondary me-2" onclick="nextTab('finanzas')">Atrás</button>
                        <button type="button" class="btn btn-info" onclick="mostrarPlanTemporal()">Ver Plan Temporal</button>
                        <button type="submit" class="btn btn-success">Guardar Solicitud</button>
                    </div>
                </div>
            </div>
        </form>
        
        {{-- Modal Plan Temporal --}}
        <div class="modal fade" id="planTemporalModal" tabindex="-1" aria-labelledby="planTemporalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Plan Temporal de Pago</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID Pago</th>
                                    <th>Fecha Programada</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaPlanTemporal"></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" onclick="imprimirPlanTemporal()">Imprimir</button>
                        {{-- Se necesita definir 'planTemporalModal' fuera de la función --}}
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

---

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

    document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('show', 'active'));
    document.querySelector(`#${id}`).classList.add('show', 'active');

    document.querySelectorAll('#prestamoTabs button').forEach(btn => btn.classList.remove('active'));
    const targetBtn = document.querySelector(`#prestamoTabs button[data-bs-target="#${id}"]`);
    if (targetBtn) targetBtn.classList.add('active');
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // -----------------------------------------------------------------
    // 🛑 IMPLEMENTACIÓN DE INCIDENCIA: MANEJO DE CIERRE SIN GUARDAR 🛑
    // -----------------------------------------------------------------
    let formularioModificado = false;
    const form = document.getElementById('prestamoForm');

    // 1. Marca la bandera al detectar cualquier cambio en el formulario
    form.addEventListener('input', function() {
        if (!formularioModificado) {
            formularioModificado = true;
        }
    });

    // 2. Desactiva la bandera cuando el formulario se envía (guardar exitoso)
    form.addEventListener('submit', function() {
        // Esto previene que se muestre la advertencia al guardar y redirigir
        formularioModificado = false;
    });

    // 3. Manejo del evento beforeunload (Cierre de ventana o navegación)
    window.addEventListener('beforeunload', function(e) {
        if (formularioModificado) {
            e.preventDefault(); 
            // El mensaje real es controlado por el navegador
            e.returnValue = 'Hay datos no guardados. ¿Está seguro de que desea salir?'; 
            return 'Hay datos no guardados. ¿Está seguro de que desea salir?';
        }
    });
    // -----------------------------------------------------------------
    
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
        if (!beneficiarioId) return destinoSelect.innerHTML = '<option value="">Seleccione una actividad</option>';

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

    if (beneficiarioSelect.value) beneficiarioSelect.dispatchEvent(new Event('change'));

    // Validaciones frontend (Enviadas por el usuario)
    form.addEventListener('submit', function (event) {
        const inputs = form.querySelectorAll('input, select, textarea');
        let formValid = true;

        inputs.forEach(input => {
            input.setCustomValidity(''); // limpiar mensajes previos

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
    // Corregí este ID. Estaba usando 'formularioPrestamo' en lugar de 'prestamoForm'.
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

        // Bloquear el signo negativo desde teclado
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
            // Permitimos letras, números, espacios y los caracteres especiales comunes para direcciones/nombres.
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

            // Campos requeridos
            if (campo.hasAttribute('required') && campo.value.trim() === '') {
                campo.setCustomValidity('Este campo es obligatorio.');
                valido = false;
            }

            // Validación personalizada para campos numéricos
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
        }
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // La lógica de precarga de actividades en la vista de edición/creación debe ser manejada
        // por la sección previa si usas old('destino'). Mantenemos esta sección por si 
        // tienes lógica específica de carga inicial aquí, aunque el bloque anterior ya la cubre.
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