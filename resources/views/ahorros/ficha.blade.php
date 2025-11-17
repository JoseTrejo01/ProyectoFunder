@extends('adminlte::page')

@section('title', 'Ficha del Ahorro')

@section('content_header')
    <h1 class="fw-bold text-dark">Editar Ahorro</h1>
@stop

@section('css')
{{-- Select2 + Bootstrap --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
    /* Mejor contraste */
    .select2-container--bootstrap-5 .select2-selection {
        border: 2px solid #3b3b3b !important;
        min-height: 2.8rem;
        border-radius: .4rem;
    }
    .select2-container--bootstrap-5 .select2-selection__rendered {
        color: #111 !important;
        font-weight: 600;
    }
    .select2-results__option--highlighted {
        background-color: #0d6efd !important;
        color: #fff !important;
    }
</style>
@stop

@section('content')
<<<<<<< HEAD
<div class="card shadow-sm border-0">
    <div class="card-body">

        <form action="{{ route('ahorros.update', $ahorro->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- CAJA --}}
            <div class="form-group mb-3">
                <label for="caja-select" class="fw-semibold text-dark">Caja Rural</label>
                <select id="caja-select" name="Id_Organizacion" class="form-control select2" required>
                    <option value="">-- Seleccione --</option>
                    @foreach ($cajas as $caja)
                        <option value="{{ $caja->Id_Organizacion }}"
                            {{ $caja->Id_Organizacion == old('Id_Organizacion', $ahorro->Id_Organizacion) ? 'selected' : '' }}>
                            {{ $caja->Nombre_Organizacion }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- BENEFICIARIO --}}
            <div class="form-group mb-3">
                <label for="beneficiario-select" class="fw-semibold text-dark">Beneficiario (Socio o Cliente)</label>
                <select id="beneficiario-select" name="Id_Beneficiario" class="form-control select2" required>
                    <option value="">Cargando beneficiarios...</option>
                </select>
            </div>

            {{-- MONTO --}}
            <div class="form-group mb-3">
                <label for="Monto" class="fw-semibold text-dark">Monto (Lps)</label>
                <input type="number"
                       step="0.000001"
                       min="0.000001"
                       name="Monto"
                       id="Monto"
                       class="form-control border-dark"
                       value="{{ old('Monto', $ahorro->Monto) }}"
                       required>
            </div>

            {{-- FECHA --}}
            <div class="form-group mb-3">
                <label for="Fecha" class="fw-semibold text-dark">Fecha</label>
                <input type="date"
                       name="Fecha"
                       id="Fecha"
                       class="form-control border-dark"
                       value="{{ old('Fecha', $ahorro->Fecha->format('Y-m-d')) }}"
                       required>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary fw-semibold px-4">Actualizar Ahorro</button>
                <a href="{{ route('ahorros.index') }}" class="btn btn-secondary fw-semibold px-4">Cancelar</a>
            </div>

        </form>

    </div>
</div>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    // Inicializar Select2
    $(".select2").select2({
        theme: "bootstrap-5",
        width: '100%',
        placeholder: "-- Seleccione --",
        allowClear: true
    });

    // Cargar beneficiarios
    async function cargarBeneficiarios(cajaId, seleccionado = null) {
        const select = $("#beneficiario-select");
        select.html(`<option value="">Cargando...</option>`);

        if (!cajaId) {
            select.html(`<option value="">Seleccione una caja primero</option>`);
            select.trigger('change');
            return;
        }

        try {
            const response = await fetch(`/api/ahorros/caja/${cajaId}/socios`);
            if (!response.ok) throw new Error("Error en la API");

            const data = await response.json();
            const beneficiarios = [...(data.socios ?? []), ...(data.clientes ?? [])];

            select.empty();
            select.append(`<option value="">-- Seleccione --</option>`);

            if (beneficiarios.length === 0) {
                select.append(`<option value="">No hay beneficiarios disponibles</option>`);
                select.trigger('change');
                return;
            }

            beneficiarios.forEach(b => {
                const option = `<option value="${b.Id_Beneficiario}" 
                                    ${b.Id_Beneficiario == seleccionado ? "selected" : ""}>
                                    ${b.Nombre_Beneficiario} (${b.Tipo_De_Socio})
                                </option>`;
                select.append(option);
            });

            select.trigger("change");

        } catch (error) {
            console.error(error);
            select.html(`<option value="">Error al cargar beneficiarios</option>`);
            select.trigger('change');
        }
    }

    // Evento de cambio de caja rural
    $("#caja-select").on("change", function () {
        cargarBeneficiarios(this.value);
    });

    // Cargar beneficiarios al abrir la página
    const cajaId = $("#caja-select").val();
    const beneficiarioSel = "{{ old('Id_Beneficiario', $ahorro->Id_Beneficiario) }}";

    if (cajaId) {
        cargarBeneficiarios(cajaId, beneficiarioSel);
    }
});
</script>
=======
<div class="card">
    <div class="card-header bg-primary text-white">
        Ahorro de: {{ $ahorro->beneficiario->Nombre_Beneficiario ?? 'N/D' }}
    </div>
    <div class="card-body">
        <ul class="list-group">
            <li class="list-group-item">
                <strong>Organización:</strong> {{ $ahorro->organizacion->Nombre_Organizacion ?? 'N/D' }}
            </li>
            <li class="list-group-item">
                <strong>Beneficiario:</strong> {{ $ahorro->beneficiario->Nombre_Beneficiario ?? 'N/D' }}
            </li>
            <li class="list-group-item">
                <strong>Tipo:</strong> {{ $ahorro->beneficiario->Tipo_De_Socio ?? 'N/D' }}
            </li>
            <li class="list-group-item">
                <strong>Monto:</strong> L. {{ number_format($ahorro->Monto, 2, '.', ',') }}
            </li>
            <li class="list-group-item">
                <strong>Total ahorrado por este beneficiario:</strong> 
                L. {{ number_format($totalAhorrosBeneficiario, 2, '.', ',') }}
            </li>
            <li class="list-group-item">
                <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($ahorro->Fecha)->format('d-m-Y') }}
            </li>
            <li class="list-group-item">
                <strong>Creado en:</strong> {{ $ahorro->created_at->format('d-m-Y H:i') }}
            </li>
            <li class="list-group-item">
                <strong>Última actualización:</strong> {{ $ahorro->updated_at->format('d-m-Y H:i') }}
            </li>
        </ul>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <a href="{{ route('ahorros.index') }}" class="btn btn-secondary">Volver</a>

        
    </div>
</div>
>>>>>>> origin/cambios-seguridad
@stop
