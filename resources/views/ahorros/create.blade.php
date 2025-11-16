@extends('adminlte::page')

@section('title', 'Registrar Nuevo Ahorro')

@section('content_header')
    <h1>Registrar Nuevo Ahorro</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('ahorros.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="caja-select">Caja Rural</label>
                    <select id="caja-select" name="Id_Organizacion" class="form-control" required>
                        <option value="">-- Seleccione --</option>
                        @foreach ($organizaciones as $organizacion)
            <option value="{{ $organizacion->Id_Organizacion }}" {{ (old('Id_Organizacion', $selectedCaja ?? '') == $organizacion->Id_Organizacion) ? 'selected' : '' }}>
                {{ $organizacion->Nombre_Organizacion }}
            </option>
        @endforeach
    </select>
</div>

                <div class="form-group">
                    <label for="beneficiario-select">Beneficiario (Socio o Cliente)</label>
                    <select id="beneficiario-select" name="Id_Beneficiario" class="form-control" required>
                        <option value="">-- Seleccione un beneficiario --</option>
                        @if(!empty($beneficiarios) && $beneficiarios->count())
                            @foreach ($beneficiarios as $beneficiario)
                                <option value="{{ $beneficiario->Id_Beneficiario }}" {{ old('Id_Beneficiario') == $beneficiario->Id_Beneficiario ? 'selected' : '' }}>
                                    {{ $beneficiario->Nombre_Beneficiario }} ({{ $beneficiario->Tipo_De_Socio }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="Monto">Monto (Lps)</label>
                    <input type="number" step="0.000001" min="0.000001" name="Monto" id="Monto" class="form-control" value="{{ old('Monto') }}" required>
                </div>

                <div class="form-group">
                    <label for="Fecha">Fecha</label>
                    <input type="date" name="Fecha" id="Fecha" class="form-control" value="{{ old('Fecha') }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Registrar Ahorro</button>
                <a href="{{ route('ahorros.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop

@section('js')
<script>
    const cajaSelect = document.getElementById('caja-select');
    const beneficiarioSelect = document.getElementById('beneficiario-select');

    // Al cambiar la caja, cargar beneficiarios dinámicamente
    cajaSelect.addEventListener('change', function() {
        const cajaId = this.value;

        beneficiarioSelect.innerHTML = '<option>Cargando beneficiarios...</option>';

        if (!cajaId) {
            beneficiarioSelect.innerHTML = '<option value="">-- Seleccione un beneficiario --</option>';
            return;
        }

        fetch(`/api/ahorros/caja/${cajaId}/socios`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener beneficiarios');
                }
                return response.json();
            })
            .then(data => {
                beneficiarioSelect.innerHTML = '';

                const beneficiarios = [...data.socios, ...data.clientes];

                if (beneficiarios.length === 0) {
                    beneficiarioSelect.innerHTML = '<option value="">No hay beneficiarios para esta caja</option>';
                    return;
                }

                beneficiarios.forEach(b => {
                    const option = document.createElement('option');
                    option.value = b.Id_Beneficiario;
                    option.textContent = `${b.Nombre_Beneficiario} (${b.Tipo_De_Socio})`;
                    beneficiarioSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error(error);
                beneficiarioSelect.innerHTML = '<option value="">Error al cargar beneficiarios</option>';
            });
    });

    // Al cargar la página, si hay caja seleccionada, disparar el evento para cargar beneficiarios
    window.addEventListener('DOMContentLoaded', () => {
        @if(!empty($selectedCaja))
            cajaSelect.dispatchEvent(new Event('change'));
        @endif
    });
</script>
@stop
