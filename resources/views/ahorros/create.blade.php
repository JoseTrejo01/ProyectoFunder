@extends('adminlte::page')

@section('title', 'Registrar Nuevo Ahorro')

@section('content_header')
    <h1 class="font-weight-bold">Registrar Nuevo Ahorro</h1>
@stop

@section('content')
<div class="card shadow-sm">
    <div class="card-body">

        <form action="{{ route('ahorros.store') }}" method="POST">
            @csrf

            {{-- CAJA RURAL --}}
            <div class="form-group mb-3">
                <label for="caja-select" class="form-label">Caja Rural</label>
                <select id="caja-select" name="Id_Organizacion" class="form-control" required>
                    <option value="">-- Seleccione --</option>
                    @foreach ($organizaciones as $organizacion)
                        <option value="{{ $organizacion->Id_Organizacion }}"
                            {{ old('Id_Organizacion', $selectedCaja ?? '') == $organizacion->Id_Organizacion ? 'selected' : '' }}>
                            {{ $organizacion->Nombre_Organizacion }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- BENEFICIARIO --}}
            <div class="form-group mb-3">
                <label for="beneficiario-select" class="form-label">Beneficiario (Socio o Cliente)</label>
                <select id="beneficiario-select" name="Id_Beneficiario" class="form-control" required>
                    <option value="">-- Seleccione un beneficiario --</option>

                    {{-- Si vienen ya cargados (edición / validación) --}}
                    @if(!empty($beneficiarios) && $beneficiarios->count())
                        @foreach ($beneficiarios as $beneficiario)
                            <option value="{{ $beneficiario->Id_Beneficiario }}"
                                {{ old('Id_Beneficiario') == $beneficiario->Id_Beneficiario ? 'selected' : '' }}>
                                {{ $beneficiario->Nombre_Beneficiario }} ({{ $beneficiario->Tipo_De_Socio }})
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            {{-- MONTO --}}
            <div class="form-group mb-3">
                <label for="Monto">Monto (Lps)</label>
                <input type="number" step="0.000001" min="0.000001"
                       name="Monto" id="Monto" class="form-control"
                       value="{{ old('Monto') }}" required>
            </div>

            {{-- FECHA --}}
            <div class="form-group mb-3">
                <label for="Fecha">Fecha</label>
                <input type="date" name="Fecha" id="Fecha" class="form-control"
                       value="{{ old('Fecha') }}" required>
            </div>

            {{-- BOTONES --}}
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Registrar Ahorro</button>
                <a href="{{ route('ahorros.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>

        </form>
    </div>
</div>
@stop


@section('js')
<script>
const cajaSelect = document.getElementById('caja-select');
const beneficiarioSelect = document.getElementById('beneficiario-select');

// Función para cargar beneficiarios
async function cargarBeneficiarios(cajaId) {

    beneficiarioSelect.innerHTML =
        `<option value="">Cargando beneficiarios...</option>`;

    try {
        const response = await fetch(`/api/ahorros/caja/${cajaId}/socios`);

        if (!response.ok) {
            throw new Error('Error al obtener beneficiarios');
        }

        const data = await response.json();

        beneficiarioSelect.innerHTML =
            `<option value="">-- Seleccione un beneficiario --</option>`;

        const beneficiarios = [
            ...(data.socios ?? []),
            ...(data.clientes ?? [])
        ];

        if (beneficiarios.length === 0) {
            beneficiarioSelect.innerHTML =
                `<option value="">No hay beneficiarios para esta caja</option>`;
            return;
        }

        beneficiarios.forEach(b => {
            const opt = document.createElement('option');
            opt.value = b.Id_Beneficiario;
            opt.textContent = `${b.Nombre_Beneficiario} (${b.Tipo_De_Socio})`;
            beneficiarioSelect.appendChild(opt);
        });

    } catch (error) {
        console.error(error);
        beneficiarioSelect.innerHTML =
            `<option value="">Error al cargar beneficiarios</option>`;
    }
}

// Listener al cambiar la caja
cajaSelect.addEventListener('change', function () {
    const cajaId = this.value;

    if (!cajaId) {
        beneficiarioSelect.innerHTML =
            `<option value="">-- Seleccione un beneficiario --</option>`;
        return;
    }

    cargarBeneficiarios(cajaId);
});

// Autocargar si hay caja preseleccionada
document.addEventListener('DOMContentLoaded', () => {
    @if(!empty($selectedCaja))
        cargarBeneficiarios('{{ $selectedCaja }}');
    @endif
});
</script>
@stop
