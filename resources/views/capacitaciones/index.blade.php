@extends('adminlte::page')

@section('content')
<div class="container">
    <h2>Registro de Capacitaciones</h2>
    <a href="{{ route('reporte.exportCapacitacionesExcel') }}" class="btn btn-success mb-3">Exportar Excel</a>
    @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: @json(session('success')),
                    showConfirmButton: false,
                    timer: 2500
                });
            });
        </script>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form action="{{ route('capacitacion.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="Id_Organizacion" class="form-label">Caja Rural</label>
            <select name="Id_Organizacion" id="Id_Organizacion" class="form-control" required>
                <option value="">Seleccione una caja rural</option>
                @foreach($organizaciones as $org)
                    <option value="{{ $org->Id_Organizacion }}">{{ $org->Nombre_Organizacion }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="beneficiarios" class="form-label">Beneficiarios</label>
            <select name="beneficiarios[]" id="beneficiarios" class="form-control" multiple required>
                {{-- Se llenará dinámicamente según la caja rural seleccionada --}}
            </select>
        </div>
        <!-- Tabs de módulos -->
        @php
            $modulosUnicos = collect($modulos)->unique('Nombre_Modulo')->values();
        @endphp
        <ul class="nav nav-tabs nav-tabs-bordered mb-1" id="moduloTabs" role="tablist" style="font-size: 0.85rem;">
            @foreach($modulosUnicos as $idx => $modulo)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $idx === 0 ? 'active' : '' }} fw-bold text-primary px-1 py-0" id="tabModulo{{ $modulo->Id_Modulo }}" data-bs-toggle="tab" data-bs-target="#modulo{{ $modulo->Id_Modulo }}" type="button" role="tab" aria-controls="modulo{{ $modulo->Id_Modulo }}" aria-selected="{{ $idx === 0 ? 'true' : 'false' }}" style="border-radius: 4px 4px 0 0; background: #f8f9fa; min-width: 80px; max-width: 120px; font-size: 0.85rem; white-space: normal;">{{ $modulo->Nombre_Modulo }}</button>
                </li>
            @endforeach
        </ul>
        <div class="tab-content pt-3" id="moduloTabsContent" style="background: #fff; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07);">
            @foreach($modulosUnicos as $idx => $modulo)
                <div class="tab-pane fade {{ $idx === 0 ? 'show active' : '' }}" id="modulo{{ $modulo->Id_Modulo }}" role="tabpanel" aria-labelledby="tabModulo{{ $modulo->Id_Modulo }}">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered shadow-sm" id="tabla-capacitacion-{{ $modulo->Id_Modulo }}" style="border-radius: 8px; overflow: hidden;">
                            <thead class="table-primary">
                                <tr>
                                    <th style="width: 60%;">Tema</th>
                                    <th style="width: 40%;">Recibió (por beneficiario)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($temasPorModulo[$modulo->Id_Modulo] as $tema)
                                    <tr data-tema-id="{{ $tema->Id_Tema }}">
                                        <td class="align-middle fw-semibold" style="font-size: 1.05rem;">{{ $tema->Nombre_Tema }}</td>
                                        <td class="beneficiarios-checkboxes align-middle" style="vertical-align: middle;">
                                            {{-- Los checkboxes se llenan por JS --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mb-3">
            <label for="Fecha" class="form-label">Fecha</label>
            <input type="date" name="Fecha" id="Fecha" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
   
    // Beneficiarios por organización
    const beneficiariosPorOrg = @json($beneficiariosPorOrg);
    // Beneficiarios seleccionados
    let beneficiariosSeleccionados = [];

    // Actualiza el select de beneficiarios según la caja rural
    document.getElementById('Id_Organizacion').addEventListener('change', function() {
        let orgId = this.value;
        let select = document.getElementById('beneficiarios');
        select.innerHTML = '';
        if (beneficiariosPorOrg[orgId]) {
            beneficiariosPorOrg[orgId].forEach(function(ben) {
                select.innerHTML += `<option value='${ben.Id_Beneficiario}'>${ben.Nombre_Beneficiario}</option>`;
            });
        }
        actualizarCheckboxes();
    });

    // Actualiza los checkboxes en la tabla según los beneficiarios seleccionados
    document.getElementById('beneficiarios').addEventListener('change', function() {
        actualizarCheckboxes();
    });

    function actualizarCheckboxes() {
        let select = document.getElementById('beneficiarios');
        let seleccionados = Array.from(select.selectedOptions).map(opt => ({id: opt.value}));
        // Buscar todas las tablas de módulos
        document.querySelectorAll('table[id^="tabla-capacitacion-"]').forEach(function(tabla) {
            let moduloId = tabla.id.replace('tabla-capacitacion-', '');
            let filas = tabla.querySelectorAll('tbody tr');
            filas.forEach(function(fila) {
                let td = fila.querySelector('.beneficiarios-checkboxes');
                td.innerHTML = '';
                let temaId = fila.getAttribute('data-tema-id');
                seleccionados.forEach(function(ben) {
                    if (temaId) {
                        td.innerHTML += `<div class='form-check form-check-inline'><input type='checkbox' name='recibio[${ben.id}][${moduloId}][${temaId}]' value='1' class='form-check-input' id='check_${ben.id}_${moduloId}_${temaId}'></div>`;
                    }
                });
            });
        });
    }
</script>
@endsection
