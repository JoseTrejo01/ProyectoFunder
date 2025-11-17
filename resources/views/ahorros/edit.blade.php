@extends('adminlte::page')

@section('title', 'Editar Ahorro')

@section('content_header')
    <h1>Editar Ahorro</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('ahorros.update', $ahorro->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="caja-select">Caja Rural</label>
                    <select id="caja-select" name="Id_Organizacion" class="form-control" required>
                        <option value="">-- Seleccione --</option>
                        @foreach ($cajas as $caja)
                            <option value="{{ $caja->Id_Organizacion }}" 
                                {{ $caja->Id_Organizacion == old('Id_Organizacion', $ahorro->Id_Organizacion) ? 'selected' : '' }}>
                                {{ $caja->Nombre_Organizacion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="beneficiario-select">Beneficiario (Socio o Cliente)</label>
                    <select id="beneficiario-select" name="Id_Beneficiario" class="form-control" required>
                        <option value="">Cargando beneficiarios...</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Monto">Monto (Lps)</label>
                    <input type="number" step="0.000001" min="0.000001" name="Monto" id="Monto" class="form-control" required
                        value="{{ old('Monto', $ahorro->Monto) }}">
                </div>

                <div class="form-group">
                    <label for="Fecha">Fecha</label>
                    <input type="date" name="Fecha" id="Fecha" class="form-control" required
       value="{{ old('Fecha', $ahorro->Fecha) }}">
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Ahorro</button>
                <a href="{{ route('ahorros.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop

@section('js')
<script>
    function cargarBeneficiarios(cajaId, beneficiarioSeleccionado = null) {
        const beneficiarioSelect = document.getElementById('beneficiario-select');
        beneficiarioSelect.innerHTML = '<option>Cargando beneficiarios...</option>';

        if (!cajaId) {
            beneficiarioSelect.innerHTML = '<option value="">Seleccione una caja rural primero</option>';
            return;
        }

        fetch(`/api/ahorros/caja/${cajaId}/socios`)
            .then(response => {
                if (!response.ok) throw new Error('Error al obtener beneficiarios');
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
                    if (b.Id_Beneficiario == beneficiarioSeleccionado) {
                        option.selected = true;
                    }
                    beneficiarioSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error(error);
                beneficiarioSelect.innerHTML = '<option value="">Error al cargar beneficiarios</option>';
            });
    }

    document.getElementById('caja-select').addEventListener('change', function () {
        cargarBeneficiarios(this.value);
    });

    // Al cargar la página, cargar beneficiarios con el seleccionado actual
    document.addEventListener('DOMContentLoaded', function () {
        const cajaId = document.getElementById('caja-select').value;
        const beneficiarioSeleccionado = "{{ old('Id_Beneficiario', $ahorro->Id_Beneficiario) }}";
        cargarBeneficiarios(cajaId, beneficiarioSeleccionado);
    });
</script>
@stop
