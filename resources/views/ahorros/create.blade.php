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
                        @foreach ($cajas as $caja)
                            <option value="{{ $caja->Id_Organizacion }}">{{ $caja->Nombre_Organizacion }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="beneficiario-select">Beneficiario (Socio o Cliente)</label>
                    <select id="beneficiario-select" name="Id_Beneficiario" class="form-control" required>
                        <option value="">Seleccione una caja rural primero</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Monto">Monto (Lps)</label>
                    <input type="number" step="0.01" min="0.01" name="Monto" id="Monto" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="Fecha">Fecha</label>
                    <input type="date" name="Fecha" id="Fecha" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Registrar Ahorro</button>
                <a href="{{ route('ahorros.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop

@section('js')
<script>
document.getElementById('caja-select').addEventListener('change', function() {
    const cajaId = this.value;
    const beneficiarioSelect = document.getElementById('beneficiario-select');

    beneficiarioSelect.innerHTML = '<option>Cargando beneficiarios...</option>';

    if (!cajaId) {
        beneficiarioSelect.innerHTML = '<option value="">Seleccione una caja rural primero</option>';
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

            // Combinar socios y clientes
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
</script>
@stop
