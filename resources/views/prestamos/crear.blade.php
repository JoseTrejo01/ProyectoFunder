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
                        <label for="socio_id" class="form-label">Organización (Socio)</label>
                        <select name="socio_id" id="socio_id" class="form-control" required>
                            <option value="">Seleccione una</option>
                            @foreach($organizaciones as $org)
                                <option value="{{ $org->Id_Organizacion }}">{{ $org->Nombre_Organizacion }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nombre_caja_rural">Caja Rural</label>
                        <input type="text" name="nombre_caja_rural" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="monto_solicitado">Monto Solicitado</label>
                        <input type="number" name="monto_solicitado" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="plazo_meses">Plazo (meses)</label>
                        <input type="number" name="plazo_meses" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="destino">Destino</label>
                        <input type="text" name="destino" class="form-control" required>
                    </div>

                    <div class="mb-3 text-end">
                        <button type="button" class="btn btn-primary" onclick="nextTab('finanzas')">Siguiente</button>
                    </div>
                </div>

                {{-- TAB 2: Finanzas --}}
                <div class="tab-pane fade" id="finanzas" role="tabpanel">
                    <div class="mb-3">
                        <label for="tipo_credito">Tipo de Crédito</label>
                        <input type="text" name="tipo_credito" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_solicitud">Fecha de Solicitud</label>
                        <input type="date" name="fecha_solicitud" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="porcentaje_mora_caja">% de Mora</label>
                        <input type="number" step="0.01" name="porcentaje_mora_caja" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="intereses_cobrados">Intereses Cobrados</label>
                        <input type="number" step="0.01" name="intereses_cobrados" class="form-control">
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
                        <input type="number" step="0.01" name="capital_social" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="capital_trabajo">Capital de Trabajo</label>
                        <input type="number" step="0.01" name="capital_trabajo" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="reservas">Reservas</label>
                        <input type="number" step="0.01" name="reservas" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="observaciones">Observaciones</label>
                        <textarea name="observaciones" class="form-control"></textarea>
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
        function nextTab(id) {
            const form = document.getElementById('prestamoForm');
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
    @stop
